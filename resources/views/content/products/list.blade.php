@extends('layouts/contentNavbarLayout')

@section('title', __('Products'))

@section('content')

<h4 class="fw-bold py-3 mb-3">
  <span class="text-muted fw-light">{{__('Products')}} /</span> {{__('Browse products')}}
  <button type="button" class="btn btn-primary" id="create" style="float:right">{{__('Add Product')}}</button>
</h4>

<!-- Basic Bootstrap Table -->
<div class="card">
  <h5 class="card-header">{{__('Products table')}}
    <select class="filter-select" id="category" name="category">
      <option value="" > {{__('Category filter')}}</option>
      @foreach ($categories as $category)
        <option value="{{$category->id}}" > {{$category->name}} </option>
      @endforeach
    </select>

    <select class="filter-select" id="subcategory" name="subcategory">
      <option value="" > {{__('Subcategory filter')}} </option>
    </select>


    <select class="filter-select" id="discount" name="discount">
      <option value="" > {{__('Discount filter')}}</option>
      <option value="1" > {{__('Discounted')}}</option>
      <option value="2" > {{__('Not discounted')}}</option>
    </select>

    <select class="filter-select" id="availability" name="availability">
      <option value="" > {{__('Availability filter')}}</option>
      <option value="1" > {{__('Available')}}</option>
      <option value="2" > {{__('Unavailable')}}</option>
    </select>


  </h5>
  <div class="table-responsive text-nowrap">
    <table class="table" id="laravel_datatable">
      <thead>
        <tr>
          <th>#</th>
          <th>{{__('Name')}}</th>
          <th>{{__('Price')}}</th>
          <th>{{__('Created at')}}</th>
          <th>{{__('is_available')}}</th>
          <th>{{__('in_discount')}}</th>
          <th>{{__('discount')}}</th>
          <th>{{__('Actions')}}</th>
        </tr>
      </thead>
    </table>
  </div>
</div>

{{-- product modal --}}
<div class="modal fade" id="modal"  aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="fw-bold py-1 mb-1">{{__('Add product')}}</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="text" id="form_type" hidden />
        <input type="text" class="form-control" id="id" name="id" hidden/>
        <form class="form-horizontal" onsubmit="event.preventDefault()" action="#"
          enctype="multipart/form-data" id="form">

          <div class="card-body">
            <div class="d-flex align-items-start align-items-sm-center gap-4">
              <div hidden><img src="{{ asset('assets/img/icons/file-not-found.jpg') }}"  alt="image" class="d-block rounded" height="100" width="100" id="old-image"/> </div>
              <img src="{{ asset('assets/img/icons/file-not-found.jpg') }}" alt="image" class="d-block rounded" height="100" width="100" id="uploaded-image" />
              <div class="button-wrapper">
                <label for="image" class="btn btn-primary" tabindex="0">
                  <span class="d-none d-sm-block">{{__('Upload new image')}}</span>
                  <i class="bx bx-upload d-block d-sm-none"></i>
                  <input class="image-input" type="file" id="image" name="image" hidden accept="image/png, image/jpeg" />
                </label>
                <button type="button" class="btn btn-outline-secondary image-reset">
                  <i class="bx bx-reset d-block d-sm-none"></i>
                  <span class="d-none d-sm-block">{{__('Reset')}}</span>
                </button>
                <br>
                {{-- <small class="text-muted mb-0">Allowed JPG, GIF or PNG. Max size of 800K</small> --}}
              </div>
            </div>
          </div>
          <hr class="my-0">

          <div class="mb-3">
            <label class="form-label" for="name">{{__('Name')}}</label>
            <div class="input-group input-group-merge">
              <input type="text" class="form-control" id="unit_name" name="unit_name" placeholder="{{__('Unit name')}}"/>
              <input type="text" class="form-control" id="pack_name" name="pack_name" placeholder="{{__('Pack name')}}"/>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label" for="name">{{__('Price')}}</label>
            <div class="input-group input-group-merge">
              <input type="text" class="form-control" id="unit_price" name="unit_price" placeholder="{{__('Unit price')}}"/>
              <input type="text" class="form-control" id="pack_price" name="pack_price" placeholder="{{__('Pack price')}}"/>
            </div>
          </div>


          <div class="mb-3">
            <label class="form-label" for="name">{{__('Pack units')}}</label>
              <input type="number" class="form-control" id="pack_units" name="pack_units"/>
          </div>

          <div class="mb-3">
            <label class="form-label" for="unit_type">{{__('Unit type')}}</label>
              <select class="form-select" id="unit_type" name="unit_type">
                <option value="1" > {{__('Piece')}}</option>
                <option value="2" > {{__('100 gram')}}</option>
                <option value="3" > {{__('1 kilogram')}}</option>
              </select>
          </div>

          <div class="mb-3">
            <label class="form-label" for="name">{{__('Subcategory')}}</label>
            <div class="input-group input-group-merge">
              <select class="form-select" id="category_id">
                <option value="" > {{__('Select category')}}</option>
                @foreach ($categories as $category)
                  <option value="{{$category->id}}" > {{$category->name}} </option>
                @endforeach
              </select>
              <select class="form-select" id="subcategory_id" name="subcategory_id">
                  <option value="" > {{__('Select category first')}} </option>
              </select>
            </div>
          </div>


          <div class="mb-3">
            <label class="form-label" for="name">{{__('Status')}}</label>
              <select class="form-select" id="status" name="status">
                <option value="1" > {{__('Available')}}</option>
                <option value="2" > {{__('Unavailable')}}</option>
              </select>
          </div>


          <div class="mb-3" style="text-align: center">
            <button type="submit" id="submit" name="submit" class="btn btn-primary">{{__('Send')}}</button>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>


{{-- discount modal --}}
<div class="modal fade" id="discount_modal"  aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="fw-bold py-1 mb-1">{{__('Add discount')}}</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="text" id="discount_form_type" hidden />
        <input type="text" class="form-control" id="discount_id" name="discount_id" hidden/>
        <form class="form-horizontal" onsubmit="event.preventDefault()" action="#"
          enctype="multipart/form-data" id="discount_form">

          <input type="text" class="form-control" id="product_id" name="product_id" hidden/>

          <div class="mb-3">
            <label class="form-label" for="type">{{__('Type')}}</label>
              <select class="form-select" id="type" name="type">
                <option value="1" > {{__('Fixed')}}</option>
                <option value="2" > {{__('Percentage')}}</option>
              </select>
          </div>

          <div class="mb-3">
            <label class="form-label" for="name">{{__('Discount amount')}}</label>
            <input type="text" class="form-control" id="amount" name="amount"/>
          </div>

          <div class="mb-3">
            <label class="form-label" for="name">{{__('Start date')}}</label>
            <input type="date" class="form-control" id="start_date" name="start_date"/>
          </div>

          <div class="mb-3">
            <label class="form-label" for="name">{{__('End date')}}</label>
            <input type="date" class="form-control" id="end_date" name="end_date"/>
          </div>


          <div class="mb-3" style="text-align: center">
            <button type="submit" id="submit_discount" name="submit_discount" class="btn btn-primary">{{__('Send')}}</button>
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
    function load_data(category = null,subcategory=null , discount=null , availability=null) {
        //$.fn.dataTable.moment( 'YYYY-M-D' );
        var table = $('#laravel_datatable').DataTable({

            responsive: true,
            processing: true,
            serverSide: true,
            pageLength: 100,

            ajax: {
                url: "{{ url('product/list') }}",
                data:{
                  category:category,
                  subcategory:subcategory,
                  discount:discount,
                  availability:availability
                },
                type: 'POST',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
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
                    data: 'price',
                    name: 'price'
                },

                {
                    data: 'created_at',
                    name: 'created_at'
                },

                {
                    data: 'availability',
                    name: 'availability',
                    render: function(data){
                          if(data == false){
                              return '<span class="badge bg-danger">{{__("No")}}</span>';
                            }else{
                              return '<span class="badge bg-success">{{__("Yes")}}</span>';
                            }
                          }
                },

                {
                    data: 'is_discounted',
                    name: 'is_discounted',
                    render: function(data){
                          if(data == false){
                              return '<span class="badge bg-danger">{{__("No")}}</span>';
                            }else{
                              return '<span class="badge bg-success">{{__("Yes")}}</span>';
                            }
                          }
                },


                {
                    data: 'discount',
                    name: 'discount'
                },


                {
                    data: 'action',
                    name: 'action',
                    render:function(data){
                      /* return '<div class="dropdown"><button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button><div class="dropdown-menu">'
                        +data+'</div></div>' */
                        return '<span>'+data+'</span>';
                    }
                }

            ]
        });
    }

    $('#category').on('change', function() {

      var category_id = document.getElementById('category').value;
      var subcategory_id = document.getElementById('subcategory').value;
      var discount = document.getElementById('discount').value;
      var availability = document.getElementById('availability').value;
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
                console.log(response.data);
                for (var i = 0; i<response.data.length; i++){
                    var option = document.createElement('option');
                    option.value = response.data[i].id;
                    option.innerHTML = response.data[i].name;
                    subcategories.appendChild(option);
                }

              }
            }
        });


      var table = $('#laravel_datatable').DataTable();
      table.destroy();
      load_data(category_id,subcategory_id,discount,availability);
    });

    $('#subcategory').on('change', function() {

      var category_id = document.getElementById('category').value;
      var subcategory_id = document.getElementById('subcategory').value;
      var discount = document.getElementById('discount').value;
      var availability = document.getElementById('availability').value;

      var table = $('#laravel_datatable').DataTable();
      table.destroy();
      load_data(category_id,subcategory_id,discount,availability);

    });

    $('#discount').on('change', function() {

      var category_id = document.getElementById('category').value;
      var subcategory_id = document.getElementById('subcategory').value;
      var discount = document.getElementById('discount').value;
      var availability = document.getElementById('availability').value;

      var table = $('#laravel_datatable').DataTable();
      table.destroy();
      load_data(category_id,subcategory_id,discount,availability);

    });

    $('#availability').on('change', function() {

      var category_id = document.getElementById('category').value;
      var subcategory_id = document.getElementById('subcategory').value;
      var discount = document.getElementById('discount').value;
      var availability = document.getElementById('availability').value;

      var table = $('#laravel_datatable').DataTable();
      table.destroy();
      load_data(category_id,subcategory_id,discount,availability);

    });

    $('#unit_name').on('blur', function() {

      var unit_name = document.getElementById('unit_name').value;

      document.getElementById('pack_name').value = ' (حزمة) '+ unit_name;


    });


    $('#create').on('click', function() {
      document.getElementById('form').reset();
      document.getElementById('form_type').value = "create";
      document.getElementById('uploaded-image').src = "{{ asset('assets/img/icons/file-not-found.jpg') }}" ;
      document.getElementById('old-image').src = "{{ asset('assets/img/icons/file-not-found.jpg') }}" ;
      $("#modal").modal('show');
    });


    $(document.body).on('click', '.update', function() {
      document.getElementById('form').reset();
      document.getElementById('form_type').value = "update";
      var product_id = $(this).attr('table_id');
      $("#id").val(product_id);

      $.ajax({
          url: '{{ url('product/update') }}',
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          type:'POST',
          data:{product_id : product_id},
          dataType : 'JSON',
          success:function(response){
              if(response.status==1){

                document.getElementById('unit_name').value =  response.data.unit_name;
                document.getElementById('pack_name').value =  response.data.pack_name;
                document.getElementById('unit_price').value =  response.data.unit_price;
                document.getElementById('pack_price').value =  response.data.pack_price;
                document.getElementById('pack_units').value =  response.data.pack_units;
                document.getElementById('unit_type').value =  response.data.unit_type;
                document.getElementById('status').value =  response.data.status == 'available' ? 1 : 2 ;

                var image = response.data.image == null ?
                "{{ asset('assets/img/icons/file-not-found.jpg') }}" : response.data.image;

                document.getElementById('uploaded-image').src = image;
                document.getElementById('old-image').src = image;

                console.log(response.data.category_id);
                document.getElementById('category_id').value = response.data.category_id;

                $('#category_id').trigger("change",function(){
                  document.getElementById('subcategory_id').value = response.data.subcategory_id;
                });



                $("#modal").modal("show");
              }
            }
        });
    });

    $('#category_id').on('change', function(e, callback) {
      var category_id = document.getElementById('category_id').value;
      $.when(
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

                var subcategories = document.getElementById('subcategory_id');
                subcategories.innerHTML = '<option value="">{{__("Not selected")}}</option>';

                for (var i = 0; i<response.data.length; i++){
                    var option = document.createElement('option');
                    option.value = response.data[i].id;
                    option.innerHTML = response.data[i].name;
                    subcategories.appendChild(option);
                }

              }
            }
        })
      ).done(function(a1, a2){
        callback();
      });



    });

    $('#submit').on('click', function() {

      /* var formdata = new FormData($("#form")[0]); */
      var queryString = new FormData($("#form")[0]);
      /* console.log(formdata.entries());
      for (var pair of formdata.entries()) {
        //console.log(pair[1]);
        if(pair[1] == '' ){
          queryString.delete(pair[0]);
          //console.log(pair[0])
        }
      } */

       var formtype = document.getElementById('form_type').value;
       //console.log(formtype);
       if(formtype == "create"){
        url = "{{ url('product/create') }}";
       }

      if(formtype == "update"){
        url = "{{ url('product/update') }}";
        queryString.append("product_id",document.getElementById('id').value)
      }

      $("#modal").modal("hide");


      $.ajax({
        url: url,
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        type:'POST',
        data:queryString,
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

      var product_id = $(this).attr('table_id');

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
            url: "{{ url('product/delete') }}",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type:'POST',
            data:{product_id : product_id},
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

    $(document.body).on('click', '.add_discount', function() {
      var product_id = $(this).attr('table_id');
      document.getElementById('discount_form').reset();
      document.getElementById('discount_form_type').value = "create";
      document.getElementById('product_id').value = product_id;
      $("#discount_modal").modal('show');
    });


    $(document.body).on('click', '.edit_discount', function() {
      document.getElementById('discount_form').reset();
      document.getElementById('discount_form_type').value = "update";
      var discount_id = $(this).attr('table_id');
      $("#discount_id").val(discount_id);

      $.ajax({
          url: '{{ url('discount/update') }}',
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          type:'POST',
          data:{discount_id : discount_id},
          dataType : 'JSON',
          success:function(response){
              if(response.status==1){

                document.getElementById('product_id').value =  response.data.product_id;
                document.getElementById('amount').value =  response.data.amount;
                document.getElementById('start_date').value =  response.data.start_date;
                document.getElementById('end_date').value =  response.data.end_date;
                document.getElementById('start_date').readOnly = true;
                document.getElementById('type').value = 2 ;

                $("#discount_modal").modal("show");
              }
            }
        });
    });

    $('#submit_discount').on('click', function() {

      var formdata = new FormData($("#discount_form")[0]);
      var formtype = document.getElementById('discount_form_type').value;
      console.log(formtype);
      if(formtype == "create"){
        url = "{{ url('discount/create') }}";
      }

      if(formtype == "update"){
        url = "{{ url('discount/update') }}";
        formdata.append("discount_id",document.getElementById('discount_id').value)
      }

      $("#discount_modal").modal("hide");


      $.ajax({
        url: url,
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

      $(document.body).on('click', '.delete_discount', function() {

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
            type:'POST',
            data:{discount_id : discount_id},
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

    $(document.body).on('change', '.image-input', function() {
        const fileInput = document.querySelector('.image-input');
        if (fileInput.files[0]) {
          document.getElementById('uploaded-image').src = window.URL.createObjectURL(fileInput.files[0]);
        }
    });
    $(document.body).on('click', '.image-reset', function() {
      const fileInput = document.querySelector('.image-input');
      fileInput.value = '';
      document.getElementById('uploaded-image').src = document.getElementById('old-image').src;
    });
});

</script>
@endsection
