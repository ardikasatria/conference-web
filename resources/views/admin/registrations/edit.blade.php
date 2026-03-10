@extends('layouts.vertical')

@section('title', 'Edit Registration')

@section('content')
<div class="container-fluid">
    @include('layouts.partials.page-title', ['title' => 'Edit Registration', 'subtitle' => 'Registration #' . ($registration->ticket_number ?? $registration->id)])

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.registrations.update', $registration) }}">
                @csrf @method('PUT')

                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label class="form-label">Conference <span class="text-danger">*</span></label>
                            <select name="conference_id" class="form-select @error('conference_id') is-invalid @enderror" required>
                                <option value="">Select Conference</option>
                                @foreach($conferences as $conference)
                                    <option value="{{ $conference->id }}" {{ old('conference_id', $registration->conference_id) == $conference->id ? 'selected' : '' }}>{{ $conference->name }}</option>
                                @endforeach
                            </select>
                            @error('conference_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">User <span class="text-danger">*</span></label>
                            <select name="user_id" class="form-select @error('user_id') is-invalid @enderror" required>
                                <option value="">Select User</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id', $registration->user_id) == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                            @error('user_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Package</label>
                            <select name="package_id" class="form-select @error('package_id') is-invalid @enderror">
                                <option value="">Select Package</option>
                                @foreach($packages as $package)
                                    <option value="{{ $package->id }}" {{ old('package_id', $registration->package_id) == $package->id ? 'selected' : '' }}>{{ $package->name }} - Rp {{ number_format($package->price, 0, ',', '.') }}</option>
                                @endforeach
                            </select>
                            @error('package_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" rows="3" class="form-control @error('notes') is-invalid @enderror">{{ old('notes', $registration->notes) }}</textarea>
                            @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror">
                                @foreach(['pending', 'confirmed', 'cancelled', 'waitlisted'] as $s)
                                    <option value="{{ $s }}" {{ old('status', $registration->status) === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                                @endforeach
                            </select>
                            @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Payment Status</label>
                            <select name="payment_status" class="form-select @error('payment_status') is-invalid @enderror">
                                @foreach(['unpaid', 'partial', 'paid', 'refunded'] as $s)
                                    <option value="{{ $s }}" {{ old('payment_status', $registration->payment_status) === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                                @endforeach
                            </select>
                            @error('payment_status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary"><i class="ti ti-check me-1"></i> Update Registration</button>
                    <a href="{{ route('admin.registrations.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
