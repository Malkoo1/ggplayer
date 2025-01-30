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

                                            <div class="nk-tb-col tb-col-sm"><span>input Content</span></div>
                                            <div class="nk-tb-col"><span>Input Word Count</span></div>
                                            <div class="nk-tb-col"><span>Output Content</span></div>
                                            <div class="nk-tb-col"><span>Out Word Count</span></div>
                                            <div class="nk-tb-col"><span>Request Time</span></div>
                                            <div class="nk-tb-col nk-tb-col-tools">

                                            </div>
                                        </div><!-- .nk-tb-item -->
                                        @isset($data)
                                            @forelse ($data as $row)
                                                <div class="nk-tb-item tr_cust">

                                                    <div class="nk-tb-col tb-col-sm">
                                                        <span class="tb-product">
                                                            <span class="title">{{ $row->input_content }}</span>
                                                        </span>
                                                    </div>

                                                    <div class="nk-tb-col">
                                                        <span class="tb-sub">{{ $row->input_count }}</span>
                                                    </div>

                                                    <div class="nk-tb-col">
                                                        <span class="tb-sub">{{ $row->output_content }}</span>
                                                    </div>
                                                    <div class="nk-tb-col">
                                                        <span class="tb-sub">{{ $row->output_count }}</span>
                                                    </div>
                                                    <div class="nk-tb-col">
                                                        <span
                                                            class="tb-sub">{{ date('M j, Y h:i A', strtotime($row->created_at)) }}</span>
                                                    </div>


                                                    <div class="nk-tb-col nk-tb-col-tools">
                                                        <ul class="nk-tb-actions gx-1 my-n1">
                                                            <li class="me-n1">
                                                                <div class="dropdown">
                                                                    <a href="#"
                                                                        class="dropdown-toggle btn btn-icon btn-trigger"
                                                                        data-bs-toggle="dropdown"><em
                                                                            class="icon ni ni-more-h"></em></a>
                                                                    <div class="dropdown-menu dropdown-menu-end">
                                                                        <ul class="link-list-opt no-bdr">
                                                                            {{-- <li><a href="#"><em class="icon ni ni-edit"></em><span>Edit Product</span></a></li> --}}
                                                                            {{-- <li><a href="{{ route('macrecord.all',$row->id) }}"><em class="icon ni ni-eye"></em><span>View Product</span></a></li> --}}
                                                                            {{-- <li><a href="#"><em class="icon ni ni-activity-round"></em><span>Product Orders</span></a></li> --}}
                                                                            <li><a href="#" class="eg-swal-av3"
                                                                                    data-id="{{ route('macrecord.delete', $row->id) }}"><em
                                                                                        class="icon ni ni-trash "></em><span>Remove
                                                                                        Chat Record</span></a></li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                        </ul>
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
        $('.eg-swal-av3').on("click", function(e) {
            var deletePath = $(this).attr("data-id");

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Delete it!',
                confirmButtonColor: "#e85347",
                cancelButtonColor: "#003366",
            }).then(function(result) {
                if (result.value) {

                    window.location.href = deletePath;
                    // Swal.fire('Deleted!', 'Your file has been deleted.', 'success');
                }
            });
            e.preventDefault();
        });
    </script>
@endsection
