@extends('layouts.vertical', ['title' => 'Admin Dashboard'])

@section('content')
<div class="container-xxl">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
        <h1 class="mb-sm-0">Admin Dashboard</h1>
        <div class="page-title-right">
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Admin</li>
            </ol>
        </div>
    </div>

    {{-- Conference Info Banner --}}
    @if($conference)
    <div class="alert alert-primary d-flex align-items-center mb-3" role="alert">
        <i class="ri-calendar-event-line fs-4 me-2"></i>
        <div>
            <strong>{{ $conference->name }}</strong>
            @if($conference->start_date && $conference->end_date)
                &mdash; {{ $conference->start_date->format('M d') }} - {{ $conference->end_date->format('M d, Y') }}
            @endif
            @if($conference->location)
                | {{ $conference->location }}
            @endif
        </div>
    </div>
    @endif

    {{-- Statistics Cards --}}
    <div class="row row-cols-xxl-4 row-cols-md-2 row-cols-1">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm bg-primary bg-opacity-10 rounded flex-shrink-0">
                            <span class="avatar-title text-primary rounded">
                                <i class="ri-user-add-line fs-4"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h3 class="fw-semibold mb-0">{{ number_format($totalRegistrations) }}</h3>
                            <p class="text-muted mb-0">Total Registrations</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm bg-info bg-opacity-10 rounded flex-shrink-0">
                            <span class="avatar-title text-info rounded">
                                <i class="ri-file-text-line fs-4"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h3 class="fw-semibold mb-0">{{ number_format($totalSubmissions) }}</h3>
                            <p class="text-muted mb-0">Total Submissions</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm bg-warning bg-opacity-10 rounded flex-shrink-0">
                            <span class="avatar-title text-warning rounded">
                                <i class="ri-time-line fs-4"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h3 class="fw-semibold mb-0">{{ number_format($pendingReviews) }}</h3>
                            <p class="text-muted mb-0">Pending Reviews</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm bg-success bg-opacity-10 rounded flex-shrink-0">
                            <span class="avatar-title text-success rounded">
                                <i class="ri-money-dollar-circle-line fs-4"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h3 class="fw-semibold mb-0">${{ number_format($totalRevenue, 2) }}</h3>
                            <p class="text-muted mb-0">Total Revenue</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Secondary Stats --}}
    <div class="row row-cols-xxl-3 row-cols-md-3 row-cols-1">
        <div class="col">
            <div class="card">
                <div class="card-body text-center">
                    <h4 class="fw-semibold text-primary mb-1">{{ number_format($totalUsers) }}</h4>
                    <p class="text-muted mb-0"><i class="ri-group-line me-1"></i>Total Users</p>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-body text-center">
                    <h4 class="fw-semibold text-info mb-1">{{ number_format($totalReviewers) }}</h4>
                    <p class="text-muted mb-0"><i class="ri-user-star-line me-1"></i>Active Reviewers</p>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-body text-center">
                    <h4 class="fw-semibold text-warning mb-1">{{ number_format($pendingApplications) }}</h4>
                    <p class="text-muted mb-0"><i class="ri-file-user-line me-1"></i>Pending Reviewer Apps</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Tables Row --}}
    <div class="row">
        {{-- Recent Registrations --}}
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="header-title mb-0">Recent Registrations</h4>
                    <span class="badge bg-primary">{{ $totalRegistrations }} total</span>
                </div>
                <div class="card-body">
                    @if($recentRegistrations->isEmpty())
                        <div class="text-center text-muted py-4">
                            <i class="ri-inbox-line fs-2 d-block mb-2"></i>
                            No registrations yet.
                        </div>
                    @else
                    <div class="table-responsive">
                        <table class="table table-hover table-sm mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentRegistrations as $reg)
                                <tr>
                                    <td>
                                        <strong>{{ $reg->user->name ?? 'N/A' }}</strong>
                                        <br><small class="text-muted">{{ $reg->user->email ?? '' }}</small>
                                    </td>
                                    <td>
                                        @switch($reg->status)
                                            @case('confirmed')
                                                <span class="badge bg-success">Confirmed</span>
                                                @break
                                            @case('pending')
                                                <span class="badge bg-warning">Pending</span>
                                                @break
                                            @case('cancelled')
                                                <span class="badge bg-danger">Cancelled</span>
                                                @break
                                            @default
                                                <span class="badge bg-secondary">{{ ucfirst($reg->status ?? 'unknown') }}</span>
                                        @endswitch
                                    </td>
                                    <td><small>{{ $reg->created_at?->format('M d, Y') }}</small></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Recent Submissions --}}
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="header-title mb-0">Recent Submissions</h4>
                    <span class="badge bg-info">{{ $totalSubmissions }} total</span>
                </div>
                <div class="card-body">
                    @if($recentSubmissions->isEmpty())
                        <div class="text-center text-muted py-4">
                            <i class="ri-inbox-line fs-2 d-block mb-2"></i>
                            No submissions yet.
                        </div>
                    @else
                    <div class="table-responsive">
                        <table class="table table-hover table-sm mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Title</th>
                                    <th>Author</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentSubmissions as $sub)
                                <tr>
                                    <td>
                                        <strong>{{ Str::limit($sub->title, 30) }}</strong>
                                    </td>
                                    <td>{{ $sub->user->name ?? $sub->presenter_name ?? 'N/A' }}</td>
                                    <td>
                                        @switch($sub->status)
                                            @case('submitted')
                                                <span class="badge bg-info">Submitted</span>
                                                @break
                                            @case('approved')
                                                <span class="badge bg-success">Approved</span>
                                                @break
                                            @case('rejected')
                                                <span class="badge bg-danger">Rejected</span>
                                                @break
                                            @case('draft')
                                                <span class="badge bg-secondary">Draft</span>
                                                @break
                                            @default
                                                <span class="badge bg-secondary">{{ ucfirst($sub->status ?? 'unknown') }}</span>
                                        @endswitch
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Pending Reviewer Applications --}}
    @if($pendingReviewerApps->isNotEmpty())
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="header-title mb-0">
                        <i class="ri-user-star-line me-1"></i>Pending Reviewer Applications
                    </h4>
                    <span class="badge bg-warning">{{ $pendingApplications }} pending</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Applicant</th>
                                    <th>Conference</th>
                                    <th>Field of Study</th>
                                    <th>Applied</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingReviewerApps as $app)
                                <tr>
                                    <td>
                                        <strong>{{ $app->full_name_with_degree ?? $app->user->name ?? 'N/A' }}</strong>
                                        <br><small class="text-muted">{{ $app->affiliation ?? '' }}</small>
                                    </td>
                                    <td>{{ $app->conference->name ?? 'N/A' }}</td>
                                    <td>{{ $app->field_of_study ?? '-' }}</td>
                                    <td><small>{{ $app->created_at?->format('M d, Y') }}</small></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
