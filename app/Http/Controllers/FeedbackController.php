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
        $input = trim($request->query('no_hp'));

        if ($input === '') {
            return response()->json([
                'found' => false,
                'message' => 'Nomor HP wajib diisi.'
            ]);
        }

        $nomorInput = $this->normalisasiNoHp($input);

        if ($nomorInput === '') {
            return response()->json([
                'found' => false,
                'message' => 'Nomor HP tidak valid.'
            ]);
        }

        $customers = Customer::all();

        $customerIds = $customers
            ->filter(function ($customer) use ($nomorInput) {
                return $this->normalisasiNoHp($customer->no_hp) === $nomorInput;
            })
            ->pluck('id');

        if ($customerIds->isEmpty()) {
            return response()->json([
                'found' => false,
                'message' => 'Nomor HP tidak ditemukan.'
            ]);
        }

        $transactions = Transaction::with(['customer', 'feedback'])
            ->whereIn('customer_id', $customerIds)
            ->where('status_transaksi', 'Sudah dilayani')
            ->whereDoesntHave('feedback')
            ->orderBy('created_at', 'desc')
            ->get();

        if ($transactions->isEmpty()) {
            return response()->json([
                'found' => false,
                'message' => 'Tidak ada transaksi yang dapat dinilai untuk nomor HP tersebut.'
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
                    'data_diminta' => $transaction->data_diminta ?: '-',
                ];
            })->values()
        ]);
    }

    private function normalisasiNoHp($noHp)
    {
        $noHp = preg_replace('/[^0-9]/', '', (string) $noHp);

        if (str_starts_with($noHp, '62')) {
            $noHp = '0' . substr($noHp, 2);
        }

        return $noHp;
    }

    public function create(Transaction $transaction)
    {
        if ($transaction->status_transaksi !== 'Sudah dilayani') {
            return redirect()
                ->route('welcome')
                ->with('error', 'Transaksi belum selesai dilayani.');
        }

        if ($transaction->feedback) {
            return redirect()
                ->route('welcome')
                ->with('error', 'Transaksi ini sudah memiliki penilaian.');
        }

        return view('feedback.create', [
            'transaction' => $transaction
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'transaction_id' => ['required', 'exists:transactions,id'],
            'rating_pelayanan' => ['required', 'integer', 'min:1', 'max:5'],
            'rating_fasilitas' => ['required', 'integer', 'min:1', 'max:5'],
            'rating_kualitas' => ['required', 'integer', 'min:1', 'max:5'],
            'saran_kritik' => ['nullable', 'string', 'max:2000'],
        ], [
            'transaction_id.required' => 'Transaksi wajib dipilih.',
            'rating_pelayanan.required' => 'Penilaian pelayanan wajib dipilih.',
            'rating_fasilitas.required' => 'Penilaian fasilitas wajib dipilih.',
            'rating_kualitas.required' => 'Penilaian kualitas wajib dipilih.',
        ]);

        $transaction = Transaction::with('feedback')
            ->findOrFail($data['transaction_id']);

        if ($transaction->status_transaksi !== 'Sudah dilayani') {
            return redirect()
                ->route('welcome')
                ->with('error', 'Transaksi belum selesai dilayani.');
        }

        if ($transaction->feedback) {
            return redirect()
                ->route('welcome')
                ->with('error', 'Transaksi ini sudah memiliki penilaian.');
        }

        Feedback::create([
            'transaction_id' => $transaction->id,
            'rating_pelayanan' => $data['rating_pelayanan'],
            'rating_fasilitas' => $data['rating_fasilitas'],
            'rating_kualitas' => $data['rating_kualitas'],
            'saran_kritik' => $data['saran_kritik'] ?? null,
        ]);

        $transaction->update([
            'status_penilaian' => 'Sudah menilai',
        ]);

        return redirect()
            ->route('welcome')
            ->with('success', 'Terima kasih atas penilaian Anda.');
    }
}