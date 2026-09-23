<div x-data="{ sidebarOpen: false, openMenu: true }">

    <button
        type="button"
        @click="sidebarOpen = true"
        class="fixed top-4 left-4 z-30 lg:hidden bg-[#3E5C8A] text-white p-2.5 rounded-lg shadow-md hover:bg-[#334D75] transition">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
    </button>

    <div
        x-show="sidebarOpen"
        x-transition.opacity
        @click="sidebarOpen = false"
        class="fixed inset-0 bg-black/40 z-40 lg:hidden">
    </div>

    <aside
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
        class="fixed lg:static inset-y-0 left-0 z-50 w-64 bg-[#3E5C8A] text-white flex flex-col min-h-screen shadow-lg lg:shadow-none transform transition-transform duration-300">

        <div class="flex items-center justify-between px-5 py-5 border-b border-white/20">

            <div class="flex items-center gap-3 min-w-0">

                <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center shrink-0 shadow-sm">
                    <img
                        src="{{ asset('images/icons/logo-bps.png') }}"
                        class="h-8 w-auto"
                        alt="BPS">
                </div>

                <div class="min-w-0">
                    <p class="font-bold text-sm truncate">
                        Badan Pusat Statistik
                    </p>

                    <p class="text-xs text-white/70">
                        Kota Jambi
                    </p>
                </div>

            </div>

            <button
                type="button"
                @click="sidebarOpen = false"
                class="lg:hidden p-1.5 rounded-md text-white/70 hover:text-white hover:bg-white/10 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

        </div>

        <nav class="flex-1 px-3 py-4 overflow-y-auto">

            <a
                href="{{ route('admin.dashboard') }}"
                class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-medium transition
                {{ request()->routeIs('admin.dashboard')
                    ? 'bg-white text-[#3E5C8A] shadow-sm'
                    : 'text-white/90 hover:bg-white/10' }}">

                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l9-9 9 9M5 10v10h14V10M9 20v-6h6v6"/>
                </svg>

                <span>Beranda</span>
            </a>

            @if (auth()->check() && strtolower(trim(auth()->user()->role)) === 'superadmin')
                <a
                    href="{{ route('superadmin.admin.index') }}"
                    class="flex items-center gap-3 px-3.5 py-2.5 mt-2 rounded-lg text-sm font-medium transition
                    {{ request()->routeIs('superadmin.admin.*')
                        ? 'bg-white text-[#3E5C8A] shadow-sm'
                        : 'text-white/90 hover:bg-white/10' }}">

                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2M9 11a4 4 0 100-8 4 4 0 000 8M22 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
                    </svg>

                    <span>Admin</span>
                </a>
            @endif

            <button
                type="button"
                @click="openMenu = !openMenu"
                class="w-full flex items-center justify-between px-3.5 py-2.5 mt-2 rounded-lg text-sm font-medium text-white/90 hover:bg-white/10 transition">

                <span class="flex items-center gap-3">

                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>

                    <span>Menu Utama</span>

                </span>

                <svg
                    class="w-4 h-4 transition-transform duration-200"
                    :class="openMenu ? 'rotate-180' : ''"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">

                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 9l-7 7-7-7"/>
                </svg>

            </button>

            <div
                x-show="openMenu"
                x-transition
                class="ml-3 mt-1 pl-3 border-l border-white/20 space-y-1">

                <a
                    href="{{ route('admin.customer.index') }}"
                    class="block px-3 py-2 rounded-lg text-sm transition
                    {{ request()->routeIs('admin.customer.*')
                        ? 'bg-white/15 text-white font-medium'
                        : 'text-white/75 hover:bg-white/10 hover:text-white' }}">
                    Pengunjung
                </a>

                <a
                    href="{{ route('admin.education.index') }}"
                    class="block px-3 py-2 rounded-lg text-sm transition
                    {{ request()->routeIs('admin.education.*')
                        ? 'bg-white/15 text-white font-medium'
                        : 'text-white/75 hover:bg-white/10 hover:text-white' }}">
                    Pendidikan
                </a>

                <a
                    href="{{ route('admin.work.index') }}"
                    class="block px-3 py-2 rounded-lg text-sm transition
                    {{ request()->routeIs('admin.work.*')
                        ? 'bg-white/15 text-white font-medium'
                        : 'text-white/75 hover:bg-white/10 hover:text-white' }}">
                    Pekerjaan
                </a>

                <a
                    href="{{ route('admin.media.index') }}"
                    class="block px-3 py-2 rounded-lg text-sm transition
                    {{ request()->routeIs('admin.media.*')
                        ? 'bg-white/15 text-white font-medium'
                        : 'text-white/75 hover:bg-white/10 hover:text-white' }}">
                    Media Pelayanan
                </a>

                <a
                    href="{{ route('admin.service.index') }}"
                    class="block px-3 py-2 rounded-lg text-sm transition
                    {{ request()->routeIs('admin.service.*')
                        ? 'bg-white/15 text-white font-medium'
                        : 'text-white/75 hover:bg-white/10 hover:text-white' }}">
                    Jenis Pelayanan
                </a>

                <a
                    href="{{ route('admin.categorie.index') }}"
                    class="block px-3 py-2 rounded-lg text-sm transition
                    {{ request()->routeIs('admin.categorie.*')
                        ? 'bg-white/15 text-white font-medium'
                        : 'text-white/75 hover:bg-white/10 hover:text-white' }}">
                    Kategori
                </a>

                <a
                    href="{{ route('admin.subcategorie.index') }}"
                    class="block px-3 py-2 rounded-lg text-sm transition
                    {{ request()->routeIs('admin.subcategorie.*')
                        ? 'bg-white/15 text-white font-medium'
                        : 'text-white/75 hover:bg-white/10 hover:text-white' }}">
                    Sub Kategori
                </a>

                <a
                    href="{{ route('admin.purpose.index') }}"
                    class="block px-3 py-2 rounded-lg text-sm transition
                    {{ request()->routeIs('admin.purpose.*')
                        ? 'bg-white/15 text-white font-medium'
                        : 'text-white/75 hover:bg-white/10 hover:text-white' }}">
                    Tujuan
                </a>

            </div>

            <a
                href="{{ route('admin.transaction.index') }}"
                class="flex items-center gap-3 px-3.5 py-2.5 mt-2 rounded-lg text-sm font-medium transition
                {{ request()->routeIs('admin.transaction.*')
                    ? 'bg-white text-[#3E5C8A] shadow-sm'
                    : 'text-white/90 hover:bg-white/10' }}">

                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a3 3 0 003 3h2a3 3 0 003-3"/>
                </svg>

                <span>Transaksi</span>
            </a>

        </nav>

    </aside>
</div>