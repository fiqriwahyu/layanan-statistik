<aside x-data="{ openMenu: false }" class="w-64 bg-[#3E5C8A] text-white flex flex-col">

    {{-- LOGO --}}
    <div class="flex items-center gap-3 px-6 py-5 border-b border-white/20">
        <img src="{{ asset('logo-bps.png') }}" class="h-10" alt="BPS">
        <div>
            <p class="font-bold text-sm">Badan Pusat Statistik</p>
            <p class="text-xs">Kota Jambi</p>
        </div>
    </div>

    {{-- MENU --}}
    <nav class="flex-1 px-4 py-4 space-y-2 text-sm">

        {{-- <a href="{{ route('dashboard') }}"
            class="flex items-center gap-3 px-4 py-2 rounded-md
            {{ request()->routeIs('dashboard') ? 'bg-white/20' : 'hover:bg-white/10' }}">
            🏠 Beranda
        </a> --}}
        <a href="{{ route('admin.dashboard') }}"
            class="flex items-center gap-3 px-4 py-2 rounded-md
            {{ request()->routeIs('admin.dashboard') ? 'bg-white/20' : 'hover:bg-white/10' }}">
            Beranda
        </a>

        @if (auth()->user()->is_admin)
            {{-- <li>
                <a href="{{ route('superadmin.admin.index') }}">
                    Kelola Admin
                </a>
            </li> --}}
            <a href="{{ route('superadmin.admin.index') }}"
                class="flex items-center gap-3 px-4 py-2 rounded-md
            {{ request()->routeIs('superadmin.admin.index') ? 'bg-white/20' : 'hover:bg-white/10' }}">
                Admin
            </a>
        @endif

        @can('users-view')
            <a href="{{ route('users.index') }}"
                class="flex items-center gap-3 px-4 py-2 rounded-md
                {{ request()->routeIs('users.*') ? 'bg-white/20' : 'hover:bg-white/10' }}">
                👤 Admin
            </a>
        @endcan

        {{-- MENU UTAMA --}}
        <button @click="openMenu = !openMenu"
            class="w-full flex items-center justify-between px-4 py-2 rounded-md hover:bg-white/10">
            <span>Menu Utama</span>
            <span x-text="openMenu ? '▲' : '▼'"></span>
        </button>

        {{-- SUB MENU --}}
        <div x-show="openMenu" x-transition class="ml-3 space-y-1 bg-white/10 rounded-md p-2">

            <a href="{{ route('admin.customer.index') }}" class="block px-3 py-2 rounded hover:bg-white/20">
                Pengunjung
            </a>
            <a href="{{ route('admin.education.index') }}" class="block px-3 py-2 rounded hover:bg-white/20">
                Pendidikan
            </a>
            <a href="{{ route('admin.work.index') }}" class="block px-3 py-2 rounded hover:bg-white/20">
                Pekerjaan
            </a>
            <a href="{{ route('admin.media.index') }}" class="block px-3 py-2 rounded hover:bg-white/20">
                Media Pelayanan
            </a>
            <a href="{{ route('admin.service.index') }}" class="block px-3 py-2 rounded hover:bg-white/20">
                Jenis Pelayanan
            </a>
            <a href="{{ route('admin.categorie.index') }}" class="block px-3 py-2 rounded hover:bg-white/20">
                Kategori
            </a>
            <a href="{{ route('admin.subcategorie.index') }}" class="block px-3 py-2 rounded hover:bg-white/20">
                Sub Kategori
            </a>
            <a href="{{ route('admin.purpose.index') }}" class="block px-3 py-2 rounded hover:bg-white/20">
                Tujuan
            </a>
        </div>
        <a href="{{ route('admin.transaction.index') }}"
            class="flex items-center gap-3 px-4 py-2 rounded-md
            {{ request()->routeIs('admin.transaction.index') ? 'bg-white/20' : 'hover:bg-white/10' }}">
            Transaksi
        </a>
    </nav>
</aside>