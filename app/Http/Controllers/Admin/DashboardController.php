<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Education;
use App\Models\Media;
use App\Models\Purpose;
use App\Models\Service;
use App\Models\Transaction;
use App\Models\Work;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\DashboardExport;
use Maatwebsite\Excel\Facades\Excel;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $from = $request->from;
        $to = $request->to;

        $customerQuery = Customer::query();
        $transactionQuery = Transaction::query();

        if ($from) {
            $customerQuery->whereDate('created_at', '>=', $from);
            $transactionQuery->whereDate('created_at', '>=', $from);
        }

        if ($to) {
            $customerQuery->whereDate('created_at', '<=', $to);
            $transactionQuery->whereDate('created_at', '<=', $to);
        }

        $totalCustomer = $customerQuery->count();

        $belumDilayani = (clone $transactionQuery)
            ->where('status_transaksi', 'Belum dilayani')
            ->count();

        $sudahDilayani = (clone $transactionQuery)
            ->where('status_transaksi', 'Sudah dilayani')
            ->count();

        $pendidikan = Education::withCount([
            'customers' => function ($query) use ($from, $to) {
                if ($from) {
                    $query->whereDate('customers.created_at', '>=', $from);
                }

                if ($to) {
                    $query->whereDate('customers.created_at', '<=', $to);
                }
            }
        ])->orderBy('id')->get();

        $pekerjaan = Work::withCount([
            'customers' => function ($query) use ($from, $to) {
                if ($from) {
                    $query->whereDate('customers.created_at', '>=', $from);
                }

                if ($to) {
                    $query->whereDate('customers.created_at', '<=', $to);
                }
            }
        ])->orderBy('id')->get();

        $mediaPelayanan = Media::withCount([
            'transactions' => function ($query) use ($from, $to) {
                if ($from) {
                    $query->whereDate('transactions.created_at', '>=', $from);
                }

                if ($to) {
                    $query->whereDate('transactions.created_at', '<=', $to);
                }
            }
        ])->orderBy('id')->get();

        $jenisPelayanan = Service::withCount([
            'transactions' => function ($query) use ($from, $to) {
                if ($from) {
                    $query->whereDate('transactions.created_at', '>=', $from);
                }

                if ($to) {
                    $query->whereDate('transactions.created_at', '<=', $to);
                }
            }
        ])->orderBy('id')->get();

        $tujuan = Purpose::withCount([
            'transactions' => function ($query) use ($from, $to) {
                if ($from) {
                    $query->whereDate('transactions.created_at', '>=', $from);
                }

                if ($to) {
                    $query->whereDate('transactions.created_at', '<=', $to);
                }
            }
        ])->orderBy('id')->get();

        $chartLabelsEducation = $pendidikan->pluck('education_type')->values();
        $chartDataEducation = $pendidikan->pluck('customers_count')->values();

        $chartLabelsWork = $pekerjaan->pluck('work_type')->values();
        $chartDataWork = $pekerjaan->pluck('customers_count')->values();

        $chartLabelsMedia = $mediaPelayanan->pluck('media_type')->values();
        $chartDataMedia = $mediaPelayanan->pluck('transactions_count')->values();

        $chartLabelsService = $jenisPelayanan->pluck('service_type')->values();
        $chartDataService = $jenisPelayanan->pluck('transactions_count')->values();

        $chartLabelsPurpose = $tujuan->pluck('purpose_type')->values();
        $chartDataPurpose = $tujuan->pluck('transactions_count')->values();

        return view('admin.dashboard', compact(
            'totalCustomer',
            'belumDilayani',
            'sudahDilayani',
            'chartLabelsEducation',
            'chartDataEducation',
            'chartLabelsWork',
            'chartDataWork',
            'chartLabelsMedia',
            'chartDataMedia',
            'chartLabelsService',
            'chartDataService',
            'chartLabelsPurpose',
            'chartDataPurpose',
            'from',
            'to'
        ));
    }

    public function exportPDF(Request $request)
    {
        $from = $request->from;
        $to = $request->to;

        $customerQuery = Customer::query();
        $transactionQuery = Transaction::query();

        if ($from) {
            $customerQuery->whereDate('created_at', '>=', $from);
            $transactionQuery->whereDate('created_at', '>=', $from);
        }

        if ($to) {
            $customerQuery->whereDate('created_at', '<=', $to);
            $transactionQuery->whereDate('created_at', '<=', $to);
        }

        $totalPengunjung = $customerQuery->count();
        $totalTransaksi = $transactionQuery->count();

        $belumDilayani = (clone $transactionQuery)
            ->where('status_transaksi', 'Belum dilayani')
            ->count();

        $sudahDilayani = (clone $transactionQuery)
            ->where('status_transaksi', 'Sudah dilayani')
            ->count();

        $pendidikan = Education::withCount([
            'customers' => function ($query) use ($from, $to) {
                if ($from) {
                    $query->whereDate('customers.created_at', '>=', $from);
                }

                if ($to) {
                    $query->whereDate('customers.created_at', '<=', $to);
                }
            }
        ])->orderBy('id')->get();

        $pekerjaan = Work::withCount([
            'customers' => function ($query) use ($from, $to) {
                if ($from) {
                    $query->whereDate('customers.created_at', '>=', $from);
                }

                if ($to) {
                    $query->whereDate('customers.created_at', '<=', $to);
                }
            }
        ])->orderBy('id')->get();

        $mediaPelayanan = Media::withCount([
            'transactions' => function ($query) use ($from, $to) {
                if ($from) {
                    $query->whereDate('transactions.created_at', '>=', $from);
                }

                if ($to) {
                    $query->whereDate('transactions.created_at', '<=', $to);
                }
            }
        ])->orderBy('id')->get();

        $jenisPelayanan = Service::withCount([
            'transactions' => function ($query) use ($from, $to) {
                if ($from) {
                    $query->whereDate('transactions.created_at', '>=', $from);
                }

                if ($to) {
                    $query->whereDate('transactions.created_at', '<=', $to);
                }
            }
        ])->orderBy('id')->get();

        $tujuan = Purpose::withCount([
            'transactions' => function ($query) use ($from, $to) {
                if ($from) {
                    $query->whereDate('transactions.created_at', '>=', $from);
                }

                if ($to) {
                    $query->whereDate('transactions.created_at', '<=', $to);
                }
            }
        ])->orderBy('id')->get();

        if ($from && $to) {
            $periode = date('d-m-Y', strtotime($from)) . ' s.d. ' . date('d-m-Y', strtotime($to));
        } elseif ($from) {
            $periode = 'Mulai ' . date('d-m-Y', strtotime($from));
        } elseif ($to) {
            $periode = 'Sampai ' . date('d-m-Y', strtotime($to));
        } else {
            $periode = 'Seluruh Data';
        }

        $pdf = Pdf::loadView('admin.pdf-dashboard', [
            'totalPengunjung' => $totalPengunjung,
            'totalTransaksi' => $totalTransaksi,
            'belumDilayani' => $belumDilayani,
            'sudahDilayani' => $sudahDilayani,
            'pendidikan' => $pendidikan,
            'pekerjaan' => $pekerjaan,
            'mediaPelayanan' => $mediaPelayanan,
            'jenisPelayanan' => $jenisPelayanan,
            'tujuan' => $tujuan,
            'periode' => $periode,
        ]);

        $pdf->setPaper('A4', 'landscape');

        return $pdf->download(
            'Laporan_Beranda_BPS_Kota_Jambi_' . now()->format('Ymd_His') . '.pdf'
        );
    }

    public function exportExcel(Request $request)
{
    $format = $request->query('format', 'xlsx');

    $export = new DashboardExport(
        $request->query('from'),
        $request->query('to')
    );

    if ($format === 'csv') {
        return Excel::download(
            $export,
            'Laporan_Beranda_BPS_Kota_Jambi_' . now()->format('Ymd_His') . '.csv',
            \Maatwebsite\Excel\Excel::CSV
        );
    }

    return Excel::download(
        $export,
        'Laporan_Beranda_BPS_Kota_Jambi_' . now()->format('Ymd_His') . '.xlsx',
        \Maatwebsite\Excel\Excel::XLSX
    );
}
}