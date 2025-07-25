@extends('layouts/contentNavbarLayout')

@section('title', __('Subcategories'))

@section('content')

    <h4 class="fw-bold py-3 mb-3 row justify-content-between">
        <div class="col-md-auto">
            <span class="text-muted fw-light">{{ __('Subcategories') }} /</span> {{ __('Browse subcategories') }}
        </div>
        <div class="col-md-auto">
            <button type="button" class="btn btn-primary" id="create">{{ __('Add subcategory') }}</button>
        </div>
    </h4>

    <!-- Basic Bootstrap Table -->
    <div class="card">
        <div class="table-responsive text-nowrap">
            <div class="table-header row justify-content-between">
                <h5 class="col-md-auto">{{ __('Subcategories table') }}</h5>
                <div class="col-md-auto">
                    <select class="form-select filter-select" id="category" name="category">
                        <option value=""> {{ __('Category filter') }}</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"> {{ $category->name }} </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <table class="table" id="laravel_datatable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Created at') }}</th>
                        <th>{{ __('Category') }}</th>
                        <th>{{ __('Products') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    {{-- subcategory modal --}}
    <div class="modal fade" id="modal" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
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
                        <div class="mb-3">
                            <label class="form-label" for="category_id">{{ __('Category') }}</label>
                            <select class="form-select" id="category_id" name="category_id">
                                <option value=""> {{ __('Select category') }}</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"> {{ $category->name }} </option>
                                @endforeach
                            </select>
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

            function load_data(category = null) {
                //$.fn.dataTable.moment( 'YYYY-M-D' );
                var table = $('#laravel_datatable').DataTable({
                    language: {!! file_get_contents(base_path('lang/' . session('locale', 'en') . '/datatable.json')) !!},
                    responsive: true,
                    processing: true,
                    serverSide: true,
                    pageLength: 10,

                    ajax: {
                        url: "{{ url('subcategory/list') }}",
                        data: {
                            category: category
                        },
                        type: 'POST',
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
                            data: 'name',
                            name: 'name'
                        },

                        {
                            data: 'created_at',
                            name: 'created_at'
                        },

                        {
                            data: 'category',
                            name: 'category'
                        },


                        {
                            data: 'products',
                            name: 'products'
                        },


                        {
                            data: 'action',
                            name: 'action',
                            searchable: false
                        }

                    ]
                });
            }

            $('#category').on('change', function() {
                var table = $('#laravel_datatable').DataTable();
                table.destroy();
                load_data(document.getElementById('category').value);
            });

            $('#create').on('click', function() {
                document.getElementById('form').reset();
                document.getElementById('form_type').value = "create";
                $("#modal").modal('show');
            });


            $(document.body).on('click', '.update', function() {
                document.getElementById('form').reset();
                document.getElementById('form_type').value = "update";
                var subcategory_id = $(this).attr('table_id');
                $("#id").val(subcategory_id);

                $.ajax({
                    url: '{{ url('subcategory/update') }}',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'POST',
                    data: {
                        subcategory_id: subcategory_id
                    },
                    dataType: 'JSON',
                    success: function(response) {
                        if (response.status == 1) {

                            //console.log(response.data);
                            /* document.getElementById('name').value = response.data.name; */
                            document.getElementById('name_ar').value = response.data.name_ar;
                            document.getElementById('name_en').value = response.data.name_en;
                            document.getElementById('name_fr').value = response.data.name_fr;
                            document.getElementById('category_id').value = response.data
                                .category_id;

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
                    url = "{{ url('subcategory/create') }}";
                }

                if (formtype == "update") {
                    url = "{{ url('subcategory/update') }}";
                    formdata.append("subcategory_id", document.getElementById('id').value)
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

                var subcategory_id = $(this).attr('table_id');

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
                            url: "{{ url('subcategory/delete') }}",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type: 'POST',
                            data: {
                                subcategory_id: subcategory_id
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

            $('#modal').on('show.bs.modal', function() {
                var formType = $(this).find('#form_type').val();
                var headerH4 = $(this).find('.modal-header h4');
                if (formType === 'create') {
                    headerH4.text("{{ __('Add subcategory') }}");
                } else if (formType === 'update') {
                    headerH4.text("{{ __('Edit subcategory') }}");
                }
            });
        });
    </script>
@endsection
