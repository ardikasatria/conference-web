@extends('layouts.base', ['title' => 'Confirm Mail'])

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
                    <h4 class="fw-semibold mb-4 fs-20">Verify Your Account</h4>
                    <img alt="img" class="mx-auto d-block" src="/images/png/mail-confirm.png" width="86" />
                    <p class="text-muted fs-14 mt-2"> A email has been send to <b>youremail@domain.com</b>.
                        Please check for an email from company and click on the included link to
                        reset your password. </p>
                    <a class="btn d-block btn-primary mt-3" href="/">Back to
                        Home</a>
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