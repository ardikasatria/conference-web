@extends('layouts.base', ['title' => 'Lock Screen'])

@section('content')
    <div class="auth-bg d-flex min-vh-100">
        <div class="row g-0 justify-content-center w-100 m-xxl-5 px-xxl-4 m-3">
            <div class="col-xxl-3 col-lg-5 col-md-6">
                <a class="auth-brand d-flex justify-content-center mb-2" href="{{ route('home') }}">
                    <img alt="dark logo" class="logo-dark" height="26" src="/images/logo-dark.png" />
                    <img alt="logo light" class="logo-light" height="26" src="/images/logo.png" />
                </a>
                <p class="fw-semibold mb-4 text-center text-muted fs-15">ICSSF Conference Management System</p>
                <div class="card overflow-hidden text-center p-xxl-4 p-3 mb-0">
                    <h4 class="fw-semibold mb-4 fs-20">Welcome Back</h4>
                    <div class="text-center">
                        @php $lockUser = auth()->user(); @endphp
                        <div class="avatar-xl rounded-circle img-thumbnail mx-auto d-flex align-items-center justify-content-center bg-primary-subtle" style="width:72px;height:72px;">
                            <span class="fs-24 fw-bold text-primary">{{ $lockUser ? strtoupper(substr($lockUser->name, 0, 2)) : 'U' }}</span>
                        </div>
                        <div class="mt-2 mb-3">
                            <h4 class="fw-semibold">Hi, {{ $lockUser->name ?? 'User' }}!</h4>
                            <p class="mb-0 fst-italic text-muted">Enter your password to continue.</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('login') }}" class="text-start mb-3">
                        @csrf
                        <input type="hidden" name="email" value="{{ $lockUser->email ?? '' }}" />
                        <div class="mb-3">
                            <label class="form-label" for="lock-password">Enter Password</label>
                            <input class="form-control @error('password') is-invalid @enderror" id="lock-password" name="password" placeholder="Password"
                                type="password" required />
                        </div>
                        <div class="mb-2 d-grid">
                            <button class="btn btn-primary fw-semibold" type="submit">Unlock</button>
                        </div>
                    </form>
                    <p class="text-muted fs-14 mb-0">
                        Not you? <a class="fw-semibold text-danger ms-1" href="{{ route('login') }}">Login with another account</a>
                    </p>
                </div>
                <p class="mt-3 text-center mb-0">
                    <script>document.write(new Date().getFullYear())</script>
                    &copy; <span class="fw-bold text-uppercase text-reset fs-12">ICSSF</span>
                </p>
            </div>
        </div>
    </div>
@endsection