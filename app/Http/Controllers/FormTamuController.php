<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\Customer;
use App\Models\Education;
use App\Models\Media;
use App\Models\Purpose;
use App\Models\Service;
use App\Models\Work;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class FormTamuController extends Controller
{
    public function create()
    {
        return view('form-tamu', [
            'works' => Work::all(),
            'educations' => Education::all(),
            'medias' => Media::all(),
            'categories' => Categorie::with('subCategories')->get(),
            'services' => Service::all(),
            'purposes' => Purpose::all(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'no_hp' => ['required', 'string', 'max:25', 'regex:/^(?=(?:\D*\d){8,})\+?[0-9][0-9\s().-]{6,23}$/'],
            'jenis_kelamin' => 'required|string|in:Laki-laki,Perempuan',
            'email' => 'nullable|email|max:255',
            'alamat' => 'required|string|max:1000',
            'usia' => 'required|integer|min:1|max:150',
            'nama_instansi' => 'nullable|string|max:255',
            'nip_nim' => 'nullable|string|max:100',
            'work_id' => 'nullable|exists:works,id',
            'education_id' => 'nullable|exists:educations,id',
            'media_id' => 'required|exists:media,id',
            'sub_categorie_id' => 'required|exists:sub_categories,id',
            'service_id' => 'required|exists:services,id',
            'purpose_id' => 'required|exists:purposes,id',
            'data_diminta' => 'required|string|max:2000',
        ], [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'no_hp.required' => 'Nomor HP wajib diisi.',
            'no_hp.regex' => 'Format nomor HP tidak valid.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'email.email' => 'Format email tidak valid.',
            'alamat.required' => 'Alamat wajib diisi.',
            'usia.required' => 'Usia wajib diisi.',
            'usia.min' => 'Usia harus minimal 1 tahun.',
            'usia.max' => 'Usia maksimal 150 tahun.',
            'media_id.required' => 'Media layanan wajib dipilih.',
            'media_id.exists' => 'Media layanan yang dipilih tidak tersedia.',
            'sub_categorie_id.required' => 'Kebutuhan data wajib dipilih.',
            'sub_categorie_id.exists' => 'Kebutuhan data yang dipilih tidak tersedia.',
            'service_id.required' => 'Layanan wajib dipilih.',
            'service_id.exists' => 'Layanan yang dipilih tidak tersedia.',
            'purpose_id.required' => 'Tujuan wajib dipilih.',
            'purpose_id.exists' => 'Tujuan yang dipilih tidak tersedia.',
            'data_diminta.required' => 'Data yang diminta wajib diisi.',
        ]);

        $trackingCode = null;
        $feedbackToken = null;

        for ($attempt = 0; $attempt < 3; $attempt++) {
            $trackingCode = bin2hex(random_bytes(16));
            $feedbackToken = bin2hex(random_bytes(16));

            try {
                DB::transaction(function () use ($data, $trackingCode, $feedbackToken) {
                    $customer = Customer::create([
                        'nama_lengkap' => $data['nama_lengkap'],
                        'no_hp' => $data['no_hp'],
                        'jenis_kelamin' => $data['jenis_kelamin'],
                        'email' => $data['email'] ?? null,
                        'alamat' => $data['alamat'],
                        'usia' => $data['usia'],
                        'nama_instansi' => $data['nama_instansi'] ?? null,
                        'nip_nim' => $data['nip_nim'] ?? null,
                        'work_id' => $data['work_id'] ?? null,
                        'education_id' => $data['education_id'] ?? null,
                    ]);

                    Transaction::create([
                        'customer_id' => $customer->id,
                        'media_id' => $data['media_id'],
                        'sub_categorie_id' => $data['sub_categorie_id'],
                        'service_id' => $data['service_id'],
                        'purpose_id' => $data['purpose_id'],
                        'data_diminta' => $data['data_diminta'],
                        'status_transaksi' => 'Belum dilayani',
                        'status_penilaian' => 'Belum menilai',
                        'feedback_token_hash' => hash('sha256', $feedbackToken),
                        'tracking_code_hash' => hash('sha256', $trackingCode),
                        'status_updated_at' => now(),
                    ]);
                });

                break;
            } catch (QueryException $exception) {
                $isTrackingCodeCollision = in_array((string) $exception->getCode(), ['23000', '23505'], true)
                    && str_contains(strtolower($exception->getMessage()), 'tracking_code_hash');

                if (!$isTrackingCodeCollision || $attempt === 2) {
                    throw $exception;
                }
            }
        }

        $whatsappNumber = $this->normalizeWhatsappNumber($data['no_hp']);
        $whatsappUrl = null;

        if ($whatsappNumber !== null) {
            $feedbackUrl = route('feedback.create', $feedbackToken);
            $message = implode("\n", [
                'Halo, pengajuan layanan statistik Anda kepada BPS Kota Jambi telah diterima.',
                'Kode pelacakan: ' . $trackingCode,
                'Cek status pengajuan: ' . route('service-status.index'),
                'Tautan feedback: ' . $feedbackUrl,
                'Feedback dapat diberikan sebelum maupun setelah layanan selesai. Tautan feedback hanya dapat digunakan satu kali.',
            ]);

            $whatsappUrl = 'https://wa.me/' . $whatsappNumber . '?text=' . rawurlencode($message);
        }

        return redirect()->route('welcome')
            ->with('success', 'Pengajuan layanan berhasil disimpan. Simpan kode pelacakan Anda.')
            ->with('tracking_code', $trackingCode)
            ->with('whatsapp_url', $whatsappUrl)
            ->with('whatsapp_warning', $whatsappNumber === null
                ? 'Nomor HP tidak dapat digunakan untuk menyiapkan WhatsApp. Salin kode pelacakan dan simpan secara manual.'
                : null);
    }

    private function normalizeWhatsappNumber(string $phone): ?string
    {
        $phone = trim($phone);
        $isInternational = str_starts_with($phone, '+');
        $digits = preg_replace('/\D+/', '', $phone);

        if (!is_string($digits) || $digits === '') {
            return null;
        }

        if (str_starts_with($digits, '00')) {
            $digits = substr($digits, 2);
            if (!str_starts_with($digits, '62')) {
                return null;
            }
        } elseif ($isInternational) {
            if (!str_starts_with($digits, '62')) {
                return null;
            }
        } elseif (!$isInternational && str_starts_with($digits, '0')) {
            $digits = '62' . substr($digits, 1);
        } elseif (!$isInternational && str_starts_with($digits, '8')) {
            $digits = '62' . $digits;
        } elseif (!$isInternational && !str_starts_with($digits, '62')) {
            return null;
        }

        return preg_match('/\A62[1-9][0-9]{7,12}\z/', $digits) === 1 ? $digits : null;
    }
}
