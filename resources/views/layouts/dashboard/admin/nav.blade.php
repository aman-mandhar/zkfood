@php
    use Illuminate\Support\Facades\Auth;

    // Prefer data passed from layout: $user, $picture
    $user = $user ?? Auth::user();

    // Avatar fallback
    $picture = $picture
        ?? ($user && $user->profile_image
            ? (str_starts_with($user->profile_image, 'http') ? $user->profile_image : asset($user->profile_image))
            : asset('dashboard/dist/img/my-avatar.png')); // adjust path if needed
@endphp

<nav class="navbar navbar-expand-lg navbar-custom">
    <div class="container-fluid">
        {{-- Mobile sidebar toggle --}}
        <button class="btn btn-outline-light d-lg-none me-2" id="sidebarToggle" type="button" aria-label="Toggle sidebar">
            <i class="fa fa-bars"></i>
        </button>

        {{-- Brand --}}
        <a href="{{ route('admin.dashboard') }}" class="navbar-brand d-flex align-items-center text-white">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" style="height:56px; background:#eefd66;">
            <span class="ms-2 fw-semibold">Admin</span>
        </a>

        {{-- Navbar collapse (right side) --}}
        <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavCollapse" aria-controls="adminNavCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div id="adminNavCollapse" class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto align-items-center">

                {{-- (Optional) Quick links
                <li class="nav-item">
                    <a class="nav-link text-white" href="{{ route('admin.orders.index') }}">Orders</a>
                </li>
                --}}

                {{-- User dropdown --}}
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="{{ $picture }}" alt="Avatar" class="rounded-circle me-2" style="width:34px;height:34px;object-fit:cover;">
                        <span class="d-none d-lg-inline text-white">{{ $user?->name ?? 'User' }}</span>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="{{ route('profile') }}">Profile</a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST" class="px-3 py-1">
                                @csrf
                                <button type="submit" class="btn btn-link dropdown-item px-0">Logout</button>
                            </form>
                        </li>
                    </ul>
                </li>

            </ul>
        </div>
    </div>
</nav>
