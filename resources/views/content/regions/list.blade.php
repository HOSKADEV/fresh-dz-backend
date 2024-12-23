@extends('layouts/contentNavbarLayout')

@section('title', __('Regions'))

@section('vendor-script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.js"></script>
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
            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                data-bs-target="#regionModal">{{ __('Add region') }}</button>
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
    </script>

    <script>
        let map = null;
        let markers = [];
        let coordinates = [];
        let isEditing = false;

        function initializeMap() {
            if (!map) {
                map = L.map('map').setView([0, 0], 2);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors'
                }).addTo(map);

                // Add click event to map
                map.on('click', function(e) {
                    let marker = L.marker(e.latlng).addTo(map);
                    markers.push(marker);
                    coordinates.push([e.latlng.lat, e.latlng.lng]);
                });
            }

            // Fix map display issues that can occur in modal
            setTimeout(() => {
                map.invalidateSize();
            }, 100);
        }

        function clearPoints() {
            if (map) {
                markers.forEach(marker => map.removeLayer(marker));
            }
            markers = [];
            coordinates = [];
        }

        function resetModal() {
            clearPoints();
            $('#regionName').val('');
            $('#region_id').val('');
            isEditing = false;
            $('#regionModalLabel').text('Create New Region');
        }

        function addMarkersToMap(boundaryPoints) {
            if (!map) return;

            clearPoints();

            boundaryPoints.forEach(coord => {
                try {
                    let marker = L.marker([coord[0], coord[1]]).addTo(map);
                    markers.push(marker);
                    coordinates.push(coord);
                } catch (e) {
                    console.error('Error adding marker:', e);
                }
            });

            // Center map on the region
            if (boundaryPoints.length > 0) {
                try {
                    const bounds = L.latLngBounds(boundaryPoints.map(coord => L.latLng(coord[0], coord[1])));
                    map.fitBounds(bounds);
                } catch (e) {
                    console.error('Error fitting bounds:', e);
                }
            }
        }

        function loadRegionData(region_id) {
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

                        // Set form data
                        $('#regionName').val(region.name);
                        $('#region_id').val(region.id);
                        $('#regionModalLabel').text('Edit Region');
                        isEditing = true;

                        // Show modal first
                        $('#regionModal').modal('show');

                        // Initialize map and add markers after modal is shown
                        $('#regionModal').on('shown.bs.modal', function(e) {
                            initializeMap();

                            // Parse boundaries if needed
                            const boundaries = typeof region.boundaries === 'string' ?
                                JSON.parse(region.boundaries) :
                                region.boundaries;

                            // Add markers after a short delay to ensure map is ready
                            setTimeout(() => {
                                addMarkersToMap(boundaries);
                            }, 200);
                        });
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
        }

        // Handle modal show event for new regions
        document.getElementById('regionModal').addEventListener('shown.bs.modal', function() {
            initializeMap();
        });

        // Handle edit button click in datatable
        $(document).on('click', '.update', function() {
            const region_id = $(this).attr('table_id');
            loadRegionData(region_id);
        });

        // Reset modal when it's hidden
        $('#regionModal').on('hidden.bs.modal', function() {
            resetModal();
        });

        $(document).ready(function() {
            $('#submit_region').on('click', function() {
                const regionName = $('#regionName').val();
                const region_id = $('#region_id').val();

                if (!regionName) {
                    Swal.fire({
                        title: 'Error',
                        text: 'Please enter a region name',
                        icon: 'error'
                    });
                    return;
                }

                if (coordinates.length < 3) {
                    Swal.fire({
                        title: 'Error',
                        text: 'Please select at least 3 points to create a region',
                        icon: 'error'
                    });
                    return;
                }

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
                                text: isEditing ? 'Region updated successfully' :
                                    'Region created successfully',
                                icon: 'success'
                            }).then((result) => {
                                // Clear form and close modal
                                $('#regionModal').modal('hide');

                                // Reload datatable
                                if (typeof $('#laravel_datatable').DataTable !==
                                    'undefined') {
                                    $('#laravel_datatable').DataTable().ajax.reload();
                                }
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
        });
    </script>
@endsection
