@php
    use Illuminate\Support\Facades\Auth;
    use App\Models\UserRole;
    use App\Models\User;

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
        .sidebar {
            background-color: #446521;
            min-height: 100vh;
            color: #e1ff7f;
        }
        .sidebar a {
            color: #ccc;
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

    @include('layouts.dashboard.promotor.nav')
    <div class="container-fluid">
        <div class="row">
            @include('layouts.dashboard.promotor.side')

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
