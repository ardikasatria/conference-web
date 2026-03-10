@extends('layouts.vertical')

@section('title', 'Speakers')

@section('content')
<div class="container-fluid">
    @include('layouts.partials.page-title', ['title' => 'Speakers', 'subtitle' => 'Manage speakers'])

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show"><i class="ri-check-line me-1"></i> {{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show"><i class="ri-error-warning-line me-1"></i> {{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="header-title mb-0">All Speakers</h4>
            <div class="d-flex gap-2">
                <form method="GET" class="d-flex gap-2">
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Search..." value="{{ request('search') }}">
                    <select name="status" class="form-select form-select-sm" style="width:130px" onchange="this.form.submit()">
                        <option value="">All Status</option>
                        @foreach(['active','inactive'] as $s)
                            <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                    <button class="btn btn-sm btn-outline-primary"><i class="ti ti-search"></i></button>
                </form>
                <a href="{{ route('admin.speakers.create') }}" class="btn btn-primary btn-sm"><i class="ti ti-plus me-1"></i> Add Speaker</a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-centered table-nowrap mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Company</th>
                            <th>Position</th>
                            <th>Sessions</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($speakers as $speaker)
                        <tr>
                            <td>{{ $loop->iteration + ($speakers->currentPage() - 1) * $speakers->perPage() }}</td>
                            <td><strong>{{ $speaker->name }}</strong></td>
                            <td>{{ $speaker->email }}</td>
                            <td>{{ $speaker->company ?? '-' }}</td>
                            <td>{{ $speaker->position ?? '-' }}</td>
                            <td><span class="badge bg-soft-primary text-primary">{{ $speaker->sessions_count }}</span></td>
                            <td>
                                <span class="badge bg-{{ $speaker->status === 'active' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($speaker->status) }}
                                </span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.speakers.edit', $speaker) }}" class="btn btn-sm btn-outline-primary me-1" title="Edit"><i class="ti ti-pencil"></i></a>
                                <form method="POST" action="{{ route('admin.speakers.destroy', $speaker) }}" class="d-inline" onsubmit="return confirm('Delete this speaker?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" title="Delete"><i class="ti ti-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="8" class="text-center text-muted py-4">No speakers found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $speakers->links() }}</div>
        </div>
    </div>
</div>
@endsection
