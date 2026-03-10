@extends('layouts.base', ['title' => 'Unauthorized'])

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
                    <div class="text-center">
                        <h1 class="text-error">401</h1>
                        <h3 class="mt-3 mb-2">Unauthorized</h3>
                        <p class="text-muted mb-3">You need to be logged in to access this page. Please log in and try again.</p>
                        <a class="btn btn-danger" href="{{ route('login') }}">
                            <i class="ti ti-login fs-16 me-1"></i> Log In
                        </a>
                    </div>
                </div>
                <p class="mt-4 text-center mb-0">
                    <script>document.write(new Date().getFullYear())</script>
                    &copy; <span class="fw-bold text-uppercase text-reset fs-12">ICSSF</span>
                </p>
            </div>
        </div>
    </div>
@endsection