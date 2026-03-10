@extends('layouts.base', ['title' => 'Lock Screen'])

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
                    <h4 class="fw-semibold mb-4 fs-20">Welcome Back</h4>
                    <div class="text-center">
                        <img alt="" class="avatar-xl rounded-circle img-thumbnail"
                            src="/images/users/avatar-1.jpg" />
                        <div class="mt-2 mb-3">
                            <h4 class="fw-semibold">Hi ! Nowak Helme.</h4>
                            <p class="mb-0 fst-italic text-muted">Enter your password to access the admin.</p>
                        </div>
                    </div>
                    <form action="/" class="text-start mb-3">
                        <div class="mb-3">
                            <label class="form-label" for="lock-password">Enter Password</label>
                            <input class="form-control" id="lock-password" name="lock-password" placeholder="Password"
                                type="password" />
                        </div>
                        <div class="mb-2 d-grid">
                            <button class="btn btn-primary fw-semibold" type="submit">Access to Screen</button>
                        </div>
                    </form>
                    <p class="text-muted fs-14 mb-0">
                        Not you? return <a class="fw-semibold text-danger ms-1" href="{{ route ('second' , ['auth','login']) }}">Login !</a>
                    </p>
                </div>
                <p class="mt-3 text-center mb-0">
                    <script>
                        document.write(new Date().getFullYear())
                    </script> © Adminto - By <span
                        class="fw-bold text-decoration-underline text-uppercase text-reset fs-12">Coderthemes</span>
                </p>
            </div>
        </div>
    </div>
    
@endsection