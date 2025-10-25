@php
        $current = App\Models\User::find(Auth::user()->id);
        $role = App\Models\UserRole::find($current->user_role_id);
        $picture = $current->profile_image
            ? asset('storage/' . $current->profile_image)
            : asset('dashboard1/dist/img/my-avatar.png');

        // Check if user has Sikh Directory record
        $hasSikhDirectory = App\Models\SikhDirectory::where('user_id', Auth::id())->exists();
        // Check if user has Business Directory record
        $hasBusinessDirectory = App\Models\BusinessDirectory::where('user_id', Auth::id())->exists();
        // Check if user has Job Directory record
        $hasJobDirectory = App\Models\JobDirectory::where('user_id', Auth::id())->exists();
        // Check if user has Matrimonial Directory record
        $hasMatrimonialDirectory = App\Models\MatrimonialDirectory::where('user_id', Auth::id())->exists();
@endphp
<nav class="col-lg-2 col-md-3 sidebar" id="sidebarMenu">
    <a href="{{ route('promoter.dashboard') }}" class="navbar-brand mx-4 mb-3">
        <i class="fa fa-tachometer-alt"></i> Dashboard
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
            <i class="fa fa-user-plus"></i> Sikh Directory
        </a>
        <div class="collapse" id="sikhMenu">
            @if(!$hasSikhDirectory)
                <a href="{{ route('sikh.directories.create') }}" class="dropdown-item"><i class="fa fa-plus"></i> Add Your Record</a>
            @else
                <a href="{{ route('sikh.directories.edit', Auth::user()->id) }}" class="dropdown-item"><i class="fa fa-edit"></i> Edit Your Record</a>
                <a href="{{ route('sikh.directories.view', Auth::user()->id) }}" class="dropdown-item"><i class="fa fa-eye"></i> View Your Record</a>
            @endif
        </div>

        <!-- Business Directory -->
        <a class="nav-link" data-bs-toggle="collapse" href="#businessMenu" role="button">
            <i class="fa fa-cogs"></i> Business Directory
        </a>
        <div class="collapse" id="businessMenu">
            @if(!$hasBusinessDirectory)
                <a href="{{ route('business.directories.create') }}" class="dropdown-item"><i class="fa fa-plus"></i> Add Your Record</a>
            @else
                <a href="{{ route('business.directories.edit', Auth::user()->id) }}" class="dropdown-item"><i class="fa fa-edit"></i> Edit Your Record</a>
                <a href="{{ route('business.directories.view', Auth::user()->id) }}" class="dropdown-item"><i class="fa fa-eye"></i> View Your Record</a>
            @endif
        </div>

        <!-- Jobs -->
        <a class="nav-link" data-bs-toggle="collapse" href="#jobMenu" role="button">
            <i class="fa fa-cogs"></i> List of Job Seekers
        </a>
        <div class="collapse" id="jobMenu">
            @if(!$hasJobDirectory)
                <a href="{{ route('job.directories.create') }}" class="dropdown-item"><i class="fa fa-plus"></i> Add Your Record</a>
            @else
                <a href="{{ route('job.directories.edit', $job_slug) }}" class="dropdown-item"><i class="fa fa-edit"></i> Edit Your Record</a>
                <a href="{{ route('job.directories.show', $job_slug) }}" class="dropdown-item"><i class="fa fa-eye"></i> View Your Record</a>
            @endif
        </div>

        <!-- Shaddi -->
        <a class="nav-link" data-bs-toggle="collapse" href="#matrimonialMenu" role="button">
            <i class="fa fa-cogs"></i> Matrimonial Listing
        </a>
        <div class="collapse" id="matrimonialMenu">
            @if(!$hasMatrimonialDirectory)
                <a href="{{ route('matrimonial.directories.create') }}" class="dropdown-item"><i class="fa fa-plus"></i> Add Your Record</a>
            @else
                <a href="{{ route('matrimonial.directories.edit', $matrimonial_slug) }}" class="dropdown-item"><i class="fa fa-edit"></i> Edit Your Record</a>
                <a href="{{ route('matrimonial.directories.show', $matrimonial_slug) }}" class="dropdown-item"><i class="fa fa-eye"></i> View Your Record</a>
            @endif
        </div>

        <!-- Kesari Virasat News -->
        <a class="nav-link" data-bs-toggle="collapse" href="#newsMenu" role="button">
            <i class="fa fa-newspaper"></i> Kesari Virasat News
        </a>
        <div class="collapse" id="newsMenu">
            <a href="{{ route('posts.all') }}" class="dropdown-item"><i class="fa fa-newspaper-o"></i> All Posts</a>
            <a href="{{ route('posts.create') }}" class="dropdown-item"><i class="fa fa-plus"></i> Add New Post</a>
        </div>
    </div>
</nav>

