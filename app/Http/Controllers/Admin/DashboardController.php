<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Customer;
use App\Models\Transaction;
use App\Models\Education;
use App\Models\Work;
use App\Models\Media;
use App\Models\Service;
use App\Models\Purpose;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard admin.
     */
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | STATISTIK UTAMA
        |--------------------------------------------------------------------------
        */

        $totalCustomer = Customer::count();

        $totalTransactions = Transaction::count();

        $belumDilayani = Transaction::where(
            'status_transaksi',
            'Belum dilayani'
        )->count();

        $sudahDilayani = Transaction::where(
            'status_transaksi',
            'Sudah dilayani'
        )->count();


        /*
        |--------------------------------------------------------------------------
        | DATA PENDIDIKAN
        |--------------------------------------------------------------------------
        */

        $education = Education::withCount('customers')
            ->orderBy('id')
            ->get();

        $chartLabelsEducation = $education->pluck('education_type');

        $chartDataEducation = $education->pluck('customers_count');


        /*
        |--------------------------------------------------------------------------
        | DATA PEKERJAAN
        |--------------------------------------------------------------------------
        */

        $work = Work::withCount('customers')
            ->orderBy('id')
            ->get();

        $chartLabelsWork = $work->pluck('work_type');

        $chartDataWork = $work->pluck('customers_count');


        /*
        |--------------------------------------------------------------------------
        | DATA MEDIA PELAYANAN
        |--------------------------------------------------------------------------
        */

        $media = Media::withCount('transactions')
            ->orderBy('id')
            ->get();

        $chartLabelsMedia = $media->pluck('media_type');

        $chartDataMedia = $media->pluck('transactions_count');


        /*
        |--------------------------------------------------------------------------
        | DATA JENIS PELAYANAN
        |--------------------------------------------------------------------------
        */

        $service = Service::withCount('transactions')
            ->orderBy('id')
            ->get();

        $chartLabelsService = $service->pluck('service_type');

        $chartDataService = $service->pluck('transactions_count');


        /*
        |--------------------------------------------------------------------------
        | DATA TUJUAN
        |--------------------------------------------------------------------------
        */

        $purpose = Purpose::withCount('transactions')
            ->orderBy('id')
            ->get();

        $chartLabelsPurpose = $purpose->pluck('purpose_type');

        $chartDataPurpose = $purpose->pluck('transactions_count');


        /*
        |--------------------------------------------------------------------------
        | KIRIM DATA KE VIEW
        |--------------------------------------------------------------------------
        */

        return view('admin.dashboard', compact(
            'totalCustomer',
            'totalTransactions',
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
            'chartDataPurpose'
        ));
    }
}