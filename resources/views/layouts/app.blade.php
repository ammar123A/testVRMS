<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>VRMS - Vehicle Reservation Management System</title>

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Your Custom CSS --}}
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/system.css') }}">
    <link rel="stylesheet" href="{{ asset('css/jquery-ui-1.8.16.custom.css') }}">
    <link rel="stylesheet" href="{{ asset('css/jQuerySlideMenu.css') }}">
    <link rel="stylesheet" href="{{ asset('css/date.css') }}">
    <link rel="stylesheet" href="{{ asset('css/reports.css') }}">

    {{-- jQuery --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/jquery-ui-1.8.16.custom.min.js') }}"></script>
    <script src="{{ asset('js/jQuerySlideMenu.js') }}"></script>
    <script src="{{ asset('js/jquery.json-2.3.js') }}"></script>
    <script src="{{ asset('js/date.js') }}"></script>
    <script src="{{ asset('js/javascript.js') }}"></script>

    {{-- Bootstrap JS Bundle --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body style="background-color:#f8f9fa;" onload="updateClock(); setInterval('updateClock()', 1000);">

    <header class="bg-white border-bottom py-3 px-4 mb-4 shadow-sm d-flex align-items-center">
        <img src="{{ asset('images/utm.png') }}" height="60" alt="UTM Logo" class="me-3">
        <div>
            <h4 class="mb-0">VEHICLE RESERVATION MANAGEMENT SYSTEM</h4>
            <small class="text-muted">Ver 2.0</small>
        </div>
    </header>

    {{-- Navigation --}}
    @include('partials.navbar')

    {{-- Main Content --}}
    <main class="container my-4">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-light text-center py-3 mt-4 border-top">
        &copy; {{ date('Y') }} Universiti Teknologi Malaysia — <span class="text-muted">Ver 2.0</span>
    </footer>

</body>
</html>
