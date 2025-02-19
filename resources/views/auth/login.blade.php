<!doctype html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, viewport-fit=cover" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="theme-color" content="#000000">
    <title>HRMS Connect</title>
    <meta name="description" content="HRMS Connect">
    <meta name="keywords" content="bootstrap 4, {{ asset('')}}mobile template, cordova, phonegap, mobile, html" />
    <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.png')}}" sizes="32x32">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/img/favicon.png')}}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css')}}">
    <link rel="manifest" href="__manifest.json">
</head>

<body class="bg-white">

    <!-- loader -->
    <div id="loader">
        <div class="spinner-border text-primary" role="status"></div>
    </div>
    <!-- * loader -->

    <!-- App Capsule -->
    <div id="appCapsule" class="pt-0">

        <div class="login-form mt-1">
            <div class="section">
                <img src="{{ asset('assets/img/login/logo-pic.png')}}" alt="image" class="form-image">
            </div>
            <div class="section mt-1">
                <h1>E-Attendance</h1>
                <h4>Silahkan Login</h4>
            </div>
            <div class="section mt-1 mb-5">
                @php
                $messageWarning = Session::get('warning');
                @endphp
                @if (Session::get('warning'))
                <div class="alert alert-outline-warning">
                    {{ $messageWarning }}
                </div>
                @endif
                <form action="/proseslogin" method="POST">
                    @csrf
                    <div class="form-group boxed">
                        <div class="input-wrapper">
                        <input type="text" name="nik_or_email" class="form-control" id="nik_or_email" placeholder="NIK or Email" value="{{ old('nik_or_email') }}">
                            <i class="clear-input">
                                <ion-icon name="close-circle"></ion-icon>
                            </i>
                        </div>
                    </div>

                    <div class="form-group boxed">
                        <div class="input-wrapper">
                            <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                            <i class="toggle-password">
                                <ion-icon name="eye-outline" onclick="togglePassword()"></ion-icon>
                            </i>
                        </div>
                    </div>
                    <div class="form-group boxed mt-2">
                        <input type="checkbox" name="remember" class="form-check-input"/>
                        <span class="form-check-label">Remember me on this device</span>
                    </div>
                    <div class="form-group boxed mt-2">
                        <div><a href="page-forgot-password.html" class="text-muted">Forgot Password?</a></div>
                        <div><a href="/panel" class="text-muted2">Admin Dashboard</a></div>
                    </div>

                    <div class="form-button-group">
                        <button type="submit" class="btn btn-success btn-block btn-lg">Log in</button>
                    </div>

                </form>
            </div>
        </div>

    </div>
    <!-- * App Capsule -->

    <!-- ///////////// Js Files ////////////////////  -->
    <!-- Jquery -->
    <script src="{{ asset('assets/js/lib/jquery-3.4.1.min.js')}}"></script>
    <!-- Bootstrap-->
    <script src="{{ asset('assets/js/lib/popper.min.js')}}"></script>
    <script src="{{ asset('assets/js/lib/bootstrap.min.js')}}"></script>
    <!-- Ionicons -->
    <script type="module" src="https://unpkg.com/ionicons@5.0.0/dist/ionicons/ionicons.js"></script>
    <!-- Owl Carousel -->
    <script src="{{ asset('assets/js/plugins/owl-carousel/owl.carousel.min.js')}}"></script>
    <!-- jQuery Circle Progress -->
    <script src="{{ asset('assets/js/plugins/jquery-circle-progress/circle-progress.min.js')}}"></script>
    <!-- Base Js File -->
    <script src="{{ asset('assets/js/base.js')}}"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.querySelector("form").addEventListener("submit", function(event) {
            let input = document.getElementById("nik_or_email").value.trim();
            let nikFormat = /^\d{4}-\d{8}M?$/; // Accepts 4-8 or 4-8M

            if (input.includes("@")) {
                // It's an email, allow submission
                return true;
            } else if (!nikFormat.test(input)) {
                // If not an email and doesn't match NIK format, show an alert
                event.preventDefault(); // Prevent form submission
                Swal.fire({
                    icon: "error",
                    title: "Format NIK / Email anda Salah",
                    text: "Gunakan format NIK: xxxx-xxxxxxxx atau xxxx-xxxxxxxxM atau masukkan email yang valid!",
                });
            }
        });
    </script>

</body>

</html>
