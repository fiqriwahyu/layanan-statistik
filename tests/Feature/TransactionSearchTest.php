<?php

namespace Tests\Feature;

use App\Models\Categorie;
use App\Models\Customer;
use App\Models\Media;
use App\Models\Purpose;
use App\Models\Service;
use App\Models\SubCategorie;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_search_transactions_by_customer_name(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $matching = $this->makeTransaction('Dewi Anggraini', 'Layanan Pengujian');
        $this->makeTransaction('Budi Santoso', 'Layanan Pengujian');

        $this->actingAs($admin)
            ->get(route('admin.transaction.index', ['search' => 'Dewi']))
            ->assertOk()
            ->assertViewHas('transactions', fn ($transactions) => $transactions->total() === 1 && $transactions->first()->is($matching));
    }

    public function test_admin_can_search_transactions_by_service_type(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $matching = $this->makeTransaction('Pengunjung A', 'Konsultasi Data Khusus');
        $this->makeTransaction('Pengunjung B', 'Layanan Pengujian');

        $this->actingAs($admin)
            ->get(route('admin.transaction.index', ['search' => 'Konsultasi Data']))
            ->assertOk()
            ->assertViewHas('transactions', fn ($transactions) => $transactions->total() === 1 && $transactions->first()->is($matching));
    }

    public function test_search_can_be_combined_with_status_and_date_filters(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $matching = $this->makeTransaction('Rani Filter', 'Pelayanan Dataset', 'Sedang dilayani');
        $this->makeTransaction('Rani Filter', 'Pelayanan Dataset', 'Sudah dilayani');

        $this->actingAs($admin)
            ->get(route('admin.transaction.index', [
                'search' => 'Rani Filter',
                'status_transaksi' => 'Sedang dilayani',
                'from' => now()->toDateString(),
                'to' => now()->toDateString(),
            ]))
            ->assertOk()
            ->assertViewHas('transactions', fn ($transactions) => $transactions->total() === 1 && $transactions->first()->is($matching));
    }

    public function test_search_form_keeps_filters_and_reset_clears_them(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->makeTransaction('Dewi Anggraini', 'Layanan Pengujian');
        $this->makeTransaction('Budi Santoso', 'Konsultasi Data');
        $filters = [
            'search' => 'Dewi',
            'status_transaksi' => 'Belum dilayani',
            'from' => now()->toDateString(),
            'to' => now()->toDateString(),
        ];

        $this->actingAs($admin)
            ->get(route('admin.transaction.index', $filters))
            ->assertOk()
            ->assertSee('value="Dewi"', false)
            ->assertSee('name="status_transaksi" value="Belum dilayani"', false)
            ->assertSee('name="from" value="' . $filters['from'] . '"', false)
            ->assertSee('name="to" value="' . $filters['to'] . '"', false)
            ->assertSee('href="' . route('admin.transaction.index') . '"', false)
            ->assertSee('Cari')
            ->assertSee('Reset');

        $this->get(route('admin.transaction.index'))
            ->assertOk()
            ->assertViewHas('transactions', fn ($transactions) => $transactions->total() === 2);
    }

    public function test_pagination_preserves_search_and_active_filters(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        for ($index = 1; $index <= 16; $index++) {
            $this->makeTransaction('Batch Permohonan ' . $index, 'Layanan Pengujian');
        }

        $filters = [
            'search' => 'Batch Permohonan',
            'status_transaksi' => 'Belum dilayani',
            'from' => now()->toDateString(),
            'to' => now()->toDateString(),
        ];

        $this->actingAs($admin)
            ->get(route('admin.transaction.index', $filters))
            ->assertOk()
            ->assertViewHas('transactions', fn ($transactions) => $transactions->total() === 16 && $transactions->currentPage() === 1)
            ->assertSee('search=Batch', false)
            ->assertSee('status_transaksi=Belum', false)
            ->assertSee('from=' . $filters['from'], false)
            ->assertSee('to=' . $filters['to'], false)
            ->assertSee('page=2', false);

        $this->get(route('admin.transaction.index', $filters + ['page' => 2]))
            ->assertOk()
            ->assertViewHas('transactions', fn ($transactions) => $transactions->total() === 16 && $transactions->currentPage() === 2 && $transactions->count() === 1);
    }

    private function makeTransaction(string $name, string $serviceType, string $status = 'Belum dilayani'): Transaction
    {
        $category = Categorie::create(['categorie_type' => 'Kategori Pengujian']);
        $subcategory = SubCategorie::create([
            'sub_categorie_type' => 'Subkategori Pengujian',
            'categorie_id' => $category->id,
        ]);
        $customer = Customer::create([
            'nama_lengkap' => $name,
            'no_hp' => '081234567890',
            'jenis_kelamin' => 'Perempuan',
            'alamat' => 'Alamat Pengujian',
            'usia' => 29,
        ]);

        return Transaction::create([
            'customer_id' => $customer->id,
            'media_id' => Media::create(['media_type' => 'Website'])->id,
            'sub_categorie_id' => $subcategory->id,
            'service_id' => Service::create(['service_type' => $serviceType])->id,
            'purpose_id' => Purpose::create(['purpose_type' => 'Penelitian'])->id,
            'data_diminta' => 'Data pengujian',
            'status_transaksi' => $status,
            'status_penilaian' => 'Belum menilai',
        ]);
    }
}
