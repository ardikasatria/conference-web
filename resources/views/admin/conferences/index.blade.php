@extends('layouts.vertical')

@section('title', 'Conferences')

@section('content')
<div class="container-fluid">
    @include('layouts.partials.page-title', ['title' => 'Conferences', 'subtitle' => 'Manage all conferences'])

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show"><i class="ri-check-line me-1"></i> {{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show"><i class="ri-error-warning-line me-1"></i> {{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="header-title mb-0">All Conferences</h4>
            <div class="d-flex gap-2">
                <form method="GET" class="d-flex gap-2">
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Search..." value="{{ request('search') }}">
                    <button class="btn btn-sm btn-outline-primary"><i class="ti ti-search"></i></button>
                </form>
                <a href="{{ route('admin.conferences.create') }}" class="btn btn-primary btn-sm"><i class="ti ti-plus me-1"></i> Add New</a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-centered table-nowrap mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Date</th>
                            <th>Location</th>
                            <th>Status</th>
                            <th>Registrations</th>
                            <th>Sessions</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($conferences as $conference)
                        <tr>
                            <td>{{ $loop->iteration + ($conferences->currentPage() - 1) * $conferences->perPage() }}</td>
                            <td>
                                <strong>{{ $conference->name }}</strong>
                                @if($conference->slug)<br><small class="text-muted">{{ $conference->slug }}</small>@endif
                            </td>
                            <td>
                                {{ $conference->start_date?->format('d M Y') }}
                                @if($conference->end_date)<br><small class="text-muted">to {{ $conference->end_date->format('d M Y') }}</small>@endif
                            </td>
                            <td>{{ $conference->location ?? '-' }}</td>
                            <td>
                                <span class="badge bg-{{ $conference->status === 'published' ? 'success' : ($conference->status === 'draft' ? 'warning' : 'secondary') }}">
                                    {{ ucfirst($conference->status) }}
                                </span>
                            </td>
                            <td><span class="badge bg-soft-info text-info">{{ $conference->registrations_count }}</span></td>
                            <td><span class="badge bg-soft-primary text-primary">{{ $conference->sessions_count }}</span></td>
                            <td class="text-end">
                                <a href="{{ route('admin.conferences.edit', $conference) }}" class="btn btn-sm btn-outline-primary me-1" title="Edit"><i class="ti ti-pencil"></i></a>
                                <form method="POST" action="{{ route('admin.conferences.destroy', $conference) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this conference?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" title="Delete"><i class="ti ti-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="8" class="text-center text-muted py-4">No conferences found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $conferences->links() }}</div>
        </div>
    </div>
</div>
@endsection
