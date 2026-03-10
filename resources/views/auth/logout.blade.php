@extends('layouts.base', ['title' => 'Log Out'])

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
                    <h4 class="fw-semibold mb-2 fs-18">You are Logged Out</h4>
                    <div class="text-center">
                        <div class="mt-4">
                            <div class="logout-checkmark">
                                <svg version="1.1" viewbox="0 0 130.2 130.2" xmlns="http://www.w3.org/2000/svg">
                                    <circle class="path circle" cx="65.1" cy="65.1" fill="none" r="62.1"
                                        stroke="#4bd396" stroke-miterlimit="10" stroke-width="6"></circle>
                                    <polyline class="path check" fill="none" points="100.2,40.2 51.5,88.8 29.8,67.5 "
                                        stroke="#4bd396" stroke-linecap="round" stroke-miterlimit="10" stroke-width="6">
                                    </polyline>
                                </svg>
                            </div>
                        </div>
                        <h3 class="mt-2">See you again!</h3>
                        <p class="text-muted">You have been successfully signed out.</p>
                    </div>
                    <div class="d-flex gap-2 mt-2 justify-content-center">
                        <a href="{{ route('login') }}" class="btn btn-primary fw-semibold">Login Again</a>
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary fw-semibold">Go to Homepage</a>
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