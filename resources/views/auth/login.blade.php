<x-guest-layout>
    <x-slot name="title">Penilaian AKIP - Login</x-slot>
    <div class="w-full md:w-1/2 lg:1/3 xl:w-1/4 p-4 md:p-3 xl:p-2">
        @include('components.auth-alert')
        <p class="text-center text-3xl text-primary font-bold px-2">Selamat Datang</p>
        <img src="{{ asset('logo/KEMENHUB.png') }}" alt="" class="w-1/2 h-auto mx-auto my-2 lg:my-4">
        <a href="/">
            <p class="text-center text-lg lg:text-xl text-primary font-bold px-2 mb-1 lg:mb-1.5 uppercase">
                {{ config('app.name') }}
            </p>
            <p class="text-center text-sm text-primary-40 font-medium px-2 mb-2 lg:mb-4 tracking-tight">
                Kementerian Perhubungan Republik Indonesia
            </p>
        </a>

        <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-4 px-4">
            @csrf
            <div>
                <x-input-label for="username" :value="__('Username')" />
                <x-text-input id="username" name="username" type="text" :value="old('username')" class="mt-1 block w-full" required autocomplete="off" />
                <x-input-error class="mt-1" :messages="$errors->get('username')" />
            </div>

            <div>
                <x-input-label for="password" :value="__('Password ')" />
                <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-1" />
            </div>

            <a  href="{{ route('password.request') }}" class="block w-fit mx-auto underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                Lupa password?
            </a>

            <button type="submit" class="text-white bg-primary hover:bg-primary-70 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-md text-sm w-full px-5 py-2.5 text-center">
                MASUK
            </button>

            <div class="border-b-2 border-primary-20"></div>

            <a href="{{ route('register') }}" class="block text-white bg-success hover:bg-success-70 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-md text-sm w-full px-5 py-2.5 text-center">
                DAFTAR AKUN BARU
            </a>
        </form>
    </div>
</x-guest-layout>