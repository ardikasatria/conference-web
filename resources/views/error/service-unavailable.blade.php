@extends('layouts.base', ['title' => 'Service Unavailable'])

@section('content')
    <div class="auth-bg d-flex min-vh-100">
        <div class="row g-0 justify-content-center w-100 m-xxl-5 px-xxl-4 m-3">
            <div class="col-xxl-3 col-lg-5 col-md-6">
                <a class="auth-brand d-flex justify-content-center mb-2" href="{{ route('home') }}">
                    <img alt="ICSSF" class="logo-dark" height="26" src="/images/logo-dark.png" />
                    <img alt="ICSSF" class="logo-light" height="26" src="/images/logo.png" />
                </a>
                <p class="fw-semibold mb-4 text-center text-muted fs-15">ICSSF Conference Management System</p>
                <div class="card overflow-hidden text-center p-xxl-4 p-3 mb-0">
                    <div class="text-center">
                        <h4 class="text-error fs-36">Service Unavailable</h4>
                        <h3 class="my-2">This site is temporarily down for maintenance.</h3>
                        <p class="text-muted mb-3">The server is currently unable to handle the request. Please try again later.</p>
                        <a class="btn btn-danger" href="{{ route('home') }}">
                            <i class="ti ti-home fs-16 me-1"></i> Back to Home
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