@php
    $flashMessage = session('error') ?: session('success');
    $flashIsError = (bool) session('error');
    $trackingCode = session('tracking_code');
    $whatsappUrl = session('whatsapp_url');
    $whatsappWarning = session('whatsapp_warning');
@endphp

@if ($flashMessage)
    <div id="flash-result-modal" class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-950/60 p-4 opacity-0 transition-opacity duration-200" role="presentation">
        <div data-flash-dialog role="{{ $flashIsError ? 'alertdialog' : 'dialog' }}" aria-modal="true" aria-labelledby="flash-result-title" aria-describedby="flash-result-message" tabindex="-1" class="w-full max-w-md translate-y-2 scale-95 rounded-2xl border border-gray-200 bg-white p-6 opacity-0 shadow-2xl transition duration-200 sm:p-7">
            <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full {{ $flashIsError ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}" aria-hidden="true">
                @if ($flashIsError)
                    <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.3 3.9 2.7 17a2 2 0 0 0 1.7 3h15.2a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0Z"/></svg>
                @else
                    <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m5 12 4 4L19 6"/></svg>
                @endif
            </div>
            <h2 id="flash-result-title" class="text-center text-xl font-bold text-[#385683]">{{ $flashIsError ? 'Belum berhasil' : ($trackingCode ? 'Pengajuan Berhasil' : 'Berhasil') }}</h2>
            <p id="flash-result-message" class="mt-2 text-center text-sm leading-relaxed text-gray-700">{{ $flashMessage }}</p>

            @if ($trackingCode)
                <div class="mt-5 rounded-xl border border-[#385683]/20 bg-[#385683]/5 p-4 text-center">
                    <p class="text-sm font-semibold text-gray-700">Kode pelacakan Anda</p>
                    <p class="mt-2 break-all font-mono text-lg font-bold tracking-wide text-[#385683]">{{ $trackingCode }}</p>
                    <button type="button" data-copy-tracking class="mt-3 rounded-lg bg-[#385683] px-4 py-2 text-sm font-semibold text-white hover:bg-[#2f496f] focus:outline-none focus:ring-2 focus:ring-[#385683] focus:ring-offset-2">Salin Kode</button>
                    <span data-copy-tracking-status class="sr-only" role="status" aria-live="polite"></span>
                    <p class="mt-2 text-xs leading-relaxed text-gray-600">Simpan kode ini. Gunakan pada halaman Cek Status Layanan untuk melihat perkembangan pengajuan.</p>
                </div>
                @if ($whatsappUrl)
                    <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer" class="mt-4 flex min-h-11 items-center justify-center rounded-lg bg-[#128c7e] px-4 py-3 text-center text-sm font-semibold text-white hover:bg-[#0f766e] focus:outline-none focus:ring-2 focus:ring-[#128c7e] focus:ring-offset-2">Kirim Informasi ke WhatsApp</a>
                    <p class="mt-2 text-center text-xs leading-relaxed text-gray-600">WhatsApp akan membuka draf pesan. Periksa kembali penerima dan isi pesan, lalu kirim sendiri jika sudah sesuai.</p>
                @elseif ($whatsappWarning)
                    <p class="mt-4 rounded-lg border border-amber-300 bg-amber-50 p-3 text-sm leading-relaxed text-amber-900" role="status">{{ $whatsappWarning }}</p>
                @endif
            @endif

            <div class="mt-6 flex justify-center">
                <button type="button" data-close-flash class="min-h-11 rounded-lg border border-gray-300 px-6 py-2 font-semibold text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-[#385683] focus:ring-offset-2">Tutup</button>
            </div>
        </div>
    </div>

    <script>
        (() => {
            const overlay = document.getElementById('flash-result-modal');
            if (!overlay) return;

            const dialog = overlay.querySelector('[data-flash-dialog]');
            const closeButton = overlay.querySelector('[data-close-flash]');
            const previousFocus = document.activeElement;
            const previousOverflow = document.body.style.overflow;
            let closed = false;

            document.body.style.overflow = 'hidden';
            requestAnimationFrame(() => {
                overlay.classList.remove('opacity-0');
                dialog.classList.remove('translate-y-2', 'scale-95', 'opacity-0');
                dialog.focus();
            });

            const close = () => {
                if (closed) return;
                closed = true;
                overlay.classList.add('opacity-0');
                dialog.classList.add('translate-y-2', 'scale-95');
                window.setTimeout(() => {
                    overlay.remove();
                    document.body.style.overflow = previousOverflow;
                    if (previousFocus && previousFocus.isConnected && typeof previousFocus.focus === 'function') previousFocus.focus();
                }, 200);
            };

            closeButton.addEventListener('click', close);
            overlay.addEventListener('click', event => { if (event.target === overlay) close(); });
            document.addEventListener('keydown', event => {
                if (closed) return;
                if (event.key === 'Escape') {
                    event.preventDefault();
                    close();
                    return;
                }
                if (event.key === 'Tab') {
                    const focusable = [...dialog.querySelectorAll('button:not([disabled]), a[href], input:not([disabled])')];
                    if (!focusable.length) return;
                    const first = focusable[0];
                    const last = focusable[focusable.length - 1];
                    if (document.activeElement === dialog) { event.preventDefault(); (event.shiftKey ? last : first).focus(); }
                    else if (event.shiftKey && document.activeElement === first) { event.preventDefault(); last.focus(); }
                    else if (!event.shiftKey && document.activeElement === last) { event.preventDefault(); first.focus(); }
                }
            });

            const copyButton = overlay.querySelector('[data-copy-tracking]');
            if (copyButton) {
                copyButton.addEventListener('click', async () => {
                    const code = dialog.querySelector('.font-mono').textContent.trim();
                    const status = overlay.querySelector('[data-copy-tracking-status]');
                    try {
                        if (navigator.clipboard && window.isSecureContext) {
                            await navigator.clipboard.writeText(code);
                        } else {
                            const temporary = document.createElement('textarea');
                            temporary.value = code;
                            temporary.setAttribute('readonly', '');
                            temporary.style.position = 'fixed';
                            temporary.style.opacity = '0';
                            document.body.appendChild(temporary);
                            temporary.select();
                            const copied = document.execCommand('copy');
                            temporary.remove();
                            if (!copied) throw new Error('copy-failed');
                        }
                        status.textContent = 'Kode berhasil disalin.';
                    } catch (error) {
                        status.textContent = 'Penyalinan otomatis gagal. Kode masih terlihat di jendela ini; salin secara manual sebelum menutupnya.';
                    }
                    status.classList.remove('sr-only');
                });
            }
        })();
    </script>
@endif
