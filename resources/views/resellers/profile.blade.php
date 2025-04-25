@extends('layouts.app')

@section('content')
    <div class="nk-content-inner">
        <div class="nk-content-body">
            <div class="nk-block-head nk-block-head-sm">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h3 class="nk-block-title page-title">Update Profile</h3>
                        <div class="nk-block-des text-soft">
                            <p>Update your profile information.</p>
                        </div>
                    </div>
                    <div class="nk-block-head-content">
                        <div class="toggle-wrap nk-block-tools-toggle">
                            <a href="{{ route('reseller.dashboard') }}" class="btn btn-outline-primary">
                                <em class="icon ni ni-arrow-left"></em>
                                <span>Back to Dashboard</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="nk-block">
                <div class="card card-bordered">
                    <div class="card-inner">
                        @if (Session::has('success'))
                            <div class="alert alert-success mt-4">
                                <strong>{{ Session::get('success') }}</strong>
                            </div>
                        @endif
                        @if (Session::has('fail'))
                            <div class="alert alert-danger mt-4">
                                <strong>{{ Session::get('fail') }}</strong>
                            </div>
                        @endif
                        <form action="{{ route('reseller.update_profile') }}" method="POST">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label" for="name">Name</label>
                                        <div class="form-control-wrap">
                                            <input type="text" class="form-control" id="name" name="name"
                                                value="{{ auth()->user()->name }}" required>
                                            @error('name')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label" for="email">Email</label>
                                        <div class="form-control-wrap">
                                            <input type="email" class="form-control" id="email" name="email"
                                                value="{{ auth()->user()->email }}" disabled>
                                            <small class="text-muted">Email cannot be changed</small>
                                            @error('email')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label" for="password">Password</label>
                                        <div class="form-control-wrap">
                                            <input type="password" class="form-control" id="password" name="password"
                                                placeholder="Leave blank to keep current password">
                                            @error('password')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label" for="password_confirmation">Confirm Password</label>
                                        <div class="form-control-wrap">
                                            <input type="password" class="form-control" id="password_confirmation"
                                                name="password_confirmation" placeholder="Confirm new password">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">Update Profile</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
