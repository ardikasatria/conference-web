@extends('layouts.vertical')

@section('title', 'My Payments')

@section('content')
<div class="container-fluid">
    @include('layouts.partials.page-title', ['title' => 'My Payments', 'subtitle' => 'View your payment history and status'])

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show"><i class="ri-check-line me-1"></i> {{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="header-title mb-0">Payment History</h4>
            <form method="GET" class="d-flex gap-2">
                <select name="status" class="form-select form-select-sm" style="width:160px" onchange="this.form.submit()">
                    <option value="">All Status</option>
                    @foreach(['pending','paid','confirmed','failed','refunded'] as $s)
                        <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
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
                            <th>Invoice</th>
                            <th>Conference</th>
                            <th>Package</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Due Date</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                        <tr>
                            <td>{{ $loop->iteration + ($payments->currentPage() - 1) * $payments->perPage() }}</td>
                            <td><strong>{{ $payment->payment_invoice_number ?? '-' }}</strong></td>
                            <td>{{ $payment->conference->name ?? '-' }}</td>
                            <td>{{ $payment->package->name ?? '-' }}</td>
                            <td>Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                            <td>
                                @php
                                    $statusColors = ['pending' => 'warning', 'paid' => 'info', 'confirmed' => 'success', 'failed' => 'danger', 'refunded' => 'secondary'];
                                @endphp
                                <span class="badge bg-{{ $statusColors[$payment->status] ?? 'secondary' }}">
                                    {{ ucfirst($payment->status) }}
                                </span>
                            </td>
                            <td>{{ $payment->due_date?->format('d M Y') ?? '-' }}</td>
                            <td class="text-end">
                                @if(in_array($payment->status, ['pending', 'failed']))
                                    <button class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#uploadProof{{ $payment->id }}" title="Upload Proof">
                                        <i class="ti ti-upload"></i> Upload Proof
                                    </button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="8" class="text-center text-muted py-4">No payments found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $payments->links() }}</div>
        </div>
    </div>
</div>

{{-- Upload Proof Modals --}}
@foreach($payments as $payment)
@if(in_array($payment->status, ['pending', 'failed']))
<div class="modal fade" id="uploadProof{{ $payment->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('participant.payments.upload-proof', $payment) }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Upload Payment Proof</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted mb-3">Amount: <strong>Rp {{ number_format($payment->amount, 0, ',', '.') }}</strong></p>

                    <div class="mb-3">
                        <label class="form-label">Bank Name <span class="text-danger">*</span></label>
                        <input type="text" name="bank_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Sender Name <span class="text-danger">*</span></label>
                        <input type="text" name="sender_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Reference Number</label>
                        <input type="text" name="reference_number" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Amount Transferred <span class="text-danger">*</span></label>
                        <input type="number" name="amount_transferred" class="form-control" step="0.01" value="{{ $payment->amount }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Proof Image <span class="text-danger">*</span></label>
                        <input type="file" name="proof_image" class="form-control" accept=".jpg,.jpeg,.png,.pdf" required>
                        <small class="text-muted">JPG, PNG, or PDF. Max 5MB.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" rows="2" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="ti ti-upload me-1"></i> Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endforeach
@endsection
