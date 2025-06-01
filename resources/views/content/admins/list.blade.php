@extends('layouts/contentNavbarLayout')

@section('title', __('Admins'))

@section('content')
    <h4 class="fw-bold py-3 mb-3 row justify-content-between">
        <div class="col-md-auto">
            <span class="text-muted fw-light">{{ __('Admins') }} /</span> {{ __('Browse admins') }}
        </div>
        <div class="col-md-auto">
            <button type="button" class="btn btn-primary" id="create">{{ __('Add admin') }}</button>
        </div>
    </h4>

    <!-- Basic Bootstrap Table -->
    <div class="card">
        <div class="table-responsive text-nowrap">
            <div class="table-header row justify-content-between">
                <h5 class="col-md-auto">{{ __('Admins table') }}</h5>
            </div>
            <table class="table" id="laravel_datatable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Phone') }}</th>
                        <th>{{ __('Email') }}</th>
                        <th>{{ __('Role') }}</th>
                        <th>{{ __('Created at') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <!-- Create Admin Modal -->
    <div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Create New Admin') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" onsubmit="event.preventDefault()" action="#"
                        enctype="multipart/form-data" id="createForm">
                        <!-- Name -->
                        <div class="mb-3">
                            <label for="adminName" class="form-label">{{ __('Name') }}</label>
                            <input type="text" class="form-control" id="adminName" name="name" required>
                        </div>

                        <!-- Phone -->
                        <div class="mb-3">
                            <label for="adminPhone" class="form-label">{{ __('Phone') }}</label>
                            <input type="tel" class="form-control" id="adminPhone" name="phone" required>
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="adminEmail" class="form-label">{{ __('Email') }}</label>
                            <input type="email" class="form-control" id="adminEmail" name="email" required>
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label for="adminPassword" class="form-label">{{ __('Password') }}</label>
                            <input type="password" class="form-control" id="adminPassword" name="password" required>
                        </div>

                        <!-- Role -->
                        <div class="mb-3">
                            <label for="adminRole" class="form-label">{{ __('Role') }}</label>
                            <select class="form-select" id="adminRole" name="role" required>
                                <option value="">{{ __('Select Role') }}</option>
                                <option value="1">{{ __('Admin') }}</option>
                                <option value="2">{{ __('Data Entry') }}</option>
                                <option value="3">{{ __('Region Manager') }}</option>
                                <option value="4">{{ __('Accountant') }}</option>
                                <option value="5">{{ __('Marketer') }}</option>
                                <option value="6">{{ __('driver') }}</option>
                            </select>
                        </div>

                        <!-- Region ID (only for marketers) -->
                        <div class="mb-3" id="regionSection" style="display: none;">
                            <label for="regionId" class="form-label">{{ __('Region') }}</label>
                            <select class="form-select" id="regionId" name="region_id">
                                <option value="">{{ __('Select Region') }}</option>
                                @foreach ($regions as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                    <button type="button" id="submit" class="btn btn-primary">{{ __('Send') }}</button>
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
                var table = $('#laravel_datatable').DataTable({
                    language: {!! file_get_contents(base_path('lang/' . session('locale', 'en') . '/datatable.json')) !!},
                    responsive: true,
                    processing: true,
                    serverSide: true,
                    pageLength: 10,

                    ajax: {
                        url: "{{ url('admin/list') }}",
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
                            data: 'phone',
                            name: 'phone'
                        },
                        {
                            data: 'email',
                            name: 'email'
                        },
                        {
                            data: 'role',
                            name: 'role',
                            render: function(data) {
                                if (data == 0) {
                                    return '<span class="badge bg-primary">{{ __('Super Admin') }}</span>';
                                }
                                if (data == 1) {
                                    return '<span class="badge bg-success">{{ __('Admin') }}</span>';
                                }
                                if (data == 2) {
                                    return '<span class="badge bg-danger">{{ __('Data Entry') }}</span>';
                                }
                                if (data == 3) {
                                    return '<span class="badge bg-warning">{{ __('Stock Manager') }}</span>';
                                }
                                if (data == 4) {
                                    return '<span class="badge bg-info">{{ __('Accountant') }}</span>';
                                }
                                if (data == 5) {
                                    return '<span class="badge bg-secondary">{{ __('Marketer') }}</span>';
                                }
                                if (data == 6) {
                                    return '<span class="badge bg-blue">{{ __('driver') }}</span>';
                                }
                            }
                        },
                        {
                            data: 'created_at',
                            name: 'created_at'
                        },
                        {
                            data: 'status',
                            name: 'status',
                            render: function(data) {
                                if (data == false) {
                                    return '<span class="badge bg-danger">{{ __('Inactive') }}</span>';
                                } else {
                                    return '<span class="badge bg-success">{{ __('Active') }}</span>';
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
                $("#createForm")[0].reset();
                $("#adminRole").trigger('change');
                $('#createModal').modal('show');
            })

            $('#adminRole').on('change', function() {
                if ($(this).val() == 3) {
                    $('#regionSection').show();
                } else {
                    $('#regionSection').hide();
                }
            })

            $('#submit').on('click', function() {
                var queryString = new FormData($("#createForm")[0]);
                $("#createModal").modal("hide");

                $.ajax({
                    url: "{{ url('admin/create') }}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'POST',
                    data: queryString,
                    dataType: 'JSON',
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.status == 1) {
                            Swal.fire({
                                title: "{{ __('Success') }}",
                                text: "{{ __('success') }}",
                                icon: 'success',
                                confirmButtonText: "{{ __('Ok') }}"
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
                    }
                });
            });

            $(document.body).on('click', '.delete', function() {
                var admin_id = $(this).attr('table_id');

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
                            url: "{{ url('admin/delete') }}",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type: 'POST',
                            data: {
                                admin_id: admin_id,
                                status: 0
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

            $(document.body).on('click', '.restore', function() {
                var admin_id = $(this).attr('table_id');

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
                            url: "{{ url('admin/restore') }}",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type: 'POST',
                            data: {
                                admin_id: admin_id,
                                status: 1
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
