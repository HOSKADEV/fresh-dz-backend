@extends('layouts/contentNavbarLayout')

@section('title', __('Items'))

@section('content')

<h4 class="fw-bold py-3 mb-3">
  <span class="text-muted fw-light">{{__('Order')}} /</span> {{__('Items')}}
  @if ($order->status == 'pending' || $order->status == 'accepted')
    <button type="button" class="btn btn-primary" id="create" style="float:right">{{__('Add item')}}</button>
  @endif

</h4>

<!-- Basic Bootstrap Table -->
<div class="card">
  <h5 class="card-header">{{__('Items table')}}</h5>
  <div class="table-responsive text-nowrap">
    <table class="table" id="laravel_datatable">
      <thead>
        <tr>
          <th>#</th>
          <th>{{__('Product')}}</th>
          <th>{{__('Type')}}</th>
          <th>{{__('Price')}}</th>
          <th>{{__('Quantity')}}</th>
          <th>{{__('Discount')}}</th>
          <th class="sum">{{__('Amount')}}</th>
          <th>{{__('Actions')}}</th>
        </tr>
      </thead>
      <tfoot>
        <tr>
          <th></th>
          <th></th>
          <th></th>
          <th></th>
          <th></th>
          <th>{{__('Total')}}</th>
          <th></th>
          <th></th>
        </tr>
    </tfoot>
    </table>
  </div>
</div>

{{-- add modal --}}
<div class="modal fade" id="add_modal"  aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="fw-bold py-1 mb-1">{{__('add item')}}</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">


        <form class="form-horizontal" onsubmit="event.preventDefault()" action="#"
          enctype="multipart/form-data" id="add_form">

          <input type="text" class="form-control" name="order_id" value="{{$order->id}}" hidden/>

          <div class="mb-3">
            <label class="form-label" for="category">{{__('Category')}}</label>
            <select class="form-select"  id="category">
              <option value="" > {{__('Select Category')}}</option>
              @foreach ($categories as $category)
              <option value="{{$category->id}}" >{{$category->name}}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label" for="subcategory">{{__('Subcategory')}}</label>
            <select class="form-select"  id="subcategory">
              <option value="" > {{__('Select category first')}}</option>
            </select>
          </div>
            <div class="mb-3">
              <label class="form-label" for="product_id">{{__('Product')}}</label>
              <select class="form-select" id="product" name="product_id">
                <option value="" > {{__('Select subcategory first')}}</option>
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label" for="quantity">{{__('Quantity')}}</label>
              <input type="number" class="form-control"  name="quantity">
              </select>
            </div>



          <div class="mb-3" style="text-align: center">
            <button type="submit" id="submit_add" name="submit_add" class="btn btn-primary">{{__('Send')}}</button>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>

{{-- edit modal --}}
<div class="modal fade" id="edit_modal"  aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="fw-bold py-1 mb-1">{{__('add item')}}</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">


        <form class="form-horizontal" onsubmit="event.preventDefault()" action="#"
          enctype="multipart/form-data" id="edit_form">

          <input type="text" class="form-control" name="order_id" value="{{$order->id}}" hidden/>

          <input type="text" class="form-control" id="item_id" name="item_id" hidden/>

            <div class="mb-3">
              <label class="form-label" for="quantity">{{__('Quantity')}}</label>
              <input type="number" class="form-control" id="quantity" name="quantity">
              </select>
            </div>



          <div class="mb-3" style="text-align: center">
            <button type="submit" id="submit_edit" name="submit_edit" class="btn btn-primary">{{__('Send')}}</button>
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
    function load_data() {
        //$.fn.dataTable.moment( 'YYYY-M-D' );
        var table = $('#laravel_datatable').DataTable({

            responsive: true,
            processing: true,
            serverSide: true,
            pageLength: 100,

            ajax: {
                url: "{{ url('item/list') }}",
                type: 'POST',
                data: {cart_id : "{{$order->cart_id}}"},
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
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
                    render:function(data){
                      if("{{$order->status}}" == "pending" || "{{$order->status}}" == "accepted" ){
                      /* return '<div class="dropdown"><button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button><div class="dropdown-menu">'
                        +data+'</div></div>' */
                        return '<span>'+data+'</span>';
                      }else{
                        return null;
                      }
                    }
                }

            ],
            footerCallback: function (row, data, start, end, display) {
              let api = this.api();

              // Remove the formatting to get integer data for summation
              let intVal = function (i) {
                  return typeof i === 'string'
                      ? i.replace(/[\$,]/g, '') * 1
                      : typeof i === 'number'
                      ? i
                      : 0;
              };

              api.columns('.sum', { page: 'total'}).every( function () {
                var sum = this
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

                this.footer().innerHTML = sum;
              } );
            }
        });
    }

    $('#create').on('click', function() {
      document.getElementById('add_form').reset();
      $("#add_modal").modal('show');
    });

    $(document.body).on('click', '.edit', function() {
      document.getElementById('edit_form').reset();
      var item_id = $(this).attr('table_id');
      document.getElementById('item_id').value = item_id;

      var quantity = $(this).attr('quantity');
      document.getElementById('quantity').value = quantity;

      $("#edit_modal").modal('show');
    });

    $('#submit_add').on('click', function() {

      var formdata = new FormData($("#add_form")[0]);

      $("#add_modal").modal("hide");


      $.ajax({
        url: "{{ url('item/add') }}",
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        type:'POST',
        data:formdata,
        dataType : 'JSON',
        contentType: false,
        processData: false,
        success:function(response){
          if(response.status==1){
                Swal.fire({
                  title: "{{ __('Success') }}",
                  text: "{{ __('success') }}",
                  icon: 'success',
                  confirmButtonText: 'Ok'
                }).then((result) => {
                  location.reload();
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
        error: function(data){
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

    $('#submit_edit').on('click', function() {

      var formdata = new FormData($("#edit_form")[0]);

      $("#edit_modal").modal("hide");


      $.ajax({
        url: "{{ url('item/edit') }}",
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        type:'POST',
        data:formdata,
        dataType : 'JSON',
        contentType: false,
        processData: false,
        success:function(response){
          if(response.status==1){
                Swal.fire({
                  title: "{{ __('Success') }}",
                  text: "{{ __('success') }}",
                  icon: 'success',
                  confirmButtonText: 'Ok'
                }).then((result) => {
                  location.reload();
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
        error: function(data){
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

      var item_id = $(this).attr('table_id');

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
            url: "{{ url('item/delete') }}",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type:'POST',
            data:{item_id : item_id},
            dataType : 'JSON',
            success:function(response){
                if(response.status==1){

                  Swal.fire(
                    "{{ __('Success') }}",
                    "{{ __('success') }}",
                    'success'
                  ).then((result)=>{
                    location.reload();
                  });
                }
              }
          });


        }
      })
    });

    $('#category').on('change', function() {

      var category_id = document.getElementById('category').value;
      $.ajax({
          url: '{{ url('subcategory/get?all=1') }}',
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          type:'POST',
          data:{category_id : category_id},
          dataType : 'JSON',
          success:function(response){
              if(response.status==1){

                var subcategories = document.getElementById('subcategory');
                subcategories.innerHTML = '<option value="">{{__("Not selected")}}</option>';

                for (var i = 0; i<response.data.length; i++){
                    var option = document.createElement('option');
                    option.value = response.data[i].id;
                    option.innerHTML = response.data[i].name;
                    subcategories.appendChild(option);
                }

              }
            }
        });
      });

      $('#subcategory').on('change', function() {

        var subcategory_id = document.getElementById('subcategory').value;
        $.ajax({
            url: '{{ url('product/get?all=1') }}',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type:'POST',
            data:{subcategory_id : subcategory_id},
            dataType : 'JSON',
            success:function(response){
                if(response.status==1){

                  var products = document.getElementById('product');
                  products.innerHTML = '<option value="">{{__("Not selected")}}</option>';

                  for (var i = 0; i<response.data.length; i++){
                      var option = document.createElement('option');
                      option.value = response.data[i].id;
                      option.innerHTML = response.data[i].unit_name;
                      products.appendChild(option);
                  }

                }
              }
        });
      });
  });


</script>
@endsection
