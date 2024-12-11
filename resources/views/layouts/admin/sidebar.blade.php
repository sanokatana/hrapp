<aside class="navbar navbar-vertical navbar-expand-lg" data-bs-theme="dark" id="sidebar">
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
            <a href="/">
                <img src="{{ asset('assets/img/login/logo-pic.png')}}" width="70" height="60" alt="Tabler" class="">
            </a>
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
                        <div>Paweł Kuna</div>
                        <div class="mt-1 small text-secondary">UI Designer</div>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <a href="#" class="dropdown-item">Status</a>
                    <a href="#" class="dropdown-item">Profile</a>
                    <a href="#" class="dropdown-item">Feedback</a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">Settings</a>
                    <a href="/panel/proseslogoutadmin" class="dropdown-item">Logout</a>
                </div>
            </div>
        </div>
        <div class="collapse navbar-collapse" id="sidebar-menu">

            <button class="btn btn-primary" id="sidebar-toggle" type="button">
                <span class="navbar-toggler-icon"></span>
            </button>
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
                @if($userLevel !== 'Management' && $userLevel !== 'Admin')
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{request()->is(['karyawan', 'data/user', 'shift', 'shiftpatt','kontrak','sk']) ? 'show' : ''}}"
                        href="#navbar-base" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button"
                        aria-expanded="{{request()->is(['karyawan', 'data/user']) ? 'true' : 'false'}}">
                        <span
                            class="nav-link-icon d-md-none d-lg-inline-block"><!-- Download SVG icon from http://tabler-icons.io/i/package -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                            </svg>
                        </span>
                        <span class="nav-link-title">
                            Employees
                        </span>
                    </a>
                    <div
                        class="dropdown-menu {{request()->is(['karyawan', 'data/user', 'shift', 'shiftpatt','kontrak','sk']) ? 'show' : ''}}">
                        <div class="dropdown-menu-columns">
                            <div class="dropdown-menu-column">
                                <a class="dropdown-item {{request()->is(['karyawan']) ? 'active' : ''}}"
                                    href="/karyawan">
                                    Karyawan
                                </a>
                                <a class="dropdown-item dropdown-toggle" href="#" data-bs-toggle="dropdown"
                                    data-bs-auto-close="false" role="button" aria-expanded="">
                                    Employee Shift
                                </a>
                                <div class="dropdown-menu {{request()->is(['shift', 'shiftpatt']) ? 'show' : ''}}">
                                    <div class="dropdown-menu-columns">
                                        <div class="dropdown-menu-column">
                                            <a class="dropdown-item {{request()->is(['shift']) ? 'active' : ''}}"
                                                href="/shift">
                                                Shifts
                                            </a>
                                            <a class="dropdown-item {{request()->is(['shiftpatt']) ? 'active' : ''}}"
                                                href="/shiftpatt">
                                                Shift Pattern
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <a class="dropdown-item {{request()->is(['kontrak']) ? 'active' : ''}}" href="/kontrak">
                                    Kontrak
                                </a>

                                <a class="dropdown-item {{request()->is(['sk']) ? 'active' : ''}}" href="/sk">
                                    SK Tetap
                                </a>
                                <a class="dropdown-item {{request()->is(['data/user']) ? 'active' : ''}}"
                                    href="/data/user">
                                    Users
                                </a>
                            </div>
                        </div>
                    </div>
                </li>
                @endif
                @if($userLevel !== 'Management' && $userLevel !== 'Admin')
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{request()->is(['konfigurasi/jabatan', 'department']) ? 'show' : ''}}"
                        href="#navbar-base" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button"
                        aria-expanded="{{request()->is(['konfigurasi/jabatan', 'department']) ? 'true' : 'false'}}">
                        <span
                            class="nav-link-icon d-md-none d-lg-inline-block"><!-- Download SVG icon from http://tabler-icons.io/i/package -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-clipboard-list">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path
                                    d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" />
                                <path
                                    d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" />
                                <path d="M9 12l.01 0" />
                                <path d="M13 12l2 0" />
                                <path d="M9 16l.01 0" />
                                <path d="M13 16l2 0" />
                            </svg>
                        </span>
                        <span class="nav-link-title">
                            Data Department
                        </span>
                    </a>
                    <div class="dropdown-menu {{request()->is(['department', 'konfigurasi/jabatan']) ? 'show' : ''}}">
                        <div class="dropdown-menu-columns">
                            <div class="dropdown-menu-column">
                                <a class="dropdown-item {{request()->is(['department']) ? 'active' : ''}}"
                                    href="/department">
                                    Department
                                </a>
                                <a class="dropdown-item {{request()->is(['konfigurasi/jabatan']) ? 'active' : ''}}"
                                    href="/konfigurasi/jabatan">
                                    Jabatan
                                </a>
                            </div>
                        </div>
                    </div>
                </li>
                @endif
                @if($userLevel !== 'Management' && $userLevel !== 'Admin')
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{request()->is(['konfigurasi/tipecuti', 'cuti']) ? 'show' : ''}}"
                        href="#navbar-base" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button"
                        aria-expanded="{{request()->is(['cuti', 'konfigurasi/tipecuti']) ? 'true' : 'false'}}">
                        <span
                            class="nav-link-icon d-md-none d-lg-inline-block"><!-- Download SVG icon from http://tabler-icons.io/i/package -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-file-database">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 12.75m-4 0a4 1.75 0 1 0 8 0a4 1.75 0 1 0 -8 0" />
                                <path d="M8 12.5v3.75c0 .966 1.79 1.75 4 1.75s4 -.784 4 -1.75v-3.75" />
                                <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                            </svg>
                        </span>
                        <span class="nav-link-title">
                            Data Cuti
                        </span>
                    </a>
                    <div class="dropdown-menu {{request()->is(['konfigurasi/tipecuti', 'cuti']) ? 'show' : ''}}">
                        <div class="dropdown-menu-columns">
                            <div class="dropdown-menu-column">
                                <a class="dropdown-item {{request()->is(['cuti']) ? 'active' : ''}}" href="/cuti">
                                    Cuti
                                </a>
                                <a class="dropdown-item {{request()->is(['konfigurasi/tipecuti']) ? 'active' : ''}}"
                                    href="/konfigurasi/tipecuti">
                                    Tipe Cuti
                                </a>
                            </div>
                        </div>
                    </div>
                </li>
                @endif
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{request()->is(['approval/izinapproval', 'approval/cutiapproval', 'approval/izinapprovalhrd', 'approval/cutiapprovalhrd']) ? 'show' : ''}}"
                        href="#navbar-base" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button"
                        aria-expanded="{{request()->is(['approval/izinapproval', 'approval/cutiapproval', 'approval/izinapprovalhrd', 'approval/cutiapprovalhrd']) ? 'true' : 'false'}}">
                        <span
                            class="nav-link-icon d-md-none d-lg-inline-block"><!-- Download SVG icon from http://tabler-icons.io/i/package -->
                            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 18 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-checkbox"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 11l3 3l8 -8" /><path d="M20 12v6a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h9" /></svg>
                        </span>
                        <span class="nav-link-title">
                            Approval
                        </span>
                    </a>
                    <div
                        class="dropdown-menu {{request()->is(['approval/izinapproval', 'approval/cutiapproval', 'approval/izinapprovalhrd', 'approval/cutiapprovalhrd']) ? 'show' : ''}}">
                        <div class="dropdown-menu-columns">
                            <div class="dropdown-menu-column">
                                <a class="dropdown-item {{request()->is(['approval/izinapproval']) ? 'active' : ''}}"
                                    href="/approval/izinapproval">
                                    Approval Izin
                                </a>
                                <a class="dropdown-item {{request()->is(['approval/cutiapproval']) ? 'active' : ''}}"
                                    href="/approval/cutiapproval">
                                    Approval Cuti
                                </a>
                                @if($userLevel !== 'Management' && $userLevel !== 'Admin')
                                <a class="dropdown-item {{request()->is(['approval/izinapprovalhrd']) ? 'active' : ''}}"
                                    href="/approval/izinapprovalhrd">
                                    Approval Izin HR
                                </a>
                                <a class="dropdown-item {{request()->is(['approval/cutiapprovalhrd']) ? 'active' : ''}}"
                                    href="/approval/cutiapprovalhrd">
                                    Approval Cuti HR
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </li>
                @if($userLevel !== 'Admin')
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{request()->is(['attendance/daymonitor', 'attendance/table', 'attendance/att_monitoring', 'timeatt/daymonitor', 'timeatt/att_monitoring', 'timeatt/table']) ? 'show' : ''}}"
                        href="#navbar-base" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button"
                        aria-expanded="{{request()->is(['attendance/daymonitor', 'attendance/table', 'attendance/att_monitoring', 'timeatt/daymonitor', 'timeatt/att_monitoring', 'timeatt/table']) ? 'true' : 'false'}}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-screen-share">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M21 12v3a1 1 0 0 1 -1 1h-16a1 1 0 0 1 -1 -1v-10a1 1 0 0 1 1 -1h9" />
                                <path d="M7 20l10 0" />
                                <path d="M9 16l0 4" />
                                <path d="M15 16l0 4" />
                                <path d="M17 4h4v4" />
                                <path d="M16 9l5 -5" />
                            </svg>
                        </span>
                        <span class="nav-link-title">
                            Attendance
                        </span>
                    </a>
                    <div
                        class="dropdown-menu {{request()->is(['attendance/daymonitor', 'attendance/table', 'attendance/att_monitoring', 'timeatt/daymonitor', 'timeatt/att_monitoring', 'timeatt/table', 'attendance/database']) ? 'show' : ''}}">
                        <div class="dropdown-menu-columns">
                            <div class="dropdown-menu-column">
                                <a class="dropdown-item dropdown-toggle" href="#" data-bs-toggle="dropdown"
                                    data-bs-auto-close="false" role="button" aria-expanded="">
                                    Shift
                                </a>
                                <div
                                    class="dropdown-menu {{request()->is(['attendance/daymonitor', 'attendance/table', 'attendance/att_monitoring']) ? 'show' : ''}}">
                                    <a class="dropdown-item {{request()->is(['attendance/daymonitor']) ? 'active' : ''}}"
                                        href="/attendance/daymonitor">
                                        Day Monitor
                                    </a>
                                    <a class="dropdown-item {{request()->is(['attendance/att_monitoring']) ? 'active' : ''}}"
                                        href="/attendance/att_monitoring">
                                        Attendance Monitor
                                    </a>
                                    <a class="dropdown-item {{request()->is(['attendance/table']) ? 'active' : ''}}"
                                        href="/attendance/table">
                                        Attendance Table
                                    </a>
                                </div>
                                <a class="dropdown-item dropdown-toggle" href="#" data-bs-toggle="dropdown"
                                    data-bs-auto-close="false" role="button"
                                    aria-expanded="{{request()->is(['timeatt/daymonitor', 'timeatt/att_monitoring', 'timeatt/table']) ? 'true' : 'false'}}">
                                    Time
                                </a>
                                <div
                                    class="dropdown-menu {{request()->is(['timeatt/daymonitor', 'timeatt/att_monitoring', 'timeatt/table']) ? 'show' : ''}}">
                                    <a class="dropdown-item {{request()->is(['timeatt/daymonitor']) ? 'active' : ''}}"
                                        href="/timeatt/daymonitor">
                                        Time Day
                                    </a>
                                    <a class="dropdown-item {{request()->is(['timeatt/att_monitoring']) ? 'active' : ''}}"
                                        href="/timeatt/att_monitoring">
                                        Time Monitor
                                    </a>
                                    <a class="dropdown-item {{request()->is(['timeatt/table']) ? 'active' : ''}}"
                                        href="/timeatt/table">
                                        Time Table
                                    </a>
                                </div>
                                <a class="dropdown-item {{request()->is(['attendance/database']) ? 'active' : ''}}"
                                    href="/attendance/database">
                                    Update Database
                                </a>
                            </div>
                        </div>
                    </div>
                </li>
                @endif
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{request()->is(['laporan/attendance', 'laporan/time', 'laporan/exportAttendanceView', 'laporan/attendanceViewAtasan','laporan/exportIzin', 'laporan/exportCuti']) ? 'show' : ''}}"
                        href="#navbar-base" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button"
                        aria-expanded="false">
                        <span
                            class="nav-link-icon d-md-none d-lg-inline-block"><!-- Download SVG icon from http://tabler-icons.io/i/package -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-report">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M8 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h5.697" />
                                <path d="M18 14v4h4" />
                                <path d="M18 11v-4a2 2 0 0 0 -2 -2h-2" />
                                <path
                                    d="M8 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" />
                                <path d="M18 18m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                                <path d="M8 11h4" />
                                <path d="M8 15h3" />
                            </svg>
                        </span>
                        <span class="nav-link-title">
                            Laporan
                        </span>
                    </a>
                    <div class="dropdown-menu {{request()->is(['laporan/viewCuti','laporan/viewIzin','laporan/viewCutiSisa', 'laporan/viewCutiManagement', 'laporan/viewIzinManagement', 'laporan/attendance', 'laporan/time' , 'laporan/exportAttendanceView', 'laporan/attendanceViewAtasan', 'laporan/exportIzin', 'laporan/exportCuti']) ? 'show' : ''}}">
                        <div class="dropdown-menu-columns">
                            <div class="dropdown-menu-column">
                                @if($userLevel !== 'Admin')
                                <a class="dropdown-item {{request()->is(['laporan/attendance']) ? 'active' : ''}}"
                                    href="/laporan/attendance">
                                    Attendance Table
                                </a>
                                <a class="dropdown-item {{request()->is(['laporan/time']) ? 'active' : ''}}"
                                    href="/laporan/time">
                                    Time Table
                                </a>
                                <a class="dropdown-item {{request()->is(['laporan/exportAttendanceView']) ? 'active' : ''}}" href="/laporan/exportAttendanceView">
                                    Export Waktu Absensi
                                </a>
                                <a class="dropdown-item dropdown-toggle" href="#" data-bs-toggle="dropdown"
                                    data-bs-auto-close="false" role="button"
                                    aria-expanded="{{request()->is(['laporan/viewIzin', 'laporan/exportIzin', 'laporan/viewIzinManagement']) ? 'true' : 'false'}}">
                                    Pengajuan Izin
                                </a>
                                <div class="dropdown-menu {{request()->is(['laporan/viewIzin', 'laporan/exportIzin', 'laporan/viewIzinManagement']) ? 'show' : ''}}">
                                    <a class="dropdown-item {{request()->is(['laporan/viewIzin']) ? 'active' : ''}}"
                                        href="/laporan/viewIzin">
                                        View
                                    </a>
                                    <a class="dropdown-item {{request()->is(['laporan/exportIzin']) ? 'active' : ''}}"
                                        href="/laporan/exportIzin">
                                        Export
                                    </a>
                                </div>
                                <a class="dropdown-item dropdown-toggle" href="#" data-bs-toggle="dropdown"
                                    data-bs-auto-close="false" role="button"
                                    aria-expanded="{{request()->is(['laporan/viewCuti', 'laporan/exportCuti', 'laporan/viewCutiManagement', 'laporan/viewCutiSisa']) ? 'true' : 'false'}}">
                                    Pengajuan Cuti
                                </a>
                                <div class="dropdown-menu {{request()->is(['laporan/viewCuti', 'laporan/exportCuti', 'laporan/viewCutiSisa']) ? 'show' : ''}}">
                                    <a class="dropdown-item {{request()->is(['laporan/viewCuti']) ? 'active' : ''}}"
                                        href="/laporan/viewCuti">
                                        View Karyawan Cuti
                                    </a>
                                    @if($userLevel === 'Management')
                                    </a><a class="dropdown-item {{request()->is(['laporan/viewCutiSisa']) ? 'active' : ''}}"
                                        href="/laporan/viewCutiSisa">
                                        Cuti Karyawan
                                    </a>
                                    @endif
                                    <a class="dropdown-item {{request()->is(['laporan/exportCuti']) ? 'active' : ''}}"
                                        href="/laporan/exportCuti">
                                        Export
                                    </a>
                                </div>
                                @endif
                                @if($userLevel == 'Admin')
                                <a class="dropdown-item {{request()->is(['laporan/attendanceViewAtasan']) ? 'active' : ''}}" href="/laporan/attendanceViewAtasan" href="#">
                                    Waktu Absensi
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </li>
                @if($userLevel !== 'Management' && $userLevel !== 'Admin')
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{request()->is(['recruitment/dashboard','recruitment/pipeline', 'recruitment/candidate', 'recruitment', 'recruitment/jobs', 'recruitment/stages']) ? 'show' : ''}}" href="#navbar-base"
                        data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="false">
                        <span
                            class="nav-link-icon d-md-none d-lg-inline-block"><!-- Download SVG icon from http://tabler-icons.io/i/package -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-briefcase">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path
                                    d="M3 7m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v9a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z" />
                                <path d="M8 7v-2a2 2 0 0 1 2 -2h4a2 2 0 0 1 2 2v2" />
                                <path d="M12 12l0 .01" />
                                <path d="M3 13a20 20 0 0 0 18 0" />
                            </svg>
                        </span>
                        <span class="nav-link-title">
                            Recruitements
                        </span>
                    </a>
                    <div class="dropdown-menu {{request()->is(['recruitment/dashboard','recruitment/pipeline', 'recruitment/candidate/data', 'recruitment/candidate/data/interview', 'recruitment/candidate', 'recruitment', 'recruitment/jobs', 'recruitment/stages']) ? 'show' : ''}}">
                        <div class="dropdown-menu-columns">
                            <div class="dropdown-menu-column">
                                <a class="dropdown-item {{request()->is(['recruitment/dashboard']) ? 'active' : ''}}" href="/recruitment/dashboard">
                                    Dashboard
                                </a>
                                <a class="dropdown-item {{request()->is(['recruitment/pipeline']) ? 'active' : ''}}" href="/recruitment/pipeline">
                                    Recruitement Pipeline
                                </a>
                                <a class="dropdown-item dropdown-toggle" href="#" data-bs-toggle="dropdown"
                                    data-bs-auto-close="false" role="button" aria-expanded="">
                                    Candidate
                                </a>
                                <div
                                    class="dropdown-menu {{request()->is(['recruitment/candidate/data', 'recruitment/candidate', 'recruitment/candidate/data/interview']) ? 'show' : ''}}">
                                    <a class="dropdown-item {{request()->is(['recruitment/candidate']) ? 'active' : ''}}" href="/recruitment/candidate">
                                        Candidate
                                    </a>
                                    <a class="dropdown-item {{request()->is(['recruitment/candidate/data']) ? 'active' : ''}}" href="/recruitment/candidate/data">
                                        Candidate Data
                                    </a>
                                    <a class="dropdown-item {{request()->is(['recruitment/candidate/data/interview']) ? 'active' : ''}}" href="/recruitment/candidate/data/interview">
                                        Candidate Interview
                                    </a>
                                </div>
                                <a class="dropdown-item {{request()->is(['recruitment']) ? 'active' : ''}}" href="/recruitment">
                                    Recruitement
                                </a>
                                <a class="dropdown-item {{request()->is(['recruitment/jobs']) ? 'active' : ''}} " href="/recruitment/jobs">
                                    Jobs
                                </a>
                                <a class="dropdown-item {{request()->is(['recruitment/stages']) ? 'active' : ''}}" href="/recruitment/stages">
                                    Stages
                                </a>
                            </div>
                        </div>
                    </div>
                </li>
                @endif
                @if($userLevel !== 'Admin')
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{request()->is(['performance/notification','performance/notificationEmail']) ? 'show' : ''}}" href="#navbar-base"
                        data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="false">
                        <span
                            class="nav-link-icon d-md-none d-lg-inline-block"><!-- Download SVG icon from http://tabler-icons.io/i/package -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-graph">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M4 18v-12a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" />
                                <path d="M7 14l3 -3l2 2l3 -3l2 2" />
                            </svg>
                        </span>
                        <span class="nav-link-title">
                            Performance
                        </span>
                    </a>
                    <div class="dropdown-menu {{request()->is(['performance/notification','performance/notificationEmail']) ? 'show' : ''}}">
                        <div class="dropdown-menu-columns">
                            <div class="dropdown-menu-column">
                                <a class="dropdown-item" href="#">
                                    Dashboard
                                </a>
                                <a class="dropdown-item {{request()->is(['performance/notification']) ? 'active' : ''}}" href="/performance/notification">
                                    Check Contract
                                </a>
                                <a class="dropdown-item {{request()->is(['performance/notificationEmail']) ? 'active' : ''}}" href="/performance/notificationEmail" href="#">
                                    Schedule Notif Email
                                </a>
                            </div>
                        </div>
                    </div>
                </li>
                @endif
                @if($userLevel !== 'Admin')
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{request()->is([]) ? 'show' : ''}}" href="#navbar-base"
                        data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="false">
                        <span
                            class="nav-link-icon d-md-none d-lg-inline-block"><!-- Download SVG icon from http://tabler-icons.io/i/package -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-receipt-2">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path
                                    d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16l-3 -2l-2 2l-2 -2l-2 2l-2 -2l-3 2" />
                                <path d="M14 8h-2.5a1.5 1.5 0 0 0 0 3h1a1.5 1.5 0 0 1 0 3h-2.5m2 0v1.5m0 -9v1.5" />
                            </svg>
                        </span>
                        <span class="nav-link-title">
                            Payroll
                        </span>
                    </a>
                    <div class="dropdown-menu {{request()->is([]) ? 'show' : ''}}">
                        <div class="dropdown-menu-columns">
                            <div class="dropdown-menu-column">
                                <a class="dropdown-item" href="#">
                                    Dashboard
                                </a>
                                <a class="dropdown-item" href="#">
                                    Allowance
                                </a>
                                <a class="dropdown-item" href="#">
                                    Deducation
                                </a>
                                <a class="dropdown-item" href="#">
                                    Payslip
                                </a>
                                <a class="dropdown-item" href="#">
                                    Federal Tax
                                </a>
                            </div>
                        </div>
                    </div>
                </li>
                @endif
                @if($userLevel !== 'Management' && $userLevel !== 'Admin')
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{request()->is(['email_export/absensi','email_export/export_absensi']) ? 'show' : ''}}" href="#navbar-base"
                        data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="false">
                        <span
                            class="nav-link-icon d-md-none d-lg-inline-block"><!-- Download SVG icon from http://tabler-icons.io/i/package -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-info-square">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 9h.01" />
                                <path d="M3 5a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v14a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-14z" />
                                <path d="M11 12h1v4h1" />
                            </svg>
                        </span>
                        <span class="nav-link-title">
                            Email & Export
                        </span>
                    </a>
                    <div class="dropdown-menu {{request()->is(['email_export/absensi','email_export/export_absensi']) ? 'show' : ''}}">
                        <div class="dropdown-menu-columns">
                            <div class="dropdown-menu-column">
                                <a class="dropdown-item {{request()->is(['email_export/absensi']) ? 'active' : ''}}" href="/email_export/absensi">
                                    Email Absensi
                                </a>
                            </div>
                        </div>
                    </div>
                </li>
                @endif
                @if($userLevel !== 'Management' && $userLevel !== 'Admin')
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{request()->is(['konfigurasi/libur-nasional', 'konfigurasi/liburkar', 'konfigurasi/lokasikantor']) ? 'show' : ''}}"
                        href="#navbar-base" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button"
                        aria-expanded="false">
                        <span
                            class="nav-link-icon d-md-none d-lg-inline-block"><!-- Download SVG icon from http://tabler-icons.io/i/package -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-settings">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path
                                    d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z" />
                                <path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" />
                            </svg>
                        </span>
                        <span class="nav-link-title">
                            Konfigurasi
                        </span>
                    </a>
                    <div
                        class="dropdown-menu {{request()->is(['konfigurasi/libur-nasional', 'konfigurasi/liburkar', 'konfigurasi/lokasikantor']) ? 'show' : ''}}">
                        <div class="dropdown-menu-columns">
                            <div class="dropdown-menu-column">
                                <a class="dropdown-item {{request()->is(['konfigurasi/libur-nasional']) ? 'active' : ''}}"
                                    href="/konfigurasi/libur-nasional">
                                    Libur Nasional
                                </a>
                                <a class="dropdown-item {{request()->is(['konfigurasi/liburkar']) ? 'active' : ''}}"
                                    href="/konfigurasi/liburkar">
                                    Libur Karyawan
                                </a>
                                <a class="dropdown-item {{request()->is(['konfigurasi/lokasikantor']) ? 'active' : ''}}"
                                    href="/konfigurasi/lokasikantor">
                                    Lokasi Kantor
                                </a>
                            </div>
                        </div>
                    </div>
                </li>
                @endif
            </ul>
        </div>
    </div>
</aside>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const sidebar = document.getElementById('sidebar');
        const logoImage = document.querySelector('.navbar-brand img');
        const dropdownMenus = document.querySelectorAll('.dropdown-menu');

        // Function to update styles based on sidebar state
        function updateStyles(isCollapsed) {
            if (isCollapsed) {
                dropdownMenus.forEach(menu => menu.classList.remove('show')); // Hide all dropdown menus
                logoImage.style.width = '40px'; // Adjust logo width when collapsed
                logoImage.style.height = '40px'; // Adjust logo height when collapsed
            } else {
                logoImage.style.width = '70px'; // Default logo width
                logoImage.style.height = '60px'; // Default logo height
            }
        }

        // Initialize sidebar state
        const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
        if (isCollapsed || isCollapsed === null) { // Default to collapsed if no state in localStorage
            sidebar.classList.add('collapsed');
        }
        updateStyles(isCollapsed);

        // Toggle sidebar collapse
        sidebarToggle.addEventListener('click', function(event) {
            event.stopPropagation(); // Prevent triggering click outside
            const isCollapsed = sidebar.classList.toggle('collapsed');
            localStorage.setItem('sidebarCollapsed', isCollapsed);
            updateStyles(isCollapsed);
        });

        // Collapse sidebar when clicking outside
        document.addEventListener('click', function(event) {
            if (!sidebar.contains(event.target) && !sidebarToggle.contains(event.target)) {
                if (!sidebar.classList.contains('collapsed')) {
                    sidebar.classList.add('collapsed');
                    localStorage.setItem('sidebarCollapsed', true);
                    updateStyles(true);
                }
            }
        });

        // Remove 'collapsed' class from sidebar when any dropdown item is clicked
        document.querySelectorAll('.nav-item .dropdown-toggle').forEach(item => {
            item.addEventListener('click', function() {
                sidebar.classList.remove('collapsed');
                localStorage.setItem('sidebarCollapsed', false);
                updateStyles(false);
            });
        });
    });
</script>
