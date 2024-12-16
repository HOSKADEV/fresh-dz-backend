@extends('layouts/contentNavbarLayout')

@section('title', __('Discounts'))

@section('content')

    <h4 class="fw-bold py-3 mb-3 row justify-content-between">
        <div class="col-md-auto">
            <span class="text-muted fw-light">{{ __('Discounts of') }} /</span> {{ $product->unit_name }}
        </div>

        <div class="col-md-auto">
            <button type="button" class="btn btn-primary" id="create">{{ __('Add discount') }}</button>
        </div>
    </h4>

    <!-- Basic Bootstrap Table -->
    <div class="card">
        <h5 class="card-header">{{ __('Discounts table') }}</h5>
        <div class="table-responsive text-nowrap">
            <table class="table" id="laravel_datatable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Discount') }}</th>
                        <th>{{ __('Start date') }}</th>
                        <th>{{ __('End date') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>


    {{-- discount modal --}}
    <div class="modal fade" id="discount_modal" aria-hidden="true">
      <div class="modal-dialog modal-sm" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h4 class="fw-bold py-1 mb-1">{{ __('Add discount') }}</h4>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                  <input type="text" id="discount_form_type" hidden />
                  <input type="text" class="form-control" id="discount_id" name="discount_id" hidden />
                  <form class="form-horizontal" onsubmit="event.preventDefault()" action="#"
                      enctype="multipart/form-data" id="discount_form">

                      <input type="text" class="form-control" id="product_id" name="product_id" value="{{$product->id}}" hidden />

                      <div class="mb-3">
                          <label class="form-label" for="type">{{ __('Type') }}</label>
                          <select class="form-select" id="type" name="type">
                              <option value="1"> {{ __('Fixed') }}</option>
                              <option value="2"> {{ __('Percentage') }}</option>
                          </select>
                      </div>

                      <div class="mb-3">
                          <label class="form-label" for="name">{{ __('Discount amount') }}</label>
                          <input type="text" class="form-control" id="amount" name="amount" />
                      </div>

                      <div class="mb-3">
                          <label class="form-label" for="name">{{ __('Start date') }}</label>
                          <input type="date" class="form-control" id="start_date" name="start_date" />
                      </div>

                      <div class="mb-3">
                          <label class="form-label" for="name">{{ __('End date') }}</label>
                          <input type="date" class="form-control" id="end_date" name="end_date" />
                      </div>


                      <div class="mb-3" style="text-align: center">
                          <button type="submit" id="submit_discount" name="submit_discount"
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
                        url: "{{ url('discount/list') }}",
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
                            data: 'name',
                            name: 'name'
                        },

                        {
                            data: 'amount',
                            name: 'amount'
                        },

                        {
                            data: 'start_date',
                            name: 'start_date'
                        },


                        {
                            data: 'end_date',
                            name: 'end_date'
                        },


                        {
                            data: 'action',
                            name: 'action',

                        }

                    ],
                });
            }

            $('#create').on('click', function() {
                document.getElementById('discount_form').reset();
                document.getElementById('discount_form_type').value = "create";
                $("#discount_modal").modal('show');
            });


            $(document.body).on('click', '.update', function() {
                document.getElementById('discount_form').reset();
                document.getElementById('discount_form_type').value = "update";
                var discount_id = $(this).attr('table_id');
                $("#discount_id").val(discount_id);

                $.ajax({
                    url: '{{ url('discount/update') }}',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'POST',
                    data: {
                        discount_id: discount_id
                    },
                    dataType: 'JSON',
                    success: function(response) {
                        if (response.status == 1) {

                            document.getElementById('product_id').value = response.data
                                .product_id;
                            document.getElementById('amount').value = response.data.amount;
                            document.getElementById('start_date').value = response.data
                                .start_date;
                            document.getElementById('end_date').value = response.data.end_date;
                            document.getElementById('start_date').readOnly = true;
                            document.getElementById('type').value = 2;

                            $("#discount_modal").modal("show");
                        }
                    }
                });
            });

            $('#submit_discount').on('click', function() {

                var formdata = new FormData($("#discount_form")[0]);
                var formtype = document.getElementById('discount_form_type').value;
                console.log(formtype);
                if (formtype == "create") {
                    url = "{{ url('discount/create') }}";
                }

                if (formtype == "update") {
                    url = "{{ url('discount/update') }}";
                    formdata.append("discount_id", document.getElementById('discount_id').value)
                }

                $("#discount_modal").modal("hide");


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

                var discount_id = $(this).attr('table_id');

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
                            url: "{{ url('discount/delete') }}",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type: 'POST',
                            data: {
                                discount_id: discount_id
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
