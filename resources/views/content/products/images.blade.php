@extends('layouts/contentNavbarLayout')

@section('title', __('Items'))

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
            <span class="text-muted fw-light">{{ __('Order') }} /</span> {{ __('Items') }}
        </div>

        <div class="col-md-auto">
            <button type="button" class="btn btn-primary" id="create" style="float:right">{{ __('Add item') }}</button>
        </div>
    </h4>

    <!-- Basic Bootstrap Table -->
    <div class="card">
        <h5 class="card-header">{{ __('Items table') }}</h5>
        <div class="table-responsive text-nowrap">
            <table class="table" id="laravel_datatable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('Product') }}</th>
                        <th>{{ __('Type') }}</th>
                        <th>{{ __('Price') }}</th>
                        <th>{{ __('Quantity') }}</th>
                        <th>{{ __('Discount') }}</th>
                        <th>{{ __('Amount') }}</th>
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
                    <h5 class="modal-title" id="modal">{{ trans('post.create_new_post') }}</h5>
                    {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> --}}
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">{{ __('Product images') }}</label>
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
                            {{ __('close') }}
                        </button>
                        <button type="button" id="images_submit_btn" class="btn btn-primary">
                            {{ __('send') }}
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
           /*  load_data(); */

            function load_data() {
                //$.fn.dataTable.moment( 'YYYY-M-D' );
                var table = $('#laravel_datatable').DataTable({

                    language: {!! file_get_contents(base_path('lang/' . session('locale', 'en') . '/datatable.json')) !!},
                    responsive: true,
                    processing: true,
                    serverSide: true,
                    pageLength: 10,

                    ajax: {
                        url: "{{ url('item/list') }}",
                        type: 'POST',
                        data: {
                            cart_id: "{{ $product->id }}"
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
                            data: 'product',
                            name: 'product'
                        },

                        {
                            data: 'type',
                            name: 'type'
                        },

                        {
                            data: 'price',
                            name: 'price'
                        },

                        {
                            data: 'quantity',
                            name: 'quantity'
                        },

                        {
                            data: 'discount',
                            name: 'discount'
                        },


                        {
                            data: 'amount',
                            name: 'amount'
                        },


                        {
                            data: 'action',
                            name: 'action',

                        }

                    ],
                    footerCallback: function(row, data, start, end, display) {
                        let api = this.api();

                        // Remove the formatting to get integer data for summation
                        let intVal = function(i) {
                            return typeof i === 'string' ?
                                i.replace(/[\$,]/g, '') * 1 :
                                typeof i === 'number' ?
                                i :
                                0;
                        };

                        api.columns('.sum', {
                            page: 'total'
                        }).every(function() {
                            var sum = this
                                .data()
                                .reduce(function(a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);

                            this.footer().innerHTML = sum;
                        });
                    }
                });
            }

            $('#create').on('click', function(){
              $('#modal').modal('show');
            })

        });
    </script>
@endsection
