@extends('layouts/contentNavbarLayout')

@section('title', __('Images'))

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
            <span class="text-muted fw-light">{{ __('Images of') }} /</span> {{ $product->name }}
        </div>

        <div class="col-md-auto">
            <button type="button" class="btn btn-primary" id="create">{{ __('Add images') }}</button>
        </div>
    </h4>

    <!-- Basic Bootstrap Table -->
    <div class="card">
        <h5 class="card-header">{{ __('Images table') }}</h5>
        <div class="table-responsive text-nowrap">
            <table class="table" id="laravel_datatable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('Image') }}</th>
                        <th>{{ __('Created at') }}</th>
                        <th>{{ __('Url') }}</th>
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
                    <h5 class="modal-title" id="modal">{{ __('Add images') }}</h5>
                    {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> --}}
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <form class="dropzone" id="images-form" action="{{ url('image/add') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="dz-message needsclick col-12">
                                {{ __('images_dropzone_message') }}
                                <span class="note needsclick">{{ __('images_dropzone_note') }}</span>
                            </div>
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <div class="fallback">
                                <input type="file" name="images[]" class="form-control" accept="image/*">
                            </div>
                        </form>

                    </div>

                    <div class="modal-footer">
                        <button type="button" id="images_close_btn" class="btn btn-secondary" data-bs-dismiss="modal">
                            {{ __('Close') }}
                        </button>
                        <button type="button" id="images_submit_btn" class="btn btn-primary">
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
                        url: "{{ url('image/list') }}",
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
                            data: 'image',
                            name: 'image',
                            render: function(data) {
                                return '<div class="avatar-wrapper"><div class="avatar avatar me-4 rounded-2 bg-label-secondary"><img src="' +
                                    data + '" class="rounded"></div></div>'
                            }
                        },

                        {
                            data: 'created_at',
                            name: 'created_at'
                        },

                        {
                            data: 'image',
                            name: 'url',
                            render: function(data) {
                                return '<a href="' + data + '"> {{ __('Show image') }} </a>'
                            }
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

                var image_id = $(this).attr('table_id');

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
                            url: "{{ url('image/delete') }}",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type: 'POST',
                            data: {
                                image_id: image_id
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
