@extends('layouts/contentNavbarLayout')

@section('title', __('Drivers'))

@section('content')

<h4 class="fw-bold py-3 mb-3">
  <span class="text-muted fw-light">{{__('Drivers')}} /</span> {{__('Browse drivers')}}
  <button type="button" class="btn btn-primary" id="create" style="float:right">{{__('Add driver')}}</button>
</h4>

<!-- Basic Bootstrap Table -->
<div class="card">
  <h5 class="card-header">{{__('Drivers table')}}</h5>
  <div class="table-responsive text-nowrap">
    <table class="table" id="laravel_datatable">
      <thead>
        <tr>
          <th>#</th>
          <th>{{__('Name')}}</th>
          <th>{{__('Phone')}}</th>
          <th>{{__('Status')}}</th>
          <th>{{__('Created at')}}</th>
          <th>{{__('Actions')}}</th>
        </tr>
      </thead>
    </table>
  </div>
</div>

{{-- driver modal --}}
<div class="modal fade" id="modal"  aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="fw-bold py-1 mb-1">{{__('Add driver')}}</h4>
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
              <label class="form-label" for="name">{{__('Full name')}}</label>
              <div class="input-group input-group-merge">
                <input type="text" class="form-control" id="firstname" name="firstname" placeholder="{{__('Firstname')}}"/>
                <input type="text" class="form-control" id="lastname" name="lastname" placeholder="{{__('Lastname')}}"/>
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label" for="phone">{{__('Phone')}}</label>
              <input type="text" class="form-control" id="phone" name="phone"/>
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
    function load_data() {
        //$.fn.dataTable.moment( 'YYYY-M-D' );
        var table = $('#laravel_datatable').DataTable({

            responsive: true,
            processing: true,
            serverSide: true,
            pageLength: 100,

            ajax: {
                url: "{{ url('driver/list') }}",
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
                    data: 'status',
                    name: 'status',
                    render: function(data){
                      if(data == false){
                          return '<span class="badge bg-danger">{{__("Occupied")}}</span>';
                        }else{
                          return '<span class="badge bg-success">{{__("Unoccupied")}}</span>';
                        }
                      }
                },

                {
                    data: 'created_at',
                    name: 'created_at'
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

    $('#create').on('click', function() {
      document.getElementById('form').reset();
      document.getElementById('uploaded-image').src = "{{ asset('assets/img/icons/file-not-found.jpg') }}";
      document.getElementById('old-image').src = "{{ asset('assets/img/icons/file-not-found.jpg') }}";
      document.getElementById('form_type').value = "create";
      $("#modal").modal('show');
    });


    $(document.body).on('click', '.update', function() {
      document.getElementById('form').reset();
      document.getElementById('form_type').value = "update";
      var driver_id = $(this).attr('table_id');
      $("#id").val(driver_id);

      $.ajax({
          url: '{{ url('driver/update') }}',
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          type:'POST',
          data:{driver_id : driver_id},
          dataType : 'JSON',
          success:function(response){
              if(response.status==1){

                document.getElementById('firstname').value =  response.data.firstname;
                document.getElementById('lastname').value =  response.data.lastname;
                document.getElementById('phone').value =  response.data.phone;

                var image = response.data.image == null ?
                "{{ asset('assets/img/icons/file-not-found.jpg') }}" : response.data.image;

                document.getElementById('uploaded-image').src = image;
                document.getElementById('old-image').src = image;

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
        url = "{{ url('driver/create') }}";
       }

      if(formtype == "update"){
        url = "{{ url('driver/update') }}";
        formdata.append("driver_id",document.getElementById('id').value)
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

      var driver_id = $(this).attr('table_id');

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
            url: "{{ url('driver/delete') }}",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type:'POST',
            data:{driver_id : driver_id},
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
