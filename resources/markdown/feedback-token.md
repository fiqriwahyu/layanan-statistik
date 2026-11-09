# Tautan feedback layanan

## Penerbitan dan penyimpanan

Saat pengajuan valid dibuat, FormTamuController menghasilkan token dengan bin2hex(random_bytes(16)): 32 karakter heksadesimal huruf kecil (128 bit entropi). Aplikasi menyimpan SHA-256 token (64 karakter) pada transactions.feedback_token_hash dan menyampaikan token mentah hanya melalui tautan konfirmasi. Model Transaction menyembunyikan kedua hash token dari serialisasi.

Token mentah tidak disimpan di database dan tidak dapat ditampilkan kembali jika hilang.

## Syarat penggunaan

FeedbackLinkController tidak memeriksa status_transaksi. Token dapat dipakai ketika transaksi berstatus Belum dilayani, Sedang dilayani, atau Sudah dilayani, selama hash masih cocok dan feedback untuk transaksi itu belum ada.

Saat feedback berhasil disimpan, satu transaksi database membuat Feedback, mengubah status_penilaian menjadi Sudah menilai, dan menghapus feedback_token_hash. feedback.transaction_id memiliki unique constraint. Karena itu, token hanya dapat berhasil digunakan satu kali; pengiriman ulang ditolak.

## Kondisi token tidak valid

Token/tautan ditolak jika token mentah bukan 32 karakter hex huruf kecil, hash tidak cocok dengan transaksi, hash sudah dihapus, atau feedback terkait sudah ada. Route juga membatasi token dengan pola 32 karakter hex. Saat pengguna memasukkan URL penuh, resolver hanya menerima URL HTTP/HTTPS ke host dan port situs saat ini dengan path tepat /feedback/{token}; URL yang menyertakan user/password, query, atau fragment ditolak. URL atau token yang tidak memenuhi batas ini tidak dapat membuka form feedback.

Token hanya membuktikan penguasaan tautan rahasia, bukan identitas hukum, kepemilikan nomor HP, atau identitas pengisi. Jangan bagikan tautan kepada pihak lain.

## Masa berlaku

Tidak ada masa berlaku berbasis waktu dalam kode saat ini. Token tetap bisa digunakan selama hash tersimpan dan feedback belum dibuat, apa pun status transaksi. Jangan menyatakan token kedaluwarsa setelah durasi tertentu.
