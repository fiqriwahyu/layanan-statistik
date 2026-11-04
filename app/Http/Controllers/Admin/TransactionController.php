<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Exports\TransactionExport;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Throwable;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $this->validateReportFilters($request);
        $query = Transaction::with([
            'customer',
            'feedback',
            'dilayaniOleh',
        ]);

        if ($request->filled('status_transaksi')) {
            $query->where('status_transaksi', $request->input('status_transaksi'));
        }

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($searchQuery) use ($search) {
                $searchQuery->whereHas('customer', function ($sub) use ($search) {
                    $sub->where('nama_lengkap', 'like', '%' . $search . '%')
                        ->orWhere('no_hp', 'like', '%' . $search . '%');
                })->orWhereHas('service', function ($sub) use ($search) {
                    $sub->where('service_type', 'like', '%' . $search . '%');
                });
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
        $targetStatus = $request->input('status_transaksi', 'Sudah dilayani');
        $validated = $request->validate([
            'status_transaksi' => ['sometimes', 'required', 'string', Rule::in(['Belum dilayani', 'Sedang dilayani', 'Sudah dilayani'])],
            'status_version' => ['nullable', 'integer', 'min:0'],
        ], [
            'status_transaksi.in' => 'Status layanan yang dipilih tidak valid.',
        ]);

        if ($transaksi->status_transaksi === 'Sudah dilayani') {
            return redirect()
                ->route('admin.transaction.index')
                ->with('error', 'Transaksi ini sudah selesai dilayani.');
        }

        if ($targetStatus !== 'Sudah dilayani') {
            try {
                DB::transaction(function () use ($transaksi, $validated, $targetStatus) {
                    $locked = Transaction::whereKey($transaksi->id)->lockForUpdate()->firstOrFail();
                    if ($locked->status_transaksi === 'Sudah dilayani') {
                        throw new \RuntimeException('already-served');
                    }
                    if (isset($validated['status_version']) && (int) $validated['status_version'] !== (int) $locked->status_version) {
                        throw new \RuntimeException('stale-status');
                    }

                    $locked->status_transaksi = $targetStatus;
                    $locked->status_updated_at = now();
                    $locked->status_version = (int) $locked->status_version + 1;
                    $locked->save();
                });
            } catch (Throwable $exception) {
                $message = $exception instanceof \RuntimeException && $exception->getMessage() === 'stale-status'
                    ? 'Status transaksi telah berubah. Muat ulang halaman sebelum mencoba kembali.'
                    : ($exception instanceof \RuntimeException && $exception->getMessage() === 'already-served'
                        ? 'Transaksi ini sudah selesai dilayani.'
                        : 'Perubahan status gagal disimpan.');
                return redirect()->route('admin.transaction.index')->with('error', $message);
            }

            return redirect()->route('admin.transaction.index')->with('success', 'Status layanan berhasil diperbarui.');
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

        $file = $request->file('bukti_pelayanan');
        $path = 'bukti-pelayanan/' . $file->hashName();

        try {
            $storedPath = $file->storeAs('bukti-pelayanan', basename($path), 'local');
        } catch (Throwable $exception) {
            $this->cleanupProofFile($path);
            return back()->withInput()->with('error', 'Bukti pelayanan gagal disimpan.');
        }

        if (!$storedPath) {
            $this->cleanupProofFile($path);
            return back()->withInput()->with('error', 'Bukti pelayanan gagal disimpan.');
        }

        $path = $storedPath;

        try {
            DB::transaction(function () use ($transaksi, $request, $validated, $path) {
                $locked = Transaction::whereKey($transaksi->id)->lockForUpdate()->firstOrFail();
                if ($locked->status_transaksi === 'Sudah dilayani') {
                    throw new \RuntimeException('already-served');
                }
                $locked->status_transaksi = 'Sudah dilayani';
                if (isset($validated['status_version']) && (int) $validated['status_version'] !== (int) $locked->status_version) {
                    throw new \RuntimeException('stale-status');
                }
                $locked->status_updated_at = now();
                $locked->status_version = (int) $locked->status_version + 1;
                $locked->bukti_pelayanan = $path;
                $locked->keterangan_pelayanan = $request->keterangan_pelayanan;
                $locked->dilayani_pada = now();
                $locked->dilayani_oleh = Auth::id();
                $locked->save();
            });
        } catch (Throwable $exception) {
            $this->cleanupProofFile($path);
            $message = $exception instanceof \RuntimeException && $exception->getMessage() === 'stale-status'
                ? 'Status transaksi telah berubah. Muat ulang halaman sebelum mencoba kembali.'
                : 'Perubahan status gagal disimpan.';
            return redirect()->route('admin.transaction.index')->with('error', $message);
        }

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

    public function downloadProof(Transaction $transaksi)
    {
        abort_unless($transaksi->bukti_pelayanan && Storage::disk('local')->exists($transaksi->bukti_pelayanan), 404);
        return Storage::disk('local')->download($transaksi->bukti_pelayanan);
    }

    public function exportExcel(Request $request)
    {
        $format = strtolower($request->query('format', 'xlsx'));
        $this->validateReportFilters($request);
        abort_unless(in_array($format, ['csv', 'xlsx'], true), 422, 'Format ekspor tidak didukung.');

        $export = new TransactionExport(
            $request->query('search'),
            $request->query('from'),
            $request->query('to'),
            $request->query('status_transaksi')
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
    $this->validateReportFilters($request);
    $query = Transaction::with([
        'customer',
        'media',
        'service',
        'purpose',
        'subCategorie',
        'feedback',
        'dilayaniOleh',
    ])->orderBy('created_at', 'desc');

    if ($request->filled('status_transaksi')) {
        $query->where('status_transaksi', $request->input('status_transaksi'));
    }

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

    private function validateReportFilters(Request $request): void
    {
        $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'from' => ['nullable', 'date_format:Y-m-d'],
            'to' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:from'],
            'status_transaksi' => ['nullable', 'string', Rule::in(['Belum dilayani', 'Sedang dilayani', 'Sudah dilayani'])],
        ]);
    }

    private function cleanupProofFile(string $path): void
    {
        try {
            $disk = Storage::disk('local');
            if (!$disk->delete($path) && $disk->exists($path)) {
                Log::error('Failed to remove an orphaned service proof file.', ['path' => $path]);
            }
        } catch (Throwable $exception) {
            Log::error('Failed to remove an orphaned service proof file.', ['path' => $path]);
        }
    }
}
