<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Penilaian AKIP - Lupa Password</title>
    <link rel="icon" href="{{ asset('logo/KEMENHUB64.png') }}" type="image/png" sizes="32x32 16x16">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body>
    <div class="flex justify-center items-center h-screen bg-primary-10 px-6">
        <div class="p-6 max-w-sm w-full bg-white shadow-md rounded-md">
            <a href="/" class="flex gap-3 justify-center items-center mb-4 border-b-2 border-primary-20 pb-4">
                <img src="{{ asset('logo/KEMENHUB.png') }}" alt="" class="w-11 h-auto">
                <div class="text-primary mb-1">
                    <p class="font-bold uppercase text-sm lg:text-base leading-[1.125rem] mb-0.5 lg:mb-1">{{ config('app.name') }}</p>
                    <p class="text-xs font-semibold tracking-tight text-primary-40">Kementerian Perhubungan Republik Indonesia</p>
                </div>
            </a>
            <p class="mb-4 text-xl font-bold text-primary text-center">Lupa Password</p>
            
            @include('components.auth-alert')
        
            <div class="mb-4 text-sm text-gray-600 text-justify">
                Lupa password? Jangan khawatir. Cukup masukkan <span class="text-primary">alamat email</span> atau <span class="text-primary">username</span> anda dan kami akan mengirimkan tautan untuk mengatur ulang kata sandi.
            </div>
        
            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />
        
            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div>
                    <x-input-label for="email_or_username" :value="__('Email atau Username')" />
                    <x-text-input id="email_or_username" name="email_or_username" type="text" class="mt-1 block w-full" :value="old('email_or_username')" required autocomplete="off" autofocus />
                    <x-input-error class="mt-2" :messages="$errors->get('email_or_username')" />
                </div>

                <button type="submit" class="mt-4 text-white bg-primary hover:bg-primary-70 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-md text-sm w-full px-5 py-2.5 text-center">
                    DAPATKAN TAUTAN
                </button>
            </form>
        </div>
    </div>
    
    @livewireScripts
    <!-- FlowBite -->
    <script src="{{ asset('js/flowbite.min.js') }}"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.3.min.js" 
        integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous">
    </script>
    <script>
        $(document).ready(function () {
            let timer = 60;
            let interval = setInterval(function() {
                let seconds = timer % 60;
                let remainingTime = (seconds < 10) ? ("0" + seconds) : (seconds);
                $('#resend-timer').text("(" + remainingTime + " detik)");
                timer--;
                if (timer < 0) {
                    clearInterval(interval);
                    $('#resend-timer').text("");
                    $("#resend-verification-button").removeClass("bg-gray-100 text-gray-400");
                    $("#resend-verification-button").addClass("text-primary bg-primary-10 hover:bg-primary-20");
                    $("#resend-verification-button").attr("disabled", false);
                }
            }, 1000);

            $("#resend-verification-button").click(function (e) { 
                e.preventDefault();
                var url = "{{ route('resend-email-verification') }}";
                window.location.href = url;
            });
        });
    </script>
</body>
</html>