<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title inertia>{{ config('app.name', 'SEA') }}</title>

    <link rel="icon" href="{{ asset('assets/img/favicon.ico') }}">

    <meta name="robots" content="max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    <link rel="canonical" href="https://preline.co/">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Comprehensive overview with charts, tables, and a streamlined dashboard layout for easy data visualization and analysis.">

    <meta name="twitter:site" content="@preline">
    <meta name="twitter:creator" content="@preline">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Tailwind CSS Admin Template | Preline UI, crafted with Tailwind CSS">
    <meta name="twitter:description" content="Comprehensive overview with charts, tables, and a streamlined dashboard layout for easy data visualization and analysis.">
    <meta name="twitter:image" content="https://preline.co/assets/img/og-image.png">

    <meta property="og:url" content="https://preline.co/">
    <meta property="og:locale" content="en_US">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Preline">
    <meta property="og:title" content="Tailwind CSS Admin Template | Preline UI, crafted with Tailwind CSS">
    <meta property="og:description" content="Comprehensive overview with charts, tables, and a streamlined dashboard layout for easy data visualization and analysis.">
    <meta property="og:image" content="https://preline.co/assets/img/og-image.png">

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Theme Check and Update -->
    <script>
        const html = document.querySelector('html');
        const isLightOrAuto = localStorage.getItem('hs_theme') === 'light' || (localStorage.getItem('hs_theme') === 'auto' && !window.matchMedia('(prefers-color-scheme: dark)').matches);
        const isDarkOrAuto = localStorage.getItem('hs_theme') === 'dark' || (localStorage.getItem('hs_theme') === 'auto' && window.matchMedia('(prefers-color-scheme: dark)').matches);

        if (isLightOrAuto && html.classList.contains('dark')) html.classList.remove('dark');
        else if (isDarkOrAuto && html.classList.contains('light')) html.classList.remove('light');
        else if (isDarkOrAuto && !html.classList.contains('dark')) html.classList.add('dark');
        else if (isLightOrAuto && !html.classList.contains('light')) html.classList.add('light');

    </script>

    <!-- Apexcharts -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts/dist/apexcharts.min.css">
    <style type="text/css">
        .apexcharts-tooltip.apexcharts-theme-light {
            background-color: transparent !important;
            border: none !important;
            box-shadow: none !important;
        }

    </style>

    <!-- CSS Preline -->
    <link rel="stylesheet" href="https://preline.co/assets/css/main.min.css">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/fonts/font-bunny.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/fontawesome/css/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/fontawesome/webfonts/fa-regular-400.ttf') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/fontawesome/webfonts/fa-regular-400.woff2') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/fontawesome/webfonts/fa-solid-900.ttf') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/fontawesome/webfonts/fa-solid-900.woff2') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/fontawesome/css/v5-font-face.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/fontawesome/css/brands.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/fontawesome/css/solid.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/fontawesome/css/regular.css') }}">
    {{-- <script src="./node_modules/preline/dist/preline.js"></script> --}}
    {{-- <script src="{{ asset('assets/js/flowbite.min.js') }}"></script> --}}

    <!-- Scripts -->
    @routes
    {{-- @vite(['resources/css/app.css','resources/js/app.js']) --}}
    @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
    @inertiaHead
    <script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
    {{-- <link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}">
    <script src="{{ asset('assets/js/select2.min.js') }}"></script> --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

</head>
<body class="bg-gray-50 dark:bg-neutral-900">

    @inertia

    {{-- <script src="{{ asset('assets/js/dataTables.js') }}"></script> --}}
    <script>
        $(document).ready(function() {
            // $('#select-residente').select2();
            $('select').select2();
        });

    </script>

</body>
</html>
