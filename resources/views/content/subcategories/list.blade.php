@extends('layouts/contentNavbarLayout')

@section('title', __('Subcategories'))

@section('content')

<h4 class="fw-bold py-3 mb-3">
  <span class="text-muted fw-light">{{__('Subcategories')}} /</span> {{__('Browse subcategories')}}
  <button type="button" class="btn btn-primary" id="create" style="float:right">{{__('Add Subcategory')}}</button>
</h4>

<!-- Basic Bootstrap Table -->
<div class="card">
  <h5 class="card-header">{{__('Subcategories table')}}
    <select class="filter-select" id="category" name="category">
      <option value="" > {{__('Category filter')}}</option>
      @foreach ($categories as $category)
        <option value="{{$category->id}}" > {{$category->name}} </option>
      @endforeach
    </select>

  </h5>
  <div class="table-responsive text-nowrap">
    <table class="table" id="laravel_datatable">
      <thead>
        <tr>
          <th>#</th>
          <th>{{__('Name')}}</th>
          <th>{{__('Created at')}}</th>
          <th>{{__('Category')}}</th>
          <th>{{__('Products')}}</th>
          <th>{{__('Actions')}}</th>
        </tr>
      </thead>
    </table>
  </div>
</div>

{{-- subcategory modal --}}
<div class="modal fade" id="modal"  aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="fw-bold py-1 mb-1">{{__('Add subcategory')}}</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="text" id="form_type" hidden />
        <input type="text" class="form-control" id="id" name="id" hidden/>
        <form class="form-horizontal" onsubmit="event.preventDefault()" action="#"
          enctype="multipart/form-data" id="form">

            <div class="mb-3">
              <label class="form-label" for="name">{{__('Name')}}</label>
              <input type="text" class="form-control" id="name" name="name"/>
            </div>
            <div class="mb-3">
              <label class="form-label" for="category_id">{{__('Category')}}</label>
              <select class="form-select" id="category_id" name="category_id">
                <option value="" > {{__('Select category')}}</option>
                @foreach ($categories as $category)
                  <option value="{{$category->id}}" > {{$category->name}} </option>
                @endforeach
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
@endsection

@section('page-script')
<script>
  $(document).ready(function(){
    load_data();
    function load_data(category = null) {
        //$.fn.dataTable.moment( 'YYYY-M-D' );
        var table = $('#laravel_datatable').DataTable({

            responsive: true,
            processing: true,
            serverSide: true,
            pageLength: 100,

            ajax: {
                url: "{{ url('subcategory/list') }}",
                data:{category:category},
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
                    data: 'created_at',
                    name: 'created_at'
                },

                {
                    data: 'category',
                    name: 'category'
                },


                {
                    data: 'products',
                    name: 'products'
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
      var table = $('#laravel_datatable').DataTable();
      table.destroy();
      load_data(document.getElementById('category').value);
    });

    $('#create').on('click', function() {
      document.getElementById('form').reset();
      document.getElementById('form_type').value = "create";
      $("#modal").modal('show');
    });


    $(document.body).on('click', '.update', function() {
      document.getElementById('form').reset();
      document.getElementById('form_type').value = "update";
      var subcategory_id = $(this).attr('table_id');
      $("#id").val(subcategory_id);

      $.ajax({
          url: '{{ url('subcategory/update') }}',
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          type:'POST',
          data:{subcategory_id : subcategory_id},
          dataType : 'JSON',
          success:function(response){
              if(response.status==1){

                //console.log(response.data);
                document.getElementById('name').value =  response.data.name;
                document.getElementById('category_id').value =  response.data.category_id;

                $("#modal").modal("show");
              }
            }
        });
    });

    $('#submit').on('click', function() {

      var formdata = new FormData($("#form")[0]);
       var formtype = document.getElementById('form_type').value;
       console.log(formtype);
       if(formtype == "create"){
        url = "{{ url('subcategory/create') }}";
       }

      if(formtype == "update"){
        url = "{{ url('subcategory/update') }}";
        formdata.append("subcategory_id",document.getElementById('id').value)
      }

      $("#modal").modal("hide");


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

    $(document.body).on('click', '.delete', function() {

      var subcategory_id = $(this).attr('table_id');

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
            url: "{{ url('subcategory/delete') }}",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type:'POST',
            data:{subcategory_id : subcategory_id},
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
});

</script>
@endsection
