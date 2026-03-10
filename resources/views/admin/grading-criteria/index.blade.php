@extends('layouts.vertical')

@section('title', 'Grading Criteria')

@section('content')
<div class="container-fluid">
    @include('layouts.partials.page-title', ['title' => 'Grading Criteria', 'subtitle' => 'Manage grading criteria'])

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show"><i class="ri-check-line me-1"></i> {{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show"><i class="ri-error-warning-line me-1"></i> {{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="header-title mb-0">All Grading Criteria</h4>
            <div class="d-flex gap-2">
                <form method="GET" class="d-flex gap-2">
                    <select name="conference_id" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">All Conferences</option>
                        @foreach($conferences as $conference)
                            <option value="{{ $conference->id }}" {{ request('conference_id') == $conference->id ? 'selected' : '' }}>{{ $conference->name }}</option>
                        @endforeach
                    </select>
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Search..." value="{{ request('search') }}">
                    <button class="btn btn-sm btn-outline-primary"><i class="ti ti-search"></i></button>
                </form>
                <a href="{{ route('admin.grading-criteria.create') }}" class="btn btn-primary btn-sm"><i class="ti ti-plus me-1"></i> Add New</a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-centered table-nowrap mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Conference</th>
                            <th>Max Score</th>
                            <th>Order</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($gradingCriteria as $criterion)
                        <tr>
                            <td>{{ $loop->iteration + ($gradingCriteria->currentPage() - 1) * $gradingCriteria->perPage() }}</td>
                            <td><strong>{{ $criterion->name }}</strong></td>
                            <td>{{ Str::limit($criterion->description, 60) ?? '-' }}</td>
                            <td>{{ $criterion->conference->name ?? '-' }}</td>
                            <td><span class="badge bg-soft-primary text-primary">{{ $criterion->max_score }}</span></td>
                            <td>{{ $criterion->order ?? '-' }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.grading-criteria.edit', $criterion) }}" class="btn btn-sm btn-outline-primary me-1" title="Edit"><i class="ti ti-pencil"></i></a>
                                <form method="POST" action="{{ route('admin.grading-criteria.destroy', $criterion) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this grading criterion?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" title="Delete"><i class="ti ti-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">No grading criteria found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $gradingCriteria->links() }}</div>
        </div>
    </div>
</div>
@endsection
