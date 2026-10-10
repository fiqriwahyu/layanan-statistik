<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Feedback;
use App\Models\Transaction;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function cekNoHp(Request $request)
    {
        $request->validate([
            'no_hp' => ['required', 'string'],
        ]);

        $noHp = preg_replace('/\D/', '', $request->no_hp);

        $customers = Customer::all()->filter(function ($customer) use ($noHp) {
            $customerNoHp = preg_replace('/\D/', '', $customer->no_hp);

            return $customerNoHp === $noHp;
        });

        if ($customers->isEmpty()) {
            return response()->json([
                'found' => false,
                'transactions' => [],
                'message' => 'Nomor HP tidak ditemukan.',
            ]);
        }

        $customerIds = $customers->pluck('id');

        $transactions = Transaction::with([
                'subCategorie',
            ])
            ->whereIn('customer_id', $customerIds)
            ->where('status_transaksi', 'Sudah dilayani')
            ->whereDoesntHave('feedback')
            ->orderByDesc('created_at')
            ->get();

        if ($transactions->isEmpty()) {
            return response()->json([
                'found' => false,
                'transactions' => [],
                'message' => 'Tidak ada layanan yang dapat dinilai.',
            ]);
        }

        return response()->json([
            'found' => true,
            'transactions' => $transactions->map(function ($transaction) {
                return [
                    'id' => $transaction->id,

                    'tanggal' => $transaction->created_at
                        ? $transaction->created_at->format('d-m-Y H:i')
                        : '-',

                    'kebutuhan_data' => $transaction->subCategorie->sub_categorie_type ?? '-',

                    'data_diminta' => $transaction->data_diminta ?? '-',
                ];
            })->values(),
        ]);
    }

    public function create(Transaction $transaction)
    {
        if ($transaction->status_transaksi !== 'Sudah dilayani') {
            return redirect()
                ->route('feedback.index')
                ->with('error', 'Layanan belum selesai dilayani.');
        }

        if ($transaction->feedback) {
            return redirect()
                ->route('feedback.index')
                ->with('error', 'Layanan ini sudah diberikan penilaian.');
        }

        return view('feedback.create', compact('transaction'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'transaction_id' => [
                'required',
                'exists:transactions,id',
            ],

            'rating_pelayanan' => [
                'required',
                'integer',
                'min:1',
                'max:5',
            ],

            'rating_fasilitas' => [
                'required',
                'integer',
                'min:1',
                'max:5',
            ],

            'rating_kualitas' => [
                'required',
                'integer',
                'min:1',
                'max:5',
            ],

            'saran_kritik' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ], [
            'transaction_id.required' => 'Layanan tidak ditemukan.',
            'transaction_id.exists' => 'Layanan tidak ditemukan.',

            'rating_pelayanan.required' => 'Penilaian pelayanan wajib diisi.',
            'rating_fasilitas.required' => 'Penilaian fasilitas wajib diisi.',
            'rating_kualitas.required' => 'Penilaian kualitas layanan wajib diisi.',
        ]);

        $transaction = Transaction::findOrFail(
            $validated['transaction_id']
        );

        if ($transaction->status_transaksi !== 'Sudah dilayani') {
            return redirect()
                ->route('feedback.index')
                ->with('error', 'Layanan belum selesai dilayani.');
        }

        if ($transaction->feedback) {
            return redirect()
                ->route('feedback.index')
                ->with('error', 'Layanan ini sudah diberikan penilaian.');
        }

        Feedback::create([
            'transaction_id' => $transaction->id,
            'rating_pelayanan' => $validated['rating_pelayanan'],
            'rating_fasilitas' => $validated['rating_fasilitas'],
            'rating_kualitas' => $validated['rating_kualitas'],
            'saran_kritik' => $validated['saran_kritik'] ?? null,
        ]);

        $transaction->update([
            'status_penilaian' => 'Sudah menilai',
        ]);

        return redirect()
            ->route('feedback.index')
            ->with('success', 'Terima kasih. Penilaian Anda berhasil dikirim.');
    }
}