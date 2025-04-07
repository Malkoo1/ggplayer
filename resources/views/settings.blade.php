@extends('layouts.app')

@section('content')
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between g-3">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">Update Setting</h3>
                            </div>
                        </div>
                    </div>
                    <!--.nk-block-head -->
                    @if (Session::has('success'))
                        <div class="alert alert-success mt-4"><strong>{{ Session::get('success') }}</strong></div>
                    @endif
                    @if (Session::has('fail'))
                        <div class="alert alert-danger mt-4"><strong>{{ Session::get('fail') }}</strong></div>
                    @endif
                    <div class="row">
                        <div class="col-md-8">
                            <div class="nk-block">
                                <div class="card">
                                    <div class="card-inner">
                                        <form action="{{ route('update.settings') }}" method="post"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="row gy-4">


                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="form-label" for="">Auto Approve</label>
                                                    </div>
                                                    <div
                                                        class="custom-control custom-switch me-n2 {{ auth()->user()->approve_status == 'on' ? 'checked' : '' }}">
                                                        <input type="checkbox" class="custom-control-input toggle-switch"
                                                            name="auto_approve"
                                                            value="{{ auth()->user()->approve_status == 'on' ? 'on' : 'off' }}"
                                                            {{ auth()->user()->approve_status == 'on' ? 'checked' : '' }}
                                                            id="activity-log"><label class="custom-control-label"
                                                            for="activity-log"></label>

                                                        @error('auto_approve')
                                                            <span class="invalid-feedback d-block" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>

                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="form-label" for="">Set URL</label>
                                                        <input type="text" class="form-control"
                                                            value="{{ auth()->user()->assign_url }}" name="assing_url"
                                                            placeholder="Enter URL">
                                                        @error('assing_url')
                                                            <span class="invalid-feedback d-block" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>




                                                <div class="col-sm-12">
                                                    <ul class="preview-list ">
                                                        <li class="preview-item">
                                                            <button type="submit" class="btn btn-primary">Update</button>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <!--col-->
                                            </div>
                                        </form>
                                        <!--row-->
                                    </div>
                                    <!--card inner-->
                                </div>
                                <!--card-->
                            </div>
                        </div>
                    </div>

                    <!--.nk-block -->
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script_content')
    <script>
        $(".toggle-switch").on("change", function() {

            var status = $(this).prop("checked") ? "on" : 'off'; // Get switch status

            $(this).val(status);

        });
    </script>
@endsection
