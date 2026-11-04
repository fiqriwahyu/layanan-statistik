<?php

namespace Tests\Feature;

use App\Models\Categorie;
use App\Models\Customer;
use App\Models\Feedback;
use App\Models\Media;
use App\Models\Purpose;
use App\Models\Service;
use App\Models\SubCategorie;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\QueryException;
use Tests\TestCase;

class FeedbackLinkTest extends TestCase
{
    use RefreshDatabase;

    public function test_feedback_index_renders_with_a_public_layout(): void
    {
        $this->get(route('feedback.index'))
            ->assertOk()
            ->assertSee('Berikan Penilaian Layanan')
            ->assertSee('action="' . route('feedback.resolve') . '"', false)
            ->assertSee('Buka halaman konfirmasi pengajuan')
            ->assertSee('Jika tombol WhatsApp tersedia')
            ->assertSee('Pesan tidak dikirim otomatis.')
            ->assertDontSee('Salin Tautan Feedback')
            ->assertSee('Token lama 64 karakter tidak lagi dapat digunakan')
            ->assertDontSee('Tautan feedback tidak tersedia atau sudah digunakan.');

        $this->get(route('welcome'))
            ->assertOk()
            ->assertSee('href="' . route('feedback.index') . '"', false)
            ->assertSee('Beri Nilai');
    }

    public function test_valid_token_or_same_site_feedback_link_redirects_to_the_matching_feedback_form(): void
    {
        $transaction = $this->makeTransaction('Sudah dilayani');
        $token = str_repeat('1', 32);
        $transaction->update(['feedback_token_hash' => hash('sha256', $token)]);

        $this->assertSame(32, strlen($token));
        $this->assertMatchesRegularExpression('/\A[a-f0-9]{32}\z/', $token);
        $this->assertSame(hash('sha256', $token), $transaction->fresh()->feedback_token_hash);

        foreach ([$token, route('feedback.create', $token)] as $input) {
            $this->post(route('feedback.resolve'), ['feedback_link' => $input])
                ->assertRedirect(route('feedback.create', $token));
        }

        $this->get(route('feedback.create', $token))
            ->assertOk()
            ->assertSee('Data test')
            ->assertSee('BPS Kota Jambi')
            ->assertSee('Nilai pelayanan 1 dari 5')
            ->assertDontSee('Pengunjung Test')
            ->assertDontSee('0800000000');
    }

    public function test_external_or_malformed_feedback_links_are_rejected_without_redirecting_externally(): void
    {
        $transaction = $this->makeTransaction('Sudah dilayani');
        $token = str_repeat('2', 32);
        $transaction->update(['feedback_token_hash' => hash('sha256', $token)]);

        foreach ([
            'https://external.example/feedback/' . $token,
            'https://localhost/not-feedback/' . $token,
            str_repeat('a', 31),
            str_repeat('b', 33),
            strtoupper(str_repeat('a', 32)),
            str_repeat('c', 63),
            str_repeat('d', 65),
            'https://localhost/feedback/' . $token . '?next=https://external.example',
            'not-a-token',
            '',
        ] as $input) {
            $this->from(route('feedback.index'))->post(route('feedback.resolve'), ['feedback_link' => $input])
                ->assertRedirect(route('feedback.index'))
                ->assertSessionHasErrors('feedback_link');
            $this->get(route('feedback.index'))
                ->assertOk()
                ->assertDontSee('Pengunjung Test')
                ->assertDontSee('0800000000');
        }
    }

    public function test_legacy_64_character_feedback_token_is_rejected(): void
    {
        $legacyToken = str_repeat('a', 64);
        $this->from(route('feedback.index'))->post(route('feedback.resolve'), [
            'feedback_link' => $legacyToken,
        ])->assertRedirect(route('feedback.index'))
            ->assertSessionHasErrors('feedback_link');

        $this->get('/feedback/' . $legacyToken)->assertNotFound();
        $this->post('/feedback/' . $legacyToken, [
            'rating_pelayanan' => 5,
            'rating_fasilitas' => 4,
            'rating_kualitas' => 5,
        ])->assertNotFound();
    }

    public function test_feedback_token_is_rejected_after_successful_submission(): void
    {
        $served = $this->makeTransaction('Belum dilayani');
        $usedToken = str_repeat('4', 32);
        $served->update(['feedback_token_hash' => hash('sha256', $usedToken)]);
        $this->post(route('feedback.store', $usedToken), [
            'rating_pelayanan' => 5,
            'rating_fasilitas' => 4,
            'rating_kualitas' => 3,
        ])->assertRedirect(route('feedback.index'));

        $this->from(route('feedback.index'))->post(route('feedback.resolve'), ['feedback_link' => $usedToken])
            ->assertRedirect(route('feedback.index'))
            ->assertSessionHasErrors('feedback_link');
    }

    public function test_invalid_token_is_rejected_without_disclosing_customer_or_transaction_data(): void
    {
        $this->followingRedirects()->from(route('feedback.index'))->post(route('feedback.resolve'), [
            'feedback_link' => str_repeat('f', 32),
        ])->assertOk()
            ->assertSee('Tautan feedback tidak valid atau sudah digunakan.')
            ->assertDontSee('Pengunjung Test')
            ->assertDontSee('0800000000')
            ->assertDontSee('Data test');
    }

    public function test_feedback_requires_the_secret_link_and_can_only_be_submitted_once(): void
    {
        $transaction = $this->makeTransaction('Sudah dilayani');
        $token = str_repeat('a', 32);
        $transaction->update(['feedback_token_hash' => hash('sha256', $token)]);

        $this->get(route('feedback.create', $token))
            ->assertOk()
            ->assertHeader('Referrer-Policy', 'no-referrer');
        $this->post(route('feedback.store', $token), [
            'rating_pelayanan' => 5,
            'rating_fasilitas' => 4,
            'rating_kualitas' => 5,
            'transaction_id' => 999999,
            'saran_kritik' => '<script>alert(1)</script>',
        ])->assertRedirect(route('feedback.index'));
        $this->get(route('feedback.index'))->assertOk()->assertSee('Terima kasih. Penilaian Anda berhasil dikirim.');

        $this->assertDatabaseHas('feedback', ['transaction_id' => $transaction->id]);
        $this->assertNull($transaction->fresh()->feedback_token_hash);
        $this->post(route('feedback.store', $token), [
            'rating_pelayanan' => 5, 'rating_fasilitas' => 5, 'rating_kualitas' => 5,
        ])->assertRedirect(route('feedback.index'));
        $this->assertSame(1, Feedback::where('transaction_id', $transaction->id)->count());

        $this->from(route('feedback.index'))->post(route('feedback.resolve'), ['feedback_link' => $token])
            ->assertRedirect(route('feedback.index'))
            ->assertSessionHasErrors('feedback_link');
    }

    public function test_invalid_feedback_route_token_is_rejected(): void
    {
        $this->get('/feedback/' . str_repeat('b', 64))->assertNotFound();
    }

    public function test_feedback_can_be_submitted_for_all_three_service_statuses(): void
    {
        foreach (['Belum dilayani', 'Sedang dilayani', 'Sudah dilayani'] as $index => $status) {
            $transaction = $this->makeTransaction($status);
            $token = str_repeat((string) ($index + 1), 32);
            $transaction->update(['feedback_token_hash' => hash('sha256', $token)]);

            $this->post(route('feedback.resolve'), ['feedback_link' => $token])
                ->assertRedirect(route('feedback.create', $token));
            $this->get(route('feedback.create', $token))->assertOk();
            $this->post(route('feedback.store', $token), [
                'rating_pelayanan' => 5,
                'rating_fasilitas' => 4,
                'rating_kualitas' => 5,
            ])->assertRedirect(route('feedback.index'));

            $this->assertDatabaseHas('feedback', ['transaction_id' => $transaction->id]);
            $this->assertNull($transaction->fresh()->feedback_token_hash);
        }
    }

    public function test_feedback_token_is_bound_to_its_own_transaction(): void
    {
        $first = $this->makeTransaction('Sudah dilayani');
        $second = $this->makeTransaction('Sudah dilayani');
        $token = str_repeat('e', 32);
        $first->update(['feedback_token_hash' => hash('sha256', $token)]);
        $secondToken = str_repeat('f', 32);
        $second->update(['feedback_token_hash' => hash('sha256', $secondToken)]);

        $this->post(route('feedback.store', $token), [
            'rating_pelayanan' => 5,
            'rating_fasilitas' => 4,
            'rating_kualitas' => 3,
            'transaction_id' => $second->id,
        ])->assertRedirect(route('feedback.index'));

        $this->assertDatabaseHas('feedback', ['transaction_id' => $first->id]);
        $this->assertDatabaseMissing('feedback', ['transaction_id' => $second->id]);
        $this->assertNull($first->fresh()->feedback_token_hash);
        $this->assertSame(hash('sha256', $secondToken), $second->fresh()->feedback_token_hash);
    }

    public function test_feedback_page_escapes_untrusted_transaction_text(): void
    {
        $transaction = $this->makeTransaction('Sudah dilayani');
        $transaction->update(['data_diminta' => '<script>alert("xss")</script>']);
        $token = str_repeat('d', 32);
        $transaction->update(['feedback_token_hash' => hash('sha256', $token)]);

        $this->get(route('feedback.create', $token))
            ->assertOk()
            ->assertDontSee('<script>alert("xss")</script>', false)
            ->assertSee('&lt;script&gt;alert(&quot;xss&quot;)&lt;/script&gt;', false);
    }

    public function test_feedback_transaction_id_has_a_database_unique_constraint(): void
    {
        $transaction = $this->makeTransaction('Sudah dilayani');
        $data = [
            'transaction_id' => $transaction->id,
            'rating_pelayanan' => 5,
            'rating_fasilitas' => 4,
            'rating_kualitas' => 5,
        ];
        Feedback::create($data);

        $this->expectException(QueryException::class);
        Feedback::create($data);
    }

    private function makeTransaction(string $status): Transaction
    {
        $category = Categorie::create(['categorie_type' => 'Kategori test']);
        $subcategory = SubCategorie::create(['sub_categorie_type' => 'Subkategori test', 'categorie_id' => $category->id]);
        $customer = Customer::create([
            'nama_lengkap' => 'Pengunjung Test', 'no_hp' => '0800000000', 'jenis_kelamin' => 'Laki-laki',
            'alamat' => 'Alamat test', 'usia' => 20,
        ]);

        return Transaction::create([
            'customer_id' => $customer->id,
            'media_id' => Media::create(['media_type' => 'Media test'])->id,
            'sub_categorie_id' => $subcategory->id,
            'service_id' => Service::create(['service_type' => 'Layanan test'])->id,
            'purpose_id' => Purpose::create(['purpose_type' => 'Tujuan test'])->id,
            'data_diminta' => 'Data test',
            'status_transaksi' => $status,
            'status_penilaian' => 'Belum menilai',
        ]);
    }
}
