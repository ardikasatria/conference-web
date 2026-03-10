@extends('layouts.vertical')

@section('title', 'Payments')

@section('content')
<div class="container-fluid">
    @include('layouts.partials.page-title', ['title' => 'Payments', 'subtitle' => 'Manage all payments'])

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show"><i class="ri-check-line me-1"></i> {{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show"><i class="ri-error-warning-line me-1"></i> {{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="header-title mb-0">All Payments</h4>
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
                        @foreach(['pending', 'paid', 'confirmed', 'failed', 'refunded'] as $s)
                            <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
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
                            <th>Invoice</th>
                            <th>User</th>
                            <th>Conference</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Due Date</th>
                            <th>Paid At</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                        @php
                            $statusColors = ['pending' => 'warning', 'paid' => 'success', 'confirmed' => 'success', 'failed' => 'danger', 'refunded' => 'info'];
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration + ($payments->currentPage() - 1) * $payments->perPage() }}</td>
                            <td><code>{{ $payment->invoice_number ?? '-' }}</code></td>
                            <td><strong>{{ $payment->user->name ?? '-' }}</strong></td>
                            <td>{{ $payment->conference->name ?? '-' }}</td>
                            <td>Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                            <td>
                                <form method="POST" action="{{ route('admin.payments.update', $payment) }}" class="d-inline-flex gap-1">
                                    @csrf @method('PUT')
                                    <select name="status" class="form-select form-select-sm" style="width: auto; min-width: 110px;">
                                        @foreach(['pending', 'paid', 'confirmed', 'failed', 'refunded'] as $s)
                                            <option value="{{ $s }}" {{ $payment->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="btn btn-sm btn-outline-primary" title="Update Status"><i class="ti ti-check"></i></button>
                                </form>
                            </td>
                            <td>{{ $payment->due_date?->format('d M Y') ?? '-' }}</td>
                            <td>{{ $payment->paid_at?->format('d M Y H:i') ?? '-' }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.payments.show', $payment) }}" class="btn btn-sm btn-outline-info me-1" title="View"><i class="ti ti-eye"></i></a>
                                <form method="POST" action="{{ route('admin.payments.destroy', $payment) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this payment?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" title="Delete"><i class="ti ti-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="9" class="text-center text-muted py-4">No payments found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $payments->links() }}</div>
        </div>
    </div>
</div>
@endsection
