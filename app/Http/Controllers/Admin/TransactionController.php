<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with([
            'customer',
            'feedback',
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
        $request->validate([
            'status_transaksi' => [
                'required',
                'string',
                'in:Belum dilayani,Sudah dilayani',
            ],
        ]);

        $transaksi->status_transaksi = $request->status_transaksi;
        $transaksi->save();

        return redirect()
            ->route('admin.transaction.index')
            ->with('success', 'Status transaksi berhasil diperbarui.');
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
        ]);

        return view('admin.transaction.show', [
            'transaction' => $transaksi,
        ]);
    }

    // public function destroy(Transaction $transaksi)
    // {
    //     $transaksi->delete();

    //     return redirect()
    //         ->route('admin.transaction.index')
    //         ->with('success', 'Transaksi berhasil dihapus.');
    // }
}