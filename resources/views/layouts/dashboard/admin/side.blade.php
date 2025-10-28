@php
        $current = App\Models\User::find(Auth::user()->id);
        $role = App\Models\UserRole::find($current->user_role);
        $picture = $current->profile_image
            ? asset('storage/' . $current->profile_image)
            : asset('dashboard1/dist/img/my-avatar.png');

@endphp
<nav class="col-lg-2 col-md-3 sidebar" id="sidebarMenu">
    <a href="#" class="navbar-brand mx-4 mb-3">
        <i class="fa fa-tachometer-alt"></i> Dashboard
    </a>

    <div class="d-flex align-items-center ms-4 mb-4">
        <div class="position-relative">
            <img class="rounded-circle" src="{{ $picture }}" alt="{{ Auth::user()->name }} profile image" style="width: 40px; height: 40px;">
            <div class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1"></div>
        </div>
        <div class="ms-3">
            <h6 class="mb-0">{{ Auth::user()->name }}</h6>
            <span>{{ $role->name }}</span>
        </div>
    </div>

    <div class="navbar-nav w-100">
        <a href="{{ route('home') }}"><i class="fa fa-home"></i> Home</a>

        <!-- A -->
        <a class="nav-link" data-bs-toggle="collapse" href="#a" role="button">
            <i class="fa fa-user-plus"></i> A
        </a>
        <div class="collapse" id="a">
            <a href="#" class="dropdown-item"><i class="fa fa-edit"></i> A1</a>
            <a href="#" class="dropdown-item"><i class="fa fa-eye"></i> A2</a>
        </div>

        <!-- B -->
        <a class="nav-link" data-bs-toggle="collapse" href="#b" role="button">
            <i class="fa fa-cogs"></i> B
        </a>
        <div class="collapse" id="b">
            <a href="#" class="dropdown-item"><i class="fa fa-edit"></i> B1</a>
            <a href="#" class="dropdown-item"><i class="fa fa-eye"></i> B2</a>
        </div>
    </div>
</nav>

