<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Penilaian AKIP KEMENHUB RI') }}</title>
    <link rel="icon" href="{{ asset('logo/KEMENHUB64.png') }}" type="image/png" sizes="32x32 16x16">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        .bg-header-nav {
            background: rgb(8,8,22);
            background: linear-gradient(347deg, rgba(8,8,22,1) 6%, rgba(47,48,134,1) 81%, rgba(82,82,154,1) 92%, rgba(116,117,174,1) 100%);
        }
        .bg-section-1 {
            background: rgb(227,227,241);
            background: linear-gradient(90deg, rgba(227,227,241,1) 0%, rgba(231,231,255,1) 35%, rgba(255,255,255,1) 100%);
        }
    </style>
</head>
<body class="antialiased">
    <div class="bg-primary-10/25 w-full">
        <!-- HEADER NAVIGATION -->
        <nav x-data="{ open: false }" class="bg-header-nav text-white border-b shadow shadow-primary-20">
            <!-- Primary Navigation Menu -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 lg:h-[4.5rem]">
                    <!-- Logo -->
                    <div class="shrink-0 flex items-center  max-w-[70%] lg:max-w-[30%]">
                        <a href="{{ route('dashboard') }}" class="flex w-fit gap-2 lg:gap-3 items-center">
                            <img src="{{ asset('logo/KEMENHUB.png') }}" class="h-10 w-auto" alt="">
                            <span class="text-white font-extrabold mb-0.5 lg:mb-1">
                                <p class="text-[0.7rem] lg:text-xs xl:text-sm font-bold tracking-tight xl:tracking-normal uppercase break-words">Penilaian Anugerah<br>Keterbukaan Informasi Publik</p>
                                <p class="text-[0.65rem] lg:text-[0.7rem] tracking-tighter lg:tracking-tight text-primary-30">
                                    Kementerian Perhubungan Republik Indonesia
                                </p>
                            </span>
                        </a>
                    </div>
        
                    <div class="flex">
                        <!-- Navigation Links -->
                        <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                            <x-nav-link href="#beranda" :active="request()->routeIs('dashboard')">
                                {{ __('Beranda') }}
                            </x-nav-link>
                            <x-nav-link href="#alur" :active="request()->routeIs('dashboard')">
                                {{ __('Alur') }}
                            </x-nav-link>
                            <x-nav-link href="#panduan" :active="request()->routeIs('dashboard')">
                                {{ __('Panduan Pengisian') }}
                            </x-nav-link>
                            @auth
                                <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                                    {{ __('Dashboard') }}
                                </x-nav-link>
                            @else
                                <x-nav-link :href="route('login')" :active="request()->routeIs('dashboard')">
                                    {{ __('Login') }}
                                </x-nav-link>
                                <x-nav-link :href="route('register')" :active="request()->routeIs('dashboard')">
                                    {{ __('Daftar') }}
                                </x-nav-link>
                            @endauth
                        </div>
                    </div>
        
                    <!-- Hamburger -->
                    <div class="-me-2 flex items-center sm:hidden">
                        <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-primary-40 hover:text-primary-50 hover:bg-primary-10 focus:outline-none focus:bg-primary-10 focus:text-primary-50 transition duration-150 ease-in-out">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        
            <!-- Responsive Navigation Menu -->
            <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-primary-10">
                <div class="pt-2 pb-3 space-y-1">
                    <x-responsive-nav-link href="#beranda" :active="request()->routeIs('dashboard')">
                        {{ __('Beranda') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link href="#alur" :active="request()->routeIs('questionnaire.index')">
                        {{ __('Alur') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link href="#panduan" :active="request()->routeIs('questionnaire.index')">
                        {{ __('Panduan Pengisian') }}
                    </x-responsive-nav-link>
                    @auth
                        <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                            {{ __('Dashboard') }}
                        </x-responsive-nav-link>
                    @else
                        <x-responsive-nav-link :href="route('login')" :active="request()->routeIs('dashboard')">
                            {{ __('Login') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('register')" :active="request()->routeIs('dashboard')">
                            {{ __('Daftar') }}
                        </x-responsive-nav-link>
                    @endauth
                </div>
            </div>
        </nav> 
        
        <!-- MAIN CONTAINER -->
        <main class="">
            <!-- SECTION 1 -->
            <section id="beranda" class="p-4 md:p-8 lg:py-12 xl:h-[calc(100vh-4.5rem)] xl:flex xl:flex-col xl:justify-center">
                <div class="relative bg-section-1 max-w-7xl aspect-[3/4] md:aspect-[2/1] mx-auto p-6 md:p-8 lg:p-16 text-primary shadow-md shadow-primary-30/50 rounded overflow-hidden">
                    <img src="{{ asset('design/low-poly-grid-haikei-4.png') }}" class="md:hidden absolute opacity-[0.075] w-full h-full top-0 left-0" alt="">
                    <img src="{{ asset('design/low-poly-grid-haikei-5.png') }}" class="hidden md:block lg:hidden absolute opacity-[0.075] w-full h-full top-0 left-0" alt="">
                    <img src="{{ asset('design/low-poly-grid-haikei-6.png') }}" class="hidden lg:block absolute opacity-[0.075] w-full h-full top-0 left-0" alt="">
                    <img src="{{ asset('logo/KEMENHUB.png') }}" class="absolute lg:w-1/2 opacity-15 -rotate-12 top-1/3 md:top-1/4 -left-1/3 md:-left-[10%]" alt="">
                    <div class="w-full h-full flex flex-col justify-center">
                        <h1 class="text-xl md:text-3xl lg:text-5xl text-center text-primary-80 font-['Impact'] font-black uppercase">
                            Anugerah Keterbukaan Informasi Publik (AKIP)
                        </h1>
                        <h2 class="text-sm md:text-2xl lg:text-4xl text-center font-semibold mb-6 text-primary-70">Kementerian Perhubungan Tahun 2024</h2>
                        <p class="text-justify text-base md:text-xl lg:text-3xl leading-5 md:leading-7 lg:leading-9 tracking-tight lg:tracking-normal"> 
                            <span class="font-bold">AKIP</span> dilakukan guna menilai dan mengevaluasi pelaksanaan keterbukaan informasi Publik di PPID Pelaksana dan PPID Pelaksana UPT di lingkungan Kementerian Perhubungan sebagaimana yang telah tertuang dalam <span class="font-semibold">Undang-Undang Nomor 14 Tahun 2008</span> tentang keterbukaan informasi Publik, <span class="font-semibold">Peraturan Menteri Perhubungan Nomor 46 Tahun 2018</span> tentang pedoman pengelola Informasi dan Dokumentasi di Lingkungan Kementerian Perhubungan, dan <span class="font-semibold">Keputusan Menteri Perhubungan Nomor 117 tahun 2022</span> tentang SOP Pejabat Pengelola Informasi dan Dokumentasi di Lingkungan Kementerian Perhubungan.</p>
                    </div>
                </div>
            </section>
            <!-- SECTION 2 -->
            <section id="alur" class="p-4 md:p-8 ">
                <div class="max-w-7xl mx-auto lg:p-10">
                    <img src="{{ asset('design/PANDUAN.png') }}" class="w-full " alt="">
                </div>
            </section>
            <!-- SECTION 3 -->
            <section id="panduan" class="bg-primary p-4 md:p-16 xl:p-44 flex justify-between">
                <div class="">
                    <p class="w-fit text-base md:text-4xl lg:text-6xl font-['Impact'] text-white mb-2 md:mb-4">Panduan Pengisian Jawaban</p>
                    <a href="{{ asset('file/Panduan Pengisian Jawaban AKIP 2024.pdf') }}" download 
                        class="flex items-center gap-3 bg-warning p-2 md:p-5 px-4 md:px-10 w-fit rounded-full text-warning-100 hover:bg-warning-70 transition-all ease-in-out duration-300">
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5 md:size-8">
                                <path fill-rule="evenodd" d="M2 4.75C2 3.784 2.784 3 3.75 3h4.836c.464 0 .909.184 1.237.513l1.414 1.414a.25.25 0 0 0 .177.073h4.836c.966 0 1.75.784 1.75 1.75v8.5A1.75 1.75 0 0 1 16.25 17H3.75A1.75 1.75 0 0 1 2 15.25V4.75Zm8.75 4a.75.75 0 0 0-1.5 0v2.546l-.943-1.048a.75.75 0 1 0-1.114 1.004l2.25 2.5a.75.75 0 0 0 1.114 0l2.25-2.5a.75.75 0 1 0-1.114-1.004l-.943 1.048V8.75Z" clip-rule="evenodd" />
                            </svg>
                        </span>
                        <span class="text-sm md:text-2xl font-bold">UNDUH</span>
                    </a>
                </div>
                <div class="border-x-2 ">
                </div>
                <div class="flex flex-col justify-center">
                    <a href="{{ route('login') }}" class="flex items-center gap-3 bg-emerald-600 p-2 md:p-5 px-4 md:px-10 w-fit rounded-full text-emerald-100 hover:bg-emerald-700 transition-all ease-in-out duration-300">
                        <span class="text-sm md:text-2xl font-bold">LOGIN</span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5 md:size-8 rotate-180">
                            <path fill-rule="evenodd" d="M3 4.25A2.25 2.25 0 0 1 5.25 2h5.5A2.25 2.25 0 0 1 13 4.25v2a.75.75 0 0 1-1.5 0v-2a.75.75 0 0 0-.75-.75h-5.5a.75.75 0 0 0-.75.75v11.5c0 .414.336.75.75.75h5.5a.75.75 0 0 0 .75-.75v-2a.75.75 0 0 1 1.5 0v2A2.25 2.25 0 0 1 10.75 18h-5.5A2.25 2.25 0 0 1 3 15.75V4.25Z" clip-rule="evenodd" />
                            <path fill-rule="evenodd" d="M19 10a.75.75 0 0 0-.75-.75H8.704l1.048-.943a.75.75 0 1 0-1.004-1.114l-2.5 2.25a.75.75 0 0 0 0 1.114l2.5 2.25a.75.75 0 1 0 1.004-1.114l-1.048-.943h9.546A.75.75 0 0 0 19 10Z" clip-rule="evenodd" />
                        </svg>
                    </a>
                </div>
            </section>
        </main>
    </div>
    @livewireScripts
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/flowbite.min.js') }}"></script>
</body>
</html>
