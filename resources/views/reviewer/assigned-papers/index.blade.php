@extends('layouts.vertical')

@section('title', 'Assigned Papers')

@section('content')
<div class="container-fluid">
    @include('layouts.partials.page-title', ['title' => 'Assigned Papers', 'subtitle' => 'Papers assigned to you for review'])

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show"><i class="ri-check-line me-1"></i> {{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="header-title mb-0">My Review Assignments</h4>
            <form method="GET" class="d-flex gap-2">
                <select name="status" class="form-select form-select-sm" style="width:160px" onchange="this.form.submit()">
                    <option value="">All Status</option>
                    @foreach(['pending','in_progress','completed'] as $s)
                        <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ str_replace('_', ' ', ucfirst($s)) }}</option>
                    @endforeach
                </select>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-centered table-nowrap mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Paper Title</th>
                            <th>Author</th>
                            <th>Conference</th>
                            <th>Status</th>
                            <th>Score</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reviews as $review)
                        <tr>
                            <td>{{ $loop->iteration + ($reviews->currentPage() - 1) * $reviews->perPage() }}</td>
                            <td><strong>{{ \Illuminate\Support\Str::limit($review->submission->title ?? 'N/A', 50) }}</strong></td>
                            <td>{{ $review->submission->user->name ?? 'N/A' }}</td>
                            <td>{{ $review->conference->name ?? '-' }}</td>
                            <td>
                                <span class="badge bg-{{ $review->status === 'completed' ? 'success' : ($review->status === 'in_progress' ? 'info' : 'warning') }}">
                                    {{ str_replace('_', ' ', ucfirst($review->status)) }}
                                </span>
                            </td>
                            <td>{{ $review->total_score ? number_format($review->total_score, 1) : '-' }}</td>
                            <td class="text-end">
                                <a href="{{ route('reviewer.assigned-papers.show', $review) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="ti ti-eye me-1"></i>
                                    {{ $review->status === 'pending' ? 'Start Review' : ($review->status === 'in_progress' ? 'Continue' : 'View') }}
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">No papers assigned for review.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $reviews->links() }}</div>
        </div>
    </div>
</div>
@endsection
