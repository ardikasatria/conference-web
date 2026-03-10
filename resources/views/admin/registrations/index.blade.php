@extends('layouts.vertical')

@section('title', 'Registrations')

@section('content')
<div class="container-fluid">
    @include('layouts.partials.page-title', ['title' => 'Registrations', 'subtitle' => 'Manage all registrations'])

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show"><i class="ri-check-line me-1"></i> {{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show"><i class="ri-error-warning-line me-1"></i> {{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="header-title mb-0">All Registrations</h4>
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
                        @foreach(['pending', 'confirmed', 'cancelled', 'waitlisted'] as $s)
                            <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Search..." value="{{ request('search') }}">
                    <button class="btn btn-sm btn-outline-primary"><i class="ti ti-search"></i></button>
                </form>
                <a href="{{ route('admin.registrations.create') }}" class="btn btn-primary btn-sm"><i class="ti ti-plus me-1"></i> Add New</a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-centered table-nowrap mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Ticket</th>
                            <th>Participant</th>
                            <th>Conference</th>
                            <th>Package</th>
                            <th>Status</th>
                            <th>Payment Status</th>
                            <th>Registered At</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($registrations as $registration)
                        @php
                            $statusColors = ['pending' => 'warning', 'confirmed' => 'success', 'cancelled' => 'danger', 'waitlisted' => 'info'];
                            $paymentColors = ['unpaid' => 'danger', 'partial' => 'warning', 'paid' => 'success', 'refunded' => 'info'];
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration + ($registrations->currentPage() - 1) * $registrations->perPage() }}</td>
                            <td><code>{{ $registration->ticket_number ?? '-' }}</code></td>
                            <td><strong>{{ $registration->user->name ?? '-' }}</strong></td>
                            <td>{{ $registration->conference->name ?? '-' }}</td>
                            <td>{{ $registration->package->name ?? '-' }}</td>
                            <td>
                                <span class="badge bg-{{ $statusColors[$registration->status] ?? 'secondary' }}">{{ ucfirst($registration->status) }}</span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $paymentColors[$registration->payment_status] ?? 'secondary' }}">{{ ucfirst($registration->payment_status ?? 'unpaid') }}</span>
                            </td>
                            <td>{{ $registration->created_at?->format('d M Y H:i') }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.registrations.edit', $registration) }}" class="btn btn-sm btn-outline-primary me-1" title="Edit"><i class="ti ti-pencil"></i></a>
                                <form method="POST" action="{{ route('admin.registrations.destroy', $registration) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this registration?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" title="Delete"><i class="ti ti-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="9" class="text-center text-muted py-4">No registrations found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $registrations->links() }}</div>
        </div>
    </div>
</div>
@endsection
