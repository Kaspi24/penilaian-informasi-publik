<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Penilaian Informasi Publik') }}</title>
    <link rel="icon" href="{{ asset('logo/KEMENHUB.png') }}" type="image/png" sizes="32x32 16x16">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body>
    <div class="flex justify-center items-center h-screen bg-primary-10 px-6">
        <div class="p-6 max-w-sm w-full bg-white shadow-md rounded-md">
            <a href="/" class="flex gap-3 justify-center items-center mb-4 border-b-2 border-primary-20 pb-4">
                <img src="{{ asset('logo/KEMENHUB.png') }}" alt="" class="w-11 h-auto">
                <div class="text-primary mb-1">
                    <p class="font-bold uppercase text-lg">{{ config('app.name') }}</p>
                    <p class="text-xs font-semibold tracking-tight">Kementerian Perhubungan Republik Indonesia</p>
                </div>
            </a>
            <p class="mb-4 text-xl font-bold text-primary text-center">Buat Password Baru</p>
            
            @include('components.auth-alert')
        
            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />
        
            <form method="POST" action="{{ route('password.store') }}">
                @csrf
        
                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">
        
                <!-- Password -->
                <div class="mt-4">
                    <x-input-label for="password" :value="__('Password Baru')" />
                    <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>
        
                <!-- Confirm Password -->
                <div class="mt-4">
                    <x-input-label for="password_confirmation" :value="__('Konfirmasi Password Baru')" />
        
                    <x-text-input id="password_confirmation" class="block mt-1 w-full"
                        type="password"
                        name="password_confirmation" required autocomplete="new-password" />
        
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>
                
                <button type="submit" class="mt-4 text-white bg-primary hover:bg-primary-70 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-md text-sm w-full px-5 py-2.5 text-center">
                    ATUR ULANG PASSWORD
                </button>
            </form>
        </div>
    </div>
    
    @livewireScripts
    <!-- FlowBite -->
    <script src="{{ asset('js/flowbite.min.js') }}"></script>
</body>
</html>
