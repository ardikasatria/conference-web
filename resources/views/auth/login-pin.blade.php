@extends('layouts.base', ['title' => 'Verify PIN'])

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
                    <h4 class="fw-semibold mb-2 fs-20">Verify PIN</h4>
                    <p class="text-muted mb-4">We sent you a verification code. Please enter it below to continue.</p>

                    @if($errors->any())
                    <div class="alert alert-danger text-start mb-3">
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="text-start mb-3">
                        @csrf
                        <label class="form-label" for="code">Enter 6 Digit Code</label>
                        <div class="d-flex gap-2 mt-1 mb-3">
                            <input class="form-control text-center" name="pin[]" maxlength="1" type="text" required />
                            <input class="form-control text-center" name="pin[]" maxlength="1" type="text" required />
                            <input class="form-control text-center" name="pin[]" maxlength="1" type="text" required />
                            <input class="form-control text-center" name="pin[]" maxlength="1" type="text" required />
                            <input class="form-control text-center" name="pin[]" maxlength="1" type="text" required />
                            <input class="form-control text-center" name="pin[]" maxlength="1" type="text" required />
                        </div>
                        <div class="mb-3 d-grid">
                            <button class="btn btn-primary fw-semibold" type="submit">Continue</button>
                        </div>
                    </form>
                    <p class="text-muted fs-14 mb-0">Back To <a class="fw-semibold text-danger ms-1"
                            href="{{ route('home') }}">Home</a></p>
                </div>
                <p class="mt-4 text-center mb-0">
                    <script>document.write(new Date().getFullYear())</script>
                    &copy; <span class="fw-bold text-uppercase text-reset fs-12">ICSSF</span>
                </p>
            </div>
        </div>
    </div>
@endsection