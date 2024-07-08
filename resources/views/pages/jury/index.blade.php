<x-app-layout x-data="{
        showaddJuryModal: {{ $errors->hasBag('create_jury') ? 'true' : 'false' }},

        showEditJuryModal: {{ $errors->hasBag('edit_jury') ? 'true' : 'false' }},
        edit_id         : '{{ $errors->hasBag('edit_jury') ? old('id') : '' }}',
        edit_name       : '{{ $errors->hasBag('edit_jury') ? old('name') : '' }}',
        edit_username   : '{{ $errors->hasBag('edit_jury') ? old('username') : '' }}',
        edit_email      : '{{ $errors->hasBag('edit_jury') ? old('email') : '' }}',
        
        showDeleteJuryModal    : false,
        delete_id               : '',
        delete_name             : '',
        delete_username         : '',
        delete_email            : ''
    }">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-primary leading-tight">
            {{ __('Juri') }}
        </h2>
    </x-slot>
    
    @include('components.alert')

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-sm mb-6">
                <div class="p-4 text-primary flex justify-between items-center">
                    <p class="text-lg lg:text-xl font-bold">Juri dan Progres Evaluasi Penilaian</p>
                    <button type="button" x-on:click="showaddJuryModal = true" class="w-fit aspect-square md:aspect-auto p-1 md:p-2 md:pr-4 rounded bg-emerald-600 text-white md:flex md:gap-2 md:items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="size-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        <p class="hidden md:inline-block text-xs font-black uppercase">Tambah Juri</p>
                    </button>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-sm">
                <div class="p-4 text-primary-70">
                    <livewire:jury-table />
                </div>
            </div>
        </div>
    </div>

    <x-slot name="modals">
        <!-- AddJuryModal -->
        <div class="fixed z-[2220] inset-0" x-cloak x-show="showaddJuryModal">
            <div class="absolute z-[2222] inset-0 bg-primary-90 bg-opacity-30 flex justify-center items-center py-4">
                <div class="bg-white w-10/12 md:w-1/2 lg:w-1/3 rounded-md p-5 lg:p-6 flex flex-col justify-center items-center">
                    <div class="w-full text-center mb-3">
                        <p class="text-lg lg:text-xl text-primary font-bold tracking-wide mb-2 uppercase">
                            Tambah Juri
                        </p>
                    </div>
                    <form action="{{ route('jury.store') }}" method="POST" class="w-full">
                        @csrf
                        <div class="mb-4">
                            <x-input-label for="name" :value="__('Nama')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required autofocus autocomplete="off" />
                            <x-input-error class="mt-2" :messages="$errors->create_jury->first('name')" />
                        </div>
                
                        <div class="mb-4">
                            <x-input-label for="username" :value="__('Username')" />
                            <x-text-input id="username" name="username" type="text" class="mt-1 block w-full" :value="old('username')" required autocomplete="off" />
                            <x-input-error class="mt-2" :messages="$errors->create_jury->first('username')" />
                        </div>
                
                        <div class="mb-4">
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email')" required autocomplete="off" />
                            <x-input-error class="mt-2" :messages="$errors->create_jury->first('email')" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="password" :value="__('Password ')" />
                            <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
                            <x-input-error :messages="$errors->create_jury->first('password')" class="mt-2" />
                        </div>
                
                        <div class="mb-8">
                            <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" />
                            <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
                            <x-input-error :messages="$errors->create_jury->first('password_confirmation')" class="mt-2" />
                        </div>
                        
                        <div class="w-full flex justify-center items-center gap-4">
                            <button type="button" x-on:click="showaddJuryModal = false" class="block w-[48%] text-primary bg-white border border-primary focus:ring-4 focus:outline-none focus:ring-blue-200 font-medium hover:font-semibold rounded-md text-sm py-2 text-center">
                                KEMBALI
                            </button>
                            <button type="submit" class="block w-[48%] text-white bg-primary hover:bg-primary-70 border border-primary focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-md text-sm py-2 text-center">
                                SIMPAN
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- EditJuryModal -->
        <div class="fixed z-[2220] inset-0" x-cloak x-show="showEditJuryModal">
            <div class="absolute z-[2222] inset-0 bg-primary-90 bg-opacity-30 flex justify-center items-center py-4">
                <div class="bg-white w-10/12 md:w-1/2 lg:w-1/3 rounded-md p-5 lg:p-6 flex flex-col justify-center items-center">
                    <div class="w-full text-center mb-3">
                        <p class="text-lg lg:text-xl text-primary font-bold tracking-wide mb-2 uppercase">
                            Edit Data Juri
                        </p>
                    </div>
                    <form action="{{ route('jury.update') }}" method="POST" class="w-full">
                        @csrf @method('PUT')
                        <input type="hidden" id="edit_id" name="id" x-model="edit_id">
                        <div class="mb-4">
                            <x-input-label for="name" :value="__('Nama')" />
                            <x-text-input id="edit_name" name="name" type="text" class="mt-1 block w-full" x-model="edit_name" required autofocus autocomplete="off" />
                            <x-input-error class="mt-2" :messages="$errors->edit_jury->first('name')" />
                        </div>
                
                        <div class="mb-4">
                            <x-input-label for="username" :value="__('Username')" />
                            <x-text-input id="edit_username" name="username" type="text" class="mt-1 block w-full" x-model="edit_username" required autocomplete="off" />
                            <x-input-error class="mt-2" :messages="$errors->edit_jury->first('username')" />
                        </div>
                
                        <div class="mb-4">
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="edit_email" name="email" type="email" class="mt-1 block w-full" x-model="edit_email" required autocomplete="off" />
                            <x-input-error class="mt-2" :messages="$errors->edit_jury->first('email')" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="password" :value="__('Password Baru ')" />
                            <small class="block w-full text-xs mb-2 font-bold text-primary-30">Kosongkan bila tidak ingin mengubah password</small>
                            <x-text-input id="edit_password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
                            <x-input-error :messages="$errors->edit_jury->first('password')" class="mt-2" />
                        </div>
                
                        <div class="mb-8">
                            <x-input-label for="password_confirmation" :value="__('Konfirmasi Password Baru')" />
                            <small class="block w-full text-xs mb-2 font-bold text-primary-30">Kosongkan bila tidak ingin mengubah password</small>
                            <x-text-input id="edit_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
                            <x-input-error :messages="$errors->edit_jury->first('password_confirmation')" class="mt-2" />
                        </div>
                        
                        <div class="w-full flex justify-center items-center gap-4">
                            <button type="button" x-on:click="
                                    edit_id             = '',
                                    edit_name           = '',
                                    edit_username       = '',
                                    edit_email          = '',
                                    showEditJuryModal  = false
                                " 
                                class="block w-[48%] text-primary bg-white border border-primary focus:ring-4 focus:outline-none focus:ring-blue-200 font-medium hover:font-semibold rounded-md text-sm py-2 text-center">
                                KEMBALI
                            </button>
                            <button type="submit" class="block w-[48%] text-white bg-primary hover:bg-primary-70 border border-primary focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-md text-sm py-2 text-center">
                                SIMPAN
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- DeleteJuryModal -->
        <div class="fixed z-[2220] inset-0" x-cloak x-show="showDeleteJuryModal">
            <div class="absolute z-[2222] inset-0 bg-primary-90 bg-opacity-30 flex justify-center items-center py-4">
                <div class="bg-white w-10/12 md:w-1/2 lg:w-1/3 rounded-md p-5 lg:p-6 flex flex-col justify-center items-center">
                    <div class="w-full text-center mb-3">
                        <p class="text-lg lg:text-xl text-primary font-bold tracking-wide mb-2 uppercase">
                            Hapus Data Juri
                        </p>
                    </div>
                    <form action="{{ route('jury.delete') }}" method="POST" class="w-full">
                        @csrf @method('DELETE')
                        <input type="hidden" id="delete_id" name="id" x-model="delete_id">
                        <div class="w-full bg-danger-10/25 p-3 rounded border border-danger mb-3">
                            <p>Anda yakin ingin menghapus data Juri berikut :</p>
                            <table>
                                <tbody class="text-gray-600">
                                    <tr class="p-1">
                                        <th class="text-left">Nama</th>
                                        <td class="px-2">:</td>
                                        <td x-text="delete_name"></td>
                                    </tr>
                                    <tr class="p-1">
                                        <th class="text-left">Username</th>
                                        <td class="px-2">:</td>
                                        <td x-text="delete_username"></td>
                                    </tr>
                                    <tr class="p-1">
                                        <th class="text-left">Email</th>
                                        <td class="px-2">:</td>
                                        <td x-text="delete_email"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="w-full flex justify-center items-center gap-4">
                            <button type="button" x-on:click="
                                    delete_id             = '',
                                    delete_name           = '',
                                    delete_username       = '',
                                    delete_email          = '',
                                    showDeleteJuryModal  = false
                                " 
                                class="block w-[48%] text-primary bg-white border border-primary focus:ring-4 focus:outline-none focus:ring-blue-200 font-medium hover:font-semibold rounded-md text-sm py-2 text-center">
                                KEMBALI
                            </button>
                            <button type="submit" class="block w-[48%] text-white bg-danger hover:bg-danger-70 border border-danger focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-md text-sm py-2 text-center">
                                HAPUS
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </x-slot>
</x-app-layout>
