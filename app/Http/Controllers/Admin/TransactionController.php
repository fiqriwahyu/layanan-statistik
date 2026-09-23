<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Exports\TransactionExport;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with([
            'customer',
            'feedback',
            'dilayaniOleh',
        ]);

        if ($request->filled('search')) {
            $search = $request->search;

            $query->whereHas('customer', function ($sub) use ($search) {
                $sub->where('nama_lengkap', 'like', '%' . $search . '%')
                    ->orWhere('no_hp', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        $transactions = $query
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('admin.transaction.index', compact('transactions'));
    }

    public function updateStatus(Request $request, Transaction $transaksi)
    {
        if ($transaksi->status_transaksi === 'Sudah dilayani') {
            return redirect()
                ->route('admin.transaction.index')
                ->with('error', 'Transaksi ini sudah selesai dilayani.');
        }

        $request->validate([
            'bukti_pelayanan' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'keterangan_pelayanan' => ['nullable', 'string', 'max:1000'],
        ], [
            'bukti_pelayanan.required' => 'Bukti pelayanan wajib dilampirkan.',
            'bukti_pelayanan.file' => 'Bukti pelayanan harus berupa file.',
            'bukti_pelayanan.mimes' => 'Bukti pelayanan harus berupa PDF, JPG, JPEG, atau PNG.',
            'bukti_pelayanan.max' => 'Ukuran bukti pelayanan maksimal 5 MB.',
            'keterangan_pelayanan.max' => 'Keterangan pelayanan maksimal 1000 karakter.',
        ]);

        $path = $request->file('bukti_pelayanan')
            ->store('bukti-pelayanan', 'public');

        $transaksi->status_transaksi = 'Sudah dilayani';
        $transaksi->bukti_pelayanan = $path;
        $transaksi->keterangan_pelayanan = $request->keterangan_pelayanan;
        $transaksi->dilayani_pada = now();
        $transaksi->dilayani_oleh = Auth::id();
        $transaksi->save();

        return redirect()
            ->route('admin.transaction.index')
            ->with('success', 'Layanan berhasil diselesaikan dan bukti pelayanan berhasil disimpan.');
    }

    public function show(Transaction $transaksi)
    {
        $transaksi->load([
            'customer',
            'media',
            'service',
            'purpose',
            'subCategorie',
            'feedback',
            'dilayaniOleh',
        ]);

        return view('admin.transaction.show', [
            'transaction' => $transaksi
        ]);
    }

    public function exportExcel(Request $request)
    {
        $format = strtolower($request->query('format', 'xlsx'));

        $export = new TransactionExport(
            $request->query('search'),
            $request->query('from'),
            $request->query('to')
        );

        if ($format === 'csv') {
            return Excel::download(
                $export,
                'Laporan_Data_Transaksi_' . now()->format('Ymd_His') . '.csv',
                \Maatwebsite\Excel\Excel::CSV
            );
        }

        return Excel::download(
            $export,
            'Laporan_Data_Transaksi_' . now()->format('Ymd_His') . '.xlsx',
            \Maatwebsite\Excel\Excel::XLSX
        );
    }

    public function exportPDF(Request $request)
{
    $query = Transaction::with([
        'customer',
        'media',
        'service',
        'purpose',
        'subCategorie',
        'feedback',
        'dilayaniOleh',
    ])->orderBy('created_at', 'desc');

    if ($request->filled('search')) {
        $search = $request->search;

        $query->whereHas('customer', function ($sub) use ($search) {
            $sub->where('nama_lengkap', 'like', '%' . $search . '%')
                ->orWhere('no_hp', 'like', '%' . $search . '%');
        });
    }

    if ($request->filled('from')) {
        $query->whereDate('created_at', '>=', $request->from);
    }

    if ($request->filled('to')) {
        $query->whereDate('created_at', '<=', $request->to);
    }

    // PENTING: gunakan get(), jangan paginate()
    $transactions = $query->get();

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

    $pdf = Pdf::loadView('admin.transaction.pdf', [
        'transactions' => $transactions,
        'periode' => $periode,
    ]);

    $pdf->setPaper('A4', 'landscape');

    return $pdf->download(
        'Laporan_Data_Transaksi_' . now()->format('Ymd_His') . '.pdf'
    );
}
}