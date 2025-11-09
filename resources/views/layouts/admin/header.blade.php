<header class="navbar navbar-expand-md d-none d-lg-flex d-print-none">
    <div class="container-xl">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu" aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="navbar-nav flex-row order-md-last">
            <div class="d-none d-md-flex">
                <!-- Company Switcher -->
                @if(Auth::guard('user')->check())
                    @php
                        $user = Auth::guard('user')->user();
                        $userCompanies = $user->level === 'Superadmin' ? \App\Models\Company::all() : $user->companies;
                        $selectedCompanyId = session('selected_company_id');
                        $selectedCompany = \App\Models\Company::find($selectedCompanyId);
                        $selectedCabangId = session('selected_cabang_id');
                    @endphp
                    
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link px-0" data-bs-toggle="dropdown" title="Switch Company" data-bs-placement="bottom">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M3 21l18 0"/>
                                <path d="M9 8l1 0"/>
                                <path d="M9 12l1 0"/>
                                <path d="M9 16l1 0"/>
                                <path d="M14 8l1 0"/>
                                <path d="M14 12l1 0"/>
                                <path d="M14 16l1 0"/>
                                <path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16"/>
                            </svg>
                            @if($selectedCompany)
                                <span class="d-none d-xl-inline-block ms-1">{{ $selectedCompany->short_name }}</span>
                            @endif
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <div class="dropdown-header">Switch Company</div>
                            @foreach($userCompanies as $company)
                                <form action="/switch-company" method="POST" style="display:inline;">
                                    @csrf
                                    <input type="hidden" name="company_id" value="{{ $company->id }}">
                                    <button type="submit" class="dropdown-item {{ $selectedCompanyId == $company->id ? 'active' : '' }}">
                                        {{ $company->short_name }}
                                    </button>
                                </form>
                            @endforeach
                        </div>
                    </div>

                    <!-- Cabang Switcher -->
                    @if($selectedCompany)
                        @php
                            $cabangs = $user->level === 'Superadmin'
                            ? \App\Models\Cabang::where('company_id', $selectedCompanyId)->get()
                            : $user->cabang()->where('cabang.company_id', $selectedCompanyId)->get(); // <-- qualify with cabang.
                            $selectedCabang = \App\Models\Cabang::find($selectedCabangId);
                        @endphp
                        
                        <div class="nav-item dropdown" style="margin-left: 10px">
                            <a href="#" class="nav-link px-0" data-bs-toggle="dropdown" title="Switch Cabang" data-bs-placement="bottom">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M3 21v-13l9 -4l9 4v13"/>
                                    <path d="M13 13h4v8h-10v-8h4"/>
                                    <path d="M9 21v-4h6v4"/>
                                </svg>
                                @if($selectedCabang)
                                    <span class="d-none d-xl-inline-block ms-1">{{ $selectedCabang->nama }}</span>
                                @else
                                    <span class="d-none d-xl-inline-block ms-1">All Cabang</span>
                                @endif
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <div class="dropdown-header">Switch Cabang</div>
                                <form action="/switch-cabang" method="POST" style="display:inline;">
                                    @csrf
                                    <input type="hidden" name="cabang_id" value="all">
                                    <button type="submit" class="dropdown-item {{ !$selectedCabangId ? 'active' : '' }}">
                                        All Cabang
                                    </button>
                                </form>
                                @foreach($cabangs as $cabang)
                                    <form action="/switch-cabang" method="POST" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="cabang_id" value="{{ $cabang->id }}">
                                        <button type="submit" class="dropdown-item {{ $selectedCabangId == $cabang->id ? 'active' : '' }}">
                                            {{ $cabang->nama }}
                                        </button>
                                    </form>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endif

                <a href="?theme=dark" class="nav-link px-0 hide-theme-dark" title="Enable dark mode" data-bs-toggle="tooltip" data-bs-placement="bottom">
                    <!-- Download SVG icon from http://tabler-icons.io/i/moon -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 18 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M12 3c.132 0 .263 0 .393 0a7.5 7.5 0 0 0 7.92 12.446a9 9 0 1 1 -8.313 -12.454z" />
                    </svg>
                </a>
                <a href="?theme=light" class="nav-link px-0 hide-theme-light" title="Enable light mode" data-bs-toggle="tooltip" data-bs-placement="bottom">
                    <!-- Download SVG icon from http://tabler-icons.io/i/sun -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 18 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M12 12m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                        <path d="M3 12h1m8 -9v1m8 8h1m-9 8v1m-6.4 -15.4l.7 .7m12.1 -.7l-.7 .7m0 11.4l.7 .7m-12.1 -.7l-.7 .7" />
                    </svg>
                </a>
                <div class="nav-item dropdown d-none d-md-flex me-3">
                    <a href="#" class="nav-link px-0" data-bs-toggle="dropdown" tabindex="-1" aria-label="Show notifications">
                        <!-- Download SVG icon from http://tabler-icons.io/i/bell -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 18 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M10 5a2 2 0 1 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6" />
                            <path d="M9 17v1a3 3 0 0 0 6 0v-1" />
                        </svg>
                        <span class="badge bg-red"></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-end dropdown-menu-card">
                        <!-- In your Blade view, for example, header.blade.php -->

                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Notification</h3>
                            </div>
                            <div class="list-group list-group-flush list-group-hoverable" style="width: max-content; max-height: 400px; overflow-y: auto;">
                                <!-- Birthdays -->
                                <div class="list-group-item">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 18 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M12 12m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                                                <path d="M3 12h1m8 -9v1m8 8h1m-9 8v1m-6.4 -15.4l.7 .7m12.1 -.7l-.7 .7m0 11.4l.7 .7m-12.1 -.7l-.7 .7" />
                                            </svg>
                                        </div>
                                        <div class="flex-grow-1 ms-2">
                                            <div class="fw-bold">John Doe</div>
                                            <div class="text-muted">Birthday on March 15</div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>


                    </div>
                </div>
            </div>
            <div class="nav-item dropdown">
                <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="Open user menu">
                    <img src="{{ asset('assets/img/pic.png')}}" class="avatar avatar-sm" alt="">
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
        <div class="collapse navbar-collapse" id="navbar-menu">
            <div>
                <form action="./" method="get" autocomplete="off" novalidate>
                    <div class="input-icon">
                        <span class="input-icon-addon">
                            <!-- Download SVG icon from http://tabler-icons.io/i/search -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 18 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" />
                                <path d="M21 21l-6 -6" />
                            </svg>
                        </span>
                        <input type="text" value="" class="form-control" placeholder="Search…" aria-label="Search in website">
                    </div>
                </form>
            </div>
        </div>
    </div>
</header>
