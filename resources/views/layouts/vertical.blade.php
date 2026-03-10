<!DOCTYPE html>
<html lang="en" @yield('html_attribute')>

<head>
    @include('layouts.partials/title-meta')

    @yield('css')

    <!-- Adminto Admin Theme CSS (pre-built) -->
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

    <!-- Adminto Theme Config -->
    <script src="/build/assets/config-LSYrJu-Q.js"></script>

    <!-- Adminto App JS -->
    <script src="/build/assets/app-C5utzVIa.js"></script>

    @yield('scripts')

</body>

</html>