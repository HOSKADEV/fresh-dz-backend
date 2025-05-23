@extends('layouts/contentNavbarLayout')

@section('title', __('Ads'))

@section('content')

    <h4 class="fw-bold py-3 mb-3 row justify-content-between">
        <div class="col-md-auto">
            <span class="text-muted fw-light">{{ __('Ads') }} /</span> {{ __('Browse ads') }}
        </div>
        <div class="col-md-auto">
            <button type="button" class="btn btn-primary" id="create">{{ __('Add ad') }}</button>
        </div>
    </h4>

    <!-- Basic Bootstrap Table -->
    <div class="card">
        <div class="table-responsive text-nowrap">
            <div class="table-header row justify-content-between">
                <h5 class="col-md-auto">{{ __('Ads table') }}</h5>
            </div>
            <table class="table" id="laravel_datatable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Type') }}</th>
                        <th>{{ __('Created at') }}</th>
                        <th>{{ __('Published') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    {{-- ad modal --}}
    <div class="modal fade" id="modal" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="fw-bold py-1 mb-1">{{ __('Add ad') }}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="text" id="form_type" hidden />
                    <input type="text" class="form-control" id="id" name="id" hidden />
                    <form class="form-horizontal" onsubmit="event.preventDefault()" action="#"
                        enctype="multipart/form-data" id="form">
                        <div class="card-body">
                            <div class="d-flex align-items-start align-items-sm-center gap-4">
                                <div hidden><img src="{{ asset('assets/img/icons/ad-not-found.jpg') }}" alt="image"
                                        class="d-block rounded" height="120" width="500" id="old-image" /> </div>
                                <img src="{{ asset('assets/img/icons/ad-not-found.jpg') }}" alt="image"
                                    class="d-block rounded" height="120" width="500" id="uploaded-image" />
                            </div>
                            <div class="button-wrapper" style="text-align: center;">
                                <br>
                                <label for="image" class="btn btn-primary" tabindex="0">
                                    <span class="d-none d-sm-block">{{ __('New image') }}</span>
                                    <i class="bx bx-upload d-block d-sm-none"></i>
                                    <input class="image-input" type="file" id="image" name="image" hidden
                                        accept="image/png, image/jpeg" />
                                </label>
                                <button type="button" class="btn btn-outline-secondary image-reset">
                                    <i class="bx bx-reset d-block d-sm-none"></i>
                                    <span class="d-none d-sm-block">{{ __('Reset') }}</span>
                                </button>
                                <br>
                                {{-- <small class="text-muted mb-0">Allowed JPG, GIF or PNG. Max size of 800K</small> --}}
                            </div>
                        </div>
                        <hr class="my-0">
                        <div class="mb-3">
                            <label class="form-label" for="name">{{ __('Name') }}</label>
                            <input type="text" class="form-control" id="name" name="name" />
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="type">{{ __('Type') }}</label>
                            <select class="form-select" id="type" name="type">
                                <option value="static"> {{ __('Static ad') }} </option>
                                <option value="url"> {{ __('Off-App ad') }} </option>
                                <option value="product"> {{ __('Product ad') }} </option>
                            </select>
                        </div>

                        <div class="mb-3" id="url-div">
                            <label class="form-label" for="name">{{ __('URL') }}</label>
                            <input type="text" class="form-control" id="url" name="url" />
                        </div>

                        <div class="mb-3" id="product-id-div">
                            <label class="form-label" for="product_id">{{ __('Product') }}</label>
                            <select class="selectpicker form-control" id="product_id" name="product_id" data-size="5"
                                data-live-search="true">
                                <option value=""> {{ __('Not selected') }} </option>
                                @foreach ($products as $key => $value)
                                    <option value="{{ $key }}"> {{ $value }} </option>
                                @endforeach
                            </select>
                        </div>

                        {{--  <div class="mb-3" id="product-name-div">
                        <label class="form-label" for="name">{{ __('Product') }}</label>
                        <input type="text" class="form-control" id="product_name"/>
                    </div> --}}


                        <div class="mb-3" style="text-align: center">
                            <button type="submit" id="submit" name="submit"
                                class="btn btn-primary">{{ __('Send') }}</button>
                        </div>

                    </form>
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
                        url: "{{ url('ad/list') }}",
                    },

                    type: 'GET',

                    columns: [

                        {
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex'
                        },

                        {
                            data: 'name',
                            name: 'name'
                        },

                        {
                            data: 'type',
                            name: 'type',
                            render: function(data) {
                                if (data == 'static') {
                                    return '<span class="badge bg-primary">{{ __('Static ad') }}</span>';
                                } else if (data == 'product') {
                                    return '<span class="badge bg-warning">{{ __('Product ad') }}</span>';
                                } else {
                                    return '<span class="badge bg-info">{{ __('Off-App ad') }}</span>';
                                }
                            }
                        },

                        {
                            data: 'created_at',
                            name: 'created_at'
                        },

                        {
                            data: 'is_published',
                            name: 'is_published',
                            render: function(data) {
                                if (data == false) {
                                    return '<span class="badge bg-danger">{{ __('No') }}</span>';
                                } else {
                                    return '<span class="badge bg-success">{{ __('Yes') }}</span>';
                                }
                            }
                        },


                        {
                            data: 'action',
                            name: 'action',
                            searchable: false
                        }

                    ]
                });
            }

            $('#create').on('click', function() {
                document.getElementById('form').reset();
                document.getElementById('form_type').value = "create";
                document.getElementById('uploaded-image').src =
                    "{{ asset('assets/img/icons/ad-not-found.jpg') }}";
                document.getElementById('old-image').src =
                    "{{ asset('assets/img/icons/ad-not-found.jpg') }}";
                $('#type').trigger("change");
                $("#modal").modal('show');
            });

            $('#type').on('change', function() {
                var type = $(this).val();

                $('#product-id-div').hide();
                $('#url-div').hide();

                if (type == 'product') {
                  $('#product-id-div').show();
                }
                if (type == 'url') {
                    $('#url-div').show();
                }

                $('#product_id').selectpicker('refresh');

            });


            $(document.body).on('click', '.update', function() {
                document.getElementById('form').reset();
                document.getElementById('form_type').value = "update";
                var ad_id = $(this).attr('table_id');
                $("#id").val(ad_id);

                $.ajax({
                    url: '{{ url('ad/update') }}',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'POST',
                    data: {
                        ad_id: ad_id
                    },
                    dataType: 'JSON',
                    success: function(response) {
                        if (response.status == 1) {
console.log(response.data);
                            document.getElementById('name').value = response.data.name;
                            document.getElementById('url').value = response.data.url;
                            //var type = response.data.type;
                            document.getElementById('type').value = response.data.type;
                            document.getElementById('product_id').value = response.data
                                .product_id;

                            $('#type').trigger("change");

                            var image = response.data.image == null ?
                                "{{ asset('assets/img/icons/ad-not-found.jpg') }}" : response
                                .data.image;

                            document.getElementById('uploaded-image').src = image;
                            document.getElementById('old-image').src = image;

                            $("#modal").modal("show");
                        }
                    }
                });
            });

            $('#submit').on('click', function() {

                var formdata = new FormData($("#form")[0]);
                var formtype = document.getElementById('form_type').value;
                console.log(formtype);
                if (formtype == "create") {
                    url = "{{ url('ad/create') }}";
                }

                if (formtype == "update") {
                    url = "{{ url('ad/update') }}";
                    formdata.append("ad_id", document.getElementById('id').value)
                }

                $("#modal").modal("hide");


                $.ajax({
                    url: url,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'POST',
                    data: formdata,
                    dataType: 'JSON',
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.status == 1) {
                            Swal.fire({
                                title: "{{ __('Success') }}",
                                text: "{{ __('success') }}",
                                icon: 'success',
                                confirmButtonText: 'Ok'
                            }).then((result) => {
                                $('#laravel_datatable').DataTable().ajax.reload();
                            });
                        } else {
                            console.log(response.message);
                            Swal.fire(
                                "{{ __('Error') }}",
                                response.message,
                                'error'
                            );
                        }
                    },
                    error: function(data) {
                        var errors = data.responseJSON;
                        console.log(errors);
                        Swal.fire(
                            "{{ __('Error') }}",
                            errors.message,
                            'error'
                        );
                        // Render the errors with js ...
                    }
                });
            });

            $(document.body).on('click', '.delete', function() {

                var ad_id = $(this).attr('table_id');

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
                            url: "{{ url('ad/delete') }}",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type: 'POST',
                            data: {
                                ad_id: ad_id
                            },
                            dataType: 'JSON',
                            success: function(response) {
                                if (response.status == 1) {

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

            $(document.body).on('click', '.add_to_home', function() {

                var ad_id = $(this).attr('table_id');

                Swal.fire({
                    title: "{{ __('Warning') }}",
                    text: "{{ __('Are you sure?') }}",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: "{{ __('Yes') }}",
                    cancelButtonText: "{{ __('No') }}"
                }).then((result) => {
                    if (result.isConfirmed) {

                        $.ajax({
                            url: "{{ url('section/add') }}",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type: 'POST',
                            data: {
                                type: "ad",
                                element: ad_id
                            },
                            dataType: 'JSON',
                            success: function(response) {
                                if (response.status == 1) {

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

            $(document.body).on('click', '.remove_from_home', function() {

                var section_id = $(this).attr('table_id');

                Swal.fire({
                    title: "{{ __('Warning') }}",
                    text: "{{ __('Are you sure?') }}",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: "{{ __('Yes') }}",
                    cancelButtonText: "{{ __('No') }}"
                }).then((result) => {
                    if (result.isConfirmed) {

                        $.ajax({
                            url: "{{ url('section/delete') }}",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type: 'POST',
                            data: {
                                section_id: section_id
                            },
                            dataType: 'JSON',
                            success: function(response) {
                                if (response.status == 1) {

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

            $(document.body).on('change', '.image-input', function() {
                const fileInput = document.querySelector('.image-input');
                if (fileInput.files[0]) {
                    document.getElementById('uploaded-image').src = window.URL.createObjectURL(fileInput
                        .files[0]);
                }
            });
            $(document.body).on('click', '.image-reset', function() {
                const fileInput = document.querySelector('.image-input');
                fileInput.value = '';
                document.getElementById('uploaded-image').src = document.getElementById('old-image').src;
            });
        });
    </script>
@endsection
