@extends('layouts.vertical')

@section('title', 'Reviewer Applications')

@section('content')
<div class="container-fluid">
    @include('layouts.partials.page-title', ['title' => 'Reviewer Applications', 'subtitle' => 'Manage reviewer applications'])

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show"><i class="ri-check-line me-1"></i> {{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show"><i class="ri-error-warning-line me-1"></i> {{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="header-title mb-0">All Applications</h4>
            <form method="GET" class="d-flex gap-2">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search applicant..." value="{{ request('search') }}">
                <select name="conference_id" class="form-select form-select-sm" style="width:180px" onchange="this.form.submit()">
                    <option value="">All Conferences</option>
                    @foreach($conferences as $conf)
                        <option value="{{ $conf->id }}" {{ request('conference_id') == $conf->id ? 'selected' : '' }}>{{ $conf->name }}</option>
                    @endforeach
                </select>
                <select name="status" class="form-select form-select-sm" style="width:140px" onchange="this.form.submit()">
                    <option value="">All Status</option>
                    @foreach(['pending','approved','rejected'] as $s)
                        <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
                <button class="btn btn-sm btn-outline-primary"><i class="ti ti-search"></i></button>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-centered table-nowrap mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Applicant</th>
                            <th>Conference</th>
                            <th>Field of Study</th>
                            <th>Topics</th>
                            <th>Status</th>
                            <th>Applied At</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($applications as $app)
                        <tr>
                            <td>{{ $loop->iteration + ($applications->currentPage() - 1) * $applications->perPage() }}</td>
                            <td>
                                <strong>{{ $app->user->name ?? 'N/A' }}</strong>
                                <br><small class="text-muted">{{ $app->user->email ?? '' }}</small>
                            </td>
                            <td>{{ $app->conference->name ?? '-' }}</td>
                            <td>{{ $app->field_of_study ?? '-' }}</td>
                            <td>
                                @foreach($app->topics as $topic)
                                    <span class="badge bg-soft-info text-info">{{ $topic->name }}</span>
                                @endforeach
                                @if($app->topics->isEmpty()) <span class="text-muted">-</span> @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $app->status === 'approved' ? 'success' : ($app->status === 'pending' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($app->status) }}
                                </span>
                            </td>
                            <td>{{ $app->created_at?->format('d M Y') }}</td>
                            <td class="text-end">
                                @if($app->status === 'pending')
                                    <form method="POST" action="{{ route('admin.reviewer-applications.approve', $app) }}" class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-outline-success me-1" title="Approve" onclick="return confirm('Approve this application?')"><i class="ti ti-check"></i></button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.reviewer-applications.reject', $app) }}" class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-outline-warning me-1" title="Reject" onclick="return confirm('Reject this application?')"><i class="ti ti-x"></i></button>
                                    </form>
                                @endif
                                <form method="POST" action="{{ route('admin.reviewer-applications.destroy', $app) }}" class="d-inline" onsubmit="return confirm('Delete this application?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" title="Delete"><i class="ti ti-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="8" class="text-center text-muted py-4">No applications found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $applications->links() }}</div>
        </div>
    </div>
</div>
@endsection
