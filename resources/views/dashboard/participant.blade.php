@extends('layouts.vertical', ['title' => 'Participant Dashboard'])

@section('content')
<div class="container-xxl">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
        <h1 class="mb-sm-0">My Dashboard</h1>
        <div class="page-title-right">
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Participant</li>
            </ol>
        </div>
    </div>

    {{-- Welcome Section --}}
    <div class="card mb-3">
        <div class="card-body">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="card-title mb-1">Welcome, {{ $user->name }}!</h5>
                    <p class="text-muted mb-0">
                        @if($conference)
                            {{ $conference->name }}
                            @if($conference->start_date)
                                &mdash; {{ $conference->start_date->format('M d') }}{{ $conference->end_date ? ' - ' . $conference->end_date->format('M d, Y') : '' }}
                            @endif
                        @else
                            Track your conference submissions and registration status.
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Status Overview Cards --}}
    <div class="row row-cols-xxl-4 row-cols-md-2 row-cols-1">
        {{-- Registration Status --}}
        <div class="col">
            <div class="card">
                <div class="card-body text-center">
                    <i class="ri-user-follow-line fs-2 text-primary mb-2 d-block"></i>
                    <h4 class="header-title">Registration</h4>
                    @if($activeRegistration)
                        @switch($activeRegistration->status)
                            @case('confirmed')
                                <h3 class="fw-semibold text-success mb-1">Confirmed</h3>
                                @break
                            @case('pending')
                                <h3 class="fw-semibold text-warning mb-1">Pending</h3>
                                @break
                            @case('cancelled')
                                <h3 class="fw-semibold text-danger mb-1">Cancelled</h3>
                                @break
                            @default
                                <h3 class="fw-semibold text-secondary mb-1">{{ ucfirst($activeRegistration->status) }}</h3>
                        @endswitch
                        <p class="text-muted mb-0">{{ $activeRegistration->ticket_number ?? 'No ticket yet' }}</p>
                    @else
                        <h3 class="fw-semibold text-muted mb-1">Not Registered</h3>
                        <p class="text-muted mb-0">No active registration</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Submission Status --}}
        <div class="col">
            <div class="card">
                <div class="card-body text-center">
                    <i class="ri-file-text-line fs-2 text-info mb-2 d-block"></i>
                    <h4 class="header-title">Submissions</h4>
                    <h3 class="fw-semibold text-info mb-1">{{ $submissionCount }}</h3>
                    <p class="text-muted mb-0">
                        {{ $submissionCount === 1 ? 'paper submitted' : 'papers submitted' }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Payment Status --}}
        <div class="col">
            <div class="card">
                <div class="card-body text-center">
                    <i class="ri-money-dollar-circle-line fs-2 text-warning mb-2 d-block"></i>
                    <h4 class="header-title">Payment</h4>
                    @if($pendingPayment)
                        <h3 class="fw-semibold text-warning mb-1">{{ ucfirst(str_replace('_', ' ', $pendingPayment->status)) }}</h3>
                        <p class="text-muted mb-0">${{ number_format($pendingPayment->amount, 2) }} due</p>
                    @elseif($payments->where('status', 'paid')->isNotEmpty())
                        <h3 class="fw-semibold text-success mb-1">Paid</h3>
                        <p class="text-muted mb-0">All payments completed</p>
                    @else
                        <h3 class="fw-semibold text-muted mb-1">No Payment</h3>
                        <p class="text-muted mb-0">No payments found</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Sessions --}}
        <div class="col">
            <div class="card">
                <div class="card-body text-center">
                    <i class="ri-calendar-event-line fs-2 text-success mb-2 d-block"></i>
                    <h4 class="header-title">Sessions</h4>
                    <h3 class="fw-semibold text-primary mb-1">{{ $sessionCount }}</h3>
                    <p class="text-muted mb-0">sessions registered</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- My Submissions --}}
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="header-title mb-0">My Submissions</h4>
                </div>
                <div class="card-body">
                    @if($submissions->isEmpty())
                        <div class="text-center text-muted py-4">
                            <i class="ri-file-add-line fs-1 d-block mb-2"></i>
                            <p class="mb-0">You haven't submitted any papers yet.</p>
                        </div>
                    @else
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Title</th>
                                    <th>Topic</th>
                                    <th>Status</th>
                                    <th>Submitted</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($submissions as $sub)
                                <tr>
                                    <td><strong>{{ Str::limit($sub->title, 40) }}</strong></td>
                                    <td><small>{{ $sub->topic ?? '-' }}</small></td>
                                    <td>
                                        @switch($sub->status)
                                            @case('draft')
                                                <span class="badge bg-secondary">Draft</span>
                                                @break
                                            @case('submitted')
                                                <span class="badge bg-info">Submitted</span>
                                                @break
                                            @case('approved')
                                                <span class="badge bg-success">Approved</span>
                                                @break
                                            @case('rejected')
                                                <span class="badge bg-danger">Rejected</span>
                                                @break
                                            @default
                                                <span class="badge bg-secondary">{{ ucfirst($sub->status) }}</span>
                                        @endswitch
                                    </td>
                                    <td><small>{{ $sub->submitted_at?->format('M d, Y') ?? '-' }}</small></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Conference Info & Important Dates --}}
        <div class="col-lg-4">
            @if($conference)
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title mb-0">Important Dates</h4>
                </div>
                <div class="card-body">
                    @if($conference->start_date)
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-sm bg-primary bg-opacity-10 rounded flex-shrink-0 me-2">
                            <span class="avatar-title text-primary rounded">
                                <i class="ri-calendar-check-line"></i>
                            </span>
                        </div>
                        <div>
                            <p class="text-muted mb-0 small">Conference Date</p>
                            <strong>{{ $conference->start_date->format('M d') }}{{ $conference->end_date ? ' - ' . $conference->end_date->format('M d, Y') : '' }}</strong>
                        </div>
                    </div>
                    @endif

                    @if($conference->location)
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-sm bg-info bg-opacity-10 rounded flex-shrink-0 me-2">
                            <span class="avatar-title text-info rounded">
                                <i class="ri-map-pin-line"></i>
                            </span>
                        </div>
                        <div>
                            <p class="text-muted mb-0 small">Location</p>
                            <strong>{{ $conference->location }}</strong>
                        </div>
                    </div>
                    @endif

                    @if($conference->contact_email)
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm bg-success bg-opacity-10 rounded flex-shrink-0 me-2">
                            <span class="avatar-title text-success rounded">
                                <i class="ri-mail-line"></i>
                            </span>
                        </div>
                        <div>
                            <p class="text-muted mb-0 small">Contact</p>
                            <strong>{{ $conference->contact_email }}</strong>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            {{-- Registration Progress --}}
            @if($activeRegistration)
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title mb-0">Registration Progress</h4>
                </div>
                <div class="card-body">
                    @php $progress = $activeRegistration->getProgressPercentage(); @endphp
                    <div class="progress mb-2" style="height: 10px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $progress }}%"
                             aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>
                    <p class="text-muted small mb-3">{{ round($progress) }}% complete</p>

                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="ri-checkbox-circle-fill text-success me-1"></i>
                            Registration completed
                        </li>
                        <li class="mb-2">
                            @if($activeRegistration->submission_status === 'approved')
                                <i class="ri-checkbox-circle-fill text-success me-1"></i>
                            @elseif($activeRegistration->submission_status === 'not_required')
                                <i class="ri-checkbox-circle-fill text-muted me-1"></i>
                            @else
                                <i class="ri-checkbox-blank-circle-line text-warning me-1"></i>
                            @endif
                            Paper submission ({{ str_replace('_', ' ', $activeRegistration->submission_status ?? 'pending') }})
                        </li>
                        <li>
                            @if($activeRegistration->payment_status === 'paid')
                                <i class="ri-checkbox-circle-fill text-success me-1"></i>
                            @elseif($activeRegistration->payment_status === 'not_required')
                                <i class="ri-checkbox-circle-fill text-muted me-1"></i>
                            @else
                                <i class="ri-checkbox-blank-circle-line text-warning me-1"></i>
                            @endif
                            Payment ({{ str_replace('_', ' ', $activeRegistration->payment_status ?? 'pending') }})
                        </li>
                    </ul>
                </div>
            </div>
            @endif
        </div>
    </div>

    {{-- Registered Sessions --}}
    @if($sessions->isNotEmpty())
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title mb-0">Registered Sessions</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Session Title</th>
                                    <th>Date & Time</th>
                                    <th>Room</th>
                                    <th>Speakers</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sessions as $session)
                                <tr>
                                    <td><strong>{{ $session->title }}</strong></td>
                                    <td>
                                        @if($session->start_time)
                                            {{ $session->start_time->format('M d, Y') }}<br>
                                            <small class="text-muted">
                                                {{ $session->start_time->format('h:i A') }}
                                                {{ $session->end_time ? '- ' . $session->end_time->format('h:i A') : '' }}
                                            </small>
                                        @else
                                            <span class="text-muted">TBD</span>
                                        @endif
                                    </td>
                                    <td>{{ $session->room ?? 'TBD' }}</td>
                                    <td>
                                        @if($session->speakers->isNotEmpty())
                                            {{ $session->speakers->pluck('name')->join(', ') }}
                                        @else
                                            <span class="text-muted">TBD</span>
                                        @endif
                                    </td>
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
