@extends('layouts.base', ['title' => 'Confirm Mail'])

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
                    <h4 class="fw-semibold mb-4 fs-20">Verify Your Account</h4>
                    <img alt="img" class="mx-auto d-block" src="/images/png/mail-confirm.png" width="86" />
                    <p class="text-muted fs-14 mt-2">
                        An email has been sent to <b>{{ auth()->user()->email ?? 'your registered email' }}</b>.
                        Please check your inbox and click on the included link to verify your account.
                    </p>
                    <a class="btn d-block btn-primary mt-3" href="{{ route('home') }}">Back to Home</a>
                </div>
                <p class="mt-3 text-center mb-0">
                    <script>document.write(new Date().getFullYear())</script>
                    &copy; <span class="fw-bold text-uppercase text-reset fs-12">ICSSF</span>
                </p>
            </div>
        </div>
    </div>
@endsection