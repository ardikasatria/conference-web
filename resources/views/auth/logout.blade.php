@extends('layouts.base', ['title' => 'Log Out'])

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
                        <h3 class="mt-2">See you again !</h3>
                        <p class="text-muted"> You are now successfully sign out. </p>
                    </div>
                    <div class="d-block mt-2">
                        <button class="btn btn-primary fw-semibold" type="submit">Support Center</button>
                    </div>
                    <p class="text-muted fs-14 mt-3 mb-0">
                        Back to <a class="text-danger fw-semibold ms-1" href=""#"">Login !</a>
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