@php
        $current = App\Models\User::find(Auth::user()->id);
        $role = App\Models\UserRole::find($current->user_role_id);
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
            <span>#</span>
        </div>
    </div>

    <div class="navbar-nav w-100">
        <a href="#"><i class="fa fa-home"></i> Home</a>

        <!-- Sikh Directory -->
        <a class="nav-link" data-bs-toggle="collapse" href="#sikhMenu" role="button">
            <i class="fa fa-user-plus"></i> Sikh Directory
        </a>
        <div class="collapse" id="sikhMenu">
            <a href="#" class="dropdown-item"><i class="fa fa-edit"></i> Edit Your Record</a>
            <a href="#" class="dropdown-item"><i class="fa fa-eye"></i> View Your Record</a>
        </div>

        <!-- Business Directory -->
        <a class="nav-link" data-bs-toggle="collapse" href="#businessMenu" role="button">
            <i class="fa fa-cogs"></i> Business Directory
        </a>
        <div class="collapse" id="businessMenu">
            <a href="#" class="dropdown-item"><i class="fa fa-edit"></i> Edit Your Record</a>
            <a href="#" class="dropdown-item"><i class="fa fa-eye"></i> View Your Record</a>
        </div>

        <!-- Jobs -->
        <a class="nav-link" data-bs-toggle="collapse" href="#jobMenu" role="button">
            <i class="fa fa-cogs"></i> List of Job Seekers
        </a>
        <div class="collapse" id="jobMenu">
            <a href="#" class="dropdown-item"><i class="fa fa-edit"></i> Edit Your Record</a>
            <a href="#" class="dropdown-item"><i class="fa fa-eye"></i> View Your Record</a>
        </div>

        <!-- Shaddi -->
        <a class="nav-link" data-bs-toggle="collapse" href="#matrimonialMenu" role="button">
            <i class="fa fa-cogs"></i> Matrimonial Listing
        </a>
        <div class="collapse" id="matrimonialMenu">
            <a href="#" class="dropdown-item"><i class="fa fa-edit"></i> Edit Your Record</a>
            <a href="#" class="dropdown-item"><i class="fa fa-eye"></i> View Your Record</a>
        </div>
    </div>
</nav>

