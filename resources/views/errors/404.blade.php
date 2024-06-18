<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>404 - Not Found</title>
        <link rel="icon" href="{{ asset('logo/KEMENHUB.png') }}" type="image/png" sizes="32x32 16x16">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        <section class="bg-white">
            <div class="container flex items-center min-h-screen px-6 py-12 mx-auto">
                <div>
                    <p class="text-xl text-primary-50 font-extrabold">404 error</p>
                    <h1 class="mt-3 text-3xl font-semibold text-gray-700  md:text-3xl">Halaman tidak ditemukan</h1>
                    <p class="mt-4 text-lg text-gray-500 ">Mohon maaf, halaman yang anda tuju tidak tersedia atau sudah di hapus</p>
        
                    <div class="flex items-center mt-6 gap-x-3">
                        <a href="{{ url()->previous() }}" class="flex items-center justify-center w-1/2 px-5 py-2 text-sm text-gray-600 transition-colors duration-200 bg-white border rounded-lg gap-x-2 sm:w-auto hover:bg-gray-100 ">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 rtl:rotate-180">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 15.75L3 12m0 0l3.75-3.75M3 12h18" />
                            </svg>
        
                            <span>Kembali</span>
                        </a>
                        <a href="{{ route('dashboard') }}" class="w-1/2 px-5 py-2 text-sm text-center tracking-wide text-white transition-colors duration-200 bg-primary-50 rounded-lg shrink-0 sm:w-auto hover:bg-primary ">
                            Ke Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </body>
</html>