<x-app-layout x-data="{
            showaddJuryModal: {{ $errors->hasBag('create_jury') ? 'true' : 'false' }}
        {{-- showaddJuryModal: {{ 1%2==1 }} --}}
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
                    <p class="text-lg lg:text-xl font-bold">Juri dan Progres Penilaian Kuesioner</p>
                    <button type="button" x-on:click="showaddJuryModal = true" class="w-fit aspect-square lg:aspect-auto p-1 lg:p-2 lg:pr-4 rounded bg-emerald-600 text-white lg:flex lg:gap-2 lg:items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5">
                            <path fill-rule="evenodd" d="M12 3.75a.75.75 0 0 1 .75.75v6.75h6.75a.75.75 0 0 1 0 1.5h-6.75v6.75a.75.75 0 0 1-1.5 0v-6.75H4.5a.75.75 0 0 1 0-1.5h6.75V4.5a.75.75 0 0 1 .75-.75Z" clip-rule="evenodd" />
                        </svg>
                        <p class="hidden lg:inline-block tex-xs font-semibold uppercase">Tambah Juri</p>
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
        <!-- SUBMIT RESPONSE CONFIRMATION MODAL -->
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
                            {{-- <x-input-error class="mt-2" :messages="$errors->get('name', 'create_jury')" /> --}}
                        </div>
                
                        <div class="mb-4">
                            <x-input-label for="username" :value="__('Username')" />
                            <x-text-input id="username" name="username" type="text" class="mt-1 block w-full" :value="old('username')" required autocomplete="off" />
                            <x-input-error class="mt-2" :messages="$errors->create_jury->first('username')" />
                            {{-- <x-input-error class="mt-2" :messages="$errors->get('username', 'create_jury')" /> --}}
                        </div>
                
                        <div class="mb-4">
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email')" required autocomplete="off" />
                            <x-input-error class="mt-2" :messages="$errors->create_jury->first('email')" />
                            {{-- <x-input-error class="mt-2" :messages="$errors->get('email', 'create_jury')" /> --}}
                        </div>

                        <div class="mb-4">
                            <x-input-label for="password" :value="__('Password ')" />
                            <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
                            <x-input-error :messages="$errors->create_jury->first('password')" class="mt-2" />
                            {{-- <x-input-error :messages="$errors->get('password', 'create_jury')" class="mt-2" /> --}}
                        </div>
                
                        <div class="mb-8">
                            <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" />
                            <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
                            <x-input-error :messages="$errors->create_jury->first('password_confirmation')" class="mt-2" />
                            {{-- <x-input-error :messages="$errors->get('password_confirmation', 'create_jury')" class="mt-2" /> --}}
                        </div>
                        
                        <div class="w-full flex justify-center items-center gap-4">
                            <button type="button" x-on:click="showaddJuryModal = false" class="block w-[48%] text-primary bg-white border border-primary focus:ring-4 focus:outline-none focus:ring-blue-200 font-medium hover:font-semibold rounded-md text-sm py-2 text-center">
                                KEMBALI
                            </button>
                            <button type="submit" class="block w-[48%] text-white bg-primary hover:bg-primary-70 border border-primary focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-md text-sm py-2 text-center">
                                KIRIM
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </x-slot>
</x-app-layout>
