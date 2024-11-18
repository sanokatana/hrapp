<!doctype html>
<!--
* Tabler - Premium and Open Source dashboard template with responsive and high quality UI.
* @version 1.0.0-beta20
* @link https://tabler.io
* Copyright 2018-2023 The Tabler Authors
* Copyright 2018-2023 codecalm.net Paweł Kuna
* Licensed under MIT (https://github.com/tabler/tabler/blob/master/LICENSE)
-->
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Dashboard</title>
    <!-- CSS files -->
    <link href="{{ asset('tabler/dist/css/tabler.css?1692870487')}}" rel="stylesheet" />
    <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.png')}}" sizes="32x32">
    <link href="{{ asset('tabler/dist/css/tabler-vendors.min.css?1692870487')}}" rel="stylesheet" />
    <link href="{{ asset('tabler/dist/css/demo.min.css?1692870487')}}" rel="stylesheet" />
    <link href="{{ asset('jquery-ui/themes/base/jquery-ui.css')}}" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.css" rel="stylesheet" type="text/css" />
    <style>
        @import url('https://rsms.me/inter/inter.css');

        :root {
            --tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
        }

        body {
            font-feature-settings: "cv03", "cv04", "cv11";
        }
    </style>
</head>

<body class="layout-fluid">
    <script src="{{ asset('tabler/dist/js/demo-theme.min.js?1692870487')}}"></script>
    <div class="page">
        <!-- Sidebar -->
        @include('layouts.admin.sidebar')
        <!-- Navbar -->
        @include('layouts.admin.header')
        <div class="page-wrapper">
            @yield('content')
            @include('layouts.admin.footer')
        </div>
    </div>
    <!-- Libs JS -->
    <script src="{{ asset('tabler/dist/libs/apexcharts/dist/apexcharts.min.js?1692870487')}}" defer></script>
    <!-- Tabler Core -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" async></script>
    <script src="https://unpkg.com/pdf-lib"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="{{ asset('tabler/dist/js/tabler.min.js?1692870487')}}" defer></script>
    <script src="{{ asset('tabler/dist/js/demo.min.js?1692870487')}}" defer></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js" defer></script>
    @stack('myscript')
</body>

</html>
