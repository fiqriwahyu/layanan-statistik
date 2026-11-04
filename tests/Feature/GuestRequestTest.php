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
use RuntimeException;
use Tests\TestCase;

class GuestRequestTest extends TestCase
{
    use RefreshDatabase;

    public function test_required_fields_and_value_bounds_are_validated_without_creating_records(): void
    {
        $payload = $this->validPayload();
        foreach (['nama_lengkap', 'no_hp', 'jenis_kelamin', 'alamat', 'usia', 'media_id', 'sub_categorie_id', 'service_id', 'purpose_id', 'data_diminta'] as $field) {
            $invalid = $payload;
            unset($invalid[$field]);
            $this->from('/form-tamu')->post('/form-tamu', $invalid)->assertSessionHasErrors($field);
        }

        foreach ([
            ['no_hp' => 'nomor-salah', 'error' => 'no_hp'],
            ['no_hp' => '+62 812 3456 7890 123456789', 'error' => 'no_hp'],
            ['email' => 'alamat-bukan-email', 'error' => 'email'],
            ['usia' => '0', 'error' => 'usia'],
            ['usia' => 'dua puluh', 'error' => 'usia'],
            ['usia' => '151', 'error' => 'usia'],
            ['jenis_kelamin' => 'lainnya', 'error' => 'jenis_kelamin'],
            ['media_id' => 999999, 'error' => 'media_id'],
            ['sub_categorie_id' => 999999, 'error' => 'sub_categorie_id'],
            ['service_id' => 999999, 'error' => 'service_id'],
            ['purpose_id' => 999999, 'error' => 'purpose_id'],
            ['work_id' => 999999, 'error' => 'work_id'],
            ['education_id' => 999999, 'error' => 'education_id'],
            ['nama_lengkap' => str_repeat('a', 256), 'error' => 'nama_lengkap'],
            ['no_hp' => str_repeat('1', 26), 'error' => 'no_hp'],
            ['email' => str_repeat('a', 250) . '@example.test', 'error' => 'email'],
            ['nama_instansi' => str_repeat('a', 256), 'error' => 'nama_instansi'],
            ['nip_nim' => str_repeat('a', 101), 'error' => 'nip_nim'],
            ['alamat' => str_repeat('a', 1001), 'error' => 'alamat'],
            ['data_diminta' => str_repeat('a', 2001), 'error' => 'data_diminta'],
        ] as $case) {
            $invalid = array_merge($payload, array_diff_key($case, ['error' => true]));
            $this->from('/form-tamu')->post('/form-tamu', $invalid)->assertSessionHasErrors($case['error']);
        }

        $this->assertDatabaseCount('customers', 0);
        $this->assertDatabaseCount('transactions', 0);
    }

    public function test_valid_input_with_optional_customer_fields_is_accepted(): void
    {
        $payload = $this->validPayload();
        unset($payload['email'], $payload['nama_instansi'], $payload['nip_nim']);
        $payload['work_id'] = null;
        $payload['education_id'] = null;

        $this->post('/form-tamu', $payload)
            ->assertRedirect(route('welcome'))
            ->assertSessionHas('success')
            ->assertSessionHas('tracking_code')
            ->assertSessionHas('whatsapp_url');
        $whatsappUrl = $this->app['session.store']->get('whatsapp_url');

        $response = $this->get(route('welcome'))
            ->assertOk()
            ->assertSee('Pengajuan Berhasil')
            ->assertSee('Salin Kode')
            ->assertSee('Kirim Informasi ke WhatsApp')
            ->assertSee('Periksa kembali penerima dan isi pesan')
            ->assertSee('id="flash-result-modal"', false)
            ->assertSee('event.key === \'Escape\'', false)
            ->assertSee('document.execCommand(\'copy\')', false)
            ->assertDontSee('tracking-code-confirmation')
            ->assertDontSee('feedback-copy-status')
            ->assertDontSee('Salin Tautan Feedback');

        $this->assertSame('wa.me', parse_url($whatsappUrl, PHP_URL_HOST));
        $this->assertSame('/6281234567890', parse_url($whatsappUrl, PHP_URL_PATH));
        parse_str(parse_url($whatsappUrl, PHP_URL_QUERY), $whatsappQuery);
        $this->assertStringContainsString('Kode pelacakan:', $whatsappQuery['text']);
        $this->assertStringContainsString(route('service-status.index'), $whatsappQuery['text']);
        $this->assertStringContainsString('Tautan feedback:', $whatsappQuery['text']);
        $this->assertStringContainsString('sebelum maupun setelah layanan selesai', $whatsappQuery['text']);

        $this->assertDatabaseCount('customers', 1);
        $this->assertDatabaseCount('transactions', 1);
    }

    public function test_homepage_feedback_button_opens_the_feedback_entry_page(): void
    {
        $this->get(route('welcome'))
            ->assertOk()
            ->assertSee('href="' . route('feedback.index') . '"', false)
            ->assertSee('Beri Nilai');
    }

    public function test_valid_input_is_preserved_and_form_opens_the_step_with_errors(): void
    {
        $payload = $this->validPayload();
        $payload['nama_lengkap'] = 'Nama Pengunjung Terjaga';
        $payload['usia'] = 0;

        $this->from('/form-tamu')->post('/form-tamu', $payload)
            ->assertRedirect('/form-tamu')
            ->assertSessionHasInput('nama_lengkap', 'Nama Pengunjung Terjaga');

        $this->get('/form-tamu')->assertOk()->assertSee('Nama Pengunjung Terjaga')->assertSee('guestRequestForm(1)');
    }

    public function test_each_submission_creates_a_separate_customer_transaction_and_feedback_token(): void
    {
        $payload = $this->validPayload();
        $first = $this->post('/form-tamu', $payload)->assertRedirect(route('welcome'))->assertSessionHas('whatsapp_url');
        $whatsappUrl = $this->app['session.store']->get('whatsapp_url');
        parse_str(parse_url($whatsappUrl, PHP_URL_QUERY), $whatsappQuery);
        preg_match('/Tautan feedback: (\S+)/', $whatsappQuery['text'], $matches);
        $token = basename(parse_url($matches[1], PHP_URL_PATH));
        $trackingCode = $this->app['session.store']->get('tracking_code');

        $this->assertSame(32, strlen($token));
        $this->assertMatchesRegularExpression('/\A[a-f0-9]{32}\z/', $token);
        $this->assertDatabaseHas('transactions', ['feedback_token_hash' => hash('sha256', $token)]);
        $this->assertDatabaseMissing('transactions', ['feedback_token_hash' => $token]);
        $this->assertSame(32, strlen($trackingCode));
        $this->assertMatchesRegularExpression('/\A[a-f0-9]{32}\z/', $trackingCode);
        $this->assertDatabaseHas('transactions', ['tracking_code_hash' => hash('sha256', $trackingCode)]);
        $this->assertDatabaseMissing('transactions', ['tracking_code_hash' => $trackingCode]);

        $this->post('/form-tamu', $payload)->assertRedirect(route('welcome'));
        $this->assertDatabaseCount('customers', 2);
        $this->assertDatabaseCount('transactions', 2);
    }

    public function test_unusable_phone_keeps_tracking_code_but_does_not_create_whatsapp_link(): void
    {
        foreach (['12345678', '+1 202 555 0123'] as $phone) {
            $payload = $this->validPayload();
            $payload['no_hp'] = $phone;

            $this->post('/form-tamu', $payload)
                ->assertRedirect(route('welcome'))
                ->assertSessionHas('tracking_code')
                ->assertSessionMissing('whatsapp_url')
                ->assertSessionHas('whatsapp_warning');

            $this->get(route('welcome'))
                ->assertOk()
                ->assertSee('Salin Kode')
                ->assertSee('Nomor HP tidak dapat digunakan untuk menyiapkan WhatsApp.')
                ->assertDontSee('Kirim Informasi ke WhatsApp');
        }
    }

    public function test_indonesian_phone_formats_are_normalized_for_whatsapp(): void
    {
        foreach ([
            ['+62 812 3456 7890', '/6281234567890'],
            ['62 812 3456 7890', '/6281234567890'],
            ['0812 3456 7890', '/6281234567890'],
            ['812 3456 7890', '/6281234567890'],
        ] as [$phone, $expectedPath]) {
            $payload = $this->validPayload();
            $payload['no_hp'] = $phone;

            $this->post('/form-tamu', $payload)
                ->assertRedirect(route('welcome'))
                ->assertSessionHas('whatsapp_url');

            $whatsappUrl = $this->app['session.store']->get('whatsapp_url');
            $this->assertSame($expectedPath, parse_url($whatsappUrl, PHP_URL_PATH));
        }
    }

    public function test_phone_lookup_does_not_expose_customer_data(): void
    {
        $this->get('/cek-nohp')->assertNotFound();

        $this->get('/feedback/cek-nohp?no_hp=0800000000')->assertNotFound();
    }

    public function test_customer_creation_rolls_back_if_transaction_creation_fails(): void
    {
        $payload = $this->validPayload();
        $this->withoutExceptionHandling();
        Transaction::creating(fn () => throw new RuntimeException('simulated transaction failure'));

        try {
            $this->post('/form-tamu', $payload);
            $this->fail('Expected the simulated transaction failure.');
        } catch (RuntimeException $exception) {
            $this->assertSame('simulated transaction failure', $exception->getMessage());
        }

        $this->assertDatabaseCount('customers', 0);
        $this->assertDatabaseCount('transactions', 0);
    }

    private function validPayload(): array
    {
        $category = Categorie::create(['categorie_type' => 'Kategori Uji']);
        $subcategory = SubCategorie::create(['sub_categorie_type' => 'Subkategori Uji', 'categorie_id' => $category->id]);

        return [
            'nama_lengkap' => 'Pengunjung Uji',
            'no_hp' => '+62 812 3456 7890',
            'jenis_kelamin' => 'Perempuan',
            'email' => 'pengunjung@example.test',
            'alamat' => 'Alamat pengunjung',
            'usia' => 25,
            'nama_instansi' => 'Instansi Uji',
            'nip_nim' => '12345678',
            'media_id' => Media::create(['media_type' => 'Media Uji'])->id,
            'sub_categorie_id' => $subcategory->id,
            'service_id' => Service::create(['service_type' => 'Layanan Uji'])->id,
            'purpose_id' => Purpose::create(['purpose_type' => 'Tujuan Uji'])->id,
            'data_diminta' => 'Data yang diperlukan untuk pengujian.',
        ];
    }
}
