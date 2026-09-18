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

class FormTamuController extends Controller
{
    public function cekNoHp(Request $request)
    {
        $noHp = $request->query('no_hp');
        $exists = Customer::where('no_hp', $noHp)->exists();

        return response()->json(['exists' => $exists]);
    }

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
            'no_hp' => 'required|string|max:25',
            'jenis_kelamin' => 'required|string|in:Laki-laki,Perempuan',
            'email' => 'nullable|email|max:255',
            'alamat' => 'required|string|max:1000',
            'usia' => 'required|integer|min:0|max:150',
            'nama_instansi' => 'nullable|string|max:255',
            'nip_nim' => 'nullable|string|max:100',
            'work_id' => 'nullable|exists:works,id',
            'education_id' => 'nullable|exists:educations,id',
            'media_id' => 'required|exists:media,id',
            'sub_categorie_id' => 'required|exists:sub_categories,id',
            'service_id' => 'required|exists:services,id',
            'purpose_id' => 'required|exists:purposes,id',
            'data_diminta' => 'required|string|max:2000',
        ]);

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
        ]);

        return redirect()->route('welcome')->with('success', 'Form tamu berhasil dikirim.');
    }
}
