@extends('layouts.base', ['title' => 'Reset Password'])

@section('content')
    <div class="auth-bg d-flex min-vh-100">
        <div class="row g-0 justify-content-center w-100 m-xxl-5 px-xxl-4 m-3">
            <div class="col-xxl-3 col-lg-5 col-md-6">
                <a class="auth-brand d-flex justify-content-center mb-2" href="/">
                    <img alt="dark logo" class="logo-dark" height="26" src="/images/logo-dark.png" />
                    <img alt="logo light" class="logo-light" height="26" src="/images/logo.png" />
                </a>
                <p class="fw-semibold mb-4 text-center text-muted fs-15">Admin Panel Design by Coderthemes</p>
                <div class="card overflow-hidden text-center p-xxl-4 p-3 mb-0">
                    <h4 class="fw-semibold mb-3 fs-18">Reset Your Password</h4>
                    <p class="text-muted mb-4">Enter your email address and we'll send you an email with instructions to
                        reset your password.</p>
                    <form action="/" class="text-start mb-3">
                        <div class="mb-3">
                            <label class="form-label" for="example-email">Email</label>
                            <input class="form-control" id="example-email" name="example-email"
                                placeholder="Enter your email" type="email" />
                        </div>
                        <div class="d-grid">
                            <button class="btn btn-primary fw-semibold" type="submit">Reset Password</button>
                        </div>
                    </form>
                    <p class="text-muted fs-14 mb-0">
                        Back To <a class="fw-semibold text-danger ms-1" href="{{ route ('second' , ['auth','login']) }}">Login !</a>
                    </p>
                </div>
                <p class="mt-4 text-center mb-0">
                    <script>
                        document.write(new Date().getFullYear())
                    </script> © Adminto - By <span
                        class="fw-bold text-decoration-underline text-uppercase text-reset fs-12">Coderthemes</span>
                </p>
            </div>
        </div>
    </div>

    
@endsection