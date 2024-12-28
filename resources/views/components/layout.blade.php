<!DOCTYPE html>
<html lang="en" class="bg-white">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.10/dist/full.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="css/BelajarCerdas.css">
    {{-- <link rel="stylesheet" href="../css/BelajarCerdas.css"> --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Document</title>
    <link rel="shortcut icon" href="#" />
    <link rel="icon" href="image/koin.png">
</head>

<body>
    <x-navbar></x-navbar>
    {{-- <x-header>{{ $title }}</x-header> --}}

    <div class="p-8 font-bold text-lg">
        {{ $slot }}
    </div>
    {{-- <x-footer></x-footer> --}}
</body>

</html>
