@extends('layouts.app')

@section('content')
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between g-3">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">Licence Activate</h3>
                            </div>
                        </div>
                    </div>
                    @if (Session::has('success'))
                        <div class="alert alert-success mt-4"><strong>{{ Session::get('success') }}</strong></div>
                    @endif
                    @if (Session::has('fail'))
                        <div class="alert alert-danger mt-4"><strong>{{ Session::get('fail') }}</strong></div>
                    @endif

                    @if ($daysLeft !== null)
                        <div class="alert alert-info mt-4">
                            <strong>Licence Expires In: {{ $daysLeft }} days</strong>
                        </div>
                    @elseif ($licence->licence_pkg && !$licence->licence_expire)
                        <div class="alert alert-warning mt-4">
                            <strong>Licence package selected, but expiry date is not set.</strong>
                        </div>
                    @elseif (!$licence->licence_pkg && $licence->licence_expire)
                        <div class="alert alert-warning mt-4">
                            <strong>Licence expiry date is set, but package is not selected.</strong>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-8">
                            <div class="nk-block">
                                <div class="card">
                                    <div class="card-inner">
                                        <form action="{{ route('update.licence', $licence->id) }}" method="post">
                                            @csrf
                                            <div class="row gy-4">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="form-label">Select Plan</label>
                                                        <div class="form-control-wrap">
                                                            <select class="form-select js-select2" name="plan"
                                                                aria-placeholder="selected Plan">
                                                                <option value="">Select Plan</option>
                                                                <option value="1"
                                                                    {{ $licence->licence_pkg === '1 Month' ? 'selected' : '' }}>
                                                                    1 Month</option>
                                                                <option value="3"
                                                                    {{ $licence->licence_pkg === '3 Month' ? 'selected' : '' }}>
                                                                    3 Month</option>
                                                                <option value="6"
                                                                    {{ $licence->licence_pkg === '6 Month' ? 'selected' : '' }}>
                                                                    6 Month</option>
                                                                <option value="12"
                                                                    {{ $licence->licence_pkg === '12 Month' ? 'selected' : '' }}>
                                                                    12 Month</option>
                                                                <option value="unlimited"
                                                                    {{ $licence->licence_pkg === 'Unlimited' ? 'selected' : '' }}>
                                                                    Unlimited</option>
                                                            </select>
                                                        </div>
                                                        @error('plan')
                                                            <span class="invalid-feedback d-block" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="form-label" for="">Note</label>
                                                        <div class="form-control-wrap">
                                                            <textarea class="form-control form-control-sm" id="cf-default-textarea" name="note" placeholder="Write your message">{{ $licence->note }}</textarea>
                                                        </div>
                                                        @error('note')
                                                            <span class="invalid-feedback d-block" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <ul class="preview-list ">
                                                        <li class="preview-item">
                                                            <button type="submit" class="btn btn-primary">Save</button>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
