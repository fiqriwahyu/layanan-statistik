<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FeedbackLinkController extends Controller
{
    public function index()
    {
        return view('feedback.link-index');
    }

    public function resolve(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'feedback_link' => ['required', 'string', 'max:2048'],
        ], [
            'feedback_link.required' => 'Masukkan token atau tautan feedback.',
            'feedback_link.string' => 'Token atau tautan feedback harus berupa teks.',
            'feedback_link.max' => 'Token atau tautan feedback terlalu panjang.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('feedback.index')->withErrors($validator)->withInput();
        }

        $token = $this->extractToken($validator->validated()['feedback_link'], $request);
        if (!$token) {
            return redirect()->route('feedback.index')->withErrors([
            'feedback_link' => 'Masukkan token baru berupa 32 karakter heksadesimal huruf kecil atau tautan feedback dari situs ini. Token lama 64 karakter tidak lagi dapat digunakan.',
            ])->withInput();
        }

        $transaction = $this->findByToken($token);
        if (!$transaction || $transaction->feedback()->exists()) {
            return redirect()->route('feedback.index')->withErrors([
                'feedback_link' => 'Tautan feedback tidak valid atau sudah digunakan.',
            ])->withInput();
        }

        return redirect()->route('feedback.create', $token);
    }

    public function create(string $token)
    {
        $transaction = $this->findByToken($token);
        abort_unless($transaction, 404);
        if ($transaction->feedback()->exists()) {
            return redirect()->route('feedback.index')->with('error', 'Tautan feedback tidak tersedia atau sudah digunakan.');
        }

        return response()
            ->view('feedback.create', compact('transaction', 'token'))
            ->header('Referrer-Policy', 'no-referrer');
    }

    public function store(Request $request, string $token)
    {
        $validated = $request->validate([
            'rating_pelayanan' => ['required', 'integer', 'min:1', 'max:5'],
            'rating_fasilitas' => ['required', 'integer', 'min:1', 'max:5'],
            'rating_kualitas' => ['required', 'integer', 'min:1', 'max:5'],
            'saran_kritik' => ['nullable', 'string', 'max:2000'],
        ]);

        $created = DB::transaction(function () use ($token, $validated) {
            $transaction = Transaction::where('feedback_token_hash', hash('sha256', $token))->lockForUpdate()->first();
            if (!$transaction || $transaction->feedback()->exists()) {
                return false;
            }

            Feedback::create([
                'transaction_id' => $transaction->id,
                'rating_pelayanan' => $validated['rating_pelayanan'],
                'rating_fasilitas' => $validated['rating_fasilitas'],
                'rating_kualitas' => $validated['rating_kualitas'],
                'saran_kritik' => $validated['saran_kritik'] ?? null,
            ]);
            $transaction->update(['status_penilaian' => 'Sudah menilai', 'feedback_token_hash' => null]);
            return true;
        });

        if (!$created) {
            return redirect()->route('feedback.index')->with('error', 'Tautan feedback tidak tersedia atau sudah digunakan.');
        }

        return redirect()->route('feedback.index')->with('success', 'Terima kasih. Penilaian Anda berhasil dikirim.');
    }

    private function findByToken(string $token): ?Transaction
    {
        return Transaction::where('feedback_token_hash', hash('sha256', $token))->first();
    }

    private function extractToken(string $input, Request $request): ?string
    {
        $input = trim($input);
        if (preg_match('/\A[a-f0-9]{32}\z/', $input)) {
            return $input;
        }

        try {
            $parts = parse_url($input);
        } catch (\ValueError $exception) {
            return null;
        }

        if (!is_array($parts)
            || !isset($parts['scheme'], $parts['host'], $parts['path'])
            || !in_array(strtolower($parts['scheme']), ['http', 'https'], true)
            || array_intersect(['user', 'pass', 'query', 'fragment'], array_keys($parts)) !== []
            || strcasecmp($parts['host'], $request->getHost()) !== 0) {
            return null;
        }

        $expectedPort = strtolower($parts['scheme']) === 'https' ? 443 : 80;
        $inputPort = $parts['port'] ?? $expectedPort;
        if ($inputPort !== $request->getPort()) {
            return null;
        }

        if (preg_match('~\A/feedback/([a-f0-9]{32})\z~', $parts['path'], $matches) !== 1) {
            return null;
        }

        return $matches[1];
    }
}
