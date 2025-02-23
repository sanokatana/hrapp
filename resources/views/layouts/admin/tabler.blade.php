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

        #loader-wrapper {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 9999;
            background: rgba(var(--tblr-body-bg-rgb), 0.9);
            backdrop-filter: blur(4px);
            display: none;
        }

        #loader {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            flex-direction: column;
        }

        .loader-spinner {
            width: 2.5rem;
            height: 2.5rem;
            border: 3px solid var(--tblr-border-color);
            border-top-color: var(--tblr-primary);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        .loader-text {
            margin-top: 1rem;
            color: var(--tblr-body-color);
            font-size: 0.875rem;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Optional: Fade in/out animation */
        .fade-in {
            animation: fadeIn 0.2s ease-in forwards;
        }

        .fade-out {
            animation: fadeOut 0.2s ease-out forwards;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes fadeOut {
            from {
                opacity: 1;
            }

            to {
                opacity: 0;
            }
        }

        .loader-dots {
            display: flex;
            gap: 0.5rem;
        }

        .loader-dots div {
            width: 0.5rem;
            height: 0.5rem;
            border-radius: 50%;
            background-color: var(--tblr-primary);
            animation: bounce 0.5s alternate infinite;
        }

        .loader-dots div:nth-child(2) {
            animation-delay: 0.15s;
        }

        .loader-dots div:nth-child(3) {
            animation-delay: 0.3s;
        }

        @keyframes bounce {
            to {
                transform: translateY(-0.5rem);
            }
        }
    </style>
</head>

<body class="layout-fluid">
    <script src="{{ asset('tabler/dist/js/demo-theme.min.js?1692870487')}}"></script>
    <div id="loader-wrapper">
        <div id="loader">
            <div class="loader-spinner"></div>
            <div class="loader-text">Loading</div>
        </div>
    </div>
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add click event listener to all links
            document.addEventListener('click', function(e) {
                const target = e.target.closest('a');
                if (target) {
                    // Skip if it's a dropdown toggle or has no href
                    if (target.hasAttribute('data-bs-toggle') || !target.hasAttribute('href')) {
                        return;
                    }

                    // Skip if it's an external link or anchor
                    if (target.getAttribute('href').startsWith('#') ||
                        target.getAttribute('href').startsWith('http') ||
                        target.getAttribute('href').startsWith('javascript:')) {
                        return;
                    }

                    e.preventDefault(); // Prevent immediate navigation
                    const href = target.href;

                    // Show loader
                    const loader = document.getElementById('loader-wrapper');
                    loader.style.display = 'block';

                    // Wait for 2 seconds before navigating
                    setTimeout(function() {
                        window.location.href = href;
                    }, 1000);
                }
            });

            // Handle form submissions
            document.addEventListener('submit', function(e) {
                const loader = document.getElementById('loader-wrapper');
                loader.style.display = 'block';
            });

            // Hide loader when pressing back button
            window.addEventListener('pageshow', function(e) {
                if (e.persisted) {
                    const loader = document.getElementById('loader-wrapper');
                    loader.style.display = 'none';
                }
            });
        });

        // Hide loader when page is fully loaded
        window.addEventListener('load', function() {
            const loader = document.getElementById('loader-wrapper');
            loader.style.display = 'none';
        });
    </script>
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
