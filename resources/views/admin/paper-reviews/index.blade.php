@extends('layouts.vertical')

@section('title', 'Paper Reviews')

@section('content')
<div class="container-fluid">
    @include('layouts.partials.page-title', ['title' => 'Paper Reviews', 'subtitle' => 'Manage paper review assignments'])

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show"><i class="ri-check-line me-1"></i> {{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show"><i class="ri-error-warning-line me-1"></i> {{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="header-title mb-0">All Paper Reviews</h4>
            <div class="d-flex gap-2">
                <form method="GET" class="d-flex gap-2">
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Search paper/reviewer..." value="{{ request('search') }}">
                    <select name="conference_id" class="form-select form-select-sm" style="width:180px" onchange="this.form.submit()">
                        <option value="">All Conferences</option>
                        @foreach($conferences as $conf)
                            <option value="{{ $conf->id }}" {{ request('conference_id') == $conf->id ? 'selected' : '' }}>{{ $conf->name }}</option>
                        @endforeach
                    </select>
                    <select name="status" class="form-select form-select-sm" style="width:140px" onchange="this.form.submit()">
                        <option value="">All Status</option>
                        @foreach(['pending','in_progress','completed'] as $s)
                            <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ str_replace('_', ' ', ucfirst($s)) }}</option>
                        @endforeach
                    </select>
                    <button class="btn btn-sm btn-outline-primary"><i class="ti ti-search"></i></button>
                </form>
                <a href="{{ route('admin.paper-reviews.create') }}" class="btn btn-primary btn-sm"><i class="ti ti-plus me-1"></i> Assign Review</a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-centered table-nowrap mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Paper Title</th>
                            <th>Reviewer</th>
                            <th>Conference</th>
                            <th>Status</th>
                            <th>Score</th>
                            <th>Recommendation</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reviews as $review)
                        <tr>
                            <td>{{ $loop->iteration + ($reviews->currentPage() - 1) * $reviews->perPage() }}</td>
                            <td>
                                <strong>{{ \Illuminate\Support\Str::limit($review->submission->title ?? 'N/A', 40) }}</strong>
                            </td>
                            <td>{{ $review->reviewer->name ?? 'N/A' }}</td>
                            <td>{{ $review->conference->name ?? '-' }}</td>
                            <td>
                                <span class="badge bg-{{ $review->status === 'completed' ? 'success' : ($review->status === 'in_progress' ? 'info' : 'warning') }}">
                                    {{ str_replace('_', ' ', ucfirst($review->status)) }}
                                </span>
                            </td>
                            <td>{{ $review->total_score ? number_format($review->total_score, 1) : '-' }}</td>
                            <td>
                                @if($review->recommendation)
                                    <span class="badge bg-{{ $review->recommendation === 'accept' ? 'success' : ($review->recommendation === 'reject' ? 'danger' : 'warning') }}">
                                        {{ str_replace('_', ' ', ucfirst($review->recommendation)) }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <form method="POST" action="{{ route('admin.paper-reviews.destroy', $review) }}" class="d-inline" onsubmit="return confirm('Delete this review assignment?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" title="Delete"><i class="ti ti-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="8" class="text-center text-muted py-4">No paper reviews found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $reviews->links() }}</div>
        </div>
    </div>
</div>
@endsection
