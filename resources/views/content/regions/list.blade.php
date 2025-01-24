@extends('layouts/contentNavbarLayout')

@section('title', __('Regions'))

@section('vendor-script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.js"></script>
    <script src="{{ asset('assets/vendor/js/mapRegion.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/mapDeliveryPoint.js') }}"></script>
@endsection

@section('page-style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.css" />
    <style>
        #map {
            width: 100%;
            height: 400px;
            margin: 10px 0;
        }

        .controls {
            margin: 20px 0;
        }
    </style>
@endsection

@section('content')

    <h4 class="fw-bold py-3 mb-3 row justify-content-between">
        <div class="col-md-auto">
            <span class="text-muted fw-light">{{ __('Regions') }} /</span> {{ __('Browse regions') }}
        </div>
        <div class="col-md-auto">
            <button type="button" class="btn btn-primary" id="create">{{ __('Add region') }}</button>
        </div>
    </h4>

    <!-- Basic Bootstrap Table -->
    <div class="card">
        <div class="table-responsive text-nowrap">
            <div class="table-header row justify-content-between">
                <h5 class="col-md-auto">{{ __('Regions table') }}</h5>
            </div>
            <table class="table" id="laravel_datatable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Created at') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    @include('content.regions.modal')
    @include('content.regions.delivery')

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
                        url: "{{ url('region/list') }}",
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
                            data: 'action',
                            name: 'action',
                            searchable: false
                        }

                    ]
                });
            }
        });

        $(document).on('click', '.update', function() {
            const region_id = $(this).attr('table_id');
            $.ajax({
                url: "{{ url('region/update') }}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                data: {
                    region_id: region_id
                },
                success: function(response) {
                    if (response.status == 1) {
                        const region = response.data;

                        $('#regionName').val(region.name);
                        $('#region_id').val(region.id);
                        isEditing = true;

                        $('#regionModal').modal('show');

                        setTimeout(() => {
                            initializeMap();
                            const boundaries = JSON.parse(region.boundaries);
                            setTimeout(() => {
                                addMarkersToMap(boundaries);
                            }, 300);
                        }, 150);
                    }
                },
                error: function(data) {
                    var errors = data.responseJSON;
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

            var region_id = $(this).attr('table_id');

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
                        url: "{{ url('region/delete') }}",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: 'POST',
                        data: {
                            region_id: region_id
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

        $('#create').on('click', function() {
            setTimeout(initializeMap, 100);
            isEditing = false;
            $('#regionModal').modal('show');
        });


        $('#regionModal').on('hidden.bs.modal', function() {
            // Clean up when modal is hidden
            destroyMap();
        });


        $('#submit_region').on('click', function() {
            const regionName = $('#regionName').val();
            const region_id = $('#region_id').val();

            const url = isEditing ?
                "{{ url('region/update') }}" :
                "{{ url('region/create') }}";

            const data = isEditing ? {
                region_id: region_id,
                name: regionName,
                coordinates: coordinates
            } : {
                name: regionName,
                coordinates: coordinates
            };

            $.ajax({
                url: url,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                data: data,
                dataType: 'JSON',
                success: function(response) {
                    if (response.status == 1) {
                        Swal.fire({
                            title: "{{ __('Success') }}",
                            text: "{{ __('success') }}",
                            icon: 'success',
                            confirmButtonText: 'Ok'
                        }).then((result) => {
                            $('#regionModal').modal('hide');
                            $('#laravel_datatable').DataTable().ajax.reload();
                        });
                    }
                },
                error: function(data) {
                    var errors = data.responseJSON;
                    Swal.fire(
                        "{{ __('Error') }}",
                        errors.message,
                        'error'
                    );
                    // Render the errors with js ...
                }
            });
        });

        $(document).on('click', '.delivery', function() {
            $('#deliveryForm')[0].reset();
            deliveryMarker = null;
            const region_id = $(this).attr('table_id');
            $('#delivery_region_id').val(region_id);

            $.ajax({
                url: "{{ url('region/update') }}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                data: {
                    region_id: region_id
                },
                success: function(response) {
                    if (response.status == 1) {
                        const region = response.data;

                        $('#deliveryPointModal').modal('show');

                        setTimeout(() => {
                            initializeDeliveryMap();
                            const boundaries = JSON.parse(region.boundaries);
                            setTimeout(() => {
                                displayRegion(boundaries);

                                // If region already has delivery coordinates, show them
                                if (region.latitude && region.longitude) {
                                    setDeliveryPoint(region.latitude, region.longitude);

                                }
                            }, 300);
                        }, 150);
                    }
                },
                error: function(data) {
                    var errors = data.responseJSON;
                    Swal.fire(
                        "{{ __('Error') }}",
                        errors.message,
                        'error'
                    );
                    // Render the errors with js ...
                }
            });
        });

        $(document).on('click', '#submit_delivery_point', function() {
            const point = getDeliveryPoint();
            if (!point) {
                Swal.fire({
                    title: "{{ __('Error') }}",
                    text: "{{ __('Please select a delivery point') }}",
                    icon: 'error'
                });
                return;
            }

            const region_id = $('#delivery_region_id').val();

            $.ajax({
                url: "{{ url('region/update') }}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                data: {
                    region_id: region_id,
                    longitude: point.lng,
                    latitude: point.lat
                },
                success: function(response) {
                    if (response.status == 1) {
                        $('#deliveryPointModal').modal('hide');
                        Swal.fire({
                            title: "{{ __('Success') }}",
                            text: "{{ __('success') }}",
                            icon: 'success',
                            confirmButtonText: 'Ok'
                        });

                        // Optionally refresh the datatable if you have one
                        // if ($.fn.DataTable.isDataTable('#regionsTable')) {
                        //     $('#regionsTable').DataTable().ajax.reload();
                        // }
                    }
                },
                error: function(data) {
                    var errors = data.responseJSON;
                    Swal.fire(
                        "{{ __('Error') }}",
                        errors.message,
                        'error'
                    );
                    // Render the errors with js ...
                }
            });
        });
    </script>

@endsection
