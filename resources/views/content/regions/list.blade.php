@extends('layouts/contentNavbarLayout')

@section('title', __('Regions'))

@section('vendor-script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.js"></script>
    <script src="{{ asset('assets/vendor/js/map.js') }}"></script>
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
                <h5 class="col-md-auto">{{ __('Ads region') }}</h5>
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
                error: function(xhr, status, error) {
                    Swal.fire({
                        title: 'Error',
                        text: 'Failed to load region data',
                        icon: 'error'
                    });
                }
            });
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
                            title: 'Success',
                            text: response.message,
                            icon: 'success'
                        }).then((result) => {
                            $('#regionModal').modal('hide');
                            $('#laravel_datatable').DataTable().ajax.reload();
                        });
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        title: 'Error',
                        text: 'An error occurred while saving the region',
                        icon: 'error'
                    });
                }
            });
        });
    </script>

@endsection
