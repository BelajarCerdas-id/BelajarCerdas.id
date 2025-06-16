<!DOCTYPE html>
<html lang="en" class="!bg-white !text-black">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Belajar Cerdas</title>
    <link rel="shortcut icon" type="image/png" href="{{ asset('image/favicon-bc/logobc_icon.png') }}">

    {{-- Preconnect hints for essential external resources --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">
    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    <link rel="preconnect" href="https://unpkg.com">

    {{-- Fonts: ensure display=swap --}}
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons&display=swap">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    {{-- Your compiled app.css (includes Tailwind, DaisyUI if configured) --}}
    @vite('resources/css/app.css')

    {{-- Your custom BelajarCerdas.css (consider merging into app.css if possible) --}}
    <link rel="stylesheet" href="{{ asset('css/BelajarCerdas.css') }}">

    {{-- Asynchronously load Font Awesome (or self-host) --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"></noscript>

    {{-- REMOVED: DaisyUI CDN (if integrated with Tailwind via @vite) --}}
    {{-- REMOVED: All other CDN scripts (CKEditor, PDF.js, Chart.js, jQuery, Flatpickr, Swiper, SweetAlert2, Midtrans) --}}

</head>

<body class="overflow-x-hidden">
    <script>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>

    {{-- Main application JavaScript (from Vite) --}}
    @vite('resources/js/app.js')

    {{-- Alpine.js (deferred is good) --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- This is where page-specific scripts or dynamic imports will be pushed --}}
    @stack('scripts')

</body>

</html>