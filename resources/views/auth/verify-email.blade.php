<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Penilaian AKIP - Verifikasi Email</title>
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
            <p class="mb-4 text-xl font-bold text-primary text-center">Verifikasi Alamat Email</p>
            
            @include('components.auth-alert')
        
            <div class="mb-4 text-[13px] p-2 rounded-md border border-primary bg-primary-10 bg-opacity-25 text-primary">
                Silakan cek kotak masuk pada 
                <span class="italic text-primary-70">{{ Auth::user()->email }}</span>, 
                untuk mendapatkan <span class="font-medium">Kode Verifikasi 6 Digit</span> 
                yang diperlukan untuk mengkonfirmasi alamat email Anda. 
                Bila anda merasa bahwa email yang anda gunakan saat mendaftar salah, 
                anda dapat mengubah alamat email pada menu profil 
                <a href="{{ route('profile.edit') }}" class="italic underline text-primary-60 font-semibold">berikut</a>.
            </div>
            <form action="{{ route('verify-email') }}" method="POST">
                @method('POST')
                @csrf
                <!-- Event Title -->
                <div class="mb-3" x-data="">
                    <label for="code" class="block w-full text-center mb-2 text-base font-medium text-gray-700">
                        Kode Verifikasi 6 Digit
                    </label>
                    <input type="text" id="code" name="code" 
                        class="tracking-[4px] text-center text-xl border-2 border-primary-20 text-primary-50 font-bold rounded-md focus:ring-primary-70 focus:border-primary-70 block w-full p-2 custom-placeholder" 
                        autocomplete="off" 
                        placeholder="XXXXXX"
                        x-mask="999999"
                        >
                    @if (session('failed_to_verify'))
                        <p class="block mt-1 text-xs font-medium text-danger">Kode yang anda masukkan tidak valid.</p>
                    @endif
                </div>
                <div class="">
                    <button type="submit" class="w-full text-white bg-primary hover:bg-primary-70 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-md text-sm px-4 py-2 text-center">
                        Verifikasi
                    </button>
                </div>
            </form>
        
            <div class="w-full border-b-2 my-3 border-primary-20"></div>
        
            @if (session('success_to_resend'))
                <div class="w-full">
                    <div id="alertMessage" class="flex justify-between gap-2 items-center p-2 mb-3 bg-green-200 rounded-lg" role="alert">
                        <div class="flex justify-start gap-2 items-center">
                            <span>
                                <svg aria-hidden="true" class="flex-shrink-0 w-4 h-4 text-green-700" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                            </span>
                            <p class="text-xs text-green-700">Email berisi <span class="font-medium">Kode Verifikasi 6 Digit</span> baru sudah dikirimkan ke alamat email <span class="font-medium italic">{{ Auth::user()->email }}</span>.</p>
                        </div>
                        <button type="button" class="flex items-center justify-center bg-green-200 text-green-500 rounded-lg focus:ring-1 focus:ring-green-400 hover:bg-green-300" data-dismiss-target="#alertMessage" aria-label="Close">
                            <svg aria-hidden="true" class="w-4 h-4 m-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            @endif
        
            <div class="mt-4 w-full">
                <button type="button" 
                    class="w-full bg-gray-100 text-gray-400 focus:ring-2 focus:outline-none focus:ring-blue-500 font-medium rounded-md text-sm px-4 py-2 text-center" 
                    id="resend-verification-button"
                    disabled>
                    Kirim Ulang Email Verifikasi <span id="resend-timer"></span>
                </button>
            </div>
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