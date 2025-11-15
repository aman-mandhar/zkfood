@php
    use Illuminate\Support\Facades\Auth;
    use App\Models\UserRole;
    use App\Models\User;

    if (Auth::check()) {
        $current = User::find(Auth::user()->id);
        $role = UserRole::find($current->user_role_id);
    } else {
        $role = null;
    }
@endphp
<!doctype html>
<html class="no-js" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<title>@yield('title', 'ZKFood - Food Hub')</title>

	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="@yield('description', 'Food Hub - Join us and Earn for long time....')">
	<meta name="keywords" content="@yield('keywords', 'food, digital, online, information, publish, partner')">
	<meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Open Graph / Twitter --}}
	<meta property="og:title" content="@yield('og_title', 'ZKFood - Food Hub')" />
	<meta property="og:description" content="@yield('og_description', 'Food Hub - Join us and Earn for long time....')" />
	<meta property="og:image" content="@yield('og_image', asset('portal/assets/img/logo/logo.png'))" />
	<meta property="og:url" content="@yield('og_url', url()->current())" />
	<meta property="og:type" content="website" />
	<meta name="twitter:card" content="summary_large_image" />
	<meta name="twitter:title" content="@yield('twitter_title', 'ZKFood - Food Hub')" />
	<meta name="twitter:description" content="@yield('twitter_description', 'Food Hub - Join us and Earn for long time....')" />
	<meta name="twitter:image" content="@yield('twitter_image', asset('portal/assets/img/logo/logo.png'))" />

    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('portal/assets/img/logo/logo.png') }}">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Nunito:wght@600;700;800&family=Pacifico&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="{{ asset('portal/assets/lib/animate/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('portal/assets/lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="{{ asset('portal/assets/lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css') }}" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{ asset('portal/assets/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="{{ asset('portal/assets/css/style.css') }}" rel="stylesheet">

    {{-- Leaflet --}}
	<link href="https://unpkg.com/leaflet/dist/leaflet.css" rel="stylesheet" />
	<link href="https://unpkg.com/leaflet.markercluster/dist/MarkerCluster.css" rel="stylesheet" />
	<link href="https://unpkg.com/leaflet.markercluster/dist/MarkerCluster.Default.css" rel="stylesheet" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">

    {{-- Livewire --}}
    @livewireStyles

    {{-- Page-specific head injections --}}
    @stack('head')
    @stack('styles')
</head>
<body>
    {{-- Include navigation partial (create this file and paste nav markup from template) --}}
    @includeIf('layouts.portal.nav')

    {{-- Main content from views --}}
    @yield('content')

    {{-- Include footer partial --}}
    @includeIf('layouts.portal.footer')

    <script src="https://code.jquery.com/jquery-3.4.1.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('portal/assets/lib/wow/wow.min.js') }}"></script>
    <script src="{{ asset('portal/assets/lib/easing/easing.min.js') }}"></script>
    <script src="{{ asset('portal/assets/lib/waypoints/waypoints.min.js') }}"></script>
    <script src="{{ asset('portal/assets/lib/counterup/counterup.min.js') }}"></script>
    <script src="{{ asset('portal/assets/lib/owlcarousel/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('portal/assets/lib/tempusdominus/js/moment.min.js') }}"></script>
    <script src="{{ asset('portal/assets/lib/tempusdominus/js/moment-timezone.min.js') }}"></script>
    <script src="{{ asset('portal/assets/lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js') }}"></script>

    <!-- Template Javascript -->
    <script src="{{ asset('portal/assets/js/main.js') }}"></script>

    {{-- Leaflet JS --}}
	<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
	<script src="https://unpkg.com/leaflet.markercluster/dist/leaflet.markercluster.js"></script>
	<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
	<script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>
	<script src="https://unpkg.com/leaflet.locatecontrol/dist/L.Control.Locate.min.js"></script>
	<script src="https://unpkg.com/leaflet-gesture-handling/dist/leaflet-gesture-handling.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    {{-- Livewire scripts --}}
    @livewireScripts

    {{-- Page-specific scripts --}}
    @stack('scripts')
</body>
</html>
