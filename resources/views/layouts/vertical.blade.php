<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light" data-layout-mode="fluid" data-topbar-color="light" data-menu-color="dark" data-sidenav-size="default">

<head>
    @include('layouts.partials/title-meta')

    @yield('css')

    <!-- Theme CSS -->
    <link href="/build/assets/app-BuwIqzw9.css" rel="stylesheet">
    <link href="/build/assets/icons-r6laq6CU.css" rel="stylesheet">
</head>

<body>

    <div class="wrapper">

        @include('layouts.partials/sidenav')

        @include('layouts.partials/topbar')

        <div class="page-content">

            <div class="page-container">

                @yield('content')

            </div>

            @include('layouts.partials/footer')
        </div>

    </div>

    @include('layouts.partials/customizer')

    <!-- Theme Config -->
    <script src="/build/assets/config-LSYrJu-Q.js"></script>

    <!-- Theme App JS (ES module - loads jQuery, Bootstrap, Simplebar, etc.) -->
    <script type="module" src="/build/assets/app-C5utzVIa.js"></script>

    @yield('scripts')

</body>

</html>