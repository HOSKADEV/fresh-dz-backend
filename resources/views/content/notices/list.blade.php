@extends('layouts/contentNavbarLayout')

@section('title', __('Notices'))

@section('content')

<h4 class="fw-bold py-3 mb-3">
  <span class="text-muted fw-light">{{__('Notices')}} /</span> {{__('Browse notices')}}
  <button type="button" id="add" class="btn rounded-pill btn-primary" style="float: right;">{{__('Add notice')}}</button>
</h4>

<!-- Basic Bootstrap Table -->
<div class="card">
  <h5 class="card-header">{{__('Notices table')}}</h5>
  <div class="table-responsive text-nowrap">
    <table class="table" id="laravel_datatable">
      <thead>
        <tr>
          <th>#</th>
          <th>{{__('Notice')}}</th>
          <th>{{__('Created at')}}</th>
          <th>{{__('Notice type')}}</th>
          {{-- <th>{{__('Views')}}</th> --}}
          <th>{{__('Actions')}}</th>
        </tr>
      </thead>
    </table>
  </div>
</div>

{{-- add notice modal --}}
<div class="modal fade" id="add_modal"  aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="fw-bold py-1 mb-1">{{__('Add notice')}}</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form class="form-horizontal" onsubmit="event.preventDefault()" action="#"
          enctype="multipart/form-data" id="add_form">
          {{-- <div class="card mb-4">
          <div class="card-body"> --}}
            <div class="mb-3">
              <label class="form-label" for="title_ar">{{__('Title')}}</label>
              <div class="input-group input-group-merge">
                <input type="text" class="form-control" name="title_ar" placeholder="{{ __('Title in arabic')}}"/>
                <input type="text" class="form-control" name="title_en" placeholder="{{ __('Title in english')}}"/>
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label" for="content_ar">{{__('Content in arabic')}}</label>
              <textarea class="form-control" name="content_ar" rows="3" style="height: 75px;"></textarea>
            </div>

            <div class="mb-3">
              <label class="form-label" for="notice_content">{{__('Content in english')}}</label>
              <textarea class="form-control" name="content_en" rows="3" style="height: 75px;"></textarea>
            </div>


            <div class="mb-3">
              <label class="form-label" for="type">{{__('Notice type')}}</label>
              <select class="form-select" name="type">
                <option value="0">{{__('Select type')}}</option>
                <option value="1">{{__('Offer Notice')}}</option>
                <option value="2">{{__('Update Notice')}}</option>
              </select>
            </div>
            <br>
          {{-- </div> --}}
          <div class="mb-3" style="text-align: center">
            <button type="submit" id="submit" name="submit" class="btn btn-primary">{{__('Send')}}</button>
          </div>
          {{-- </div> --}}
        </form>
      </div>
    </div>
  </div>
</div>



{{-- view modal --}}
<div class="modal fade" id="view_modal"  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="fw-bold py-1 mb-1">{{__('Notice content')}}</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

            <div class="mb-3">
              <label class="form-label" for="title">{{__('Title')}}</label>
              <div class="input-group input-group-merge">
                <input type="text" class="form-control" id="title_ar" disabled/>
                <input type="text" class="form-control" id="title_en" disabled/>
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label" for="notice_content">{{__('Content in arabic')}}</label>
              <textarea class="form-control" id="content_ar" rows="5" style="height: 125px;" disabled></textarea>
            </div>

            <div class="mb-3">
              <label class="form-label" for="notice_content">{{__('Content in english')}}</label>
              <textarea class="form-control" id="content_en" rows="5" style="height: 125px;" disabled></textarea>
            </div>

          {{-- </div> --}}
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
        $.fn.dataTable.moment( 'YYYY-M-D' );
        var table = $('#laravel_datatable').DataTable({

            responsive: true,
            processing: true,
            serverSide: true,
            pageLength: 100,

            ajax: {
                url: "{{ url('notice/list') }}",
            },

            type: 'GET',

            columns: [

                {
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },

                {
                    data: 'title',
                    name: 'title'
                },

                {
                    data: 'created_at',
                    name: 'created_at'
                },

                {
                    data: 'type',
                    name: 'type',
                    render: function(data){
                        if(data == 1){
                            return '<span class="badge bg-success">{{__("Offer Notice")}}</span>';
                          }else if(data == 2){
                            return '<span class="badge bg-info">{{__("Update Notice")}}</span>';
                          }else{
                            return '<span class="badge bg-secondary">{{__("Other")}}</span>';
                          }
                        }
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



    $('#add').on('click',function(){
      $("#add_modal").modal('show');
    });

    $('#submit').on('click', function() {
      var queryString = new FormData($("#add_form")[0]);

      $.ajax({
        url: '{{ url('notice/create') }}',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type:'POST',
        data:queryString,
        dataType : 'JSON',
        contentType: false,
        processData: false,
        success:function(response){
            if(response.status==1){
              $("#add_modal").modal('hide');
              Swal.fire(
                  'Success',
                  "{{ __('success') }}",
                  'success'
              ).then((result)=>{
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

    $(document.body).on('click','.view', function() {
      notice_id = $(this).attr('table_id');
      $.ajax({
          url: '{{ url('notice/update') }}',
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          type:'POST',
          data:{
            notice_id : notice_id,
          },
          dataType : 'JSON',
          success:function(response){
              if(response.status==1){

                //console.log(response.data)
                $("#title_ar").val(response.data.title_ar);
                $("#title_en").val(response.data.title_en);
                $("#content_ar").val(response.data.content_ar);
                $("#content_en").val(response.data.content_en);
                $("#view_modal").modal("show");
              }
            }
      });
    });

    $(document.body).on('click', '.delete', function() {

      var notice_id = $(this).attr('table_id');
      //console.log(vaccination_id);
      Swal.fire({
        title: "{{ __('Are you sure?') }}",
        text: "{{ __('You will not be able to revert this!') }}",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: "{{ __('Delete') }}",
        cancelButtonText: "{{ __('Cancel') }}"
      }).then((result) => {
        if (result.isConfirmed) {

          $.ajax({
            url: '{{ url('notice/delete') }}',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type:'POST',
            data:{
              notice_id : notice_id,
            },
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
