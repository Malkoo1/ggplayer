@extends('layouts.app')

@section('content')
    <div class="nk-content-inner">
        <div class="nk-content-body">
            <div class="nk-block-head nk-block-head-sm">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h3 class="nk-block-title page-title">Assign App</h3>
                        <div class="nk-block-des text-soft">
                            <p>Search and assign apps to your account.</p>
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
                        <div class="card-head">
                            <h5 class="card-title">Your Credit Balance: {{ Auth::user()->credit }}
                            </h5>
                        </div>
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
                        <form action="{{ route('reseller.search_app') }}" method="POST" class="mb-4">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label" for="app_id">App ID</label>
                                        <div class="form-control-wrap">
                                            <input type="text" class="form-control" id="app_id" name="app_id"
                                                placeholder="Enter App ID">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label" for="os">Operating System</label>
                                        <div class="form-control-wrap">
                                            <select class="form-control" id="os" name="os" required>
                                                <option value="">Select OS</option>
                                                <option value="android">Android</option>
                                                <option value="ios">iOS</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">Search</button>
                                </div>
                            </div>
                        </form>

                        @if (isset($apps) && count($apps) > 0)
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>App ID</th>
                                            <th>OS</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($apps as $app)
                                            <tr>
                                                <td>{{ $app->id }}</td>
                                                <td>{{ $app->app_id }}</td>
                                                <td>{{ $app->os }}</td>
                                                <td>{{ $app->status }}</td>
                                                <td>
                                                    <a href="{{ route('reseller.assign_app_details', $app->id) }}"
                                                        class="btn btn-sm btn-primary">
                                                        <em class="icon ni ni-plus"></em>
                                                        <span>Assign</span>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @elseif(isset($apps) && count($apps) == 0)
                            <div class="alert alert-info">
                                No apps found matching your search criteria.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
