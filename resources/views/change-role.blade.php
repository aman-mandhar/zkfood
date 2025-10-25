@extends('layouts.dashboard.admin.layout') {{-- adjust your layout as needed --}}

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Change User Role</h2>

    {{-- Search Form --}}
    <form action="{{ route('admin.search-user') }}" method="GET" class="mb-4">
        <div class="input-group">
            <input type="text" name="query" class="form-control" placeholder="Search by name or mobile"
                   value="{{ request('query') }}">
        </div>
        <div class="input-group mt-2">
            <button class="btn btn-primary" type="submit">Search</button>
        </div>
        @if(request('query'))
            <div class="mt-2">
                <a href="{{ route('admin.change-role') }}" class="btn btn-secondary">Clear Search</a>
            </div>
        @endif
    </form>

    {{-- Users Table --}}
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Mobile</th>
                <th>Current Role</th>
                <th>Change Role</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $index => $user)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->mobile }}</td>
                    <td>{{ $user->userRole->name ?? 'N/A' }}</td>
                    <td>
                        <form action="{{ route('admin.change-user-role', $user->id) }}" method="POST">
                            @csrf
                            <div class="input-group">
                                <select name="user_role_id" class="form-select">
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}" {{ $user->user_role_id == $role->id ? 'selected' : '' }}>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <button class="btn btn-success btn-sm" type="submit">Update</button>
                            </div>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">No users found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
