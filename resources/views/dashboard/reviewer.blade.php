@extends('layouts.vertical', ['title' => 'Reviewer Dashboard'])

@section('content')
<div class="container-xxl">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
        <h1 class="mb-sm-0">Reviewer Dashboard</h1>
        <div class="page-title-right">
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Reviewer</li>
            </ol>
        </div>
    </div>

    {{-- Welcome Banner --}}
    <div class="card mb-3">
        <div class="card-body">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="card-title mb-1">Welcome, {{ $user->name }}</h5>
                    <p class="text-muted mb-0">
                        @if($conference)
                            Reviewing for <strong>{{ $conference->name }}</strong>
                            @if($nextDeadline)
                                &mdash; Deadline: {{ $nextDeadline->format('M d, Y') }}
                                ({{ now()->diffInDays($nextDeadline, false) > 0 ? now()->diffInDays($nextDeadline) . ' days left' : 'Past deadline' }})
                            @endif
                        @else
                            Manage your paper reviews and assignments.
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Review Statistics --}}
    <div class="row row-cols-xxl-4 row-cols-md-2 row-cols-1">
        <div class="col">
            <div class="card">
                <div class="card-body text-center">
                    <i class="ri-file-list-3-line fs-2 text-primary mb-2 d-block"></i>
                    <h4 class="header-title">Assigned Papers</h4>
                    <h3 class="fw-semibold text-primary mb-1">{{ $totalAssigned }}</h3>
                    <p class="text-muted mb-0">total assigned</p>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card">
                <div class="card-body text-center">
                    <i class="ri-checkbox-circle-line fs-2 text-success mb-2 d-block"></i>
                    <h4 class="header-title">Completed</h4>
                    <h3 class="fw-semibold text-success mb-1">{{ $completedReviews }}</h3>
                    <p class="text-muted mb-0">reviews submitted</p>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card">
                <div class="card-body text-center">
                    <i class="ri-time-line fs-2 text-warning mb-2 d-block"></i>
                    <h4 class="header-title">Pending</h4>
                    <h3 class="fw-semibold text-warning mb-1">{{ $pendingReviews }}</h3>
                    <p class="text-muted mb-0">need attention</p>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card">
                <div class="card-body text-center">
                    <i class="ri-calendar-todo-line fs-2 text-danger mb-2 d-block"></i>
                    <h4 class="header-title">Deadline</h4>
                    @if($nextDeadline)
                        @php $daysLeft = now()->diffInDays($nextDeadline, false); @endphp
                        <h3 class="fw-semibold {{ $daysLeft <= 7 ? 'text-danger' : 'text-info' }} mb-1">
                            {{ $daysLeft > 0 ? $daysLeft . ' days' : 'Overdue' }}
                        </h3>
                        <p class="text-muted mb-0">{{ $nextDeadline->format('M d, Y') }}</p>
                    @else
                        <h3 class="fw-semibold text-muted mb-1">N/A</h3>
                        <p class="text-muted mb-0">No deadline set</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Papers Pending Review --}}
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="header-title mb-0">Papers Awaiting Review</h4>
                    <div>
                        <span class="badge bg-primary">{{ $totalAssigned }} Total</span>
                        <span class="badge bg-warning">{{ $pendingReviews }} Pending</span>
                    </div>
                </div>
                <div class="card-body">
                    @if($pendingPapers->isEmpty())
                        <div class="text-center text-muted py-4">
                            <i class="ri-checkbox-circle-line fs-1 text-success d-block mb-2"></i>
                            <p class="mb-0">All caught up! No pending reviews.</p>
                        </div>
                    @else
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Paper Title</th>
                                    <th>Author</th>
                                    <th>Topic</th>
                                    <th>Status</th>
                                    <th>Progress</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingPapers as $review)
                                <tr>
                                    <td><strong>{{ Str::limit($review->submission->title ?? 'Untitled', 40) }}</strong></td>
                                    <td>{{ $review->submission->presenter_name ?? $review->submission->user->name ?? 'N/A' }}</td>
                                    <td><small>{{ $review->submission->topic ?? '-' }}</small></td>
                                    <td>
                                        @switch($review->status)
                                            @case('pending')
                                                <span class="badge bg-warning">Pending</span>
                                                @break
                                            @case('in_progress')
                                                <span class="badge bg-info">In Progress</span>
                                                @break
                                            @default
                                                <span class="badge bg-secondary">{{ ucfirst($review->status) }}</span>
                                        @endswitch
                                    </td>
                                    <td>
                                        @php $progress = $review->getProgressPercentage(); @endphp
                                        <div class="progress" style="height: 6px; width: 80px;">
                                            <div class="progress-bar bg-primary" style="width: {{ $progress }}%"></div>
                                        </div>
                                        <small class="text-muted">{{ round($progress) }}%</small>
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

    <div class="row">
        {{-- Review History --}}
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title mb-0">Review History</h4>
                </div>
                <div class="card-body">
                    @if($completedPapers->isEmpty())
                        <div class="text-center text-muted py-4">
                            <i class="ri-history-line fs-1 d-block mb-2"></i>
                            <p class="mb-0">No completed reviews yet.</p>
                        </div>
                    @else
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Paper</th>
                                    <th>Score</th>
                                    <th>Decision</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($completedPapers as $review)
                                <tr>
                                    <td>{{ Str::limit($review->submission->title ?? 'Untitled', 35) }}</td>
                                    <td>
                                        @if($review->total_score)
                                            <span class="fw-semibold">{{ number_format($review->total_score, 1) }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($review->recommend_accept === true)
                                            <span class="badge bg-success">Accept</span>
                                        @elseif($review->recommend_accept === false)
                                            <span class="badge bg-danger">Reject</span>
                                        @else
                                            <span class="badge bg-info">{{ ucfirst($review->status) }}</span>
                                        @endif
                                    </td>
                                    <td><small>{{ $review->review_date?->format('M d, Y') ?? $review->updated_at?->format('M d, Y') }}</small></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Expert Topics & Application Info --}}
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title mb-0">Expert Topics</h4>
                </div>
                <div class="card-body">
                    @if($expertTopics->isNotEmpty())
                        <div class="mb-2">
                            @foreach($expertTopics as $topic)
                                <span class="badge bg-primary me-1 mb-1">{{ $topic->name }}</span>
                            @endforeach
                        </div>
                        <p class="text-muted small mb-0">Papers are assigned based on your expertise areas.</p>
                    @else
                        <p class="text-muted mb-0">No expert topics registered.</p>
                    @endif
                </div>
            </div>

            @if($reviewerApplication)
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title mb-0">Reviewer Profile</h4>
                </div>
                <div class="card-body">
                    @if($reviewerApplication->field_of_study)
                    <p class="mb-2">
                        <small class="text-muted d-block">Field of Study</small>
                        <strong>{{ $reviewerApplication->field_of_study }}</strong>
                    </p>
                    @endif
                    @if($reviewerApplication->sub_field)
                    <p class="mb-2">
                        <small class="text-muted d-block">Sub-field</small>
                        <strong>{{ $reviewerApplication->sub_field }}</strong>
                    </p>
                    @endif
                    @if($reviewerApplication->affiliation)
                    <p class="mb-0">
                        <small class="text-muted d-block">Affiliation</small>
                        <strong>{{ $reviewerApplication->affiliation }}</strong>
                    </p>
                    @endif
                </div>
            </div>
            @endif

            {{-- Review Guidelines --}}
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title mb-0">Review Guidelines</h4>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="ri-checkbox-circle-line text-success me-1"></i>
                            <strong>Originality</strong> — Is the work original?
                        </li>
                        <li class="mb-2">
                            <i class="ri-checkbox-circle-line text-success me-1"></i>
                            <strong>Quality</strong> — Technical quality & methodology
                        </li>
                        <li class="mb-2">
                            <i class="ri-checkbox-circle-line text-success me-1"></i>
                            <strong>Relevance</strong> — Relevant to conference topics
                        </li>
                        <li class="mb-2">
                            <i class="ri-checkbox-circle-line text-success me-1"></i>
                            <strong>Clarity</strong> — Writing & presentation quality
                        </li>
                        <li>
                            <i class="ri-checkbox-circle-line text-success me-1"></i>
                            <strong>Significance</strong> — Contribution to the field
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
