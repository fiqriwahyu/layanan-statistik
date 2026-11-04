# Pengajuan formulir tamu

Setiap pengiriman formulir yang valid membuat satu record pelanggan dan satu transaksi baru. Pengajuan berikutnya tetap membuat transaksi tersendiri walaupun nomor HP sama. Aplikasi tidak menggabungkan pelanggan dan tidak membatasi pengajuan berdasarkan nomor HP.

Perilaku ini dipertahankan sesuai aturan bisnis yang berlaku. Jangan menambahkan unique constraint nomor HP, menolak pengajuan berulang, atau menggabungkan record secara otomatis tanpa keputusan bisnis baru. Hubungan satu pelanggan dengan beberapa transaksi perlu diputuskan secara eksplisit sebelum kebijakan perubahan diterapkan.
