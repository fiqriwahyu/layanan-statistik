<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Penilaian Layanan</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 min-h-screen">

    <div class="min-h-screen flex items-center justify-center px-4 py-10">

        <div class="w-full max-w-2xl bg-white rounded-xl shadow-lg p-6 md:p-8">

            <div class="text-center mb-8">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800">
                    Penilaian Layanan
                </h1>

                <p class="text-gray-500 mt-2">
                    Masukkan nomor HP yang digunakan saat melakukan pelayanan.
                </p>
            </div>

            @if (session('success'))
                <div class="mb-5 p-4 rounded-lg bg-green-50 border border-green-200 text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-5 p-4 rounded-lg bg-red-50 border border-red-200 text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            <form id="cekNoHpForm" class="space-y-5">

                <div>
                    <label for="no_hp" class="block text-sm font-semibold text-gray-700 mb-2">
                        Nomor HP
                    </label>

                    <input
                        type="text"
                        id="no_hp"
                        name="no_hp"
                        placeholder="Contoh: 08123456789"
                        autocomplete="off"
                        class="w-full h-12 px-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    >

                    <p id="errorNoHp" class="hidden text-sm text-red-500 mt-2"></p>
                </div>

                <button
                    type="submit"
                    id="btnCek"
                    class="w-full h-12 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold transition"
                >
                    Cek Transaksi
                </button>

            </form>

            <div id="loading" class="hidden text-center py-8">
                <div class="inline-block w-8 h-8 border-4 border-gray-200 border-t-blue-600 rounded-full animate-spin"></div>

                <p class="text-gray-500 mt-3">
                    Mencari transaksi...
                </p>
            </div>

            <div id="hasil" class="hidden mt-8">

                <div class="mb-4">
                    <h2 class="text-lg font-bold text-gray-800">
                        Transaksi yang Dapat Dinilai
                    </h2>

                    <p class="text-sm text-gray-500 mt-1">
                        Pilih transaksi yang ingin Anda berikan penilaian.
                    </p>
                </div>

                <div id="daftarTransaksi" class="space-y-3"></div>

            </div>

        </div>

    </div>

    <script>
        const form = document.getElementById('cekNoHpForm');
        const noHpInput = document.getElementById('no_hp');
        const btnCek = document.getElementById('btnCek');
        const loading = document.getElementById('loading');
        const hasil = document.getElementById('hasil');
        const daftarTransaksi = document.getElementById('daftarTransaksi');
        const errorNoHp = document.getElementById('errorNoHp');

        form.addEventListener('submit', async function (event) {
            event.preventDefault();

            const noHp = noHpInput.value.trim();

            errorNoHp.classList.add('hidden');
            errorNoHp.textContent = '';
            hasil.classList.add('hidden');
            daftarTransaksi.innerHTML = '';

            if (!noHp) {
                errorNoHp.textContent = 'Nomor HP wajib diisi.';
                errorNoHp.classList.remove('hidden');
                noHpInput.focus();
                return;
            }

            btnCek.disabled = true;
            btnCek.classList.add('opacity-60', 'cursor-not-allowed');
            loading.classList.remove('hidden');

            try {
                const url = "{{ route('feedback.cek-nohp') }}?no_hp=" + encodeURIComponent(noHp);

                const response = await fetch(url, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await response.json();

                console.log('Response cek HP:', data);

                if (!response.ok) {
                    throw new Error(data.message || 'Terjadi kesalahan pada server.');
                }

                if (
                    !data.found ||
                    !Array.isArray(data.transactions) ||
                    data.transactions.length === 0
                ) {
                    errorNoHp.textContent =
                        data.message || 'Tidak ada transaksi yang dapat dinilai.';

                    errorNoHp.classList.remove('hidden');
                    return;
                }

                data.transactions.forEach(function (transaction) {

                    const item = document.createElement('div');

                    item.className =
                        'border border-gray-200 rounded-lg p-4 hover:border-blue-400 hover:bg-blue-50 transition';

                    item.innerHTML = `
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                            <div class="min-w-0">

                                <div class="font-semibold text-gray-800">
                                    Transaksi #${transaction.id}
                                </div>

                                <div class="text-sm text-gray-500 mt-1">
                                    Tanggal: ${transaction.tanggal}
                                </div>

                                <div class="text-sm text-gray-600 mt-1">
                                    Data diminta: ${transaction.data_diminta}
                                </div>

                            </div>

                            <a
                                href="{{ url('/feedback') }}/${transaction.id}"
                                class="shrink-0 inline-flex items-center justify-center h-10 px-4 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold"
                            >
                                Beri Penilaian
                            </a>

                        </div>
                    `;

                    daftarTransaksi.appendChild(item);
                });

                hasil.classList.remove('hidden');

            } catch (error) {

                console.error('Error cek nomor HP:', error);

                errorNoHp.textContent =
                    error.message || 'Terjadi kesalahan saat mencari transaksi.';

                errorNoHp.classList.remove('hidden');

            } finally {

                loading.classList.add('hidden');

                btnCek.disabled = false;
                btnCek.classList.remove('opacity-60', 'cursor-not-allowed');
            }
        });
    </script>

</body>

</html>