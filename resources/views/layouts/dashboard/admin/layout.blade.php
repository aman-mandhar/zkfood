@php
    use Illuminate\Support\Facades\Auth;
    use App\Models\UserRole;
    use Illuminate\Support\Str;

    // Current user (or null)
    $user = Auth::user();

    // Resolve role safely (your users table column seems "user_role")
    $role = null;
    if ($user && !empty($user->user_role)) {
        // If user_role is an ID for UserRole model
        $role = UserRole::find($user->user_role);
        // If instead you store a string like "customer", you can fake a role object:
        if (!$role && is_string($user->user_role)) {
            $role = (object)['slug' => Str::slug($user->user_role), 'name' => $user->user_role];
        }
    }

    // Normalize to your folder names under layouts/dashboard/*
    $roleKey = Str::of(optional($role)->slug ?? optional($role)->name ?? '')
                ->lower()->replace(' ', '')->toString();

    // Map some likely variants to your directories
    $map = [
        'customer'        => 'customer',
        'user'            => 'customer',
        'buyer'           => 'customer',

        'deliverypartner' => 'deliverypartner',
        'rider'           => 'deliverypartner',
        'driver'          => 'deliverypartner',

        'foodvendor'      => 'foodvendor',
        'merchant'        => 'foodvendor',
        'restaurant'      => 'foodvendor',
        'vendor'          => 'foodvendor',
    ];
    $roleViewDir = $map[$roleKey] ?? 'customer'; // default to customer layout

    // Avatar (fallback if none)
    $picture = $user && $user->profile_image
        ? asset($user->profile_image)       // e.g. 'storage/avatars/..' (ensure storage:link)
        : asset('dashboard/dist/img/my-avatar.png');
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    @livewireStyles

    <style>
        body { font-family: 'Segoe UI', sans-serif; }
        .navbar-custom { background-color: #000000; }

        .main-wrapper { display: flex; }

        /* Content Area (right side) */
        .content { flex: 1; width: auto; padding: 20px; }

        /* Sidebar (left side) */
        .sidebar {
            background-color: #ffb52b;
            min-height: 100vh;
            color: #000000;
            width: 220px;
            flex-shrink: 0;
        }
        .sidebar a {
            color: #000d73;
            text-decoration: none;
            display: block;
            padding: 10px 15px;
        }
        .sidebar a:hover,
        .sidebar a.active {
            background-color: #ff6600;
            color: #fff;
        }
        @media (max-width: 768px) {
            .sidebar {
                position: absolute;
                left: -260px;
                width: 250px;
                transition: left 0.3s;
                z-index: 1000;
            }
            .sidebar.show { left: 0; }
        }
    </style>

    @stack('styles')
</head>
<body>

    {{-- Top Nav --}}
    @auth
        {{-- Role-specific nav: layouts/dashboard/{role}/nav.blade.php --}}
        @includeIf("layouts.dashboard.$roleViewDir.nav", ['user' => $user, 'picture' => $picture])
    @else
        {{-- Guest / portal nav --}}
        @includeIf('layouts.portal.nav')
    @endauth

    <div class="container-fluid">
        <div class="row">
            {{-- Sidebar --}}
            @auth
                {{-- Role-specific side: layouts/dashboard/{role}/side.blade.php --}}
                @includeIf("layouts.dashboard.$roleViewDir.side", ['user' => $user, 'picture' => $picture])
            @endauth

            {{-- Content --}}
            <main class="@auth col-lg-10 col-md-9 @else col-12 @endauth ms-sm-auto px-md-4 py-4">
                {{-- Flash alerts (optional shared partial) --}}
                @includeIf('layouts.portal.alert')

                @yield('content')
            </main>
        </div>
    </div>

    {{-- Footer for portal (optional) --}}
    @guest
        @includeIf('layouts.portal.footer')
    @endguest

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @livewireScripts

    <script>
        // Guard against nulls if a page doesn't render the toggle/side IDs
        (function () {
            var toggle = document.getElementById('sidebarToggle');
            var menu   = document.getElementById('sidebarMenu');
            if (toggle && menu) {
                toggle.addEventListener('click', function () {
                    menu.classList.toggle('show');
                });
            }
        })();
    </script>

    @stack('scripts')
</body>
</html>
