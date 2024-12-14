@extends('layouts/contentNavbarLayout')

@section('title', __('Videos'))

@section('vendor-script')
    <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
    <script src="{{ asset('assets/vendor/js/dropzone.js') }}"></script>
@endsection

@section('page-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/dropzone.css') }}" type="text/css" />
@endsection

@section('content')

    <h4 class="fw-bold py-3 mb-3 row justify-content-between">
        <div class="col-md-auto">
            <span class="text-muted fw-light">{{ __('Videos of') }} /</span> {{ $product->unit_name }}
        </div>

        <div class="col-md-auto">
            <button type="button" class="btn btn-primary" id="create">{{ __('Add video') }}</button>
        </div>
    </h4>

    <!-- Basic Bootstrap Table -->
    <div class="card">
        <h5 class="card-header">{{ __('Videos table') }}</h5>
        <div class="table-responsive text-nowrap">
            <table class="table" id="laravel_datatable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('Url') }}</th>
                        <th>{{ __('Created at') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    {{-- add modal --}}
    <div class="modal fade" id="modal" tabindex="-1" aria-labelledby="modal" aria-hidden="true"
        data-bs-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal">{{ __('Add video') }}</h5>
                    {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> --}}
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <form class="dropzone" id="video-form" action="{{ url('video/add') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="dz-message needsclick col-12">
                                {{ __('video_dropzone_message') }}
                                <span class="note needsclick">{{ __('video_dropzone_note') }}</span>
                            </div>
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <div class="fallback">
                                <input type="file" name="video" class="form-control" accept="video/*">
                            </div>
                        </form>

                    </div>

                    <div class="modal-footer">
                        <button type="button" id="video_close_btn" class="btn btn-secondary" data-bs-dismiss="modal">
                            {{ __('Close') }}
                        </button>
                        <button type="button" id="video_submit_btn" class="btn btn-primary">
                            {{ __('Send') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('page-script')
    <script>
        $(document).ready(function() {
            load_data();

            function load_data() {
                //$.fn.dataTable.moment( 'YYYY-M-D' );
                var table = $('#laravel_datatable').DataTable({

                    language: {!! file_get_contents(base_path('lang/' . session('locale', 'en') . '/datatable.json')) !!},
                    responsive: true,
                    processing: true,
                    serverSide: true,
                    pageLength: 10,

                    ajax: {
                        url: "{{ url('video/list') }}",
                        type: 'POST',
                        data: {
                            product_id: "{{ $product->id }}"
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                    },

                    columns: [

                        {
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex'
                        },

                        {
                            data: 'video',
                            name: 'url',
                            render: function(data) {
                                return '<a href="' + data + '"> {{ __('Show video') }} </a>'
                            }
                        },


                        {
                            data: 'created_at',
                            name: 'created_at'
                        },


                        {
                            data: 'action',
                            name: 'action',

                        }

                    ],
                });
            }

            $('#create').on('click', function() {
                $('#modal').modal('show');
            })

            $(document.body).on('click', '.delete', function() {

                var video_id = $(this).attr('table_id');

                Swal.fire({
                    title: "{{ __('Warning') }}",
                    text: "{{ __('Are you sure?') }}",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: "{{ __('Delete') }}",
                    cancelButtonText: "{{ __('Cancel') }}"
                }).then((result) => {
                    if (result.isConfirmed) {

                        $.ajax({
                            url: "{{ url('video/delete') }}",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type: 'POST',
                            data: {
                                video_id: video_id
                            },
                            dataType: 'JSON',
                            success: function(response) {
                                if (response.status) {

                                    Swal.fire(
                                        "{{ __('Success') }}",
                                        "{{ __('success') }}",
                                        'success'
                                    ).then((result) => {
                                        $('#laravel_datatable').DataTable().ajax
                                            .reload();
                                    });
                                }
                            }
                        });


                    }
                })
            });

        });
    </script>
@endsection
