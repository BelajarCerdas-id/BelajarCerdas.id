<!DOCTYPE html>
<html lang="en" class="!bg-white !text-black">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <!--- fotnt awesome CDN ---->
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.10/dist/full.min.css" rel="stylesheet" type="text/css">
    <!--- daisy cdn ---->
    <link rel="stylesheet" href="{{ asset('css/BelajarCerdas.css') }}"> <!--- link CSS ---->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!--- source script filter data(ajax) ---->
    <script src="https://cdn.ckeditor.com/ckeditor5/34.2.0/classic/ckeditor.js"></script> <!--- CKEDITOR 5 ---->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.min.js"></script> <!-- upload PDF ---->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!--- sweetAlert CDN  ---->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css"> <!--- flatpickr ---->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script> <!--- flatpickr ---->
    <script src="https://cdn.tailwindcss.com"></script> <!--- tailwind cdn ---->
    @vite('resources/css/app.css') <!--- vite ---->
    @vite('resources/js/app.js') <!--- vite pusher ---->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}">
    </script> <!--- script midtrans snap js (diganti "sandbox" nya kalau suda production)) ---->

    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <!--- link for icon video ---->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <title>Document</title>
    <link rel="shortcut icon" href="#" />
    <link rel="icon" href="image/bc-favicon.ico">
</head>

<body class="overflow-x-hidden">
    <script>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
</body>

</html>
