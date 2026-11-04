<?php

namespace Tests\Feature;

use App\Models\Categorie;
use App\Models\Customer;
use App\Models\Media;
use App\Models\Purpose;
use App\Models\Service;
use App\Models\SubCategorie;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServiceStatusTest extends TestCase
{
    use RefreshDatabase;

    public function test_status_form_is_public_and_submits_by_post(): void
    {
        $this->get(route('service-status.index'))
            ->assertOk()
            ->assertSee('Cek Status Layanan')
            ->assertSee('method="POST"', false)
            ->assertSee(route('service-status.lookup'), false);

        $this->get(route('welcome'))->assertOk()->assertSee(route('service-status.index'), false);
    }

    public function test_tracking_code_hash_resolves_only_minimal_status_information_without_personal_data(): void
    {
        $transaction = $this->makeTransaction('Belum dilayani');
        $code = str_repeat('a', 32);
        $transaction->update([
            'tracking_code_hash' => hash('sha256', $code),
            'status_updated_at' => now(),
        ]);

        $this->post(route('service-status.lookup'), ['tracking_code' => $code])
            ->assertRedirect(route('service-status.index'));

        $this->get(route('service-status.index'))
            ->assertOk()
            ->assertSee('Informasi Status Layanan')
            ->assertSee('Belum dilayani')
            ->assertSee('Layanan Pengujian')
            ->assertDontSee($code)
            ->assertDontSee('Pengunjung Privat')
            ->assertDontSee('081234567890');

        $this->assertSame(hash('sha256', $code), $transaction->fresh()->tracking_code_hash);
    }

    public function test_invalid_tracking_codes_are_rejected_generically_and_not_flashed_back(): void
    {
        foreach (['123', str_repeat('g', 32), str_repeat('a', 64), (string) $this->makeTransaction('Belum dilayani')->id] as $code) {
            $this->from(route('service-status.index'))
                ->post(route('service-status.lookup'), ['tracking_code' => $code])
                ->assertRedirect(route('service-status.index'))
                ->assertSessionHasErrors('tracking_code')
                ->assertSessionMissing('_old_input.tracking_code');
        }
    }

    public function test_unknown_tracking_code_does_not_disclose_transaction_or_customer_data(): void
    {
        $transaction = $this->makeTransaction('Sudah dilayani');
        $unknown = str_repeat('f', 32);
        $this->post(route('service-status.lookup'), ['tracking_code' => $unknown])
            ->assertRedirect(route('service-status.index'));

        $this->get(route('service-status.index'))
            ->assertOk()
            ->assertSee('Kode pelacakan tidak valid atau tidak tersedia.')
            ->assertDontSee('Pengunjung Privat')
            ->assertDontSee('081234567890')
            ->assertDontSee('Data rahasia');
        $this->assertDatabaseHas('transactions', ['id' => $transaction->id]);
    }

    public function test_feedback_token_cannot_be_used_as_a_tracking_code(): void
    {
        $transaction = $this->makeTransaction('Sudah dilayani');
        $feedbackToken = str_repeat('b', 32);
        $transaction->update(['feedback_token_hash' => hash('sha256', $feedbackToken)]);

        $this->post(route('service-status.lookup'), ['tracking_code' => $feedbackToken])
            ->assertRedirect(route('service-status.index'));
        $this->get(route('service-status.index'))
            ->assertOk()
            ->assertSee('Kode pelacakan tidak valid atau tidak tersedia.')
            ->assertDontSee('Pengunjung Privat');
    }

    public function test_tracking_code_cannot_be_used_as_a_feedback_token(): void
    {
        $transaction = $this->makeTransaction('Sudah dilayani');
        $trackingCode = str_repeat('c', 32);
        $transaction->update(['tracking_code_hash' => hash('sha256', $trackingCode)]);

        $this->from(route('feedback.index'))->post(route('feedback.resolve'), ['feedback_link' => $trackingCode])
            ->assertRedirect(route('feedback.index'))
            ->assertSessionHasErrors('feedback_link');
        $this->assertDatabaseCount('feedback', 0);
    }

    public function test_all_three_existing_service_statuses_are_displayed(): void
    {
        foreach (['Belum dilayani', 'Sedang dilayani', 'Sudah dilayani'] as $index => $status) {
            $transaction = $this->makeTransaction($status);
            $code = str_repeat((string) ($index + 1), 32);
            $transaction->update(['tracking_code_hash' => hash('sha256', $code)]);

            $this->post(route('service-status.lookup'), ['tracking_code' => $code])
                ->assertRedirect(route('service-status.index'));
            $this->get(route('service-status.index'))->assertOk()->assertSee($status);
        }
    }

    public function test_lookup_endpoint_is_rate_limited(): void
    {
        for ($request = 0; $request < 10; $request++) {
            $this->post(route('service-status.lookup'), ['tracking_code' => 'bad'])->assertRedirect();
        }

        $this->post(route('service-status.lookup'), ['tracking_code' => 'bad'])->assertTooManyRequests();
    }

    public function test_admin_transaction_list_can_filter_each_service_status(): void
    {
        $admin = \App\Models\User::factory()->create(['role' => 'admin']);
        $this->makeTransaction('Belum dilayani');
        $inProgress = $this->makeTransaction('Sedang dilayani');
        $this->makeTransaction('Sudah dilayani');

        $this->actingAs($admin)->get(route('admin.transaction.index', ['status_transaksi' => 'Sedang dilayani']))
            ->assertOk()
            ->assertViewHas('transactions', fn ($transactions) => $transactions->total() === 1 && $transactions->first()->is($inProgress));
    }

    private function makeTransaction(string $status): Transaction
    {
        $category = Categorie::create(['categorie_type' => 'Kategori Pengujian']);
        $subcategory = SubCategorie::create(['sub_categorie_type' => 'Subkategori Pengujian', 'categorie_id' => $category->id]);
        $customer = Customer::create([
            'nama_lengkap' => 'Pengunjung Privat', 'no_hp' => '081234567890', 'jenis_kelamin' => 'Perempuan',
            'alamat' => 'Alamat Privat', 'usia' => 29,
        ]);

        return Transaction::create([
            'customer_id' => $customer->id,
            'media_id' => Media::create(['media_type' => 'Website'])->id,
            'sub_categorie_id' => $subcategory->id,
            'service_id' => Service::create(['service_type' => 'Layanan Pengujian'])->id,
            'purpose_id' => Purpose::create(['purpose_type' => 'Penelitian'])->id,
            'data_diminta' => 'Data rahasia',
            'status_transaksi' => $status,
            'status_penilaian' => 'Belum menilai',
            'status_updated_at' => now(),
        ]);
    }
}
