@extends('layouts.app')

@section('content')
    <div class="nk-content-inner">
        <div class="nk-content-body">
            <div class="nk-block-head nk-block-head-sm">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h3 class="nk-block-title page-title">Reseller Dashboard</h3>
                        <div class="nk-block-des text-soft">
                            <p>Welcome, {{ Auth::user()->name }}. Your credit balance:
                                {{ Auth::user()->credit }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="nk-block">
                <div class="card card-bordered card-full">
                    <div class="card-inner">
                        <div class="card-head">
                            <h5 class="card-title">Search Available Apps</h5>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="app_id_search">Search App ID</label>
                            <div class="form-control-wrap">
                                <div class="form-icon form-icon-right">
                                    <em class="icon ni ni-search"></em>
                                </div>
                                <input type="text" class="form-control" id="app_id_search"
                                    placeholder="Enter App ID to search">
                            </div>
                            <div id="search_results" class="mt-3"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="nk-block">
                <div class="card card-bordered card-full">
                    <div class="card-inner">
                        <div class="card-head">
                            <h5 class="card-title">Your App Records</h5>
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
                                    {{-- <th>Created At</th> --}}
                                    <th>Actions</th>
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
                                        {{-- <td>{{ $record->created_at->format('Y-m-d H:i:s') }}</td> --}}
                                        <td>
                                            {{-- <a href="{{ route('user.licence', $record->id) }}" class="btn btn-sm btn-info">
                                                <em class="icon ni ni-license"></em>
                                            </a> --}}
                                            <a href="{{ route('reseller.edit_app', $record->id) }}"
                                                class="btn btn-sm btn-primary">
                                                <em class="icon ni ni-edit"></em>
                                            </a>
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

@section('script_content')
    <script>
        $(document).ready(function() {
            let searchTimer;

            $('#app_id_search').on('input', function() {
                clearTimeout(searchTimer);
                const searchTerm = $(this).val().trim();

                if (searchTerm.length < 3) {
                    $('#search_results').html('');
                    return;
                }

                searchTimer = setTimeout(function() {
                    $.ajax({
                        url: '{{ route('reseller.search_app_ajax') }}',
                        type: 'POST',
                        data: {
                            app_id: searchTerm,
                            _token: '{{ csrf_token() }}'
                        },
                        beforeSend: function() {
                            $('#search_results').html(
                                '<div class="alert alert-info">Searching...</div>');
                        },
                        success: function(response) {
                            if (response.success) {
                                let html =
                                    '<div class="table-responsive"><table class="table table-striped">';
                                html +=
                                    '<thead><tr><th>App ID</th><th>OS</th><th>Status</th><th>Action</th></tr></thead>';
                                html += '<tbody>';

                                if (response.apps.length > 0) {
                                    response.apps.forEach(function(app) {
                                        html += '<tr>';
                                        html += '<td>' + app.app_id + '</td>';
                                        html += '<td>' + app.os + '</td>';
                                        html +=
                                            '<td><span class="badge badge-dot badge-dot-xs bg-warning">Disabled</span></td>';
                                        html += '<td><a href="' + app
                                            .assign_url +
                                            '" class="btn btn-sm btn-primary">Assign</a></td>';
                                        html += '</tr>';
                                    });
                                } else {
                                    html +=
                                        '<tr><td colspan="4" class="text-center">No disabled apps found</td></tr>';
                                }

                                html += '</tbody></table></div>';
                                $('#search_results').html(html);
                            } else {
                                $('#search_results').html(
                                    '<div class="alert alert-danger">' + response
                                    .message + '</div>');
                            }
                        },
                        error: function() {
                            $('#search_results').html(
                                '<div class="alert alert-danger">An error occurred while searching</div>'
                            );
                        }
                    });
                }, 500);
            });
        });
    </script>
@endsection
