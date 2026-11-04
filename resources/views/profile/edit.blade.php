<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            @if ($canDeleteAccount)
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="max-w-xl">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            @elseif ($user->role === 'admin')
                <div class="rounded border border-blue-200 bg-blue-50 p-4 text-blue-900" role="status">
                    Akun admin hanya dapat dihapus oleh superadmin melalui halaman pengelolaan admin.
                </div>
            @else
                <div class="rounded border border-blue-200 bg-blue-50 p-4 text-blue-900" role="status">
                    Akun superadmin terakhir tidak dapat dihapus.
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
