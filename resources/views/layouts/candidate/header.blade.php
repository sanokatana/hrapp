<header class="navbar navbar-expand-md d-print-none">
@php
    $user = Auth::guard('candidate')->user();
    $userVerify = $user ? $user->verify_offer : null;

    // Get recruitment_type_id through proper table joins
    $candidateInfo = DB::table('candidates')
        ->join('job_openings', 'candidates.job_opening_id', '=', 'job_openings.id')
        ->where('candidates.id', $user->id)
        ->select('job_openings.recruitment_type_id')
        ->first();

    $recruitmentTypeId = $candidateInfo ? $candidateInfo->recruitment_type_id : null;
    @endphp
    <div class="container-xl">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu" aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
            <a href=".">
                <img src="{{ asset('assets/img/logo-pic.png')}}" width="70" height="70" alt="Tabler" class="">
            </a>
        </h1>
        <div class="navbar-nav flex-row order-md-last">
            <div class="d-none d-md-flex" style="margin-right: 10px">
                <a href="?theme=dark" class="nav-link px-0 hide-theme-dark" title="Enable dark mode" data-bs-toggle="tooltip"
                    data-bs-placement="bottom">
                    <!-- Download SVG icon from http://tabler-icons.io/i/moon -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M12 3c.132 0 .263 0 .393 0a7.5 7.5 0 0 0 7.92 12.446a9 9 0 1 1 -8.313 -12.454z" />
                    </svg>
                </a>
                <a href="?theme=light" class="nav-link px-0 hide-theme-light" title="Enable light mode" data-bs-toggle="tooltip"
                    data-bs-placement="bottom">
                    <!-- Download SVG icon from http://tabler-icons.io/i/sun -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M12 12m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                        <path d="M3 12h1m8 -9v1m8 8h1m-9 8v1m-6.4 -15.4l.7 .7m12.1 -.7l-.7 .7m0 11.4l.7 .7m-12.1 -.7l-.7 .7" />
                    </svg>
                </a>
            </div>
            <div class="nav-item dropdown">
                <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="Open user menu">
                    <img src="{{ asset('assets/img/pic.png')}}" class="avatar avatar-sm" alt="">
                    <div class="d-none d-xl-block ps-2">
                        <div>{{ Auth::guard('candidate')->user()->nama_candidate}}</div>
                        @php
                        $user = Auth::guard('candidate')->user();
                        $userLevel = $user ? $user->level : null;
                        @endphp
                        <div class="mt-1 small text-secondary">Candidate</div>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <a href="/candidate/proseslogout" class="dropdown-item">Logout</a>
                </div>
            </div>
        </div>
        <div class="collapse navbar-collapse" id="navbar-menu">
            <div class="d-flex flex-column flex-md-row flex-fill align-items-stretch align-items-md-center">
                <ul class="navbar-nav">
                    <li class="nav-item {{request()->is(['candidate/dashboard']) ? 'active' : ''}}">
                        <a class="nav-link" href="/">
                            <span class="nav-link-title">
                                Home
                            </span>
                        </a>
                    </li>
                    @if($recruitmentTypeId == 2)
                    {{-- Show intern menu --}}
                    <li class="nav-item {{request()->is(['candidate/data/intern']) ? 'active' : ''}}">
                        <a class="nav-link" href="/candidate/data/intern">
                            <span class="nav-link-title">
                                Candidate Data Intern
                            </span>
                        </a>
                    </li>
                    @else
                    {{-- Show regular candidate menu --}}
                    <li class="nav-item {{request()->is(['candidate/data']) ? 'active' : ''}}">
                        <a class="nav-link" href="/candidate/data">
                            <span class="nav-link-title">
                                Candidate Data
                            </span>
                        </a>
                    </li>
                    @endif
                    @if($userVerify == '1')
                    <li class="nav-item {{request()->is(['candidate/data/perlengkapan']) ? 'active' : ''}}">
                        <a class="nav-link" href="/candidate/data/perlengkapan">
                            <span class="nav-link-title">
                                Perlengkapan Data
                            </span>
                        </a>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</header>
