@extends('layouts/contentNavbarLayout')

@section('title', __('Groups'))

@section('content')

    <h4 class="fw-bold py-3 mb-3 row justify-content-between">
        <div class="col-md-auto">
            <span class="text-muted fw-light">{{ __('Groups') }} /</span> {{ __('Browse groups') }}
        </div>
        <div class="col-md-auto">
            <button type="button" class="btn btn-primary" id="create">{{ __('Add Group') }}</button>
        </div>
    </h4>

    <!-- Basic Bootstrap Table -->
    <div class="card">
        <div class="table-responsive text-nowrap">
          <div class="table-header row justify-content-between">
            <h5 class="col-md-auto">{{ __('Groups table') }}</h5>
          </div>
            <table class="table" id="laravel_datatable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('Name Ar') }}</th>
                        <th>{{ __('Name En') }}</th>
                        <th>{{ __('Created at') }}</th>
                        <th>{{ __('Subcategories') }}</th>
                        <th>{{ __('Published') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    {{-- group modal --}}
    <div class="modal fade" id="modal" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="fw-bold py-1 mb-1">{{ __('Add/update group') }}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="text" id="form_type" hidden />
                    <input type="text" class="form-control" id="id" name="id" hidden />
                    <form class="form-horizontal" onsubmit="event.preventDefault()" action="#"
                        enctype="multipart/form-data" id="form">

                        <div class="mb-3">
                            <label class="form-label" for="name">{{ __('Name Ar') }}</label>
                            <input type="text" class="form-control" id="name" name="name" />
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="name_en">{{ __('Name En') }}</label>
                            <input type="text" class="form-control" id="name_en" name="name_en" />
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="subcategories">{{ __('Subcategories') }}</label>
                            <select class="selectpicker form-control" id="subcategories" name="subcategories" multiple>
                                @foreach ($subcategories as $subcategory)
                                    <option value="{{ $subcategory->id }}"> {{ $subcategory->name }} </option>
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

            function load_data() {
                //$.fn.dataTable.moment( 'YYYY-M-D' );
                var table = $('#laravel_datatable').DataTable({

                  language:  {!! file_get_contents(base_path('lang/'.session('locale','en').'/datatable.json')) !!},

                    responsive: true,
                    processing: true,
                    serverSide: true,
                    pageLength: 10,

                    ajax: {
                        url: "{{ url('group/list') }}",
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
                            data: 'name_en',
                            name: 'name_en'
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
                $('#subcategories').selectpicker('val', []);
                $("#modal").modal('show');
            });


            $(document.body).on('click', '.update', function() {
                document.getElementById('form').reset();
                document.getElementById('form_type').value = "update";
                var group_id = $(this).attr('table_id');
                $("#id").val(group_id);

                $.ajax({
                    url: '{{ url('group/update') }}',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'POST',
                    data: {
                        group_id: group_id
                    },
                    dataType: 'JSON',
                    success: function(response) {
                        if (response.status == 1) {

                            document.getElementById('name').value = response.data.name;
                            document.getElementById('name_en').value = response.data.name_en;

                            $.ajax({
                                url: '{{ url('subcategory/get?all=1') }}',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                        'content')
                                },
                                type: 'POST',
                                data: {
                                    group_id: group_id
                                },
                                dataType: 'JSON',
                                success: function(response) {
                                    if (response.status == 1) {

                                        var subcategories = document.getElementById(
                                            'subcategories');
                                        //subsubcategories.innerHTML = '<option value="">{{ __('Not selected') }}</option>';
                                        //console.log(response.data);
                                        const getKey = (array, key) => array.map(
                                            a => a[key]);
                                        var options = getKey(response.data, 'id');
                                        $('#subcategories').selectpicker('val',
                                            options);
                                        $("#modal").modal("show");

                                    }
                                }
                            });


                        }
                    }
                });
            });

            $('#submit').on('click', function() {

                var formdata = new FormData();
                formdata.append('name', $("#name").val());
                formdata.append('name_en', $("#name_en").val());
                var subcategories = document.getElementById('subcategories');

                for (var i = 0; i < subcategories.options.length; i++) {
                    if (subcategories.options[i].selected) {
                        formdata.append(`subcategories[${i}]`, subcategories.options[i].value);
                    }
                }

                var formtype = document.getElementById('form_type').value;

                if (formtype == "create") {
                    url = "{{ url('group/create') }}";
                }

                if (formtype == "update") {
                    url = "{{ url('group/update') }}";
                    formdata.append("group_id", document.getElementById('id').value)
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

                var group_id = $(this).attr('table_id');

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
                            url: "{{ url('group/delete') }}",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type: 'POST',
                            data: {
                                group_id: group_id
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
                                }
                            }
                        });


                    }
                })
            });

            $(document.body).on('click', '.add_to_home', function() {

                var group_id = $(this).attr('table_id');

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
                                type: "group",
                                element: group_id
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
                                        $('#laravel_datatable').DataTable().ajax.reload();
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
