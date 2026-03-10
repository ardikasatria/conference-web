<!-- Sidenav Menu Start -->
<div class="sidenav-menu">

    <!-- Brand Logo -->
    <a href="{{ route('home') }}" class="logo">
        <span class="logo-light">
            <span class="logo-lg"><img src="/images/logo.png" alt="logo"></span>
            <span class="logo-sm"><img src="/images/logo-sm.png" alt="small logo"></span>
        </span>

        <span class="logo-dark">
            <span class="logo-lg"><img src="/images/logo-dark.png" alt="dark logo"></span>
            <span class="logo-sm"><img src="/images/logo-sm.png" alt="small logo"></span>
        </span>
    </a>

    <!-- Sidebar Hover Menu Toggle Button -->
    <button class="button-sm-hover">
        <i class="ri-circle-line align-middle"></i>
    </button>

    <!-- Sidebar Menu Toggle Button -->
    <button class="sidenav-toggle-button">
        <i class="ri-menu-5-line fs-20"></i>
    </button>

    <!-- Full Sidebar Menu Close Button -->
    <button class="button-close-fullsidebar">
        <i class="ti ti-x align-middle"></i>
    </button>

    <div data-simplebar>

        <!-- User -->
        <div class="sidenav-user">
            <div class="dropdown-center text-center">
                <a class="topbar-link dropdown-toggle text-reset drop-arrow-none px-2" data-bs-toggle="dropdown"
                    type="button" aria-haspopup="false" aria-expanded="false">
                    @php $sidenavUser = auth()->user(); @endphp
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center bg-primary text-white" style="width:46px;height:46px;">
                        <span class="fw-bold fs-18">{{ $sidenavUser ? strtoupper(substr($sidenavUser->name, 0, 2)) : 'U' }}</span>
                    </div>
                    <span class="d-flex gap-1 sidenav-user-name my-2">
                        <span>
                            <span class="mb-0 fw-semibold lh-base fs-15">{{ $sidenavUser->name ?? 'User' }}</span>
                            <p class="my-0 fs-13 text-muted">
                                @if($sidenavUser?->hasRole('admin'))
                                    Admin
                                @elseif($sidenavUser?->hasRole('reviewer'))
                                    Reviewer
                                @else
                                    Participant
                                @endif
                            </p>
                        </span>
                        <i class="ri-arrow-down-s-line d-block sidenav-user-arrow align-middle"></i>
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-end">
                    <div class="dropdown-header noti-title">
                        <h6 class="text-overflow m-0">Welcome!</h6>
                    </div>
                    <a href="{{ route('dashboard') }}" class="dropdown-item">
                        <i class="ri-dashboard-line me-1 fs-16 align-middle"></i>
                        <span class="align-middle">My Dashboard</span>
                    </a>
                    <div class="dropdown-divider"></div>
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

        <!--- Sidenav Menu -->
        <ul class="side-nav">

            {{-- MAIN NAVIGATION --}}
            <li class="side-nav-title mt-1">Main</li>

            <li class="side-nav-item">
                <a href="{{ route('dashboard') }}" class="side-nav-link">
                    <span class="menu-icon"><i class="ti ti-dashboard"></i></span>
                    <span class="menu-text"> Dashboard </span>
                </a>
            </li>

            <li class="side-nav-item">
                <a href="{{ route('home') }}" class="side-nav-link">
                    <span class="menu-icon"><i class="ti ti-world"></i></span>
                    <span class="menu-text"> Visit Website </span>
                </a>
            </li>

            {{-- ADMIN MENU (only for admins) --}}
            @if(auth()->user()?->hasRole('admin'))
            <li class="side-nav-title mt-2">Administration</li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarConferences" aria-expanded="false"
                    aria-controls="sidebarConferences" class="side-nav-link">
                    <span class="menu-icon"><i class="ti ti-calendar-event"></i></span>
                    <span class="menu-text"> Conferences </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarConferences">
                    <ul class="sub-menu">
                        <li class="side-nav-item">
                            <a href="{{ route('admin.conferences.index') }}" class="side-nav-link">
                                <span class="menu-text">All Conferences</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="{{ route('admin.topics.index') }}" class="side-nav-link">
                                <span class="menu-text">Topics</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="{{ route('admin.sessions.index') }}" class="side-nav-link">
                                <span class="menu-text">Sessions</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="{{ route('admin.packages.index') }}" class="side-nav-link">
                                <span class="menu-text">Packages</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarUsers" aria-expanded="false"
                    aria-controls="sidebarUsers" class="side-nav-link">
                    <span class="menu-icon"><i class="ti ti-users"></i></span>
                    <span class="menu-text"> Users </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarUsers">
                    <ul class="sub-menu">
                        <li class="side-nav-item">
                            <a href="{{ route('admin.users.index') }}" class="side-nav-link">
                                <span class="menu-text">All Users</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="{{ route('admin.roles.index') }}" class="side-nav-link">
                                <span class="menu-text">Roles</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarRegistrations" aria-expanded="false"
                    aria-controls="sidebarRegistrations" class="side-nav-link">
                    <span class="menu-icon"><i class="ti ti-user-check"></i></span>
                    <span class="menu-text"> Registrations </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarRegistrations">
                    <ul class="sub-menu">
                        <li class="side-nav-item">
                            <a href="{{ route('admin.registrations.index') }}" class="side-nav-link">
                                <span class="menu-text">All Registrations</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="{{ route('admin.payments.index') }}" class="side-nav-link">
                                <span class="menu-text">Payments</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarSubmissions" aria-expanded="false"
                    aria-controls="sidebarSubmissions" class="side-nav-link">
                    <span class="menu-icon"><i class="ti ti-file-text"></i></span>
                    <span class="menu-text"> Submissions </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarSubmissions">
                    <ul class="sub-menu">
                        <li class="side-nav-item">
                            <a href="{{ route('admin.submissions.index') }}" class="side-nav-link">
                                <span class="menu-text">All Submissions</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="{{ route('admin.grading-criteria.index') }}" class="side-nav-link">
                                <span class="menu-text">Grading Criteria</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarReviewers" aria-expanded="false"
                    aria-controls="sidebarReviewers" class="side-nav-link">
                    <span class="menu-icon"><i class="ti ti-user-star"></i></span>
                    <span class="menu-text"> Reviewers </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarReviewers">
                    <ul class="sub-menu">
                        <li class="side-nav-item">
                            <a href="{{ route('admin.reviewer-applications.index') }}" class="side-nav-link">
                                <span class="menu-text">Applications</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="{{ route('admin.paper-reviews.index') }}" class="side-nav-link">
                                <span class="menu-text">Paper Reviews</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="side-nav-item">
                <a href="{{ route('admin.speakers.index') }}" class="side-nav-link">
                    <span class="menu-icon"><i class="ti ti-speakerphone"></i></span>
                    <span class="menu-text"> Speakers </span>
                </a>
            </li>
            @endif

            {{-- REVIEWER MENU --}}
            @if(auth()->user()?->hasRole('reviewer'))
            <li class="side-nav-title mt-2">Review</li>

            <li class="side-nav-item">
                <a href="{{ route('dashboard.reviewer') }}" class="side-nav-link">
                    <span class="menu-icon"><i class="ti ti-clipboard-check"></i></span>
                    <span class="menu-text"> My Reviews </span>
                </a>
            </li>

            <li class="side-nav-item">
                <a href="{{ route('reviewer.assigned-papers') }}" class="side-nav-link">
                    <span class="menu-icon"><i class="ti ti-file-search"></i></span>
                    <span class="menu-text"> Assigned Papers </span>
                </a>
            </li>
            @endif

            {{-- PARTICIPANT MENU --}}
            @if(auth()->user()?->hasRole('participant') || (!auth()->user()?->hasRole('admin') && !auth()->user()?->hasRole('reviewer')))
            <li class="side-nav-title mt-2">My Conference</li>

            <li class="side-nav-item">
                <a href="{{ route('dashboard.participant') }}" class="side-nav-link">
                    <span class="menu-icon"><i class="ti ti-user"></i></span>
                    <span class="menu-text"> My Dashboard </span>
                </a>
            </li>

            <li class="side-nav-item">
                <a href="{{ route('participant.submissions.index') }}" class="side-nav-link">
                    <span class="menu-icon"><i class="ti ti-file-upload"></i></span>
                    <span class="menu-text"> My Submissions </span>
                </a>
            </li>

            <li class="side-nav-item">
                <a href="{{ route('participant.payments.index') }}" class="side-nav-link">
                    <span class="menu-icon"><i class="ti ti-credit-card"></i></span>
                    <span class="menu-text"> My Payments </span>
                </a>
            </li>

            <li class="side-nav-item">
                <a href="{{ route('participant.sessions.index') }}" class="side-nav-link">
                    <span class="menu-icon"><i class="ti ti-calendar"></i></span>
                    <span class="menu-text"> My Sessions </span>
                </a>
            </li>
            @endif

        </ul>

        <div class="clearfix"></div>
    </div>
</div>
<!-- Sidenav Menu End -->
