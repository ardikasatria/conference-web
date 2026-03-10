@extends('layouts.vertical')

@section('title', 'My Profile')

@section('content')
<div class="container-fluid">
    @include('layouts.partials.page-title', ['title' => 'My Profile', 'subtitle' => 'Account Settings'])

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="ri-check-line me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="ri-error-warning-line me-1"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        {{-- Profile Info Card --}}
        <div class="col-lg-4">
            <div class="card text-center">
                <div class="card-body">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center bg-primary bg-gradient text-white mx-auto mb-3" style="width:90px;height:90px;">
                        <span class="fw-bold" style="font-size:32px;">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                    </div>

                    <h4 class="mb-1">{{ $user->name }}</h4>
                    <p class="text-muted mb-2">{{ $user->email }}</p>

                    @if($user->roles->isNotEmpty())
                        <div class="mb-3">
                            @foreach($user->roles as $role)
                                <span class="badge bg-{{ $role->name === 'admin' ? 'danger' : ($role->name === 'reviewer' ? 'info' : 'success') }} bg-soft-{{ $role->name === 'admin' ? 'danger' : ($role->name === 'reviewer' ? 'info' : 'success') }}">
                                    {{ ucfirst($role->name) }}
                                </span>
                            @endforeach
                        </div>
                    @endif

                    <div class="text-muted">
                        <p class="mb-1"><i class="ri-calendar-line me-1"></i> Joined {{ $user->created_at->format('d M Y') }}</p>
                        @if($user->email_verified_at)
                            <p class="mb-0 text-success"><i class="ri-checkbox-circle-line me-1"></i> Email Verified</p>
                        @else
                            <p class="mb-0 text-warning"><i class="ri-alert-line me-1"></i> Email Not Verified</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Quick Stats --}}
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Account Summary</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="ri-user-line me-2 text-primary"></i> Full Name</span>
                            <span class="fw-semibold">{{ $user->name }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="ri-mail-line me-2 text-primary"></i> Email</span>
                            <span class="fw-semibold">{{ $user->email }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="ri-shield-user-line me-2 text-primary"></i> Role(s)</span>
                            <span class="fw-semibold">
                                {{ $user->roles->pluck('name')->map(fn($r) => ucfirst($r))->join(', ') ?: 'Participant' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Edit Forms --}}
        <div class="col-lg-8">
            {{-- Update Profile --}}
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="ri-user-settings-line me-1"></i> Update Profile</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $user->name) }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email', $user->email) }}" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-save-line me-1"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Change Password --}}
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="ri-lock-password-line me-1"></i> Change Password</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('profile.password') }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="current_password" class="form-label">Current Password <span class="text-danger">*</span></label>
                            <input type="password" name="current_password" id="current_password"
                                class="form-control @error('current_password') is-invalid @enderror" required>
                            @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password" class="form-label">New Password <span class="text-danger">*</span></label>
                                    <input type="password" name="password" id="password"
                                        class="form-control @error('password') is-invalid @enderror" required>
                                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">Confirm New Password <span class="text-danger">*</span></label>
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                        class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-warning">
                                <i class="ri-lock-line me-1"></i> Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
