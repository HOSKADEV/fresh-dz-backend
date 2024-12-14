@extends('layouts/contentNavbarLayout')

@section('title', __('Users'))

@section('content')

<h4 class="fw-bold py-3 mb-3">
  <span class="text-muted fw-light">{{__('Users')}} /</span> {{__('Browse users')}}
</h4>

<!-- Basic Bootstrap Table -->
<div class="card">
  <div class="table-responsive text-nowrap">
    <div class="table-header row justify-content-between">
      <h5 class="col-md-auto">{{__('Users table')}}</h5>
    </div>
    <table class="table" id="laravel_datatable">
      <thead>
        <tr>
          <th>#</th>
          <th>{{__('Name')}}</th>
          <th>{{__('Phone')}}</th>
          <th>{{__('Email')}}</th>
          <th>{{__('Status')}}</th>
          <th>{{__('Created at')}}</th>
          <th>{{__('Actions')}}</th>
        </tr>
      </thead>
    </table>
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
          language:  {!! file_get_contents(base_path('lang/'.session('locale','en').'/datatable.json')) !!},
            responsive: true,
            processing: true,
            serverSide: true,
            pageLength: 10,

            ajax: {
                url: "{{ url('user/list') }}",
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
                    data: 'email',
                    name: 'email'
                },


                {
                    data: 'status',
                    name: 'status',
                    render: function(data){
                          if(data == false){
                              return '<span class="badge bg-danger">{{__("Inactive")}}</span>';
                            }else{
                              return '<span class="badge bg-success">{{__("Active")}}</span>';
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
                    searchable: false
                }

            ]
        });
    }

    $(document.body).on('click', '.delete', function() {

      var user_id = $(this).attr('table_id');

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
            url: "{{ url('user/update') }}",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type:'POST',
            data:{
              user_id : user_id,
              status : 0
            },
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

      $(document.body).on('click', '.restore', function() {

      var user_id = $(this).attr('table_id');

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
            url: "{{ url('user/update') }}",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type:'POST',
            data:{
              user_id : user_id,
              status : 1
            },
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

  });



</script>
@endsection
