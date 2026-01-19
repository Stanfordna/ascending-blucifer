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
</head>
<body class="antialiased">
    <div id="app"></div>
</body>
</html>
