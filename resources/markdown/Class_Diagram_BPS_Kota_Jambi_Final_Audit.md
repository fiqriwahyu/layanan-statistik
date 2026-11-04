# Ringkasan Audit Class Diagram

Audit ini mencocokkan 11 model dengan migration, controller, route, serta middleware. Diagram final menampilkan nama atribut tanpa tipe data database. Operasi pada kompartemen bawah adalah operasi sistem konseptual berdasarkan controller dan route; operasi tersebut bukan method yang didefinisikan pada model Eloquent. Model hanya mencantumkan relasi Eloquent yang benar-benar tersedia pada source Laravel.

## Class dan operasi

| Class | Atribut yang diverifikasi | Operasi sistem yang ditampilkan | Bukti utama |
|---|---|---|---|
| Work | `id`, `work_type` | lihat, tambah, edit, hapus | `Work.php`, migration works, `WorkController.php`, route `admin.work.*` |
| Education | `id`, `education_type` | lihat, tambah, edit, hapus | `Education.php`, migration educations, `EducationController.php`, route `admin.education.*` |
| Customer | `id`, `nama_lengkap`, `no_hp`, `jenis_kelamin`, `email`, `alamat`, `usia`, `nama_instansi`, `nip_nim`, `work_id`, `education_id` | lihat, daftar pemohon, ekspor Excel, ekspor PDF | `Customer.php`, migration customers, `CustomerController.php`, `FormTamuController.php`, route customer/form-tamu |
| Media | `id`, `media_type` | lihat, tambah, edit, hapus | `Media.php`, migration media, `MediaController.php`, route `admin.media.*` |
| Service | `id`, `service_type` | lihat, tambah, edit, hapus | `Service.php`, migration services, `ServiceController.php`, route `admin.service.*` |
| Purpose | `id`, `purpose_type` | lihat, tambah, edit, hapus | `Purpose.php`, migration purposes, `PurposeController.php`, route `admin.purpose.*` |
| Categorie | `id`, `categorie_type` | lihat, tambah, edit, hapus | `Categorie.php`, migration categories, `CategorieController.php`, route `admin.categorie.*` |
| SubCategorie | `id`, `sub_categorie_type`, `categorie_id` | lihat, tambah, edit, hapus | `SubCategorie.php`, migration sub_categories, `SubCategorieController.php`, route `admin.subcategorie.*` |
| Transaction | `id`, `customer_id`, `media_id`, `sub_categorie_id`, `service_id`, `purpose_id`, `data_diminta`, `feedback_token_hash`, `tracking_code_hash`, `status_transaksi`, `status_updated_at`, `status_version`, `status_penilaian`, `bukti_pelayanan`, `keterangan_pelayanan`, `dilayani_pada`, `dilayani_oleh` | ajukan, lihat, cek status, ubah status, unduh bukti, ekspor Excel, ekspor PDF | `Transaction.php`, migration transactions, `TransactionController.php`, `FormTamuController.php`, `ServiceStatusController.php`, route `admin.transaction.*` dan `service-status.*` |
| Feedback | `id`, `transaction_id`, `rating_pelayanan`, `rating_fasilitas`, `rating_kualitas`, `saran_kritik` | buka halaman, resolve token, isi form, kirim feedback | `Feedback.php`, migration feedback, `FeedbackLinkController.php`, route `feedback.*` |
| User | `id`, `name`, `email`, `email_verified_at`, `password`, `role`, `remember_token` | login, logout, lihat/tambah/edit/hapus akun | `User.php`, migration users, `AuthenticatedSessionController.php`, `AdminController.php`, `routes/auth.php`, route `superadmin.admin.*` |

`role` disimpan sebagai enum (`admin`, `superadmin`). Kolom timestamp direpresentasikan sebagai tanggal/waktu secara konseptual, tetapi tipe data sengaja tidak dicetak pada diagram. Migration menambahkan `created_at` dan `updated_at` pada model-model tersebut; atribut audit standar itu tidak dimasukkan agar diagram berfokus pada atribut domain.

Constraint yang diverifikasi: `users.email` unique; `feedback.transaction_id` wajib dan unique; `transactions.feedback_token_hash` serta `tracking_code_hash` nullable dan unique. `customers.email`, `nama_instansi`, `nip_nim`, `work_id`, dan `education_id` nullable. FK utama Transaction ke Customer/Media/Service/Purpose/SubCategorie wajib, sedangkan `dilayani_oleh` nullable dan dihapus menjadi null saat User dihapus. `users.email_verified_at` dan `remember_token`, serta `feedback.saran_kritik`, nullable.

## Relasi dan kardinalitas

| Relasi | Method Eloquent | FK dan constraint | Kardinalitas |
|---|---|---|---|
| Work–Customer | `Work::customers()`, `Customer::work()` | `customers.work_id`, nullable | Customer ke Work `0..1`; Work ke Customer `0..*` |
| Education–Customer | `Education::customers()`, `Customer::education()` | `customers.education_id`, nullable | Customer ke Education `0..1`; Education ke Customer `0..*` |
| Customer–Transaction | `Customer::transactions()`, `Transaction::customer()` | `transactions.customer_id`, wajib | Transaction ke Customer `1`; Customer ke Transaction `0..*` |
| Media–Transaction | `Media::transactions()`, `Transaction::media()` | `transactions.media_id`, wajib | Transaction ke Media `1`; Media ke Transaction `0..*` |
| Service–Transaction | `Service::transactions()`, `Transaction::service()` | `transactions.service_id`, wajib | Transaction ke Service `1`; Service ke Transaction `0..*` |
| Purpose–Transaction | `Purpose::transactions()`, `Transaction::purpose()` | `transactions.purpose_id`, wajib | Transaction ke Purpose `1`; Purpose ke Transaction `0..*` |
| Categorie–SubCategorie | `Categorie::subCategories()`, `SubCategorie::categorie()` | `sub_categories.categorie_id`, wajib | SubCategorie ke Categorie `1`; Categorie ke SubCategorie `0..*` |
| SubCategorie–Transaction | `SubCategorie::transactions()`, `Transaction::subCategorie()` | `transactions.sub_categorie_id`, wajib | Transaction ke SubCategorie `1`; SubCategorie ke Transaction `0..*` |
| Transaction–Feedback | `Transaction::feedback()`, `Feedback::transaction()` | `feedback.transaction_id`, wajib dan unique | Feedback ke Transaction `1`; Transaction ke Feedback `0..1` |
| User–Transaction | `Transaction::dilayaniOleh()` | `transactions.dilayani_oleh`, nullable, `nullOnDelete()` | Transaction ke petugas User `0..1`; User ke Transaction `0..*` |

Hash token feedback dan pelacakan pada Transaction bersifat nullable dan unique di migration. Token mentah tidak disimpan; diagram menampilkan nama atribut hash saja. `User` tidak mendefinisikan relasi Eloquent khusus. Relasi petugas didefinisikan dari sisi `Transaction` melalui `dilayaniOleh()`.

## Berkas sumber yang diperiksa

- Model: `app/Models/{Work,Education,Customer,Media,Service,Purpose,Categorie,SubCategorie,Transaction,Feedback,User}.php`
- Migration domain: `2026_05_15_152742_create_works_table.php`, `2026_05_15_152743_create_educations_table.php`, `2026_05_15_152743_create_media_table.php`, `2026_05_15_152744_create_categories_table.php`, `2026_05_15_152744_create_services_table.php`, `2026_05_15_152745_create_purposes_table.php`, `2026_05_15_152745_create_sub_categories_table.php`, `2026_05_15_152746_create_customers_table.php`, `2026_05_15_152746_create_transactions_table.php`, `2026_05_15_152747_create_feedback_table.php`, `0001_01_01_000000_create_users_table.php`
- Routes: `routes/web.php`, `routes/auth.php`
- Controller: controller admin yang disebut pada tabel serta `FormTamuController`, `ServiceStatusController`, `FeedbackLinkController`, dan `AuthenticatedSessionController`
- Middleware: `AdminMiddleware` dan `SuperAdminMiddleware`

Tidak ditemukan informasi atribut, operasi, atau relasi yang memerlukan tebakan. Operasi controller pada diagram adalah operasi sistem konseptual untuk mengikuti notasi diagram rujukan, bukan klaim bahwa operasi tersebut merupakan method model.
