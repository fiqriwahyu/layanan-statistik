<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ServiceStatusController extends Controller
{
    public function index()
    {
        return view('service-status.index');
    }

    public function lookup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tracking_code' => ['required', 'string', 'regex:/\A[a-f0-9]{32}\z/'],
        ], [
            'tracking_code.required' => 'Masukkan kode pelacakan.',
            'tracking_code.string' => 'Kode pelacakan harus berupa teks.',
            'tracking_code.regex' => 'Format kode pelacakan tidak valid.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('service-status.index')
                ->withErrors($validator)
                ->withInput($request->except('tracking_code'));
        }

        $code = $validator->validated()['tracking_code'];
        $transaction = Transaction::query()
            ->with('service')
            ->where('tracking_code_hash', hash('sha256', $code))
            ->first();

        if (!$transaction) {
            return redirect()->route('service-status.index')
                ->withErrors(['tracking_code' => 'Kode pelacakan tidak valid atau tidak tersedia.'])
                ->withInput($request->except('tracking_code'));
        }

        $maskedCode = substr($code, 0, 4) . str_repeat('•', 8) . substr($code, -4);

        return redirect()->route('service-status.index')->with('tracking_result', [
            'code' => $maskedCode,
            'service' => $transaction->service?->service_type,
            'submitted_at' => $transaction->created_at?->format('d-m-Y H:i'),
            'status' => $transaction->status_transaksi,
            'updated_at' => $transaction->status_updated_at?->format('d-m-Y H:i'),
        ]);
    }
}
