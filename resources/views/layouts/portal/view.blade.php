@php
    use Illuminate\Support\Facades\Auth;
    use App\Models\UserRole;
    use App\Models\User;

    if (Auth::check()) {
        $current = User::find(Auth::user()->id);
        $role = UserRole::find($current->user_role_id);
        $picture = $current->profile_image ? asset($current->profile_image) : asset('dashboard/dist/img/my-avatar.png');
        $hasSikhDirectory = App\Models\SikhDirectory::where('user_id', Auth::id())->exists();
        $hasBusinessDirectory = App\Models\BusinessDirectory::where('user_id', Auth::id())->exists();
        $hasJobDirectory = App\Models\JobDirectory::where('user_id', Auth::id())->exists();
        $hasMatrimonialDirectory = App\Models\MatrimonialDirectory::where('user_id', Auth::id())->exists();
        $hasPosts = App\Models\Post::where('user_id', Auth::id())->exists();

        $sikh_slug = App\Models\SikhDirectory::where('user_id', Auth::id())->value('slug');
        $business_slug = App\Models\BusinessDirectory::where('user_id', Auth::id())->value('slug');
        $job_slug = App\Models\JobDirectory::where('user_id', Auth::id())->value('slug');
        $matrimonial_slug = App\Models\MatrimonialDirectory::where('user_id', Auth::id())->value('slug');
    } else {
        $role = null;
        $picture = asset('dashboard/dist/img/my-avatar.png');
        $hasSikhDirectory = false;
        $hasBusinessDirectory = false;
        $hasJobDirectory = false;
        $hasMatrimonialDirectory = false;
        $hasPosts = false;
        $sikh_slug = '';
        $business_slug = '';
        $job_slug = '';
        $matrimonial_slug = '';
    }
@endphp

<!doctype html>
<html class="no-js" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<title>@yield('title', 'ZKNews - Digital Media Hub')</title>

	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="@yield('description', 'Digital Media Hub - Join as Media Partner, Publish Information and Earn for long time....')">
	<meta name="keywords" content="@yield('keywords', 'zknews, zknews.in, news, media, digital, india news, current news, today news, news portal, online, information, publish, partner')">
	<meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Open Graph / Twitter --}}
	<meta property="og:title" content="@yield('og_title', 'ZKNews - Digital Media Hub')" />
	<meta property="og:description" content="@yield('og_description', 'Join Now ...')" />
	<meta property="og:image" content="@yield('og_image', asset('portal/assets/img/logo/logo.png'))" />
	<meta property="og:url" content="@yield('og_url', url()->current())" />
	<meta property="og:type" content="website" />
	<meta name="twitter:card" content="summary_large_image" />
	<meta name="twitter:title" content="@yield('twitter_title', 'ZKNews - Digital Media Hub')" />
	<meta name="twitter:description" content="@yield('twitter_description', 'Join Now ...')" />
	<meta name="twitter:image" content="@yield('twitter_image', asset('portal/assets/img/logo/logo.png'))" />

    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('portal/assets/img/favicon.ico') }}">

    {{-- CSS files from AzNews template (public/portal/assets/...) --}}
    <link rel="stylesheet" href="{{ asset('portal/assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('portal/assets/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('portal/assets/css/ticker-style.css') }}">
    <link rel="stylesheet" href="{{ asset('portal/assets/css/flaticon.css') }}">
    <link rel="stylesheet" href="{{ asset('portal/assets/css/slicknav.css') }}">
    <link rel="stylesheet" href="{{ asset('portal/assets/css/animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('portal/assets/css/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('portal/assets/css/fontawesome-all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('portal/assets/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('portal/assets/css/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('portal/assets/css/nice-select.css') }}">
    <link rel="stylesheet" href="{{ asset('portal/assets/css/style.css') }}">

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

    {{-- JS files (load from public/portal/assets/js) --}}
    <script src="{{ asset('portal/assets/js/vendor/modernizr-3.5.0.min.js') }}"></script>
    <script src="{{ asset('portal/assets/js/vendor/jquery-1.12.4.min.js') }}"></script>
    <script src="{{ asset('portal/assets/js/popper.min.js') }}"></script>
    <script src="{{ asset('portal/assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('portal/assets/js/jquery.slicknav.min.js') }}"></script>

    <script src="{{ asset('portal/assets/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('portal/assets/js/slick.min.js') }}"></script>
    <script src="{{ asset('portal/assets/js/gijgo.min.js') }}"></script>
    <script src="{{ asset('portal/assets/js/wow.min.js') }}"></script>
    <script src="{{ asset('portal/assets/js/animated.headline.js') }}"></script>
    <script src="{{ asset('portal/assets/js/jquery.magnific-popup.js') }}"></script>

    <script src="{{ asset('portal/assets/js/jquery.ticker.js') }}"></script>
    <script src="{{ asset('portal/assets/js/site.js') }}"></script>

    <script src="{{ asset('portal/assets/js/jquery.scrollUp.min.js') }}"></script>
    <script src="{{ asset('portal/assets/js/jquery.nice-select.min.js') }}"></script>
    <script src="{{ asset('portal/assets/js/jquery.sticky.js') }}"></script>

    {{-- contact / form scripts --}}
    <script src="{{ asset('portal/assets/js/contact.js') }}"></script>
    <script src="{{ asset('portal/assets/js/jquery.form.js') }}"></script>
    <script src="{{ asset('portal/assets/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('portal/assets/js/mail-script.js') }}"></script>
    <script src="{{ asset('portal/assets/js/jquery.ajaxchimp.min.js') }}"></script>

    <script src="{{ asset('portal/assets/js/plugins.js') }}"></script>
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
