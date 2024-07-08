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
        <nav x-data="{ open: false }" class="bg-header-nav text-white shadow shadow-primary-20">
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
                            @foreach ($contents as $content)
                                @if ($content->is_visible)
                                    <x-nav-link href="#content-{{ $content->id }}">
                                        {{ $content->name }}
                                    </x-nav-link>
                                @endif
                            @endforeach
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
                    @foreach ($contents as $content)
                        @if ($content->is_visible)
                            <x-responsive-nav-link href="#content-{{ $content->id }}">
                                {{ $content->name }}
                            </x-responsive-nav-link>
                        @endif
                    @endforeach
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
        
        @auth
            @if (Auth::user()->role === 'SUPERADMIN')
                <div class="w-full max-w-7xl mx-auto p-4 lg:p-6 flex justify-end items-center">
                    <a href="{{ route('landing-page.edit') }}" class="w-fit bg-primary hover:bg-primary-70 text-white p-2 text-xs lg:text-sm flex items-center gap-2 rounded">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4 lg:size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                        </svg>
                        <span class="uppercase">
                            Kelola konten Beranda
                        </span>
                    </a>
                </div>
            @endif
        @endauth
        <!-- MAIN CONTAINER -->
        <main class="">
            @forelse ($contents as $content)
                @if ($content->is_visible)
                    <div id="content-{{ $content->id }}" class="w-full max-w-screen-2xl mx-auto">
                        <img src="{{ asset('storage/'.$content->image) }}" class="w-full h-auto" alt="">
                    </div>
                @endif
            @empty
                <div class="w-full max-w-7xl mx-auto">
                    <img src="{{ asset('image/2.jpg') }}" class="w-full h-auto" alt="">
                </div>
                <div class="w-full max-w-7xl mx-auto">
                    <img src="{{ asset('image/3.jpg') }}" class="w-full h-auto" alt="">
                </div>
                <div class="w-full max-w-7xl mx-auto">
                    <img src="{{ asset('image/4.jpg') }}" class="w-full h-auto" alt="">
                </div>
            @endforelse
        </main>
    </div>
    @livewireScripts
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/flowbite.min.js') }}"></script>
</body>
</html>
