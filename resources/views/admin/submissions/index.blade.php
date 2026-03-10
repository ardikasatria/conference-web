@extends('layouts.vertical')

@section('title', 'Submissions')

@section('content')
<div class="container-fluid">
    @include('layouts.partials.page-title', ['title' => 'Submissions', 'subtitle' => 'Manage all paper submissions'])

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show"><i class="ri-check-line me-1"></i> {{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show"><i class="ri-error-warning-line me-1"></i> {{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="header-title mb-0">All Submissions</h4>
            <div class="d-flex gap-2">
                <form method="GET" class="d-flex gap-2">
                    <select name="conference_id" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">All Conferences</option>
                        @foreach($conferences as $conference)
                            <option value="{{ $conference->id }}" {{ request('conference_id') == $conference->id ? 'selected' : '' }}>{{ $conference->name }}</option>
                        @endforeach
                    </select>
                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">All Statuses</option>
                        @foreach(['draft', 'submitted', 'under_review', 'revision_required', 'accepted', 'rejected'] as $s)
                            <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $s)) }}</option>
                        @endforeach
                    </select>
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Search..." value="{{ request('search') }}">
                    <button class="btn btn-sm btn-outline-primary"><i class="ti ti-search"></i></button>
                </form>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-centered table-nowrap mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Presenter</th>
                            <th>Conference</th>
                            <th>Topic</th>
                            <th>Status</th>
                            <th>Submitted At</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($submissions as $submission)
                        @php
                            $statusColors = [
                                'draft' => 'secondary', 'submitted' => 'primary', 'under_review' => 'info',
                                'revision_required' => 'warning', 'accepted' => 'success', 'rejected' => 'danger',
                            ];
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration + ($submissions->currentPage() - 1) * $submissions->perPage() }}</td>
                            <td><strong>{{ Str::limit($submission->title, 50) }}</strong></td>
                            <td>{{ $submission->user->name ?? '-' }}</td>
                            <td>{{ $submission->conference->name ?? '-' }}</td>
                            <td>{{ $submission->topic->name ?? '-' }}</td>
                            <td>
                                <form method="POST" action="{{ route('admin.submissions.update-status', $submission) }}" class="d-inline-flex gap-1">
                                    @csrf @method('PUT')
                                    <select name="status" class="form-select form-select-sm" style="width: auto; min-width: 130px;">
                                        @foreach(['draft', 'submitted', 'under_review', 'revision_required', 'accepted', 'rejected'] as $s)
                                            <option value="{{ $s }}" {{ $submission->status === $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $s)) }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="btn btn-sm btn-outline-primary" title="Update Status"><i class="ti ti-check"></i></button>
                                </form>
                            </td>
                            <td>{{ $submission->submitted_at?->format('d M Y') ?? '-' }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.submissions.show', $submission) }}" class="btn btn-sm btn-outline-info me-1" title="View"><i class="ti ti-eye"></i></a>
                                <form method="POST" action="{{ route('admin.submissions.destroy', $submission) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this submission?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" title="Delete"><i class="ti ti-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="8" class="text-center text-muted py-4">No submissions found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $submissions->links() }}</div>
        </div>
    </div>
</div>
@endsection
