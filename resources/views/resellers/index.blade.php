@extends('layouts.app')

@section('content')
    <div class="nk-content-inner">
        <div class="nk-content-body">
            <div class="nk-block-head nk-block-head-sm">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h3 class="nk-block-title page-title">Resellers</h3>
                        <div class="nk-block-des text-soft">
                            <p>Manage your resellers here.</p>
                        </div>
                    </div>
                    <div class="nk-block-head-content">
                        <div class="toggle-wrap nk-block-tools-toggle">
                            <a href="{{ route('resellers.create') }}" class="btn btn-primary">
                                <em class="icon ni ni-plus"></em>
                                <span>Add Reseller</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="nk-block">
                <div class="card card-bordered card-full">
                    <div class="card-inner">
                        <div class="card-head">
                            <h5 class="card-title">Reseller List</h5>
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
                                    <th class="nk-tb-col"><span class="sub-text">ID</span></th>
                                    <th class="nk-tb-col"><span class="sub-text">Name</span></th>
                                    <th class="nk-tb-col"><span class="sub-text">Email</span></th>
                                    <th class="nk-tb-col"><span class="sub-text">Credit</span></th>
                                    <th class="nk-tb-col"><span class="sub-text">Sample Password</span></th>
                                    <th class="nk-tb-col"><span class="sub-text">Created At</span></th>
                                    <th class="nk-tb-col"><span class="sub-text">Actions</span></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($resellers as $reseller)
                                    <tr>
                                        <td class="nk-tb-col">{{ $reseller->id }}</td>
                                        <td class="nk-tb-col">{{ $reseller->name }}</td>
                                        <td class="nk-tb-col">{{ $reseller->email }}</td>
                                        <td class="nk-tb-col">{{ $reseller->credit }}</td>
                                        <td class="nk-tb-col">{{ $reseller->sample_password }}</td>


                                        <td class="nk-tb-col">{{ $reseller->created_at->format('Y-m-d H:i:s') }}</td>
                                        <td>
                                            <a href="{{ route('resellers.show', $reseller) }}" class="btn btn-sm btn-info">
                                                <em class="icon ni ni-eye"></em>
                                            </a>
                                            <a href="{{ route('resellers.edit', $reseller) }}"
                                                class="btn btn-sm btn-primary">
                                                <em class="icon ni ni-edit"></em>
                                            </a>
                                            <form action="{{ route('resellers.destroy', $reseller) }}" method="POST"
                                                style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Are you sure you want to delete this reseller?')">
                                                    <em class="icon ni ni-trash"></em>
                                                </button>
                                            </form>
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
