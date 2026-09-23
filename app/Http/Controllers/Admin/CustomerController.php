<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Exports\CustomerExport;
use App\Models\Customer;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::query();

        if ($request->filled('search')) {
            $query->where(function ($sub) use ($request) {
                $sub->where('nama_lengkap', 'like', '%' . $request->search . '%')
                    ->orWhere('no_hp', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        $customers = $query
            ->with(['education', 'work'])
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('admin.customer.index', compact('customers'));
    }

    public function show(Customer $customer)
    {
        return view('admin.customer.show', compact('customer'));
    }

    public function exportExcel(Request $request)
    {
        $format = strtolower($request->query('format', 'xlsx'));

        $export = new CustomerExport(
            $request->query('search'),
            $request->query('from'),
            $request->query('to')
        );

        if ($format === 'csv') {
            return Excel::download(
                $export,
                'Laporan_Data_Pengunjung_' . now()->format('Ymd_His') . '.csv',
                \Maatwebsite\Excel\Excel::CSV
            );
        }

        return Excel::download(
            $export,
            'Laporan_Data_Pengunjung_' . now()->format('Ymd_His') . '.xlsx',
            \Maatwebsite\Excel\Excel::XLSX
        );
    }

    public function exportPDF(Request $request)
    {
        $query = Customer::with(['work', 'education'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $query->where(function ($sub) use ($request) {
                $sub->where('nama_lengkap', 'like', '%' . $request->search . '%')
                    ->orWhere('no_hp', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        $customers = $query->get();

        if ($request->filled('from') && $request->filled('to')) {
            $periode = date('d-m-Y', strtotime($request->from))
                . ' s.d. ' .
                date('d-m-Y', strtotime($request->to));
        } elseif ($request->filled('from')) {
            $periode = 'Mulai ' . date('d-m-Y', strtotime($request->from));
        } elseif ($request->filled('to')) {
            $periode = 'Sampai ' . date('d-m-Y', strtotime($request->to));
        } else {
            $periode = 'Seluruh Periode';
        }

        $pdf = Pdf::loadView('admin.customer.pdf', [
            'pengunjungs' => $customers,
            'periode' => $periode,
            'search' => $request->search,
        ]);

        $pdf->setPaper('A4', 'landscape');

        return $pdf->download(
            'Laporan_Data_Pengunjung_' . now()->format('Ymd_His') . '.pdf'
        );
    }
}