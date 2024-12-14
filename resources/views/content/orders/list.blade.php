@extends('layouts/contentNavbarLayout')

@section('title', __('Orders'))

@section('content')

<h4 class="fw-bold py-3 mb-3">
  <span class="text-muted fw-light">{{__('Orders')}} /</span> {{__('Browse orders')}}
  {{-- <small>
  <div class="form-check form-switch mb-2" style="display: inline; float:right">
    <input class="form-check-input" type="checkbox" id="shipping_switch" @if($shipping->status == 1 ) checked @endif>
    <label class="form-check-label" for="shipping_switch" >{{__('Free Shipping')}}</label>
  </div>
  </small> --}}
</h4>

<!-- Basic Bootstrap Table -->
<div class="card">
  <div class="table-responsive text-nowrap">
    <div class="table-header row justify-content-between">
      <h5 class="col-md-auto">{{ __('Orders table') }}</h5>
      <div class="col-md-auto">
        <select class="form-select filter-select" id="status" name="status">
          <option value="default" > {{__('Default')}}</option>
          <option value="pending" > {{__('Pending')}}</option>
          <option value="accepted" > {{__('Accepted')}}</option>
          <option value="canceled" > {{__('Canceled')}}</option>
          <option value="ongoing" > {{__('Ongoing')}}</option>
          <option value="delivered" > {{__('Delivered')}}</option>
          <option value="" > {{__('All')}}</option>
        </select>
      </div>
    </div>
    <table class="table" id="laravel_datatable">
      <thead>
        <tr>
          <th>#</th>
          <th>{{__('User')}}</th>
          <th>{{__('Phone')}}</th>
          <th>{{__('Created at')}}</th>
          <th>{{__('Status')}}</th>
          <th>{{__('Driver')}}</th>
          <th>{{__('Purchase amount')}}</th>
          <th>{{__('Tax amount')}}</th>
          <th>{{__('Total amount')}}</th>
          <th>{{__('Actions')}}</th>
        </tr>
      </thead>
    </table>
  </div>
</div>

{{-- invoice modal --}}
{{-- <div class="modal fade" id="invoice_modal"  aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="fw-bold py-1 mb-1">{{__('Create invoice')}}</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">


        <form class="form-horizontal" onsubmit="event.preventDefault()" action="#"
          enctype="multipart/form-data" id="invoice_form">

            <input type="text" id="invoice_order_id" name="order_id" hidden />

            <div class="mb-3">
              <label class="form-label" for="tax_type">{{__('Tax type')}}</label>
              <select class="form-select" id="tax_type" name="tax_type">
                <option value="1" > {{__('Fixed')}}</option>
                <option value="2" > {{__('Percentage')}}</option>
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label" for="tax_amount">{{__('Tax amount')}}</label>
              <input type="number" class="form-control" id="tax_amount" name="tax_amount">
              </select>
            </div>

          <div class="mb-3" style="text-align: center">
            <button type="submit" id="submit_invoice" name="submit_invoice" class="btn btn-primary">{{__('Send')}}</button>
          </div>



        </form>
      </div>
    </div>
  </div>
</div> --}}

{{-- payment modal --}}
<div class="modal fade" id="payment_modal"  aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="fw-bold py-1 mb-1">{{__('Order payment')}}</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

        <form class="form-horizontal" onsubmit="event.preventDefault()" action="#"
          enctype="multipart/form-data" id="payment_form">

            <input type="text" id="payment_order_id" name="order_id" hidden />

            <div class="mb-3">
              <label class="form-label" for="payment_method">{{__('Payment method')}}</label>
              <select class="form-select" id="payment_method" name="payment_method">
                <option value="1" > {{__('Card')}}</option>
                <option value="2" > {{__('Cash')}}</option>
              </select>
            </div>


          <div class="mb-3" style="text-align: center">
            <button type="submit" id="submit_payment" name="submit_payment" class="btn btn-primary">{{__('Send')}}</button>
          </div>



        </form>
      </div>
    </div>
  </div>
</div>

{{-- driver modal --}}
<div class="modal fade" id="driver_modal"  aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="fw-bold py-1 mb-1">{{__('Ship order')}}</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

        <form class="form-horizontal" onsubmit="event.preventDefault()" action="#"
          enctype="multipart/form-data" id="driver_form">


            <input type="text" id="driver_order_id" name="order_id" hidden />

            <div class="mb-3">
              <label class="form-label" for="driver_id">{{__('Driver')}}</label>
              <select class="form-select" id="driver_id" name="driver_id">
                <option value="" > {{__('Select driver')}}</option>
                @foreach ($drivers as $driver)
                  <option value="{{$driver->id}}" > {{$driver->fullname()}} </option>
                @endforeach
              </select>
            </div>
          <div class="mb-3" style="text-align: center">
            <button type="submit" id="submit_driver" name="submit_driver" class="btn btn-primary">{{__('Send')}}</button>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>

{{-- note modal --}}
<div class="modal fade" id="note_modal"  aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="fw-bold py-1 mb-1">{{__('Order note')}}</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

        <form class="form-horizontal" onsubmit="event.preventDefault()" action="#"
          enctype="multipart/form-data" id="note_form">


            <input type="text" id="note_order_id" name="order_id" hidden />

            <div class="mb-3">
              <label class="form-label" for="driver_id">{{__('Note')}}</label>
              <textarea id="note" name="note" class="form-control" rows="5" style="height: 125;" dir="rtl" ></textarea>
            </div>
          <div class="mb-3" style="text-align: center">
            <button type="submit" id="submit_note" name="submit_note" class="btn btn-primary">{{__('Send')}}</button>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>

@endsection

@section('page-script')
<script>
  $(document).ready(function(){
    load_data();
    function load_data(status = 'default') {
        //$.fn.dataTable.moment( 'YYYY-M-D' );
        var table = $('#laravel_datatable').DataTable({
          language:  {!! file_get_contents(base_path('lang/'.session('locale','en').'/datatable.json')) !!},
            responsive: true,
            processing: true,
            serverSide: true,
            pageLength: 10,

            ajax: {
                url: "{{ url('order/list') }}",
                type: 'POST',
                data:{
                  status:status,
                },
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            },

            columns: [

                {
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },

                {
                    data: 'user',
                    name: 'user'
                },

                {
                    data: 'phone',
                    name: 'phone'
                },

                {
                    data: 'created_at',
                    name: 'created_at'
                },

                {
                    data: 'status',
                    name: 'status',
                    render: function(data){
                          if(data == 'pending'){
                              return '<span class="badge bg-secondary">{{__("pending")}}</span>';
                            }
                            if(data == 'accepted'){
                              return '<span class="badge bg-primary">{{__("accepted")}}</span>';
                            }
                            if(data == 'canceled'){
                              return '<span class="badge bg-danger">{{__("canceled")}}</span>';
                            }
                            if(data == 'ongoing'){
                              return '<span class="badge bg-info">{{__("ongoing")}}</span>';
                            }
                            if(data == 'delivered'){
                              return '<span class="badge bg-success">{{__("delivered")}}</span>';
                            }
                          }
                },


                {
                    data: 'driver',
                    name: 'driver'
                },

                {
                    data: 'purchase_amount',
                    name: 'purchase_amount'
                },

                {
                    data: 'tax_amount',
                    name: 'tax_amount'
                },

                {
                    data: 'total_amount',
                    name: 'total_amount'
                },

                {
                    data: 'action',
                    name: 'action',
                    searchable: false
                }

            ]
        });
    }

    $('#status').on('change', function() {

      var status = document.getElementById('status').value;

      var table = $('#laravel_datatable').DataTable();
      table.destroy();
      load_data(status);

    });
  });



  $(document.body).on('click', '.refuse', function() {

      var order_id = $(this).attr('table_id');

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
            url: "{{ url('order/update') }}",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type:'POST',
            data:{
              order_id : order_id,
              status : "canceled"},
            dataType : 'JSON',
            success:function(response){
                if(response.status==1){

                  Swal.fire(
                    "{{ __('Success') }}",
                    "{{ __('success') }}",
                    'success'
                  ).then((result)=>{
                    $('#laravel_datatable').DataTable().ajax.reload();
                  });
                }
              }
          });


        }
      })

  });

  $(document.body).on('click', '.accept', function() {
      //document.getElementById('invoice_form').reset();
      //document.getElementById('invoice_order_id').value = order_id;
      //$("#invoice_modal").modal('show');
      var order_id = $(this).attr('table_id');

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
            url: "{{ url('order/update') }}",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type:'POST',
            data:{
              order_id : order_id,
              status : "accepted"},
            dataType : 'JSON',
            success:function(response){
                if(response.status==1){

                  Swal.fire(
                    "{{ __('Success') }}",
                    "{{ __('success') }}",
                    'success'
                  ).then((result)=>{
                    $('#laravel_datatable').DataTable().ajax.reload();
                  });
                }
              }
          });


        }
      })
  });


  $(document.body).on('click', '.ship', function() {
    document.getElementById('driver_form').reset();
    var order_id = $(this).attr('table_id');
    document.getElementById('driver_order_id').value = order_id;
    $("#driver_modal").modal('show');
  });

  $(document.body).on('click', '.note', function() {
      document.getElementById('note_form').reset();
      var order_id = $(this).attr('table_id');
      document.getElementById('note_order_id').value = order_id;

      $.ajax({
            url: "{{ url('order/update') }}",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type:'POST',
            data:{
              order_id : order_id},
            dataType : 'JSON',
            success:function(response){
                if(response.status==1){


                    document.getElementById('note').innerHTML = response.data.note;
                    $("#note_modal").modal('show');
                }
              }
          });


  });

  $(document.body).on('click', '#submit_note', function() {

    var formdata = new FormData($("#note_form")[0]);

      $.ajax({
            url: "{{ url('order/update') }}",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type:'POST',
            data:formdata,
            dataType : 'JSON',
            contentType: false,
            processData: false,
            success:function(response){
                if(response.status==1){

                  Swal.fire(
                    "{{ __('Success') }}",
                    "{{ __('success') }}",
                    'success'
                  )
                }
              }
          });

      $("#note_modal").modal('hide');
  });

  $(document.body).on('click', '.payment', function() {
    document.getElementById('payment_form').reset();
    var order_id = $(this).attr('table_id');
    document.getElementById('payment_order_id').value = order_id;
    $("#payment_modal").modal('show');
  });

 /*  $('#submit_invoice').on('click', function() {
    var formdata = new FormData($("#invoice_form")[0]);
    formdata.append('status','accepted');
    $("#driver_modal").modal('hide');

    $.ajax({
            url: "{{ url('order/update') }}",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type:'POST',
            data:formdata,
            dataType : 'JSON',
            contentType: false,
            processData: false,
            success:function(response){
                if(response.status==1){

                  Swal.fire(
                    "{{ __('Success') }}",
                    "{{ __('success') }}",
                    'success'
                  ).then((result)=>{
                    $('#laravel_datatable').DataTable().ajax.reload();
                  });
                }
              }
          });

  }); */

  $(document.body).on('click', '.delete', function() {

var order_id = $(this).attr('table_id');

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
      url: "{{ url('order/delete') }}",
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      type:'POST',
      data:{order_id : order_id},
      dataType : 'JSON',
      success:function(response){
          if(response.status==1){

            Swal.fire(
              "{{ __('Success') }}",
              "{{ __('success') }}",
              'success'
            ).then((result)=>{
              $('#laravel_datatable').DataTable().ajax.reload();
            });
          }
        }
    });


  }
})
});

  $('#submit_driver').on('click', function() {
    var formdata = new FormData($("#driver_form")[0]);
    formdata.append('status','ongoing');
    $("#driver_modal").modal('show');

    $.ajax({
            url: "{{ url('order/update') }}",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type:'POST',
            data:formdata,
            dataType : 'JSON',
            contentType: false,
            processData: false,
            success:function(response){
                if(response.status==1){

                  Swal.fire(
                    "{{ __('Success') }}",
                    "{{ __('success') }}",
                    'success'
                  ).then((result)=>{
                    $('#laravel_datatable').DataTable().ajax.reload();
                  });
                }
              }
          });

  });

  $('#submit_payment').on('click', function() {
    var formdata = new FormData($("#payment_form")[0]);
    formdata.append('status','delivered');
    $("#payment_modal").modal('hide');

    $.ajax({
            url: "{{ url('order/update') }}",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type:'POST',
            data:formdata,
            dataType : 'JSON',
            contentType: false,
            processData: false,
            success:function(response){
                if(response.status==1){

                  Swal.fire(
                    "{{ __('Success') }}",
                    "{{ __('success') }}",
                    'success'
                  ).then((result)=>{
                    $('#laravel_datatable').DataTable().ajax.reload();
                  });
                }
              }
          });
  });

  $('#shipping_switch').on('change', function() {
    var checkbox = document.getElementById('shipping_switch');
    var status = checkbox.checked ? 1 : 0;
    $.ajax({
            url: "{{ url('shipping/switch') }}",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type:'POST',
            dataType : 'JSON',
            data:{
              status:status,
            },
            //contentType: false,
            //processData: false,
            success:function(response){
                if(response.status==1){
                  $('#laravel_datatable').DataTable().ajax.reload();
                }
              }
          });

  });

  $(document).on('click','.invoice',function(){

      Swal.fire({
        title: "{{ __('Wait a moment') }}",
        icon: 'info',
        html:
        '<div style="height:50px;"><div class="spinner-border text-primary" role="status"><span class="visually-hidden"></div></div>',
        showCloseButton: false,
        showCancelButton: false,
        showConfirmButton: false,
        allowOutsideClick: false,
        allowEscapeKey: false,
        allowEnterKey: false,
      });

      var invoice_id = $(this).attr('table_id');


      $.ajax({
          url: '{{ url('invoice/update') }}',
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          type:'POST',
          data:{invoice_id : invoice_id},
          dataType : 'JSON',
          success:function(response){
              if(response.status==1){
                Swal.close();
                window.open(response.data)
              }
            }
        });
  });


</script>
@endsection
