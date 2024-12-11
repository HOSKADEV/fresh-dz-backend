@extends('layouts/contentNavbarLayout')

@section('title', __('Sections'))

@section('content')

<h4 class="fw-bold py-3 mb-3">
  <span class="text-muted fw-light">{{__('Sections')}} /</span> {{__('Homepage sections')}}
</h4>

<!-- Basic Bootstrap Table -->
<div class="card">
  <h5 class="card-header">{{__('Sections table')}}</h5>
  <div class="table-responsive text-nowrap">
    <table class="table" id="laravel_datatable">
      <thead>
        <tr>
          <th>#</th>
          <th>{{__('Name')}}</th>
          <th>{{__('Type')}}</th>
          <th>{{__('Created at')}}</th>
          <th>{{__('Actions')}}</th>
        </tr>
      </thead>
    </table>
  </div>
</div>

{{-- section modal --}}
<div class="modal fade" id="modal"  aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="fw-bold py-1 mb-1">{{__('Switch/Insert section')}}</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="text" id="form_type" hidden />
        <form class="form-horizontal" onsubmit="event.preventDefault()" action="#"
          enctype="multipart/form-data" id="form">


            <input type="text" class="form-control" id="section_id" name="section_id" hidden/>

            <div class="mb-3">
              <label class="form-label" for="name">{{__('Rank')}}</label>
              <input type="text" class="form-control" id="rank" name="rank"/>
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
                url: "{{ url('section/list') }}",
                type: 'GET',
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
                    data: 'type',
                    name: 'type',
                    render: function(data){
                          if(data == 'ad'){
                              return '<span class="badge bg-warning">{{__("ad")}}</span>';
                            }
                            if(data == 'solo'){
                              return '<span class="badge bg-primary">{{__("solo")}}</span>';
                            }
                            if(data == 'offer'){
                              return '<span class="badge bg-success">{{__("offer")}}</span>';
                            }
                            if(data == 'family'){
                              return '<span class="badge bg-info">{{__("family")}}</span>';
                            }


                  },
                },

                {
                    data: 'created_at',
                    name: 'created_at'
                },

                {
                    data: 'action',
                    name: 'action',
                    render:function(data){
                      if(data == ""){
                        return null;
                      }
                      /* return '<div class="dropdown"><button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button><div class="dropdown-menu">'
                        +data+'</div></div>' */
                        return '<span>'+data+'</span>';
                    }
                }

            ]
        });
    }

    $(document.body).on('click', '.switch', function() {
      document.getElementById('form').reset();
      var section_id = $(this).attr('table_id');
      document.getElementById('section_id').value = section_id;
      document.getElementById('form_type').value = "switch";
      $("#modal").modal('show');
    });

    $(document.body).on('click', '.insert', function() {
      document.getElementById('form').reset();
      var section_id = $(this).attr('table_id');
      document.getElementById('section_id').value = section_id;
      document.getElementById('form_type').value = "insert";
      $("#modal").modal('show');
    });

    $('#submit').on('click', function() {

      var formdata = new FormData($("#form")[0]);
       var formtype = document.getElementById('form_type').value;

       if(formtype == "switch"){
        url = "{{ url('section/switch') }}";
       }

      if(formtype == "insert"){
        url = "{{ url('section/insert') }}";
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

      var section_id = $(this).attr('table_id');

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
            url: "{{ url('section/delete') }}",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type:'POST',
            data:{section_id : section_id},
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
