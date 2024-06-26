<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($title) ? $title : config('app.name', 'Penilaian Anugerah Keterbukaan Informasi Publik') }}</title>
    <link rel="icon" href="{{ asset('logo/KEMENHUB64.png') }}" type="image/png" sizes="32x32 16x16">

    <!-- Poppins Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="w-full min-h-screen bg-white flex items-center justify-center">
        {{ $slot }}
    </div>

    <!-- FlowBite -->
    <script src="{{ asset('js/flowbite.min.js') }}"></script>
    @isset($scripts)
        {{ $scripts }}
    @endisset
</body>
</html>
