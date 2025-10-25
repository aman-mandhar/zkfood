@php
        $current = App\Models\User::find(Auth::user()->id);
        $role = App\Models\UserRole::find($current->user_role_id);
        $picture = $current->profile_image
@endphp
<nav class="col-lg-2 col-md-3 sidebar" id="sidebarMenu">
    <a href="{{ route('admin.dashboard') }}" class="navbar-brand mx-4 mb-3">
        <i class="fa-solid fa-gauge"></i> Dashboard
    </a>

    <div class="d-flex align-items-center ms-4 mb-4">
        <div class="position-relative">
            <div class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1"></div>
        </div>
        <div class="ms-3">
            <h6 class="mb-0">{{ Auth::user()->name }}</h6>
            @if($role)
                <span>{{ $role->name }}</span>
            @endif
        </div>
    </div>

    <div class="navbar-nav w-100">
        <a href="{{ route('welcome') }}"><i class="fa fa-home"></i> Home</a>

        <!-- Sikh Directory -->
        <a class="nav-link" data-bs-toggle="collapse" href="#sikhMenu" role="button">
            <i class="fa fa-solid fa-briefcase"></i> Vacancies
        </a>
        <div class="collapse" id="sikhMenu">
            <a href="#" class="dropdown-item"><i class="fa fa-plus"></i> Add New</a>
            <a href="#" class="dropdown-item"><i class="fa fa-list"></i> Category</a>
            <a href="#" class="dropdown-item"><i class="fa fa-list-alt"></i> Sub-Category</a>
            <a href="#" class="dropdown-item"><i class="fa fa-list-ol"></i> Sub-Sub-Category</a>
        </div>

        <!-- Business Directory -->
        <a class="nav-link" data-bs-toggle="collapse" href="#businessMenu" role="button">
            <i class="fa fa-solid fa-store"></i> Vendor Directory
        </a>
        <div class="collapse" id="businessMenu">
            <a href="#" class="dropdown-item"><i class="fa fa-plus"></i> Add New</a>
            <a href="#" class="dropdown-item"><i class="fa fa-list"></i> Category</a>
            <a href="#" class="dropdown-item"><i class="fa fa-list-alt"></i> Sub-Category</a>
            <a href="#" class="dropdown-item"><i class="fa fa-list-ol"></i> Sub-Sub-Category</a>
        </div>

        <!-- Jobs -->
        <a class="nav-link" data-bs-toggle="collapse" href="#jobMenu" role="button">
            <i class="fa-solid fa-user"></i> Job Seekers
        </a>
        <div class="collapse" id="jobMenu">
            <a href="#" class="dropdown-item"><i class="fa fa-plus"></i> Add New</a>
            <a href="#" class="dropdown-item"><i class="fa fa-list"></i> Category</a>
            <a href="#" class="dropdown-item"><i class="fa fa-list-alt"></i> Sub-Category</a>
            <a href="#" class="dropdown-item"><i class="fa fa-list-ol"></i> Sub-Sub-Category</a>
        </div>

        <!-- Shaddi -->
        <a class="nav-link" data-bs-toggle="collapse" href="#matrimonialMenu" role="button">
            <i class="fa fa-solid fa-heart"></i> Matrimonial Listing
        </a>
        <div class="collapse" id="matrimonialMenu">


        <!-- Kesari Virasat News -->
        <a class="nav-link" data-bs-toggle="collapse" href="#newsMenu" role="button">
            <i class="fa fa-newspaper"></i> News & Articles
        </a>
        <div class="collapse" id="newsMenu">

        </div>

        <a href="{{ route('admin.change-role') }}" class="nav-link">
            <i class="fa fa-users"></i> Change User Role
        </a>
    </div>
</nav>

