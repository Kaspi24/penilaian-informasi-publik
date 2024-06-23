<section>
    <header>
        <h2 class="text-lg font-medium text-primary-70">
            Data Pribadi Responden
        </h2>

        <p class="mt-1 text-sm text-primary-40">
            @if($user->role === 'RESPONDENT' || $user->role === 'JURY') {{{
                (
                    $user->name 
                    && $user->username && $user->email 
                    && $user->phone && $user->whatsapp
                ) ? 'Perbarui' : 'Lengkapi'
            }}}
            @else Perbarui @endif
            data pribadi akun anda.
        </p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')
        
        <div class="">
            <x-input-label for="name" :value="__('Nama')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="off" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="username" :value="__('Username')" />
            <x-text-input id="username" name="username" type="text" class="mt-1 block w-full" :value="old('username', $user->username)" required autocomplete="off" />
            <x-input-error class="mt-2" :messages="$errors->get('username')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="off" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
        </div>
        @if (Auth::user()->role === 'RESPONDENT')
            <div>
                <x-input-label for="phone" :value="__('Nomor Telepon')" />
                <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $user->phone)" required autocomplete="off" />
                <x-input-error class="mt-2" :messages="$errors->get('phone')" />
            </div>

            <div>
                <x-input-label for="whatsapp" :value="__('Nomor WhatsApp')" />
                <x-text-input id="whatsapp" name="whatsapp" type="text" class="mt-1 block w-full" :value="old('whatsapp', $user->whatsapp)" required autocomplete="off" />
                <x-input-error class="mt-2" :messages="$errors->get('whatsapp')" />
            </div>
        @endif

        <div class="flex items-center gap-4">
            <x-primary-button>Simpan Perubahan</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-primary-40 "
                >Perubahan Disimpan!</p>
            @endif
        </div>
    </form>
</section>
