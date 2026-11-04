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
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Mockery;
use RuntimeException;
use Tests\TestCase;

class ServiceProofTest extends TestCase
{
    use RefreshDatabase;

    private ?string $localDiskRoot = null;

    protected function setUp(): void
    {
        parent::setUp();
        $this->localDiskRoot = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'layanan-proof-tests-' . bin2hex(random_bytes(8));
        mkdir($this->localDiskRoot, 0777, true);
        config(['filesystems.disks.local.root' => $this->localDiskRoot]);
        Storage::forgetDisk('local');
    }

    protected function tearDown(): void
    {
        if ($this->localDiskRoot && is_dir($this->localDiskRoot)) {
            File::deleteDirectory($this->localDiskRoot);
        }
        parent::tearDown();
    }

    public function test_admin_and_superadmin_can_download_private_proofs_but_guests_cannot(): void
    {
        Storage::disk('local')->put('bukti-pelayanan/proof.jpg', 'image-data');
        $transaction = $this->makeTransaction(['bukti_pelayanan' => 'bukti-pelayanan/proof.jpg']);

        $this->get(route('admin.transaction.proof', $transaction))->assertRedirect(route('login'));
        $this->actingAs(User::factory()->create(['role' => 'admin']))
            ->get(route('admin.transaction.proof', $transaction))->assertOk();
        $this->actingAs(User::factory()->create(['role' => 'superadmin']))
            ->get(route('admin.transaction.proof', $transaction))->assertOk();
        $publicResponse = $this->get('/storage/bukti-pelayanan/proof.jpg');
        $this->assertContains($publicResponse->status(), [403, 404]);
    }

    public function test_jpg_png_and_pdf_uploads_are_saved_privately_and_set_service_status(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $files = [
            UploadedFile::fake()->image('proof.jpg'),
            UploadedFile::fake()->image('proof.png'),
            UploadedFile::fake()->createWithContent('proof.pdf', "%PDF-1.4\nproof\n%%EOF"),
        ];

        foreach ($files as $file) {
            $transaction = $this->makeTransaction();
            $this->actingAs($admin)->put(route('admin.transaction.updateStatus', $transaction), [
                'bukti_pelayanan' => $file,
                'keterangan_pelayanan' => 'Bukti tersimpan untuk pengujian.',
            ])->assertRedirect(route('admin.transaction.index'))->assertSessionHasNoErrors();

            $transaction->refresh();
            $this->assertSame('Sudah dilayani', $transaction->status_transaksi);
            $this->assertNotEmpty($transaction->bukti_pelayanan);
            Storage::disk('local')->assertExists($transaction->bukti_pelayanan);
            $this->assertFalse(Storage::disk('public')->exists($transaction->bukti_pelayanan));
        }
    }

    public function test_admin_can_update_unfinished_statuses_and_complete_with_a_proof(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $transaction = $this->makeTransaction();

        $this->actingAs($admin)->put(route('admin.transaction.updateStatus', $transaction), [
            'status_transaksi' => 'Sedang dilayani',
            'status_version' => 0,
        ])->assertRedirect(route('admin.transaction.index'))->assertSessionHas('success');

        $transaction->refresh();
        $this->assertSame('Sedang dilayani', $transaction->status_transaksi);
        $this->assertSame(1, $transaction->status_version);
        $this->assertNotNull($transaction->status_updated_at);

        $this->actingAs($admin)->put(route('admin.transaction.updateStatus', $transaction), [
            'status_transaksi' => 'Belum dilayani',
            'status_version' => 1,
        ])->assertRedirect(route('admin.transaction.index'))->assertSessionHas('success');

        $transaction->refresh();
        $this->assertSame('Belum dilayani', $transaction->status_transaksi);
        $this->assertSame(2, $transaction->status_version);

        $this->actingAs($admin)->put(route('admin.transaction.updateStatus', $transaction), [
            'status_transaksi' => 'Sudah dilayani',
            'status_version' => 2,
            'bukti_pelayanan' => UploadedFile::fake()->image('completion.jpg'),
        ])->assertRedirect(route('admin.transaction.index'))->assertSessionHas('success');

        $transaction->refresh();
        $this->assertSame('Sudah dilayani', $transaction->status_transaksi);
        $this->assertSame(3, $transaction->status_version);
        Storage::disk('local')->assertExists($transaction->bukti_pelayanan);
    }

    public function test_invalid_or_stale_status_updates_are_rejected(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $transaction = $this->makeTransaction();

        $this->actingAs($admin)->from('/admin/transaction')->put(route('admin.transaction.updateStatus', $transaction), [
            'status_transaksi' => 'Selesai palsu',
            'status_version' => 0,
        ])->assertRedirect('/admin/transaction')->assertSessionHasErrors('status_transaksi');

        $this->actingAs($admin)->put(route('admin.transaction.updateStatus', $transaction), [
            'status_transaksi' => 'Sedang dilayani',
            'status_version' => 0,
        ])->assertRedirect(route('admin.transaction.index'));

        $this->actingAs($admin)->put(route('admin.transaction.updateStatus', $transaction), [
            'status_transaksi' => 'Belum dilayani',
            'status_version' => 0,
        ])->assertRedirect(route('admin.transaction.index'))->assertSessionHas('error', 'Status transaksi telah berubah. Muat ulang halaman sebelum mencoba kembali.');

        $this->assertSame('Sedang dilayani', $transaction->fresh()->status_transaksi);
        $this->assertSame(1, $transaction->fresh()->status_version);
    }

    public function test_status_changes_preserve_feedback_and_the_used_or_unused_token_state(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $transaction = $this->makeTransaction([
            'status_transaksi' => 'Sedang dilayani',
            'feedback_token_hash' => null,
        ]);
        $feedback = Feedback::create([
            'transaction_id' => $transaction->id,
            'rating_pelayanan' => 5,
            'rating_fasilitas' => 4,
            'rating_kualitas' => 5,
        ]);

        $this->actingAs($admin)->put(route('admin.transaction.updateStatus', $transaction), [
            'status_transaksi' => 'Belum dilayani',
            'status_version' => 0,
        ])->assertRedirect(route('admin.transaction.index'))->assertSessionHas('success');

        $this->assertDatabaseHas('feedback', ['id' => $feedback->id, 'transaction_id' => $transaction->id]);
        $this->assertNull($transaction->fresh()->feedback_token_hash);
    }

    public function test_admin_action_feedback_is_rendered_in_a_closeable_modal(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin)->withSession(['success' => 'Pembaruan berhasil.'])
            ->get(route('admin.transaction.index'))
            ->assertOk()
            ->assertSee('id="flash-result-modal"', false)
            ->assertSee('role="dialog"', false)
            ->assertSee('data-close-flash', false)
            ->assertSee("event.key === 'Escape'", false)
            ->assertSee('Pembaruan berhasil.');
    }

    public function test_invalid_type_and_oversized_proof_are_rejected_without_changing_status(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        foreach ([
            UploadedFile::fake()->create('proof.txt', 10, 'text/plain'),
            UploadedFile::fake()->create('large.jpg', 5121, 'image/jpeg'),
        ] as $file) {
            $transaction = $this->makeTransaction();
            $this->actingAs($admin)->from('/admin/transaction')->put(route('admin.transaction.updateStatus', $transaction), [
                'bukti_pelayanan' => $file,
            ])->assertRedirect('/admin/transaction')->assertSessionHasErrors('bukti_pelayanan');

            $this->assertSame('Belum dilayani', $transaction->fresh()->status_transaksi);
            $this->assertSame([], Storage::disk('local')->allFiles('bukti-pelayanan'));
        }
    }

    public function test_database_failure_removes_uploaded_proof_and_does_not_report_success(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $transaction = $this->makeTransaction();
        Transaction::updating(fn () => throw new RuntimeException('simulated database failure'));

        $this->actingAs($admin)->put(route('admin.transaction.updateStatus', $transaction), [
            'bukti_pelayanan' => UploadedFile::fake()->image('proof.jpg'),
        ])->assertRedirect(route('admin.transaction.index'))->assertSessionHas('error')->assertSessionMissing('success');

        $this->assertSame('Belum dilayani', $transaction->fresh()->status_transaksi);
        $this->assertSame([], Storage::disk('local')->allFiles('bukti-pelayanan'));
    }

    public function test_storage_exception_is_reported_and_known_partial_path_is_removed(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $transaction = $this->makeTransaction();
        $disk = Mockery::mock();
        $disk->shouldReceive('putFileAs')->once()->andThrow(new RuntimeException('simulated storage failure'));
        $disk->shouldReceive('delete')->once()->with(Mockery::on(fn ($path) => str_starts_with($path, 'bukti-pelayanan/')))->andReturn(true);
        Storage::shouldReceive('disk')->with('local')->andReturn($disk);

        $this->actingAs($admin)->from('/admin/transaction')->put(route('admin.transaction.updateStatus', $transaction), [
            'bukti_pelayanan' => UploadedFile::fake()->image('proof.jpg'),
        ])->assertRedirect('/admin/transaction')->assertSessionHas('error', 'Bukti pelayanan gagal disimpan.');

        $this->assertSame('Belum dilayani', $transaction->fresh()->status_transaksi);
        $this->assertNull($transaction->fresh()->bukti_pelayanan);
    }

    public function test_failed_cleanup_after_storage_exception_is_logged_for_manual_follow_up(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $transaction = $this->makeTransaction();
        $disk = Mockery::mock();
        $disk->shouldReceive('putFileAs')->once()->andThrow(new RuntimeException('simulated storage failure'));
        $disk->shouldReceive('delete')->once()->andReturn(false);
        $disk->shouldReceive('exists')->once()->andReturn(true);
        Storage::shouldReceive('disk')->with('local')->andReturn($disk);
        Log::shouldReceive('error')->once()->with(
            'Failed to remove an orphaned service proof file.',
            Mockery::on(fn ($context) => str_starts_with($context['path'] ?? '', 'bukti-pelayanan/'))
        );

        $this->actingAs($admin)->from('/admin/transaction')->put(route('admin.transaction.updateStatus', $transaction), [
            'bukti_pelayanan' => UploadedFile::fake()->image('proof.jpg'),
        ])->assertRedirect('/admin/transaction')->assertSessionHas('error');

        $this->assertSame('Belum dilayani', $transaction->fresh()->status_transaksi);
    }

    public function test_failed_cleanup_after_database_error_is_logged_and_orphan_can_be_identified(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $transaction = $this->makeTransaction();
        Transaction::updating(fn () => throw new RuntimeException('simulated database failure'));
        $disk = Mockery::mock();
        $relativePath = null;
        $disk->shouldReceive('putFileAs')->once()->andReturnUsing(function ($directory, $file, $name) use (&$relativePath) {
            $relativePath = $directory . '/' . $name;
            $absolutePath = $this->localDiskRoot . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relativePath);
            mkdir(dirname($absolutePath), 0777, true);
            file_put_contents($absolutePath, 'orphaned proof');
            return $relativePath;
        });
        $disk->shouldReceive('delete')->once()->andReturn(false);
        $disk->shouldReceive('exists')->once()->andReturn(true);
        Storage::shouldReceive('disk')->with('local')->andReturn($disk);
        Log::shouldReceive('error')->once()->with(
            'Failed to remove an orphaned service proof file.',
            Mockery::on(fn ($context) => str_starts_with($context['path'] ?? '', 'bukti-pelayanan/'))
        );

        $this->actingAs($admin)->from('/admin/transaction')->put(route('admin.transaction.updateStatus', $transaction), [
            'bukti_pelayanan' => UploadedFile::fake()->image('proof.jpg'),
        ])->assertRedirect(route('admin.transaction.index'))->assertSessionHas('error');

        $this->assertSame('Belum dilayani', $transaction->fresh()->status_transaksi);
        $this->assertFileExists($this->localDiskRoot . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relativePath));
    }

    public function test_completed_transaction_cannot_be_completed_again_or_have_its_proof_replaced(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Storage::disk('local')->put('bukti-pelayanan/original.pdf', 'original-proof');
        $transaction = $this->makeTransaction([
            'status_transaksi' => 'Sudah dilayani',
            'bukti_pelayanan' => 'bukti-pelayanan/original.pdf',
        ]);

        $this->actingAs($admin)->put(route('admin.transaction.updateStatus', $transaction), [
            'bukti_pelayanan' => UploadedFile::fake()->image('replacement.jpg'),
        ])->assertRedirect(route('admin.transaction.index'))->assertSessionHas('error');

        $this->assertSame('bukti-pelayanan/original.pdf', $transaction->fresh()->bukti_pelayanan);
        Storage::disk('local')->assertExists('bukti-pelayanan/original.pdf');
        $this->assertSame(['bukti-pelayanan/original.pdf'], Storage::disk('local')->allFiles('bukti-pelayanan'));
    }

    private function makeTransaction(array $overrides = []): Transaction
    {
        $category = Categorie::create(['categorie_type' => 'Kategori Bukti']);
        $subcategory = SubCategorie::create(['sub_categorie_type' => 'Subkategori Bukti', 'categorie_id' => $category->id]);
        $customer = Customer::create([
            'nama_lengkap' => 'Pelanggan Bukti', 'no_hp' => '081234567890', 'jenis_kelamin' => 'Laki-laki',
            'alamat' => 'Alamat Bukti', 'usia' => 30,
        ]);
        $attributes = [
            'customer_id' => $customer->id,
            'media_id' => Media::create(['media_type' => 'Media Bukti'])->id,
            'sub_categorie_id' => $subcategory->id,
            'service_id' => Service::create(['service_type' => 'Layanan Bukti'])->id,
            'purpose_id' => Purpose::create(['purpose_type' => 'Tujuan Bukti'])->id,
            'data_diminta' => 'Data Bukti',
            'status_transaksi' => 'Belum dilayani',
        ];

        return Transaction::create(array_merge($attributes, $overrides));
    }
}
