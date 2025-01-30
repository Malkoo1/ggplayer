@extends('layouts.app')

@section('content')
<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between g-3">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">Update Password</h3>
                        </div>
                    </div>
                </div>
                <!--.nk-block-head -->
                @if(Session::has('success'))
                <div class="alert alert-success mt-4"><strong>{{Session::get('success')}}</strong></div>
                @endif
                @if(Session::has('fail'))
                    <div class="alert alert-danger mt-4"><strong>{{Session::get('fail')}}</strong></div>
                @endif
                <div class="row">
                    <div class="col-md-8">
                        <div class="nk-block">
                            <div class="card">
                                <div class="card-inner">
                                    <form action="{{ route('update.crend') }}" method="post" enctype="multipart/form-data">
                                        @csrf
                                        <div class="row gy-4">


                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="form-label" for="">Email</label>
                                                    <input type="email" class="form-control" name="email" value="{{ auth()->user()->email }}" placeholder="Enter Email">
                                                    @error('email')
                                                        <span class="invalid-feedback d-block" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                             <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="form-label" for="">Current Password</label>
                                                    <input type="password" class="form-control" name="current_password" placeholder="Enter Current Password">
                                                    @error('current_password')
                                                        <span class="invalid-feedback d-block" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="form-label" for="">New Password</label>
                                                    <input type="password" class="form-control" name="new_password" placeholder="Enter New Password">
                                                    @error('new_password')
                                                        <span class="invalid-feedback d-block" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="form-label" for="">New Confirm Password</label>
                                                    <input type="password" class="form-control" name="new_confirm_password" placeholder="Enter New Confirm Password">
                                                    @error('new_confirm_password')
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
<link rel="stylesheet" href="{{ asset('assets/css/editors/summernote.css') }}?ver=3.0.3">
<script src="{{ asset('assets/js/libs/editors/summernote.js') }}?ver=3.0.3"></script>
<script>
    $(document).ready(function() {
  $('#summernote').summernote({
        placeholder: 'Enter Detail',
        height: 200
      });
});
</script>

@endsection
