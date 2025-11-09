<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>Welcome To CHL</title>
    <!-- CSS files -->
    <link href="{{ asset('tabler/dist/css/tabler.css?1692870487')}}" rel="stylesheet" />
    <link href="{{ asset('tabler/dist/css/tabler-flags.min.css?1692870487')}}" rel="stylesheet" />
    <link href="{{ asset('tabler/dist/css/tabler-payments.min.css?1692870487')}}" rel="stylesheet" />
    <link href="{{ asset('tabler/dist/css/tabler-vendors.min.css?1692870487')}}" rel="stylesheet" />
    <link href="{{ asset('tabler/dist/css/demo.min.css?1692870487')}}" rel="stylesheet" />
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
  <body  class=" border-top-wide border-primary d-flex flex-column">
  <script src="{{ asset('tabler/dist/js/demo-theme.min.js?1692870487')}}"></script>
    <div class="page page-center">
      <div class="container-tight py-4" style="max-width: 100%">
        <div class="empty">
        <img src="{{ asset('assets/img/login/logo-pic.png')}}" height="300" class="d-block mx-auto" alt="">
          <p class="empty-title">Welcome to Cipta Harmoni Lestari's Human Resources Website</p>
          <p class="empty-subtitle text-secondary">
            Please Direct Yourself To One Of Our Pages
          </p>
          <div class="empty-action">
            <a href="/panel" class="btn btn-yellow">
              Karyawan
            </a>
            <a href="/panel" class="btn btn-yellow">
              Admin Panel
            </a>
          </div>
        </div>
      </div>
    </div>
    <!-- Libs JS -->
    <!-- Tabler Core -->
    <script src="{{ asset('tabler/dist/js/tabler.min.js?1692870487')}}" defer></script>
    <script src="{{ asset('tabler/dist/js/demo.min.js?1692870487')}}" defer></script>
  </body>
</html>
