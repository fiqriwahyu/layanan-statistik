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
use Illuminate\Support\Facades\File;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Component\Process\Process;
use Tests\TestCase;

class ReportFeatureTest extends TestCase
{
    use RefreshDatabase;

    private ?string $excelTempPath = null;

    protected function setUp(): void
    {
        parent::setUp();
        $this->excelTempPath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'layanan-excel-tests-' . bin2hex(random_bytes(8));
        mkdir($this->excelTempPath, 0777, true);
        config(['excel.temporary_files.local_path' => $this->excelTempPath]);
    }

    protected function tearDown(): void
    {
        if ($this->excelTempPath && is_dir($this->excelTempPath)) {
            File::deleteDirectory($this->excelTempPath);
        }
        parent::tearDown();
    }

    public function test_dashboard_counts_follow_the_selected_period_and_empty_period(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $old = $this->makeTransaction('Belum dilayani', '2026-01-01 10:00:00');
        $inProgress = $this->makeTransaction('Sedang dilayani', '2026-02-10 10:00:00');
        $current = $this->makeTransaction('Sudah dilayani', '2026-02-15 10:00:00');

        $this->actingAs($admin)->get('/admin/dashboard?from=2026-02-01&to=2026-02-28')
            ->assertOk()
            ->assertViewHas('totalCustomer', 2)
            ->assertViewHas('belumDilayani', 0)
            ->assertViewHas('sedangDilayani', 1)
            ->assertViewHas('sudahDilayani', 1);

        $this->actingAs($admin)->get('/admin/dashboard?from=2027-01-01&to=2027-01-31')
            ->assertOk()
            ->assertViewHas('totalCustomer', 0)
            ->assertViewHas('belumDilayani', 0)
            ->assertViewHas('sedangDilayani', 0)
            ->assertViewHas('sudahDilayani', 0);

        $this->actingAs($admin)->get('/admin/dashboard?from=2026-02-30')->assertSessionHasErrors('from');
        $this->actingAs($admin)->get('/admin/dashboard?from=2026-02-20&to=2026-02-01')->assertSessionHasErrors('to');
        $this->assertNotNull($old->fresh());
        $this->assertNotNull($inProgress->fresh());
        $this->assertNotNull($current->fresh());
    }

    public function test_customer_transaction_and_dashboard_exports_have_matching_formats(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->makeTransaction('Sudah dilayani', '2026-02-15 10:00:00');

        $downloads = [
            ['/admin/customer/export?format=csv', 'text/csv'],
            ['/admin/customer/export?format=xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'],
            ['/admin/customer/export-pdf', 'application/pdf'],
            ['/admin/transaction/export/excel?format=csv', 'text/csv'],
            ['/admin/transaction/export/excel?format=xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'],
            ['/admin/transaction/export/pdf', 'application/pdf'],
            ['/admin/dashboard/export-excel?format=csv', 'text/csv'],
            ['/admin/dashboard/export-excel?format=xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'],
            ['/admin/dashboard/export-pdf', 'application/pdf'],
        ];

        foreach ($downloads as [$uri, $contentType]) {
            $response = $this->actingAs($admin)->get($uri)->assertOk();
            $this->assertStringStartsWith($contentType, $response->headers->get('Content-Type'));
            $this->assertStringContainsString('attachment;', $response->headers->get('Content-Disposition'));
        }

        $this->actingAs($admin)->get('/admin/customer/export?format=exe')->assertStatus(422);
        $this->actingAs($admin)->get('/admin/transaction/export/excel?format=exe')->assertStatus(422);
        $this->actingAs($admin)->get('/admin/dashboard/export-excel?format=exe')->assertStatus(422);
        $this->actingAs($admin)->get('/admin/transaction/export/pdf?from=2026-02-20&to=2026-02-01')->assertSessionHasErrors('to');
    }

    public function test_csv_and_xlsx_export_contents_match_filtered_source_data(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->makeTransaction('Sudah dilayani', '2026-02-15 10:00:00', 'PeriodeAktif');
        $this->makeTransaction('Belum dilayani', '2026-01-15 10:00:00', 'PeriodeLama');
        $period = '?from=2026-02-01&to=2026-02-28';
        $reports = [
            'customer' => ['/admin/customer/export?format=csv', '/admin/customer/export?format=xlsx', 'Pelanggan PeriodeAktif'],
            'transaction' => ['/admin/transaction/export/excel?format=csv', '/admin/transaction/export/excel?format=xlsx', 'Data PeriodeAktif'],
            'dashboard' => ['/admin/dashboard/export-excel?format=csv', '/admin/dashboard/export-excel?format=xlsx', 'Total Transaksi'],
        ];

        foreach ($reports as $name => [$csvUrl, $xlsxUrl, $expectedText]) {
            $query = str_contains($csvUrl, '?') ? '&' . ltrim($period, '?') : $period;
            $csv = $this->actingAs($admin)->get($csvUrl . $query)->assertOk()->streamedContent();
            $rows = array_map('str_getcsv', preg_split('/\r\n|\r|\n/', trim($csv)));
            $flatCsv = implode(' ', array_map(fn ($row) => implode(' ', $row), $rows));
            $this->assertStringContainsString($expectedText, $flatCsv, $name . ' CSV should contain expected source values.');
            $this->assertStringNotContainsString('PeriodeLama', $flatCsv, $name . ' CSV should exclude records outside the period.');

            $xlsxQuery = str_contains($xlsxUrl, '?') ? '&' . ltrim($period, '?') : $period;
            $xlsx = $this->actingAs($admin)->get($xlsxUrl . $xlsxQuery)->assertOk()->streamedContent();
            $xlsxPath = $this->excelTempPath . DIRECTORY_SEPARATOR . $name . '.xlsx';
            file_put_contents($xlsxPath, $xlsx);
            $sheetRows = IOFactory::load($xlsxPath)->getActiveSheet()->toArray();
            $flatXlsx = implode(' ', array_map(fn ($row) => implode(' ', array_map('strval', $row)), $sheetRows));
            $this->assertStringContainsString($expectedText, $flatXlsx, $name . ' XLSX should contain expected source values.');
            $this->assertStringNotContainsString('PeriodeLama', $flatXlsx, $name . ' XLSX should exclude records outside the period.');
        }

        $pdfs = [
            ['/admin/customer/export-pdf' . $period, 'Pelanggan PeriodeAktif', 'PeriodeLama'],
            ['/admin/transaction/export/pdf' . $period, 'Data PeriodeAktif', 'PeriodeLama'],
            ['/admin/dashboard/export-pdf' . $period, 'LAPORAN RINGKASAN LAYANAN', null],
        ];
        foreach ($pdfs as [$url, $expectedText, $excludedText]) {
            $pdf = $this->actingAs($admin)->get($url)->assertOk()->getContent();
            $pdfPath = $this->excelTempPath . DIRECTORY_SEPARATOR . 'report.pdf';
            file_put_contents($pdfPath, $pdf);
            $extractor = new Process(['pdftotext', $pdfPath, '-']);
            $extractor->run();
            $this->assertSame(0, $extractor->getExitCode(), 'pdftotext must extract the generated PDF.');
            $text = $extractor->getOutput();
            $this->assertStringContainsString($expectedText, $text);
            if ($excludedText !== null) {
                $this->assertStringNotContainsString($excludedText, $text);
            }
        }

        $this->actingAs($admin)->get('/admin/customer/export-pdf?from=2026-02-30')->assertSessionHasErrors('from');
        $this->actingAs($admin)->get('/admin/dashboard/export-pdf?from=2026-02-30')->assertSessionHasErrors('from');
    }

    private function makeTransaction(string $status, string $createdAt, string $suffix = 'Laporan'): Transaction
    {
        $category = Categorie::create(['categorie_type' => 'Kategori Laporan']);
        $subcategory = SubCategorie::create(['sub_categorie_type' => 'Subkategori Laporan', 'categorie_id' => $category->id]);
        $customer = Customer::create([
            'nama_lengkap' => 'Pelanggan ' . $suffix, 'no_hp' => '081234567890', 'jenis_kelamin' => 'Laki-laki',
            'alamat' => 'Alamat ' . $suffix, 'usia' => 30,
        ]);

        $transaction = Transaction::create([
            'customer_id' => $customer->id,
            'media_id' => Media::create(['media_type' => 'Media Laporan'])->id,
            'sub_categorie_id' => $subcategory->id,
            'service_id' => Service::create(['service_type' => 'Layanan Laporan'])->id,
            'purpose_id' => Purpose::create(['purpose_type' => 'Tujuan Laporan'])->id,
            'data_diminta' => 'Data ' . $suffix,
            'status_transaksi' => $status,
        ]);
        $transaction->forceFill(['created_at' => $createdAt])->saveQuietly();
        $customer->forceFill(['created_at' => $createdAt])->saveQuietly();

        return $transaction;
    }
}
