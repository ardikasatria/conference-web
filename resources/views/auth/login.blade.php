@extends('layouts.base', ['title' => 'Log In'])

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
                    <h4 class="fw-semibold mb-3 fs-18">Log in to your account</h4>
                    <form method="POST" action="{{ route('login') }}" class="text-start mb-3">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" for="example-email">Email</label>
                            <input class="form-control" id="example-email" name="email"
                                placeholder="Enter your email" type="email" required />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="example-password">Password</label>
                            <input class="form-control" id="example-password" name="password" placeholder="Enter your password"
                                type="password" required />
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <div class="form-check">
                                <input class="form-check-input" id="checkbox-signin" name="remember" type="checkbox" />
                                <label class="form-check-label" for="checkbox-signin">Remember me</label>
                            </div>
                            <a class="text-muted border-bottom border-dashed" href="#">Forget
                                Password</a>
                        </div>
                        <div class="d-grid">
                            <button class="btn btn-primary fw-semibold" type="submit">Login</button>
                        </div>
                    </form>
                    <p class="text-muted fs-14 mb-0">Don't have an account?
                        <a class="fw-semibold text-danger ms-1" href="#">Sign Up !</a>
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