@extends('layouts.vertical')

@section('title', 'Sessions')

@section('content')
<div class="container-fluid">
    @include('layouts.partials.page-title', ['title' => 'Sessions', 'subtitle' => 'Manage all sessions'])

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show"><i class="ri-check-line me-1"></i> {{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show"><i class="ri-error-warning-line me-1"></i> {{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="header-title mb-0">All Sessions</h4>
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
                <a href="{{ route('admin.sessions.create') }}" class="btn btn-primary btn-sm"><i class="ti ti-plus me-1"></i> Add New</a>
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
                            <th>Time</th>
                            <th>Room</th>
                            <th>Capacity</th>
                            <th>Status</th>
                            <th>Speakers</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sessions as $session)
                        <tr>
                            <td>{{ $loop->iteration + ($sessions->currentPage() - 1) * $sessions->perPage() }}</td>
                            <td><strong>{{ $session->title }}</strong></td>
                            <td>{{ $session->conference->name ?? '-' }}</td>
                            <td>
                                {{ $session->start_time?->format('d M Y H:i') }}
                                @if($session->end_time)<br><small class="text-muted">to {{ $session->end_time->format('H:i') }}</small>@endif
                            </td>
                            <td>{{ $session->room ?? '-' }}</td>
                            <td>{{ $session->capacity ?? '-' }}</td>
                            <td>
                                @php
                                    $statusColors = ['scheduled' => 'primary', 'ongoing' => 'info', 'completed' => 'success', 'cancelled' => 'danger'];
                                @endphp
                                <span class="badge bg-{{ $statusColors[$session->status] ?? 'secondary' }}">{{ ucfirst($session->status) }}</span>
                            </td>
                            <td><span class="badge bg-soft-primary text-primary">{{ $session->speakers_count ?? $session->speakers->count() }}</span></td>
                            <td class="text-end">
                                <a href="{{ route('admin.sessions.edit', $session) }}" class="btn btn-sm btn-outline-primary me-1" title="Edit"><i class="ti ti-pencil"></i></a>
                                <form method="POST" action="{{ route('admin.sessions.destroy', $session) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this session?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" title="Delete"><i class="ti ti-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="9" class="text-center text-muted py-4">No sessions found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $sessions->links() }}</div>
        </div>
    </div>
</div>
@endsection
