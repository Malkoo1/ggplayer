@extends('layouts.app')

@section('content')
    <div class="nk-content-inner">
        <div class="nk-content-body">
            <div class="nk-block-head nk-block-head-sm">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h3 class="nk-block-title page-title">Reseller Details</h3>
                        <div class="nk-block-des text-soft">
                            <p>View and manage reseller information</p>
                        </div>
                    </div>
                    <div class="nk-block-head-content">
                        <div class="toggle-wrap nk-block-tools-toggle">
                            <a href="#" class="btn btn-icon btn-trigger toggle-expand me-n1"><em
                                    class="icon ni ni-more-v"></em></a>
                            <div class="toggle-expand-content">
                                <ul class="nk-block-tools g-3">
                                    <li>
                                        <a href="{{ route('resellers.edit', $reseller) }}" class="btn btn-primary">
                                            <em class="icon ni ni-edit"></em>
                                            <span>Edit Reseller</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('resellers.index') }}" class="btn btn-outline-light">
                                            <em class="icon ni ni-arrow-left"></em>
                                            <span>Back to List</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="nk-block">
                <div class="card card-bordered">
                    <div class="card-inner">
                        <div class="card-head">
                            <h5 class="card-title">Reseller Information</h5>
                        </div>
                        <div class="row g-4">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label">Name</label>
                                    <div class="form-control-wrap">
                                        <div class="form-text">{{ $reseller->name }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label">Email</label>
                                    <div class="form-control-wrap">
                                        <div class="form-text">{{ $reseller->email }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label">Credit Balance</label>
                                    <div class="form-control-wrap">
                                        <div class="form-text">{{ $reseller->credit }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label">Created At</label>
                                    <div class="form-control-wrap">
                                        <div class="form-text">
                                            {{ $reseller->created_at->format('Y-m-d H:i:s') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="nk-block">
                <div class="card card-bordered card-full">
                    <div class="card-inner">
                        <div class="card-head">
                            <h5 class="card-title">App Records</h5>
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
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>App ID</th>
                                    <th>OS</th>
                                    <th>Status</th>
                                    <th>Assign URL</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($appRecords as $record)
                                    <tr>
                                        <td>{{ $record->id }}</td>
                                        <td>{{ $record->app_id }}</td>
                                        <td>{{ $record->os }}</td>
                                        <td>
                                            @if ($record->status == 'enable')
                                                <span class="badge badge-dot badge-dot-xs bg-success">Enabled</span>
                                            @else
                                                <span class="badge badge-dot badge-dot-xs bg-warning">Disabled</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($record->assign_url)
                                                <a href="{{ $record->assign_url }}" target="_blank" class="text-primary">
                                                    <em class="icon ni ni-link"></em>
                                                    {{ Str::limit($record->assign_url, 30) }}
                                                </a>
                                            @else
                                                <span class="text-muted">Not set</span>
                                            @endif
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
