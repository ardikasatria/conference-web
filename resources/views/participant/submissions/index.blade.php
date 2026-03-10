@extends('layouts.vertical')

@section('title', 'My Submissions')

@section('content')
<div class="container-fluid">
    @include('layouts.partials.page-title', ['title' => 'My Submissions', 'subtitle' => 'View and manage your paper submissions'])

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
                    <select name="status" class="form-select form-select-sm" style="width:160px" onchange="this.form.submit()">
                        <option value="">All Status</option>
                        @foreach(['draft','submitted','under_review','revision_required','accepted','rejected'] as $s)
                            <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ str_replace('_', ' ', ucfirst($s)) }}</option>
                        @endforeach
                    </select>
                </form>
                <a href="{{ route('participant.submissions.create') }}" class="btn btn-primary btn-sm"><i class="ti ti-plus me-1"></i> New Submission</a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-centered table-nowrap mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Conference</th>
                            <th>Topic</th>
                            <th>Status</th>
                            <th>Submitted</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($submissions as $submission)
                        <tr>
                            <td>{{ $loop->iteration + ($submissions->currentPage() - 1) * $submissions->perPage() }}</td>
                            <td><strong>{{ \Illuminate\Support\Str::limit($submission->title, 50) }}</strong></td>
                            <td>{{ $submission->conference->name ?? '-' }}</td>
                            <td>{{ $submission->topic ?? '-' }}</td>
                            <td>
                                @php
                                    $statusColors = ['draft' => 'secondary', 'submitted' => 'primary', 'under_review' => 'info', 'revision_required' => 'warning', 'accepted' => 'success', 'rejected' => 'danger'];
                                @endphp
                                <span class="badge bg-{{ $statusColors[$submission->status] ?? 'secondary' }}">
                                    {{ str_replace('_', ' ', ucfirst($submission->status)) }}
                                </span>
                            </td>
                            <td>{{ $submission->submitted_at?->format('d M Y') ?? '-' }}</td>
                            <td class="text-end">
                                @if(in_array($submission->status, ['draft', 'revision_required']))
                                    <a href="{{ route('participant.submissions.edit', $submission) }}" class="btn btn-sm btn-outline-primary" title="Edit"><i class="ti ti-pencil"></i></a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">No submissions yet. <a href="{{ route('participant.submissions.create') }}">Create your first submission</a>.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $submissions->links() }}</div>
        </div>
    </div>
</div>
@endsection
