<section>
    <header>
        <h2 class="text-lg font-medium text-primary-70">
            Data Unit Kerja
        </h2>

        <p class="mt-1 text-sm text-primary-40">
                {{
                    (
                        $user->work_unit->name &&
                        $user->work_unit->head_name &&
                        $user->work_unit->email &&
                        $user->work_unit->phone
                    ) ? 'Perbarui' : 'Lengkapi' 
                }}
                data unit kerja anda.
        </p>
    </header>

    <form method="post" action="{{ route('profile.updateWorkUnit') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div class="">
            <x-input-label for="unit_name" :value="__('Nama Unit Kerja')" />
            <x-text-input id="unit_name" name="name" type="text" class="mt-1 block w-full bg-primary-10" :value="old('name', $user->work_unit->name)" readonly disabled/>
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div class="">
            <x-input-label for="unit_head_name" :value="__('Nama Kepala Unit Kerja')" />
            <small class="block w-full text-xs mb-2 font-bold text-primary-30">Isi dengan nama kepala unit kerja</small>
            <x-text-input id="unit_head_name" name="head_name" type="text" class="mt-1 block w-full" :value="old('head_name', $user->work_unit->head_name)" required autocomplete="off"/>
            <x-input-error class="mt-2" :messages="$errors->get('head_name')" />
        </div>

        <div>
            <x-input-label for="unit_email" :value="__('Email Kantor Unit Kerja')" />
            <small class="block w-full text-xs mb-2 font-bold text-primary-30">Isi dengan alamat email aktif unit kerja</small>
            <x-text-input id="unit_email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->work_unit->email)" required autocomplete="off" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
        </div>

        <div>
            <x-input-label for="unit_phone" :value="__('Nomor Telepon Kantor Unit Kerja')" />
            <small class="block w-full text-xs mb-2 font-bold text-primary-30">Isi dengan nomor telepon kantor unit kerja</small>
            <x-text-input id="unit_phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $user->work_unit->phone)" required autocomplete="off" />
            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
        </div>

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
