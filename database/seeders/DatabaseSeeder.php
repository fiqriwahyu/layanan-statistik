<?php

namespace Database\Seeders;

use App\Models\Categorie;
use App\Models\Customer;
use App\Models\Education;
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

    public function run(): void
    {
        /*
        | USER ADMIN
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
        | USER SUPER ADMIN
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
        | MASTER PEKERJAAN
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
        | MASTER PENDIDIKAN
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
        | MASTER MEDIA PELAYANAN
        */

        $medias = collect([
            'Pelayanan statistik terpadu',
            'WhatsApp',
        ])->map(
            fn ($media) => Media::firstOrCreate([
                'media_type' => $media
            ])
        );


        /*
        | MASTER JENIS PELAYANAN
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
        | MASTER TUJUAN
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
        | MASTER KATEGORI DAN SUB KATEGORI
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
        | DATA CONTOH PENGUNJUNG
        */

        $customers = collect([

            [
                'no_hp' => '081234567890',
                'nama_lengkap' => 'Budi Santoso',
                'jenis_kelamin' => 'Laki-laki',
                'email' => 'budi@example.com',
                'alamat' => 'Jl. Sudirman No. 1, Kota Jambi',
                'usia' => 30,
                'nama_instansi' => 'BPS Kota Jambi',
                'nip_nim' => '1987654321',
                'work' => 'Pegawai Negeri',
                'education' => 'SMA/SMK',
            ],

            [
                'no_hp' => '081234567891',
                'nama_lengkap' => 'Siti Rahma',
                'jenis_kelamin' => 'Perempuan',
                'email' => 'siti@example.com',
                'alamat' => 'Jl. Kol Pol M Taher, Kota Jambi',
                'usia' => 25,
                'nama_instansi' => 'Universitas Jambi',
                'nip_nim' => '2020123456',
                'work' => 'Pelajar/Mahasiswa',
                'education' => 'Sarjana',
            ],

            [
                'no_hp' => '081234567892',
                'nama_lengkap' => 'Andi Saputra',
                'jenis_kelamin' => 'Laki-laki',
                'email' => 'andi@example.com',
                'alamat' => 'Jl. Patimura, Kota Jambi',
                'usia' => 32,
                'nama_instansi' => 'Dinas Pemerintah',
                'nip_nim' => '1990123456',
                'work' => 'Pegawai Negeri',
                'education' => 'Sarjana',
            ],

            [
                'no_hp' => '081234567893',
                'nama_lengkap' => 'Rina Marlina',
                'jenis_kelamin' => 'Perempuan',
                'email' => 'rina@example.com',
                'alamat' => 'Jl. Jenderal Ahmad Yani, Kota Jambi',
                'usia' => 28,
                'nama_instansi' => 'Universitas Batanghari',
                'nip_nim' => '2021123456',
                'work' => 'Pelajar/Mahasiswa',
                'education' => 'Pascasarjana',
            ],

            [
                'no_hp' => '081234567894',
                'nama_lengkap' => 'Dedi Irawan',
                'jenis_kelamin' => 'Laki-laki',
                'email' => 'dedi@example.com',
                'alamat' => 'Jl. Lingkar Selatan, Kota Jambi',
                'usia' => 40,
                'nama_instansi' => 'Perusahaan Swasta',
                'nip_nim' => '1985123456',
                'work' => 'Swasta',
                'education' => 'Diploma',
            ],

            [
                'no_hp' => '081234567895',
                'nama_lengkap' => 'Maya Sari',
                'jenis_kelamin' => 'Perempuan',
                'email' => 'maya@example.com',
                'alamat' => 'Jl. HOS Cokroaminoto, Kota Jambi',
                'usia' => 35,
                'nama_instansi' => 'Usaha Mandiri',
                'nip_nim' => '1991123456',
                'work' => 'Wirausaha',
                'education' => 'SMA/SMK',
            ],

        ]);


        /*
        | SIMPAN DATA PENGUNJUNG
        */

        $customerModels = collect();

        foreach ($customers as $data) {

            $customer = Customer::updateOrCreate(
                [
                    'no_hp' => $data['no_hp']
                ],
                [
                    'nama_lengkap' => $data['nama_lengkap'],
                    'jenis_kelamin' => $data['jenis_kelamin'],
                    'email' => $data['email'],
                    'alamat' => $data['alamat'],
                    'usia' => $data['usia'],
                    'nama_instansi' => $data['nama_instansi'],
                    'nip_nim' => $data['nip_nim'],

                    'work_id' => $works
                        ->firstWhere('work_type', $data['work'])
                        ->id,

                    'education_id' => $educations
                        ->firstWhere('education_type', $data['education'])
                        ->id,
                ]
            );

            $customerModels->push($customer);
        }


        /*
        | DATA MASTER UNTUK TRANSAKSI
        */

        $subKependudukan = SubCategorie::where(
            'sub_categorie_type',
            'Data Kependudukan'
        )->first();

        $subEkonomi = SubCategorie::where(
            'sub_categorie_type',
            'Data Ekonomi'
        )->first();

        $subSosial = SubCategorie::where(
            'sub_categorie_type',
            'Data Sosial'
        )->first();


        $mediaWhatsApp = $medias->firstWhere(
            'media_type',
            'WhatsApp'
        );

        $mediaPst = $medias->firstWhere(
            'media_type',
            'Pelayanan statistik terpadu'
        );


        $servicePermintaan = $services->firstWhere(
            'service_type',
            'Permintaan Data'
        );

        $serviceKonsultasi = $services->firstWhere(
            'service_type',
            'Konsultasi Statistik'
        );

        $serviceDokumen = $services->firstWhere(
            'service_type',
            'Layanan Dokumen'
        );

        $serviceMonitoring = $services->firstWhere(
            'service_type',
            'Monitoring Data'
        );


        $purposePendidikan = $purposes->firstWhere(
            'purpose_type',
            'Pendidikan'
        );

        $purposePenelitian = $purposes->firstWhere(
            'purpose_type',
            'Penelitian'
        );

        $purposeBisnis = $purposes->firstWhere(
            'purpose_type',
            'Bisnis'
        );

        $purposeAdministrasi = $purposes->firstWhere(
            'purpose_type',
            'Administrasi'
        );

        $purposeLainnya = $purposes->firstWhere(
            'purpose_type',
            'Lainnya'
        );


        /*
        | DATA CONTOH TRANSAKSI
        */

        if (
            $subKependudukan &&
            $subEkonomi &&
            $subSosial &&
            $mediaWhatsApp &&
            $mediaPst &&
            $servicePermintaan &&
            $serviceKonsultasi &&
            $serviceDokumen &&
            $serviceMonitoring &&
            $purposePendidikan &&
            $purposePenelitian &&
            $purposeBisnis &&
            $purposeAdministrasi &&
            $purposeLainnya
        ) {

            $transactionData = [

                [
                    'customer_id' => $customerModels[0]->id,
                    'media_id' => $mediaWhatsApp->id,
                    'sub_categorie_id' => $subKependudukan->id,
                    'service_id' => $servicePermintaan->id,
                    'purpose_id' => $purposePendidikan->id,
                    'data_diminta' => 'Data jumlah penduduk Kota Jambi tahun 2020-2024 berdasarkan kecamatan',
                ],

                [
                    'customer_id' => $customerModels[1]->id,
                    'media_id' => $mediaPst->id,
                    'sub_categorie_id' => $subEkonomi->id,
                    'service_id' => $serviceKonsultasi->id,
                    'purpose_id' => $purposePenelitian->id,
                    'data_diminta' => 'Konsultasi data ekonomi Kota Jambi untuk kebutuhan penelitian',
                ],

                [
                    'customer_id' => $customerModels[2]->id,
                    'media_id' => $mediaWhatsApp->id,
                    'sub_categorie_id' => $subSosial->id,
                    'service_id' => $serviceDokumen->id,
                    'purpose_id' => $purposeAdministrasi->id,
                    'data_diminta' => 'Permintaan dokumen statistik sosial Kota Jambi',
                ],

                [
                    'customer_id' => $customerModels[3]->id,
                    'media_id' => $mediaPst->id,
                    'sub_categorie_id' => $subKependudukan->id,
                    'service_id' => $servicePermintaan->id,
                    'purpose_id' => $purposePenelitian->id,
                    'data_diminta' => 'Data penduduk berdasarkan kelompok umur dan jenis kelamin',
                ],

                [
                    'customer_id' => $customerModels[4]->id,
                    'media_id' => $mediaWhatsApp->id,
                    'sub_categorie_id' => $subEkonomi->id,
                    'service_id' => $serviceMonitoring->id,
                    'purpose_id' => $purposeBisnis->id,
                    'data_diminta' => 'Data perkembangan ekonomi dan kegiatan usaha Kota Jambi',
                ],

                [
                    'customer_id' => $customerModels[5]->id,
                    'media_id' => $mediaPst->id,
                    'sub_categorie_id' => $subSosial->id,
                    'service_id' => $serviceKonsultasi->id,
                    'purpose_id' => $purposeLainnya->id,
                    'data_diminta' => 'Konsultasi mengenai data sosial Kota Jambi',
                ],

            ];


            foreach ($transactionData as $data) {

                Transaction::updateOrCreate(
                    [
                        'customer_id' => $data['customer_id'],
                        'media_id' => $data['media_id'],
                        'sub_categorie_id' => $data['sub_categorie_id'],
                        'service_id' => $data['service_id'],
                        'purpose_id' => $data['purpose_id'],
                    ],
                    [
                        'data_diminta' => $data['data_diminta'],
                        'status_transaksi' => 'Belum dilayani',
                        'status_penilaian' => 'Belum menilai',
                    ]
                );
            }
        }
    }
}