<aside class="navbar navbar-vertical navbar-expand-lg sidebar-collapsed" data-bs-theme="dark" id="sidebar">
    @php
    $user = Auth::guard('user')->user();
    $userLevel = $user ? $user->level : null;
    @endphp
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu"
            aria-controls="sidebar-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <h1 class="navbar-brand navbar-brand-autodark">
        </h1>
        <div class="navbar-nav flex-row d-lg-none">
            <div class="d-none d-lg-flex">
                <div class="nav-item dropdown d-none d-md-flex me-3">
                    <a href="#" class="nav-link px-0" data-bs-toggle="dropdown" tabindex="-1"
                        aria-label="Show notifications">
                        <!-- Download SVG icon from http://tabler-icons.io/i/bell -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 18 24"
                            stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path
                                d="M10 5a2 2 0 1 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6" />
                            <path d="M9 17v1a3 3 0 0 0 6 0v-1" />
                        </svg>
                        <span class="badge bg-red"></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-end dropdown-menu-card">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Last updates</h3>
                            </div>
                            <div class="list-group list-group-flush list-group-hoverable">
                                <div class="list-group-item">
                                    <div class="row align-items-center">
                                        <div class="col-auto"><span
                                                class="status-dot status-dot-animated bg-red d-block"></span></div>
                                        <div class="col text-truncate">
                                            <a href="#" class="text-body d-block">Example 1</a>
                                            <div class="d-block text-secondary text-truncate mt-n1">
                                                Change deprecated html tags to text decoration classes (#29604)
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <a href="#" class="list-group-item-actions">
                                                <!-- Download SVG icon from http://tabler-icons.io/i/star -->
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon text-muted"
                                                    width="24" height="24" viewBox="0 0 18 24" stroke-width="2"
                                                    stroke="currentColor" fill="none" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path
                                                        d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z" />
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="list-group-item">
                                    <div class="row align-items-center">
                                        <div class="col-auto"><span class="status-dot d-block"></span></div>
                                        <div class="col text-truncate">
                                            <a href="#" class="text-body d-block">Example 2</a>
                                            <div class="d-block text-secondary text-truncate mt-n1">
                                                justify-content:between ⇒ justify-content:space-between (#29734)
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <a href="#" class="list-group-item-actions show">
                                                <!-- Download SVG icon from http://tabler-icons.io/i/star -->
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon text-yellow"
                                                    width="24" height="24" viewBox="0 0 18 24" stroke-width="2"
                                                    stroke="currentColor" fill="none" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path
                                                        d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z" />
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="list-group-item">
                                    <div class="row align-items-center">
                                        <div class="col-auto"><span class="status-dot d-block"></span></div>
                                        <div class="col text-truncate">
                                            <a href="#" class="text-body d-block">Example 3</a>
                                            <div class="d-block text-secondary text-truncate mt-n1">
                                                Update change-version.js (#29736)
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <a href="#" class="list-group-item-actions">
                                                <!-- Download SVG icon from http://tabler-icons.io/i/star -->
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon text-muted"
                                                    width="24" height="24" viewBox="0 0 18 24" stroke-width="2"
                                                    stroke="currentColor" fill="none" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path
                                                        d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z" />
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="list-group-item">
                                    <div class="row align-items-center">
                                        <div class="col-auto"><span
                                                class="status-dot status-dot-animated bg-green d-block"></span></div>
                                        <div class="col text-truncate">
                                            <a href="#" class="text-body d-block">Example 4</a>
                                            <div class="d-block text-secondary text-truncate mt-n1">
                                                Regenerate package-lock.json (#29730)
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <a href="#" class="list-group-item-actions">
                                                <!-- Download SVG icon from http://tabler-icons.io/i/star -->
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon text-muted"
                                                    width="24" height="24" viewBox="0 0 18 24" stroke-width="2"
                                                    stroke="currentColor" fill="none" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path
                                                        d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z" />
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="nav-item dropdown">
                <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown"
                    aria-label="Open user menu">
                    <img src="{{ asset('assets/img/white.png')}}" class="avatar avatar-sm">
                    <div class="d-none d-xl-block ps-2">
                        <div>{{ Auth::guard('user')->user()->name}}</div>
                        @php
                        $user = Auth::guard('user')->user();
                        $userLevel = $user ? $user->level : null;
                        @endphp
                        <div class="mt-1 small text-secondary">{{$userLevel}}</div>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <a href="/panel/accountSetting" class="dropdown-item">Settings</a>
                    <a href="/panel/proseslogoutadmin" class="dropdown-item">Logout</a>
                </div>
            </div>
        </div>
        <div class="collapse navbar-collapse" id="sidebar-menu">
            <ul class="navbar-nav pt-lg-3">
                <li class="nav-item">
                    <a class="nav-link" href="/">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                viewBox="0 0 18 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M5 12l-2 0l9 -9l9 9l-2 0" />
                                <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" />
                                <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" />
                            </svg>
                        </span>
                        <span class="nav-link-title">
                            Home
                        </span>
                    </a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{request()->is(['companies', 'cabang', 'departments', 'jabatans'])? 'show' : '' }}" href="#navbar-base" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="{{request()->is(['companies', 'data/user']) ? 'true' : 'false' }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-building-community">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M3 21v-13l9 -4l9 4v13" />
                                <path d="M13 13h4v8h-10v-8h4" />
                                <path d="M9 9h1" />
                                <path d="M9 12h1" />
                                <path d="M15 9h1" />
                                <path d="M3 21h18" />
                                <path d="M9 21v-4h6v4" />
                            </svg>
                        </span>
                        <span class="nav-link-title">
                            Organisasi
                        </span>
                    </a>
                    <div class="dropdown-menu {{ request()->is(['companies', 'cabang', 'departments', 'jabatans']) ? 'show' : '' }}">
                        <div class="dropdown-menu-columns">
                            <div class="dropdown-menu-column">
                                <a class="dropdown-item {{ request()->is('companies*') ? 'active' : '' }}" href="/companies">
                                    Perusahaan
                                </a>
                                <a class="dropdown-item {{ request()->is('cabang*') ? 'active' : '' }}" href="/cabang">
                                    Cabang
                                </a>
                                <a class="dropdown-item {{ request()->is('departments*') ? 'active' : '' }}" href="/departments">
                                    Department
                                </a>
                                <a class="dropdown-item {{ request()->is('jabatans*') ? 'active' : '' }}" href="/jabatans">
                                    Jabatan
                                </a>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->is(['karyawan*', 'data/user*']) ? 'show' : '' }}" href="#navbar-people" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="{{ request()->is(['karyawan*', 'data/user*']) ? 'true' : 'false' }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-users">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                                <path d="M3 21v-2a4 4 0 0 1 4 -4h2" />
                                <path d="M16 11m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                                <path d="M19 21v-2a3 3 0 0 0 -2.623 -2.959" />
                            </svg>
                        </span>
                        <span class="nav-link-title">
                            Manajemen Karyawan
                        </span>
                    </a>
                    <div class="dropdown-menu {{ request()->is(['karyawan*', 'data/user*']) ? 'show' : '' }}">
                        <div class="dropdown-menu-columns">
                            <div class="dropdown-menu-column">
                                <a class="dropdown-item {{ request()->is('karyawan*') ? 'active' : '' }}" href="/karyawan">
                                    Data Pekerja
                                </a>
                                @if($userLevel === 'Superadmin')
                                <a class="dropdown-item {{ request()->is('data/user*') ? 'active' : '' }}" href="/data/user">
                                    Pengguna Sistem
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->is('absensi/*') ? 'show' : '' }}" href="#navbar-attendance" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="{{ request()->is('absensi/*') ? 'true' : 'false' }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-calendar">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z" />
                                <path d="M16 3v4" />
                                <path d="M8 3v4" />
                                <path d="M4 11h16" />
                                <path d="M11 15h1" />
                                <path d="M12 15v3" />
                            </svg>
                        </span>
                        <span class="nav-link-title">Absensi</span>
                    </a>
                    <div class="dropdown-menu {{ request()->is('absensi/*') ? 'show' : '' }}">
                        <div class="dropdown-menu-columns">
                            <div class="dropdown-menu-column">
                                <a class="dropdown-item {{ request()->is('absensi/per-pekerja') ? 'active' : '' }}" href="/absensi/per-pekerja">
                                    Per Pekerja
                                </a>
                                <a class="dropdown-item {{ request()->is('absensi/tabel') ? 'active' : '' }}" href="/absensi/tabel">
                                    Tabel Periode
                                </a>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->is('payroll/*') ? 'show' : '' }}" href="#navbar-payroll" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="{{ request()->is('payroll/*') ? 'true' : 'false' }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-cash-banknote">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 12m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                                <path d="M3 6m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z" />
                                <path d="M18 12l.01 0" />
                                <path d="M6 12l.01 0" />
                            </svg>
                        </span>
                        <span class="nav-link-title">Payroll</span>
                    </a>
                    <div class="dropdown-menu {{ request()->is('payroll/*') ? 'show' : '' }}">
                        <div class="dropdown-menu-columns">
                            <div class="dropdown-menu-column">
                                <a class="dropdown-item {{ request()->is('payroll/per-pekerja') ? 'active' : '' }}" href="/payroll/per-pekerja">
                                    Per Pekerja
                                </a>
                                <a class="dropdown-item {{ request()->is('payroll/tabel') ? 'active' : '' }}" href="/payroll/tabel">
                                    Tabel Periode
                                </a>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</aside>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.getElementById('sidebar');
    const dropdowns = document.querySelectorAll('.nav-item.dropdown'); // Select all dropdown items
    const minDesktopWidth = 968; // Define the minimum width for desktops

    // Function to check if the view is desktop
    function isDesktopView() {
        return window.innerWidth >= minDesktopWidth;
    }

    // Remove any "show" and "active" classes on page load (only for desktop)
    if (isDesktopView()) {
        dropdowns.forEach(dropdown => {
            const dropdownMenu = dropdown.querySelector('.dropdown-menu');
            const dropdownItems = dropdownMenu.querySelectorAll('a');

            dropdownMenu.classList.remove('show'); // Hide all dropdown menus
            dropdownItems.forEach(item => item.classList.remove('active')); // Remove active class from all items
        });
    }

    // Handle hover event on sidebar to show the dropdown related to active route (only for desktop)
    sidebar.addEventListener('mouseenter', function () {
        if (isDesktopView()) {
            dropdowns.forEach(dropdown => {
                const dropdownToggle = dropdown.querySelector('.dropdown-toggle'); // Dropdown link
                const dropdownMenu = dropdown.querySelector('.dropdown-menu'); // Dropdown menu

                // Check if any of the dropdown items match the current route
                let isActive = false;
                const dropdownItems = dropdownMenu.querySelectorAll('a'); // Links inside the dropdown

                dropdownItems.forEach(item => {
                    if (item && window.location.pathname === item.getAttribute('href')) {
                        item.classList.add('active'); // Mark the link as active
                        isActive = true;
                    }
                });

                if (isActive) {
                    dropdownMenu.classList.add('show'); // Show the dropdown menu
                }
            });
        }
    });

    // Reset dropdown visibility and active classes when mouse leaves the sidebar (only for desktop)
    sidebar.addEventListener('mouseleave', function () {
        if (isDesktopView()) {
            dropdowns.forEach(dropdown => {
                const dropdownMenu = dropdown.querySelector('.dropdown-menu');
                const dropdownItems = dropdownMenu.querySelectorAll('a');

                dropdownMenu.classList.remove('show'); // Hide the dropdown menu
                dropdownItems.forEach(item => item.classList.remove('active')); // Remove active class
            });
        }
    });
});

</script>