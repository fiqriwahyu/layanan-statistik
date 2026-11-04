# Tautan feedback layanan

Setiap pengajuan layanan publik menghasilkan token acak khusus transaksi. Aplikasi menyimpan SHA-256 token pada transaksi, lalu menampilkan tautan rahasia sekali pada halaman konfirmasi pengajuan. Pengunjung dapat menggunakan tautan setelah transaksi berstatus **Sudah dilayani**.

Token dibuat dengan `bin2hex(random_bytes(16))`, sehingga berisi tepat 32 karakter heksadesimal huruf kecil acak (128 bit entropi). Aplikasi hanya menerima format `^[a-f0-9]{32}$`. Database menyimpan hash SHA-256 sepanjang 64 karakter, bukan token aslinya.

Tautan lama dengan token 64 karakter tidak lagi dapat digunakan. Pengunjung perlu memakai token baru yang diterbitkan pada konfirmasi pengajuan; aplikasi tidak membuat ulang token secara otomatis untuk transaksi yang sudah ada.

Token membuktikan bahwa pengirim feedback menguasai tautan rahasia tersebut. Token tidak membuktikan identitas hukum, kepemilikan nomor HP, atau bahwa orang yang memegang tautan adalah pelanggan yang tercatat. Jangan mengirimkan atau membagikan tautan kepada pihak lain.

Saat feedback berhasil disimpan, aplikasi menyimpan feedback dan menghapus hash token dalam transaksi database yang sama. Unique constraint pada `feedback.transaction_id` membatasi satu feedback per transaksi. Permintaan berulang ditolak.

## Masa berlaku

Saat ini token tidak memiliki masa berlaku berbasis waktu. Token dapat digunakan ketika transaksi berstatus **Belum dilayani**, **Sedang dilayani**, atau **Sudah dilayani**, sampai feedback berhasil dikirim atau hash token dihapus karena tindakan lain. Kebijakan durasi belum ditetapkan; jangan menyampaikan kepada pengguna bahwa tautan akan kedaluwarsa.

Sebelum menerapkan kedaluwarsa, pemilik proses perlu memilih durasi (misalnya 30 atau 90 hari) serta titik awalnya: waktu pengajuan atau waktu pelayanan selesai. Hitungan dari waktu pengajuan membatasi usia token, tetapi dapat membuat tautan kedaluwarsa sebelum layanan selesai; hitungan dari waktu selesai mempertahankan kesempatan memberi masukan sepanjang proses, tetapi token tetap aktif tanpa batas selama layanan belum selesai. Penerapan memerlukan timestamp yang konsisten, keputusan zona waktu, dan test batas waktu. Jangan menetapkan durasi permanen tanpa keputusan tersebut.
