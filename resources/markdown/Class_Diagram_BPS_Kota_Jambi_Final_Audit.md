# Audit Class Diagram BPS Kota Jambi

Dokumen ini membedakan struktur database, method Eloquent yang didefinisikan pada model, dan operasi sistem yang ditangani Controller. Nama model Categorie dan SubCategorie dipertahankan persis sesuai source code. Operasi Controller bukan method Model.

## Notasi tipe

id = BIGINT UNSIGNED AUTO_INCREMENT dari Laravel $table->id(); foreignId = BIGINT UNSIGNED; string = VARCHAR(255) kecuali panjang dinyatakan; timestamps = created_at dan updated_at bertipe TIMESTAMP nullable. Nullable dan default ditulis sesuai migration.

## Atribut dan tipe data dari migration

| Model / tabel | Kolom |
|---|---|
| Work / works | id BIGINT UNSIGNED; work_type VARCHAR(255); created_at, updated_at TIMESTAMP nullable |
| Education / educations | id BIGINT UNSIGNED; education_type VARCHAR(255); created_at, updated_at TIMESTAMP nullable |
| Customer / customers | id BIGINT UNSIGNED; nama_lengkap, no_hp, jenis_kelamin VARCHAR(255); email VARCHAR(255) nullable; alamat TEXT; usia INT; nama_instansi, nip_nim VARCHAR(255) nullable; work_id, education_id BIGINT UNSIGNED nullable FK; timestamps |
| Media / media | id BIGINT UNSIGNED; media_type VARCHAR(255); timestamps |
| Service / services | id BIGINT UNSIGNED; service_type VARCHAR(255); timestamps |
| Purpose / purposes | id BIGINT UNSIGNED; purpose_type VARCHAR(255); timestamps |
| Categorie / categories | id BIGINT UNSIGNED; categorie_type VARCHAR(255); timestamps |
| SubCategorie / sub_categories | id BIGINT UNSIGNED; sub_categorie_type VARCHAR(255); categorie_id BIGINT UNSIGNED FK; timestamps |
| Transaction / transactions | id BIGINT UNSIGNED; customer_id, media_id, sub_categorie_id, service_id, purpose_id BIGINT UNSIGNED required FK; data_diminta TEXT; feedback_token_hash, tracking_code_hash VARCHAR(64) nullable unique; status_transaksi VARCHAR(255), default Belum dilayani; status_updated_at TIMESTAMP nullable; status_version INT UNSIGNED, default 0; bukti_pelayanan VARCHAR(255) nullable; keterangan_pelayanan TEXT nullable; dilayani_pada TIMESTAMP nullable; dilayani_oleh BIGINT UNSIGNED nullable FK; status_penilaian VARCHAR(255), default Belum menilai; timestamps |
| Feedback / feedback | id BIGINT UNSIGNED; transaction_id BIGINT UNSIGNED required unique FK; rating_pelayanan, rating_fasilitas, rating_kualitas INT; saran_kritik TEXT nullable; timestamps |
| User / users | id BIGINT UNSIGNED; name, email VARCHAR(255), email unique; email_verified_at TIMESTAMP nullable; profile_photo_path VARCHAR(255) nullable; password VARCHAR(255); role ENUM(admin, superadmin); remember_token VARCHAR(100) nullable; timestamps |

Migration awal juga membuat tabel password_reset_tokens dan sessions; tabel framework tersebut bukan bagian dari 11 model domain di atas.

## Method Eloquent yang benar-benar ada

| Model | Relasi / hook yang didefinisikan |
|---|---|
| Work | customers(): hasMany(Customer) |
| Education | customers(): hasMany(Customer) |
| Customer | work(): belongsTo(Work); education(): belongsTo(Education); transactions(): hasMany(Transaction) |
| Media | transactions(): hasMany(Transaction) |
| Service | transactions(): hasMany(Transaction) |
| Purpose | transactions(): hasMany(Transaction) |
| Categorie | subCategories(): hasMany(SubCategorie) |
| SubCategorie | categorie(): belongsTo(Categorie); transactions(): hasMany(Transaction) |
| Transaction | customer(), media(), service(), purpose(), subCategorie(), dilayaniOleh(): belongsTo; feedback(): hasOne(Feedback) |
| Feedback | transaction(): belongsTo(Transaction) |
| User | sendPasswordResetNotification(token); sendEmailVerificationNotification() |

Transaction.dilayaniOleh() memakai FK dilayani_oleh. User menggunakan kontrak/trait verifikasi email Laravel; dua hook notifikasi di atas didefinisikan pada User. User tidak mendefinisikan relasi Eloquent balik.

## Relasi dan kardinalitas

| Relasi | Method Eloquent | FK | Kardinalitas |
|---|---|---|---|
| Work–Customer | Work.customers(), Customer.work() | customers.work_id nullable | Customer ke Work 0..1; Work ke Customer 0..* |
| Education–Customer | Education.customers(), Customer.education() | customers.education_id nullable | Customer ke Education 0..1; Education ke Customer 0..* |
| Customer–Transaction | Customer.transactions(), Transaction.customer() | transactions.customer_id wajib | Transaction ke Customer 1; Customer ke Transaction 0..* |
| Media–Transaction | Media.transactions(), Transaction.media() | transactions.media_id wajib | Transaction ke Media 1; Media ke Transaction 0..* |
| Service–Transaction | Service.transactions(), Transaction.service() | transactions.service_id wajib | Transaction ke Service 1; Service ke Transaction 0..* |
| Purpose–Transaction | Purpose.transactions(), Transaction.purpose() | transactions.purpose_id wajib | Transaction ke Purpose 1; Purpose ke Transaction 0..* |
| Categorie–SubCategorie | Categorie.subCategories(), SubCategorie.categorie() | sub_categories.categorie_id wajib | SubCategorie ke Categorie 1; Categorie ke SubCategorie 0..* |
| SubCategorie–Transaction | SubCategorie.transactions(), Transaction.subCategorie() | transactions.sub_categorie_id wajib | Transaction ke SubCategorie 1; SubCategorie ke Transaction 0..* |
| Transaction–Feedback | Transaction.feedback(), Feedback.transaction() | feedback.transaction_id wajib dan unique | Feedback ke Transaction 1; Transaction ke Feedback 0..1 |
| User–Transaction | Transaction.dilayaniOleh() | transactions.dilayani_oleh nullable; nullOnDelete | Transaction ke petugas User 0..1; User ke Transaction 0..* |

## Operasi Controller (bukan method Model)

| Model terkait | Operasi sistem | Controller / route |
|---|---|---|
| Work, Education, Media, Service, Purpose, Categorie, SubCategorie | Daftar, tambah, ubah, hapus | Controller admin masing-masing; route admin.work.*, admin.education.*, admin.media.*, admin.service.*, admin.purpose.*, admin.categorie.*, admin.subcategorie.* |
| Customer | Dibuat saat pengajuan; daftar, detail, ekspor Excel/PDF | FormTamuController, CustomerController; form-tamu.* dan admin.customer.* |
| Transaction | Pengajuan, cek status, daftar/detail, ubah status, unduh bukti, ekspor Excel/PDF | FormTamuController, ServiceStatusController, TransactionController; form-tamu.*, service-status.*, admin.transaction.* |
| Feedback | Buka form, resolve token, kirim feedback | FeedbackLinkController; feedback.* |
| User | Login/logout, reset/verifikasi email, profil/foto, pengelolaan akun admin | Auth controllers, ProfileController, AdminController; routes/auth.php, profile.*, superadmin.admin.* |

## Catatan token feedback

Token mentah dibuat dengan bin2hex(random_bytes(16)); yang disimpan hanya SHA-256 pada transactions.feedback_token_hash. Tidak ada pemeriksaan status transaksi dan tidak ada kedaluwarsa waktu. Hash dihapus ketika feedback disimpan; feedback.transaction_id unique mencegah feedback kedua untuk transaksi yang sama.

## Sumber pencocokan

Model: app/Models/{Work,Education,Customer,Media,Service,Purpose,Categorie,SubCategorie,Transaction,Feedback,User}.php. Struktur kolom/constraint berasal dari migration users dan sepuluh migration domain. Operasi sistem dicocokkan dengan controller terkait, routes/web.php, routes/auth.php, AdminMiddleware, dan SuperAdminMiddleware.
