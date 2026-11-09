<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <span class="block text-sm font-medium text-gray-700">Foto profil</span>
            <div class="mt-2 flex items-center gap-4">
                <div id="profile-photo-placeholder" class="flex h-20 w-20 items-center justify-center overflow-hidden rounded-full border border-gray-200 bg-gray-100 text-2xl font-semibold text-[#3E5C8A]">
                    @if ($user->profile_photo_path)
                        <img id="profile-photo-preview" src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($user->profile_photo_path) }}" alt="Foto profil {{ $user->name }}" class="h-full w-full object-cover">
                    @else
                        <span id="profile-photo-initial">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                    @endif
                </div>
                <div class="space-y-2">
                    <input id="profile_photo" name="profile_photo" type="file" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" class="block w-full text-sm text-gray-600 file:mr-3 file:rounded-lg file:border-0 file:bg-[#3E5C8A] file:px-3 file:py-2 file:text-white" />
                    <p class="text-xs text-gray-500">JPG, JPEG, PNG, atau WebP. Maksimal 2 MB.</p>
                    @if ($user->profile_photo_path)
                        <label class="flex items-center gap-2 text-sm text-gray-600"><input type="checkbox" name="remove_profile_photo" value="1" class="rounded border-gray-300 text-[#3E5C8A]"> Hapus foto dan gunakan avatar default</label>
                    @endif
                    <x-input-error class="mt-2" :messages="$errors->get('profile_photo')" />
                </div>
            </div>
        </div>
        <script>
            document.getElementById('profile_photo')?.addEventListener('change', event => {
                const file = event.target.files?.[0];
                if (!file) return;
                const preview = document.getElementById('profile-photo-preview');
                if (preview) preview.src = URL.createObjectURL(file);
                else {
                    const image = document.createElement('img');
                    image.id = 'profile-photo-preview'; image.src = URL.createObjectURL(file);
                    image.alt = 'Pratinjau foto profil'; image.className = 'h-full w-full object-cover';
                    document.getElementById('profile-photo-placeholder').replaceChildren(image);
                }
            });
        </script>

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if (! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        Email belum diverifikasi.

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Kirim ulang email verifikasi
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            Tautan verifikasi baru telah dikirim.
                        </p>
                    @endif
                </div>
            @endif
            <p class="mt-2 text-sm text-gray-600">Role: <span class="font-medium capitalize">{{ $user->role }}</span> · Status email: <span class="font-medium">{{ $user->hasVerifiedEmail() ? 'Terverifikasi' : 'Belum terverifikasi' }}</span></p>
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (in_array(session('status'), ['profile-updated', 'profile-updated-verification-sent'], true))
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ session('status') === 'profile-updated-verification-sent' ? 'Profil tersimpan. Tautan verifikasi dikirim ke email baru.' : 'Profil berhasil diperbarui.' }}</p>
            @endif
        </div>
    </form>
</section>
