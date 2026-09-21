<div x-data="{ sidebarOpen: false, openMenu: true }">

    {{-- TOMBOL MENU MOBILE --}}
    <button
        type="button"
        @click="sidebarOpen = true"
        class="fixed top-4 left-4 z-30 lg:hidden bg-[#3E5C8A] text-white p-2.5 rounded-lg shadow-md hover:bg-[#334D75] transition"
    >
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
    </button>


    {{-- OVERLAY MOBILE --}}
    <div
        x-show="sidebarOpen"
        x-transition.opacity
        @click="sidebarOpen = false"
        class="fixed inset-0 bg-black/40 z-40 lg:hidden"
    ></div>


    {{-- SIDEBAR --}}
    <aside
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
        class="fixed lg:static inset-y-0 left-0 z-50 w-64 bg-[#3E5C8A] text-white flex flex-col min-h-screen shadow-lg lg:shadow-none transform transition-transform duration-300"
    >

        {{-- LOGO --}}
        <div class="flex items-center justify-between px-5 py-5 border-b border-white/20">

            <div class="flex items-center gap-3 min-w-0">

                <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center shrink-0 shadow-sm">
                    <img
                        src="{{ asset('images/icons/logo-bps.png') }}"
                        class="h-8 w-auto"
                        alt="BPS"
                    >
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


            {{-- TUTUP MOBILE --}}
            <button
                type="button"
                @click="sidebarOpen = false"
                class="lg:hidden p-1.5 rounded-md text-white/70 hover:text-white hover:bg-white/10 transition"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

        </div>


        {{-- MENU --}}
        <nav class="flex-1 px-3 py-4 overflow-y-auto">

            {{-- BERANDA --}}
            <a
                href="{{ route('admin.dashboard') }}"
                class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-medium transition
                {{ request()->routeIs('admin.dashboard')
                    ? 'bg-white text-[#3E5C8A] shadow-sm'
                    : 'text-white/90 hover:bg-white/10' }}"
            >

                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l9-9 9 9M5 10v10h14V10M9 20v-6h6v6"/>
                </svg>

                <span>Beranda</span>

            </a>


            {{-- MENU UTAMA --}}
            <button
                type="button"
                @click="openMenu = !openMenu"
                class="w-full flex items-center justify-between px-3.5 py-2.5 mt-2 rounded-lg text-sm font-medium text-white/90 hover:bg-white/10 transition"
            >

                <span class="flex items-center gap-3">

                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>

                    <span>Menu Utama</span>

                </span>

                <svg
                    class="w-4 h-4 transition-transform duration-200"
                    :class="openMenu ? 'rotate-180' : ''"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>

            </button>


            {{-- SUB MENU --}}
            <div
                x-show="openMenu"
                x-transition
                class="ml-3 mt-1 pl-3 border-l border-white/20 space-y-1"
            >

                <a
                    href="{{ route('admin.customer.index') }}"
                    class="block px-3 py-2 rounded-lg text-sm transition
                    {{ request()->routeIs('admin.customer.*')
                        ? 'bg-white/15 text-white font-medium'
                        : 'text-white/75 hover:bg-white/10 hover:text-white' }}"
                >
                    Pengunjung
                </a>


                <a
                    href="{{ route('admin.education.index') }}"
                    class="block px-3 py-2 rounded-lg text-sm transition
                    {{ request()->routeIs('admin.education.*')
                        ? 'bg-white/15 text-white font-medium'
                        : 'text-white/75 hover:bg-white/10 hover:text-white' }}"
                >
                    Pendidikan
                </a>


                <a
                    href="{{ route('admin.work.index') }}"
                    class="block px-3 py-2 rounded-lg text-sm transition
                    {{ request()->routeIs('admin.work.*')
                        ? 'bg-white/15 text-white font-medium'
                        : 'text-white/75 hover:bg-white/10 hover:text-white' }}"
                >
                    Pekerjaan
                </a>


                <a
                    href="{{ route('admin.media.index') }}"
                    class="block px-3 py-2 rounded-lg text-sm transition
                    {{ request()->routeIs('admin.media.*')
                        ? 'bg-white/15 text-white font-medium'
                        : 'text-white/75 hover:bg-white/10 hover:text-white' }}"
                >
                    Media Pelayanan
                </a>


                <a
                    href="{{ route('admin.service.index') }}"
                    class="block px-3 py-2 rounded-lg text-sm transition
                    {{ request()->routeIs('admin.service.*')
                        ? 'bg-white/15 text-white font-medium'
                        : 'text-white/75 hover:bg-white/10 hover:text-white' }}"
                >
                    Jenis Pelayanan
                </a>


                <a
                    href="{{ route('admin.categorie.index') }}"
                    class="block px-3 py-2 rounded-lg text-sm transition
                    {{ request()->routeIs('admin.categorie.*')
                        ? 'bg-white/15 text-white font-medium'
                        : 'text-white/75 hover:bg-white/10 hover:text-white' }}"
                >
                    Kategori
                </a>


                <a
                    href="{{ route('admin.subcategorie.index') }}"
                    class="block px-3 py-2 rounded-lg text-sm transition
                    {{ request()->routeIs('admin.subcategorie.*')
                        ? 'bg-white/15 text-white font-medium'
                        : 'text-white/75 hover:bg-white/10 hover:text-white' }}"
                >
                    Sub Kategori
                </a>


                <a
                    href="{{ route('admin.purpose.index') }}"
                    class="block px-3 py-2 rounded-lg text-sm transition
                    {{ request()->routeIs('admin.purpose.*')
                        ? 'bg-white/15 text-white font-medium'
                        : 'text-white/75 hover:bg-white/10 hover:text-white' }}"
                >
                    Tujuan
                </a>

            </div>


            {{-- TRANSAKSI --}}
            <a
                href="{{ route('admin.transaction.index') }}"
                class="flex items-center gap-3 px-3.5 py-2.5 mt-2 rounded-lg text-sm font-medium transition
                {{ request()->routeIs('admin.transaction.*')
                    ? 'bg-white text-[#3E5C8A] shadow-sm'
                    : 'text-white/90 hover:bg-white/10' }}"
            >

                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a3 3 0 003 3h2a3 3 0 003-3"/>
                </svg>

                <span>Transaksi</span>

            </a>

        </nav>


        {{-- USER --}}
        {{-- <div class="border-t border-white/20 p-4">

            @auth

                <div class="flex items-center gap-3 mb-3 p-2 rounded-lg bg-white/10">

                    <div class="w-9 h-9 rounded-full bg-white/15 flex items-center justify-center shrink-0">

                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>

                    </div>

                    <div class="min-w-0">

                        <p class="font-semibold text-sm truncate">
                            {{ auth()->user()->name }}
                        </p>

                        <p class="text-xs text-white/60 truncate">
                            {{ auth()->user()->email }}
                        </p>

                        @if(auth()->user()->role)

                            <p class="text-[11px] text-white/60 mt-0.5">
                                {{ ucfirst(auth()->user()->role) }}
                            </p>

                        @endif

                    </div>

                </div>


                <form method="POST" action="{{ route('logout') }}">

                    @csrf

                    <button
                        type="submit"
                        class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm text-white/85 hover:bg-white/10 transition"
                    >

                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>

                        <span>Logout</span>

                    </button>

                </form>

            @endauth

        </div> --}}

    </aside>

</div>