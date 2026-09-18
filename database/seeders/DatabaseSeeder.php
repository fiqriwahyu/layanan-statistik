<?php

namespace Database\Seeders;

use App\Models\Categorie;
use App\Models\Customer;
use App\Models\Education;
use App\Models\Feedback;
use App\Models\Media;
use App\Models\Purpose;
use App\Models\Service;
use App\Models\SubCategorie;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Work;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | USER ADMIN
        |--------------------------------------------------------------------------
        */

        User::updateOrCreate(
            ['email' => 'admin@bps.go.id'],
            [
                'name' => 'Admin BPS',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | USER SUPER ADMIN
        |--------------------------------------------------------------------------
        */

        User::updateOrCreate(
            ['email' => 'superadmin@bps.go.id'],
            [
                'name' => 'Super Admin BPS',
                'password' => Hash::make('password'),
                'role' => 'superadmin',
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | MASTER PEKERJAAN
        |--------------------------------------------------------------------------
        */

        $works = collect([
            'Pegawai Negeri',
            'Wirausaha',
            'Pelajar/Mahasiswa',
            'Swasta',
            'Ibu Rumah Tangga',
            'Lainnya',
        ])->map(
            fn ($work) => Work::firstOrCreate([
                'work_type' => $work
            ])
        );


        /*
        |--------------------------------------------------------------------------
        | MASTER PENDIDIKAN
        |--------------------------------------------------------------------------
        */

        $educations = collect([
            'SD',
            'SMP',
            'SMA/SMK',
            'Diploma',
            'Sarjana',
            'Pascasarjana',
        ])->map(
            fn ($education) => Education::firstOrCreate([
                'education_type' => $education
            ])
        );


        /*
        |--------------------------------------------------------------------------
        | MASTER MEDIA PELAYANAN
        |--------------------------------------------------------------------------
        */

        $medias = collect([
            'Online',
            'Telepon',
            'Datang Langsung',
            'Email',
            'WhatsApp',
        ])->map(
            fn ($media) => Media::firstOrCreate([
                'media_type' => $media
            ])
        );


        /*
        |--------------------------------------------------------------------------
        | MASTER JENIS PELAYANAN
        |--------------------------------------------------------------------------
        */

        $services = collect([
            'Permintaan Data',
            'Konsultasi Statistik',
            'Layanan Dokumen',
            'Monitoring Data',
        ])->map(
            fn ($service) => Service::firstOrCreate([
                'service_type' => $service
            ])
        );


        /*
        |--------------------------------------------------------------------------
        | MASTER TUJUAN
        |--------------------------------------------------------------------------
        */

        $purposes = collect([
            'Pendidikan',
            'Penelitian',
            'Bisnis',
            'Administrasi',
            'Lainnya',
        ])->map(
            fn ($purpose) => Purpose::firstOrCreate([
                'purpose_type' => $purpose
            ])
        );


        /*
        |--------------------------------------------------------------------------
        | MASTER KATEGORI DAN SUB KATEGORI
        |--------------------------------------------------------------------------
        */

        $categories = [
            'Layanan Statistik' => [
                'Data Kependudukan',
                'Data Ekonomi',
                'Data Sosial',
            ],

            'Layanan Informasi' => [
                'Permohonan Dokumen',
                'Konsultasi',
                'Akses Layanan',
            ],
        ];

        foreach ($categories as $categorieType => $subTypes) {

            $categorie = Categorie::firstOrCreate([
                'categorie_type' => $categorieType
            ]);

            foreach ($subTypes as $subType) {

                SubCategorie::firstOrCreate([
                    'sub_categorie_type' => $subType,
                    'categorie_id' => $categorie->id,
                ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | DATA CONTOH PENGUNJUNG
        |--------------------------------------------------------------------------
        */

        $customer = Customer::updateOrCreate(
            [
                'no_hp' => '081234567890'
            ],
            [
                'nama_lengkap' => 'Budi Santoso',
                'jenis_kelamin' => 'Laki-laki',
                'email' => 'budi@example.com',
                'alamat' => 'Jl. Sudirman No. 1, Kota Jambi',
                'usia' => 30,
                'nama_instansi' => 'BPS Kota Jambi',
                'nip_nim' => '1987654321',
                'work_id' => $works->first()->id,
                'education_id' => $educations
                    ->firstWhere('education_type', 'SMA/SMK')
                    ->id,
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | DATA CONTOH TRANSAKSI
        |--------------------------------------------------------------------------
        */

        $subCategorie = SubCategorie::where(
            'sub_categorie_type',
            'Data Kependudukan'
        )->first();

        if ($subCategorie) {

            $transaction = Transaction::updateOrCreate(
                [
                    'customer_id' => $customer->id,
                    'media_id' => $medias
                        ->firstWhere('media_type', 'Online')
                        ->id,
                    'sub_categorie_id' => $subCategorie->id,
                    'service_id' => $services
                        ->firstWhere('service_type', 'Permintaan Data')
                        ->id,
                    'purpose_id' => $purposes
                        ->firstWhere('purpose_type', 'Pendidikan')
                        ->id,
                ],
                [
                    'data_diminta' =>
                        'Data jumlah penduduk Kota Jambi tahun 2020–2024 berdasarkan kecamatan',

                    'status_transaksi' => 'Belum dilayani',

                    'status_penilaian' => 'Belum menilai',
                ]
            );


            /*
            |--------------------------------------------------------------------------
            | DATA CONTOH FEEDBACK
            |--------------------------------------------------------------------------
            */

            Feedback::firstOrCreate(
                [
                    'transaction_id' => $transaction->id
                ],
                [
                    'rating_pelayanan' => 4,
                    'rating_fasilitas' => 4,
                    'rating_kualitas' => 5,
                    'saran_kritik' => 'Pelayanan baik dan cepat.',
                ]
            );
        }
    }
}