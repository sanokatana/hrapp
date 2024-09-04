<!doctype html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover, user-scalable=1" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="theme-color" content="#000000">
    <title>E-Attendance</title>
    <meta name="description" content="E-Attendance CHL">
    <meta name="keywords" content="bootstrap 4, mobile template, cordova, phonegap, mobile, html" />
    <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.png')}}" sizes="32x32">
    <link rel="apple-touch-icon" sizes="180x180" href="assets/img/favicon.png">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css')}}">
    <link rel="manifest" href="__manifest.json">
    <style>
        /* Dark Mode Styles */
        body.dark-mode-active {
            background-color: #343a40;
            color: #fff;
        }
        /* Add more styles as needed for dark mode */
    </style>
    @stack('styles') <!-- Stack for additional styles -->
</head>

<body style="background-color:#e9ecef;">

    <!-- loader -->
    <div id="loader">
        <div class="spinner-border text-primary" role="status"></div>
    </div>
    <!-- * loader -->

    @yield('header')

    <!-- App Capsule -->
    <div id="appCapsule">
        @yield('content')
    </div>
    <!-- * App Capsule -->

    @include('layouts.bottomNav')

    @include('layouts.script')

    @stack('scripts') <!-- Stack for additional scripts -->

</body>

</html>
