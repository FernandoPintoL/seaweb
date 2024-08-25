<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title inertia>{{ config('app.name', 'SEA') }}</title>

    <link rel="icon" href="{{ asset('/assets/img/favicon.ico') }}">

    <!-- CSS Preline -->
    <link href="https://preline.co/assets/css/main.min.css" rel="stylesheet">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link href="{{ asset('/assets/fonts/font-bunny.css') }}" rel="stylesheet">
    <link href="{{ asset('/assets/css/fontawesome/css/fontawesome.css') }}" rel="stylesheet">
    <link href="{{ asset('/assets/css/fontawesome/css/v5-font-face.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/assets/css/fontawesome/css/brands.css') }}" rel="stylesheet">
    <link href="{{ asset('/assets/css/fontawesome/css/solid.css') }}" rel="stylesheet">
    <link href="{{ asset('/assets/css/fontawesome/css/regular.css') }}" rel="stylesheet">

    <script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
    {{-- <link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}">
    <script src="{{ asset('assets/js/select2.min.js') }}"></script> --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

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
    <!-- Scripts -->
    @routes
    @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
    @inertiaHead


</head>
<body class="bg-gray-50 dark:bg-neutral-900">
    @inertia
    <script src="https://cdn.jsdelivr.net/npm/preline/dist/preline.min.js"></script>
</body>
</html>
