<aside
    x-data="{ openMenu: true }"
    class="w-64 bg-[#3E5C8A] text-white flex flex-col min-h-screen"
>

    {{-- LOGO --}}
    <div class="flex items-center gap-3 px-6 py-5 border-b border-white/20">

        <img
            src="{{ asset('logo-bps.png') }}"
            class="h-10"
            alt="BPS"
        >

        <div>

            <p class="font-bold text-sm">
                Badan Pusat Statistik
            </p>

            <p class="text-xs">
                Kota Jambi
            </p>

        </div>

    </div>


    {{-- MENU --}}
    <nav class="flex-1 px-4 py-4 space-y-2 text-sm">

        <a
            href="{{ route('admin.dashboard') }}"
            class="flex items-center gap-3 px-4 py-2 rounded-md
            {{ request()->routeIs('admin.dashboard')
                ? 'bg-white/20'
                : 'hover:bg-white/10' }}"
        >
            Beranda
        </a>


        <button
            type="button"
            @click="openMenu = !openMenu"
            class="w-full flex items-center justify-between px-4 py-2 rounded-md hover:bg-white/10"
        >

            <span>
                Menu Utama
            </span>

            <span x-text="openMenu ? '▲' : '▼'"></span>

        </button>


        <div
            x-show="openMenu"
            x-transition
            class="ml-3 space-y-1 bg-white/10 rounded-md p-2"
        >

            <a
                href="{{ route('admin.customer.index') }}"
                class="block px-3 py-2 rounded hover:bg-white/20
                {{ request()->routeIs('admin.customer.*')
                    ? 'bg-white/20'
                    : '' }}"
            >
                Pengunjung
            </a>


            <a
                href="{{ route('admin.education.index') }}"
                class="block px-3 py-2 rounded hover:bg-white/20
                {{ request()->routeIs('admin.education.*')
                    ? 'bg-white/20'
                    : '' }}"
            >
                Pendidikan
            </a>


            <a
                href="{{ route('admin.work.index') }}"
                class="block px-3 py-2 rounded hover:bg-white/20
                {{ request()->routeIs('admin.work.*')
                    ? 'bg-white/20'
                    : '' }}"
            >
                Pekerjaan
            </a>


            <a
                href="{{ route('admin.media.index') }}"
                class="block px-3 py-2 rounded hover:bg-white/20
                {{ request()->routeIs('admin.media.*')
                    ? 'bg-white/20'
                    : '' }}"
            >
                Media Pelayanan
            </a>


            <a
                href="{{ route('admin.service.index') }}"
                class="block px-3 py-2 rounded hover:bg-white/20
                {{ request()->routeIs('admin.service.*')
                    ? 'bg-white/20'
                    : '' }}"
            >
                Jenis Pelayanan
            </a>


            <a
                href="{{ route('admin.categorie.index') }}"
                class="block px-3 py-2 rounded hover:bg-white/20
                {{ request()->routeIs('admin.categorie.*')
                    ? 'bg-white/20'
                    : '' }}"
            >
                Kategori
            </a>


            <a
                href="{{ route('admin.subcategorie.index') }}"
                class="block px-3 py-2 rounded hover:bg-white/20
                {{ request()->routeIs('admin.subcategorie.*')
                    ? 'bg-white/20'
                    : '' }}"
            >
                Sub Kategori
            </a>


            <a
                href="{{ route('admin.purpose.index') }}"
                class="block px-3 py-2 rounded hover:bg-white/20
                {{ request()->routeIs('admin.purpose.*')
                    ? 'bg-white/20'
                    : '' }}"
            >
                Tujuan
            </a>

        </div>


        <a
            href="{{ route('admin.transaction.index') }}"
            class="flex items-center gap-3 px-4 py-2 rounded-md
            {{ request()->routeIs('admin.transaction.*')
                ? 'bg-white/20'
                : 'hover:bg-white/10' }}"
        >
            Transaksi
        </a>

    </nav>


    {{-- USER --}}
    <div class="border-t border-white/20 p-4">

        @auth

            <div class="mb-3">

                <p class="font-semibold text-sm">
                    {{ auth()->user()->name }}
                </p>

                <p class="text-xs text-white/70">
                    {{ auth()->user()->email }}
                </p>

                @if(auth()->user()->role)

                    <p class="text-xs text-white/70 mt-1">
                        {{ ucfirst(auth()->user()->role) }}
                    </p>

                @endif

            </div>


            <form
                method="POST"
                action="{{ route('logout') }}"
            >

                @csrf

                <button
                    type="submit"
                    class="w-full text-left px-4 py-2 rounded-md hover:bg-white/10"
                >
                    Logout
                </button>

            </form>

        @endauth

    </div>

</aside>