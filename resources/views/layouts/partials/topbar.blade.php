<!-- Topbar Start -->
<header class="app-topbar" id="header">
    <div class="page-container topbar-menu">
        <div class="d-flex align-items-center gap-2">

            <!-- Brand Logo -->
            <a href="{{ route('home') }}" class="logo">
                <span class="logo-light">
                    <span class="logo-lg"><img src="/images/logo.png" alt="ICSSF"></span>
                    <span class="logo-sm"><img src="/images/logo-sm.png" alt="ICSSF"></span>
                </span>

                <span class="logo-dark">
                    <span class="logo-lg"><img src="/images/logo-dark.png" alt="ICSSF"></span>
                    <span class="logo-sm"><img src="/images/logo-sm.png" alt="ICSSF"></span>
                </span>
            </a>

            <!-- Sidebar Menu Toggle Button -->
            <button class="sidenav-toggle-button px-2">
                <i class="ri-menu-5-line fs-24"></i>
            </button>

            <!-- Horizontal Menu Toggle Button -->
            <button class="topnav-toggle-button px-2" data-bs-toggle="collapse" data-bs-target="#topnav-menu-content">
                <i class="ri-menu-5-line fs-24"></i>
            </button>

            <!-- Topbar Page Title -->
            <div class="topbar-item d-none d-md-flex px-2">
                @if(isset($topbarTitle))
                <div>
                    <h4 class="page-title fs-20 fw-semibold mb-0">{{ $topbarTitle }}</h4>
                </div>
                @else
                <div>
                    <h4 class="page-title fs-20 fw-semibold mb-0">Welcome!</h4>
                </div>
                @endif
            </div>

        </div>

        <div class="d-flex align-items-center gap-2">

            <!-- Button Trigger Customizer Offcanvas -->
            <div class="topbar-item d-none d-sm-flex">
                <button class="topbar-link" data-bs-toggle="offcanvas" data-bs-target="#theme-settings-offcanvas"
                    type="button">
                    <i class="ri-settings-4-line fs-22"></i>
                </button>
            </div>

            <!-- Light/Dark Mode Button -->
            <div class="topbar-item d-none d-sm-flex">
                <button class="topbar-link" id="light-dark-mode" type="button">
                    <i class="ri-moon-line light-mode-icon fs-22"></i>
                    <i class="ri-sun-line dark-mode-icon fs-22"></i>
                </button>
            </div>

            <!-- Fullscreen Button -->
            <div class="topbar-item d-none d-md-flex">
                <button class="topbar-link" data-toggle="fullscreen" type="button">
                    <i class="ri-fullscreen-line fs-22"></i>
                </button>
            </div>

            <!-- User Dropdown -->
            <div class="topbar-item nav-user">
                <div class="dropdown">
                    <a class="topbar-link dropdown-toggle drop-arrow-none px-2" data-bs-toggle="dropdown"
                        data-bs-offset="0,25" type="button" aria-haspopup="false" aria-expanded="false">
                        @php $topbarUser = auth()->user(); @endphp
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center bg-primary bg-gradient text-white me-lg-2" style="width:32px;height:32px;">
                            <span class="fw-bold" style="font-size:13px;">{{ strtoupper(substr($topbarUser->name ?? 'U', 0, 2)) }}</span>
                        </div>
                        <span class="d-lg-flex flex-column gap-1 d-none">
                            <h5 class="my-0">{{ $topbarUser->name ?? 'User' }}</h5>
                            <span class="fs-12 text-muted">
                                @if($topbarUser?->hasRole('admin'))
                                    Admin
                                @elseif($topbarUser?->hasRole('reviewer'))
                                    Reviewer
                                @else
                                    Participant
                                @endif
                            </span>
                        </span>
                        <i class="ri-arrow-down-s-line d-none d-lg-block align-middle ms-1"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end">
                        <!-- item-->
                        <div class="dropdown-header noti-title">
                            <h6 class="text-overflow m-0">Welcome, {{ Str::words($topbarUser->name ?? 'User', 1, '') }}!</h6>
                        </div>

                        <!-- Profile -->
                        <a href="{{ route('profile.show') }}" class="dropdown-item">
                            <i class="ri-account-circle-line me-1 fs-16 align-middle"></i>
                            <span class="align-middle">My Profile</span>
                        </a>

                        <!-- Dashboard -->
                        <a href="{{ route('dashboard') }}" class="dropdown-item">
                            <i class="ri-dashboard-line me-1 fs-16 align-middle"></i>
                            <span class="align-middle">Dashboard</span>
                        </a>

                        <!-- Visit Website -->
                        <a href="{{ route('home') }}" class="dropdown-item">
                            <i class="ri-global-line me-1 fs-16 align-middle"></i>
                            <span class="align-middle">Visit Website</span>
                        </a>

                        <div class="dropdown-divider"></div>

                        <!-- Sign Out -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="ri-logout-box-line me-1 fs-16 align-middle"></i>
                                <span class="align-middle">Sign Out</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- Topbar End -->