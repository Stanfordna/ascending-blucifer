<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Maggie Chamberlain, MS, RD, CDCES | Diabetes Care & Nutrition</title>
    <meta name="description" content="Expert diabetes care and nutrition guidance from Maggie Chamberlain, a Registered Dietitian and Certified Diabetes Care and Education Specialist in Colorado.">

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">

    <!-- Open Graph -->
    <meta property="og:title" content="Maggie Chamberlain, MS, RD, CDCES">
    <meta property="og:description" content="Expert diabetes care and nutrition guidance rooted in the Colorado spirit.">
    <meta property="og:type" content="website">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @php
        $siteColors = [
            'primary' => \App\Models\SiteSetting::get('color_primary', '#4A7C89'),
            'secondary' => \App\Models\SiteSetting::get('color_secondary', '#C4785A'),
            'accent' => \App\Models\SiteSetting::get('color_accent', '#D4A84B'),
        ];
    @endphp
    <script>
        window.__SITE_COLORS__ = @json($siteColors);
    </script>
</head>
<body class="antialiased">
    <div id="app"></div>
</body>
</html>
