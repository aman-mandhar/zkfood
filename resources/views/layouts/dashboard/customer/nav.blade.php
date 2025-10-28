@php
    use Illuminate\Support\Facades\Auth;
    use App\Models\UserRole;
    use App\Models\User;

    $current = User::find(Auth::user()->id);
    $role = UserRole::find($current->user_role_id);
    $picture = $current->profile_image
    ? asset('storage/' . $current->profile_image)
    : asset('dashboard1/dist/img/my-avatar.png');
@endphp
<nav class="navbar navbar-expand-lg navbar-custom">
    <div class="container-fluid">
        <button class="btn btn-outline-light d-lg-none me-2" id="sidebarToggle">
            <i class="fa fa-bars"></i>
        </button>
        <a href="#" class="navbar-brand text-white">
            <img src="{{ asset('images/logo.png') }}" style="height: 60px; background-color: #eefd66;" alt="Logo">
        </a>

        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle text-white" data-bs-toggle="dropdown">
                        <span class="d-none d-lg-inline-flex">{{ $current->name }}</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0">
                        <a href="#" class="dropdown-item">Profile</a>
                        <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="dropdown-item">Logout</button>
                        </form>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>
