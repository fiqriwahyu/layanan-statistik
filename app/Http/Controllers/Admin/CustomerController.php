<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

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

        $customers = $query->with(['education', 'work'])->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.customer.index', compact('customers'));
    }

    public function show(Customer $customer)
    {
        return view('admin.customer.show', compact('customer'));
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()->route('admin.customer.index')->with('success', 'Customer berhasil dihapus.');
    }

    public function exportExcel(Request $request)
    {
        $format = $request->query('format', 'csv');
        $extension = $format === 'xlsx' ? 'xlsx' : 'csv';
        $filename = 'customers_export_' . now()->format('Ymd_His') . '.' . $extension;

        $customers = Customer::with(['work', 'education'])->orderBy('created_at', 'desc')->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($customers) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Nama', 'No HP', 'Jenis Kelamin', 'Email', 'Pendidikan', 'Pekerjaan', 'Tanggal']);

            foreach ($customers as $customer) {
                fputcsv($handle, [
                    $customer->nama_lengkap,
                    $customer->no_hp,
                    $customer->jenis_kelamin,
                    $customer->email,
                    $customer->education?->education_type,
                    $customer->work?->work_type,
                    $customer->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPDF(Request $request)
    {
        $customers = Customer::with(['work', 'education'])->orderBy('created_at', 'desc')->get();

        $content = view('admin.customer.pdf', [
            'pengunjungs' => $customers,
        ])->render();

        return response($content, 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="customers_report.pdf"');
    }
}
