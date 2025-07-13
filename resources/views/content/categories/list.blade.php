@extends('layouts/contentNavbarLayout')

@section('title', __('Categories'))

@section('content')

    <h4 class="fw-bold py-3 mb-3 row justify-content-between">
        <div class="col-md-auto">
            <span class="text-muted fw-light">{{ __('Categories') }} /</span> {{ __('Browse categories') }}
        </div>
        <div class="col-md-auto">
            <button type="button" class="btn btn-primary" id="create">{{ __('Add category') }}</button>
        </div>
    </h4>

    <!-- Basic Bootstrap Table -->
    <div class="card">
        <div class="table-responsive text-nowrap">
            <table class="table" id="laravel_datatable">
              <div class="table-header row justify-content-between">
                <h5 class="col-md-auto">{{ __('Categories table') }}</h5>
              </div>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Created at') }}</th>
                        <th>{{ __('Subcategories') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    {{-- category modal --}}
    <div class="modal fade" id="modal" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="fw-bold py-1 mb-1"></h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="text" id="form_type" hidden />
                    <input type="text" class="form-control" id="id" name="id" hidden />
                    <form class="form-horizontal" onsubmit="event.preventDefault()" action="#"
                        enctype="multipart/form-data" id="form">
                        <div class="card-body">
                            <div class="d-flex align-items-start align-items-sm-center gap-4">
                                <div hidden><img src="{{ asset('assets/img/icons/file-not-found.jpg') }}" alt="image"
                                        class="d-block rounded" height="100" width="100" id="old-image" /> </div>
                                <img src="{{ asset('assets/img/icons/file-not-found.jpg') }}" alt="image"
                                    class="d-block rounded" height="100" width="100" id="uploaded-image" />
                                <div class="button-wrapper">
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
                        </div>
                        <hr class="my-0">
{{--                         <div class="mb-3">
                            <label class="form-label" for="name">{{ __('Name') }}</label>
                            <input type="text" class="form-control" id="name" name="name" />
                        </div> --}}
                        <div class="mb-3">
                            <label class="form-label" for="name_ar">{{ __('Name in Arabic') }}</label>
                            <input type="text" class="form-control" id="name_ar" name="name_ar" />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="name_en">{{ __('Name in English') }}</label>
                            <input type="text" class="form-control" id="name_en" name="name_en" />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="name_fr">{{ __('Name in French') }}</label>
                            <input type="text" class="form-control" id="name_fr" name="name_fr" />
                        </div>


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
                    language:  {!! file_get_contents(base_path('lang/'.session('locale','en').'/datatable.json')) !!},
                    responsive: true,
                    processing: true,
                    serverSide: true,
                    pageLength: 10,

                    ajax: {
                        url: "{{ url('category/list') }}",
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
                            data: 'created_at',
                            name: 'created_at'
                        },


                        {
                            data: 'subcategories',
                            name: 'subcategories'
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
                    "{{ asset('assets/img/icons/file-not-found.jpg') }}";
                document.getElementById('old-image').src =
                    "{{ asset('assets/img/icons/file-not-found.jpg') }}";
                $("#modal").modal('show');
            });


            $(document.body).on('click', '.update', function() {
                document.getElementById('form').reset();
                document.getElementById('form_type').value = "update";
                var category_id = $(this).attr('table_id');
                $("#id").val(category_id);

                $.ajax({
                    url: '{{ url('category/update') }}',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'POST',
                    data: {
                        category_id: category_id
                    },
                    dataType: 'JSON',
                    success: function(response) {
                        if (response.status == 1) {
                            document.getElementById('id').value = response.data.id;
                            document.getElementById('name_ar').value = response.data.name_ar;
                            document.getElementById('name_en').value = response.data.name_en;
                            document.getElementById('name_fr').value = response.data.name_fr;
/*                             document.getElementById('name').value = response.data.name; */
                            console.log(response.data.image);

                            var image = response.data.image == null ?
                                "{{ asset('assets/img/icons/file-not-found.jpg') }}" : response
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
                    url = "{{ url('category/create') }}";
                }

                if (formtype == "update") {
                    url = "{{ url('category/update') }}";
                    formdata.append("category_id", document.getElementById('id').value)
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

                var category_id = $(this).attr('table_id');

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
                            url: "{{ url('category/delete') }}",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type: 'POST',
                            data: {
                                category_id: category_id
                            },
                            dataType: 'JSON',
                            success: function(response) {
                                if (response.status == 1) {

                                    Swal.fire(
                                        "{{ __('Success') }}",
                                        "{{ __('success') }}",
                                        'success'
                                    ).then((result) => {
                                        $('#laravel_datatable').DataTable().ajax.reload();
                                    });
                                } else {
                                    Swal.fire(
                                        "{{ __('Error') }}",
                                        response.message,
                                        'error'
                                    )
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

            $('#modal').on('show.bs.modal', function() {
                var formType = $(this).find('#form_type').val();
                var headerH4 = $(this).find('.modal-header h4');
                if (formType === 'create') {
                    headerH4.text("{{ __('Add category') }}");
                } else if (formType === 'update') {
                    headerH4.text("{{ __('Edit category') }}");
                }
            });
        });
    </script>
@endsection
