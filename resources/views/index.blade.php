@extends('layouts.app')

@section('content')
    <!-- content @s -->
    <div class="nk-content nk-content-fluid">
        <div class="container-xl wide-xl">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">All App Record</h3>
                            </div><!-- .nk-block-head-content -->
                            <div class="nk-block-head-content">
                                <div class="toggle-wrap nk-block-tools-toggle">
                                    <a href="#" class="btn btn-icon btn-trigger toggle-expand me-n1"
                                        data-target="pageMenu"><em class="icon ni ni-more-v"></em></a>
                                    <div class="toggle-expand-content" data-content="pageMenu">
                                        <ul class="nk-block-tools g-3">
                                            <li>
                                                <div class="form-control-wrap">
                                                    <div class="form-icon form-icon-right">
                                                        <em class="icon ni ni-search"></em>
                                                    </div>
                                                    <input type="text" class="form-control" id="myInput"
                                                        placeholder="Quick search by id">
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div><!-- .nk-block-head-content -->
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    @if (Session::has('success'))
                        <div class="alert alert-success mt-4"><strong>{{ Session::get('success') }}</strong></div>
                    @endif
                    @if (Session::has('fail'))
                        <div class="alert alert-danger mt-4"><strong>{{ Session::get('fail') }}</strong></div>
                    @endif
                    <div class="nk-block">
                        <div class="card card-bordered">
                            <div class="card-inner-group">
                                <div class="card-inner p-0">
                                    <div class="nk-tb-list" id="table_search">
                                        <div class="nk-tb-item nk-tb-head">

                                            <div class="nk-tb-col tb-col-sm"><span>App id</span></div>
                                            <div class="nk-tb-col"><span>Operating System</span></div>
                                            <div class="nk-tb-col"><span>Created At</span></div>
                                            <div class="nk-tb-col"><span>Status</span></div>
                                            <div class="nk-tb-col"><span>Assign Url</span></div>


                                        </div><!-- .nk-tb-item -->
                                        @isset($data)
                                            @forelse ($data as $row)
                                                <div class="nk-tb-item tr_cust">

                                                    <div class="nk-tb-col tb-col-sm">
                                                        <span class="tb-product">
                                                            <span class="title">{{ $row->app_id }}</span>
                                                        </span>
                                                    </div>

                                                    <div class="nk-tb-col">
                                                        <span class="tb-sub">{{ $row->os }}</span>
                                                    </div>
                                                    <div class="nk-tb-col">
                                                        <span
                                                            class="tb-sub">{{ $row->created_at ? date('M j, Y', strtotime($row->created_at)) : 'null' }}</span>
                                                    </div>
                                                    <div class="nk-tb-col">
                                                        <div
                                                            class="custom-control custom-switch me-n2 {{ $row->status == 'enable' ? 'checked' : '' }}">
                                                            <input type="checkbox" class="custom-control-input toggle-switch"
                                                                data-id="{{ $row->id }}"
                                                                {{ $row->status == 'enable' ? 'checked' : '' }}
                                                                id="activity-log"><label class="custom-control-label"
                                                                for="activity-log"></label>
                                                        </div>
                                                        {{-- <span class="tb-sub">{{ $row->assign_url }}</span> --}}
                                                    </div>

                                                    <div class="nk-tb-col">
                                                        <div class="drodown"><a href="#"
                                                                class="dropdown-toggle btn btn-icon btn-trigger"
                                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                                @if (isset($row->assign_url))
                                                                    <em class="icon ni ni-edit"></em>
                                                                @else
                                                                    <em class="icon ni ni-plus"></em>
                                                                @endif

                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-end"
                                                                style="width: 300px;padding: 17px;border-radius: 13px;">
                                                                <form action="{{ route('update.url', $row->id) }}"
                                                                    method="POST" class="form-validate is-alter"
                                                                    novalidate="novalidate">
                                                                    @csrf
                                                                    <div class="form-group"><label class="form-label"
                                                                            for="full-name">URL</label>
                                                                        <div class="form-control-wrap"><input type="text"
                                                                                class="form-control assign_id" name="assign_url"
                                                                                value="{{ $row->assign_url }}"></div>
                                                                    </div>

                                                                    <div class="form-group"><button type="submit"
                                                                            class="btn btn-lg btn-primary">Save
                                                                        </button></div>
                                                                </form>
                                                            </div>
                                                        </div>

                                                    </div>










                                                </div>
                                            @empty
                                            @endforelse
                                        @endisset

                                    </div><!-- .nk-tb-list -->
                                </div>

                            </div>
                        </div>
                    </div><!-- .nk-block -->

                </div>
            </div>
        </div>
    </div>
    <!-- content @e -->

@endsection

@section('script_content')
    <script>
        $(document).ready(function() {
            $("#myInput").on("keyup", function() {

                var value = $(this).val().toLowerCase();
                $("#table_search .tr_cust").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });


        $(".toggle-switch").on("change", function() {
            var itemId = $(this).data("id");
            var status = $(this).prop("checked") ? "enable" : 'disable'; // Get switch status

            $.ajax({
                url: "{{ route('switch.update') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: itemId,
                    status: status
                },
                success: function(response) {

                    Swal.fire({
                        position: "top-end",
                        icon: "success",
                        title: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                    // alert(response.message);
                },
                error: function(xhr) {
                    alert("Something went wrong!");
                }
            });
        });
    </script>
@endsection
