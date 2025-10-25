@php
    use Illuminate\Support\Facades\Auth;
    use App\Models\UserRole;
    use App\Models\User;

    $current = User::find(Auth::user()->id);
    $role = UserRole::find($current->user_role_id);
    $picture = $current->profile_image ? asset($current->profile_image) : asset('dashboard/dist/img/my-avatar.png');
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
        body {
            font-family: 'Segoe UI', sans-serif;
        }
        .navbar-custom {
            background-color: #000000;
        }
        .main-wrapper {
            display: flex;
        }

        /* Sidebar (left side) */


        /* Content Area (right side) */
        .content {
            flex: 1;         /* take remaining space */
            width: auto;    /* prevent overflow */
            padding: 20px;
        }
        .sidebar {
            background-color: #ffb52b;
            min-height: 100vh;
            color: #000000;
            width: 220px;   /* fix width for sidebar */
            flex-shrink: 0; /* prevent shrinking */
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
            .sidebar.show {
                left: 0;
            }
        }
    </style>

    @stack('styles')
</head>
<body>

    @include('layouts.dashboard.admin.nav')
    <div class="container-fluid">
        <div class="row">
            @include('layouts.dashboard.admin.side')
            <main class="col-lg-10 col-md-9 ms-sm-auto px-md-4 py-4">
                @yield('content')
            </main>
        </div>
    </div>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @livewireScripts

    <script>
        document.getElementById('sidebarToggle').addEventListener('click', function () {
            document.getElementById('sidebarMenu').classList.toggle('show');
        });
    </script>

    @stack('scripts')
</body>
</html>
