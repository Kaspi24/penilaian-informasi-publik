<section>
    <header>
        <h2 class="text-lg font-medium text-primary-70">
            Data Pribadi {{ Auth::user()->role === 'RESPONDENT' && 'Responden' }} 
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

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')
        
        @if (Auth::user()->role === 'RESPONDENT')
            <div class="">
                <div class="mb-3">
                    <p class="block mb-0.5 font-medium text-sm text-gray-700">Foto Kartu Pegawai</p>
                    <small class="block w-full text-xs mb-2 font-bold text-primary-30">File Gambar (JPG/JPEG/PNG), max. 1MB</small>
                    <input type="file" name="profile_picture" id="profile_picture" class="hidden"/>
                    <small class="{{ Auth::user()->profile_picture ? 'hidden' : 'block' }} w-full text-xs mb-2 font-bold text-danger">Foto Kartu Pegawai belum diisi.</small>
                    <div class="relative block w-1/2 aspect-[4300/2699] lg:flex lg:items-center lg:justify-center rounded-sm overflow-hidden bg-white border-2 border-primary-30 focus:ring-primary-70 focus:border-primary-70 p-1 group">
                        <img id="image_preview" src="{{ Auth::user()->profile_picture ? asset('storage/'.Auth::user()->profile_picture) : asset('design/BLANK-ID-CARD.png') }}" class="object-contain rounded-sm" alt="FOTO KARTU PEGAWAI">
                    </div>
                    <p id="profile_pictureerrorcontainer"
                        class="w-fit items-center hidden gap-2 p-2.5 rounded-md border-2 text-xs my-2 bg-danger-10/40  border-danger-30 text-danger">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                        <span id="profile_pictureerror" class="max-w-[calc(100%-1.375rem)] break-words"></span>
                    </p>
                    <label class="block" for="profile_picture">
                        <p id="gantiprofile_picture"
                            class="{{ Auth::user()->profile_picture ? 'block' : 'hidden' }} px-1 mt-2 text-xs font-bold tracking-wider text-gray-400 cursor-pointer hover:text-gray-500 transition-all ease-in-out duration-200 w-fit">
                            Ganti Foto Kartu Pegawai
                        </p>
                        <div id="pilihprofile_picture" class="{{ Auth::user()->profile_picture ? 'hidden' : 'flex' }} mt-2 w-1/2 justify-center items-center gap-2.5 p-2.5 border-2 border-primary-20 rounded-md cursor-pointer text-primary-50 hover:bg-primary-10/25 transition-all ease-in-out duration-200 hover:ring-primary-70 hover:border-primary-70">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                            </svg>
                            <p class="font-bold text-sm">
                                Pilih File
                            </p>
                        </div>
                    </label>
                    @error('profile_picture')
                        <p class="block mt-1 text-xs font-medium text-danger">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        @endif
        
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
