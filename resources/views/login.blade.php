@extends('layouts.portal.view')
@section('content')

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header fw-bold text-center">{{ __('Login to ZK Superstore') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        {{-- Mobile Number --}}
                        <div class="mb-3 row">
                            <label for="mobile_number" class="col-md-4 col-form-label text-md-end">Mobile Number</label>
                            <div class="col-md-6">
                                <input id="mobile_number" type="text"
                                       class="form-control @error('mobile_number') is-invalid @enderror"
                                       name="mobile_number" value="{{ old('mobile_number') }}" required autofocus maxlength="10" pattern="\d{10}">
                                @error('mobile_number')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- Password --}}
                        <div class="mb-3 row">
                            <label for="password" class="col-md-4 col-form-label text-md-end">Password</label>
                            <div class="col-md-6">
                                <input id="password" type="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       name="password" required>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- Remember Me --}}
                        <div class="mb-3 row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="remember">
                                        Remember Me
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- Submit Button --}}
                        <div class="mb-0 row">
                            <div class="col-md-8 offset-md-4 d-flex align-items-center gap-2">
                                <button type="submit" class="btn btn-success px-4">
                                    Login
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link text-decoration-none" href="{{ route('password.request') }}">
                                        Forgot Password?
                                    </a>
                                @endif
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
