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
        $noHp = $request->query('no_hp');
        $customer = Customer::where('no_hp', $noHp)->latest()->first();

        if (! $customer) {
            return response()->json(['found' => false]);
        }

        $transaction = $customer->transactions()->latest()->first();

        return response()->json([
            'found' => (bool) $transaction,
            'transaction_id' => $transaction?->id,
        ]);
    }

    public function create(Transaction $transaction)
    {
        return view('feedback.create', ['transaction' => $transaction]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'transaction_id' => 'required|exists:transactions,id',
            'rating_pelayanan' => 'required|integer|min:1|max:5',
            'rating_fasilitas' => 'required|integer|min:1|max:5',
            'rating_kualitas' => 'required|integer|min:1|max:5',
            'saran_kritik' => 'nullable|string|max:2000',
        ]);

        $transaction = Transaction::findOrFail($data['transaction_id']);

        if ($transaction->feedback) {
            $transaction->feedback->update([
                'rating_pelayanan' => $data['rating_pelayanan'],
                'rating_fasilitas' => $data['rating_fasilitas'],
                'rating_kualitas' => $data['rating_kualitas'],
                'saran_kritik' => $data['saran_kritik'] ?? null,
            ]);
        } else {
            Feedback::create([
                'transaction_id' => $transaction->id,
                'rating_pelayanan' => $data['rating_pelayanan'],
                'rating_fasilitas' => $data['rating_fasilitas'],
                'rating_kualitas' => $data['rating_kualitas'],
                'saran_kritik' => $data['saran_kritik'] ?? null,
            ]);
        }

        $transaction->update([
            'status_penilaian' => 'Sudah menilai',
            'status_transaksi' => 'Sudah dilayani',
        ]);

        return redirect()->route('welcome')->with('success', 'Terima kasih atas penilaian Anda.');
    }
}
