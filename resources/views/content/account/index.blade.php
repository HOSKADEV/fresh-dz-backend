@extends('layouts/contentNavbarLayout')

@section('title', __('Account'))

@section('content')
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">{{ __('Account Settings') }} /</span> {{ __('Account') }}
    </h4>

    <div class="row">
        <div class="col-md-12">
            <!-- Profile Details -->
            <form id="formAccountSettings" method="POST">
                <div class="card mb-4">
                    <h5 class="card-header">{{ __('Profile Details') }}</h5>
                    <div class="card-body">
                        <div class="d-flex align-items-start align-items-sm-center gap-4">
                            <div hidden><img src="{{ auth()->user()->image }}"
                                    alt="image" class="d-block rounded" height="100" width="100" id="old-image" />
                            </div>
                            <img src="{{ auth()->user()->image }}" alt="image"
                                class="d-block rounded" height="100" width="100" id="uploaded-image" />
                            <div class="button-wrapper">
                                <label for="image" class="btn btn-primary" tabindex="0">
                                    <span class="d-none d-sm-block">{{ __('New image') }}</span>
                                    <i class="bx bx-upload d-block d-sm-none"></i>
                                    <input class="image-input" type="file" id="image" name="image" hidden
                                        accept="image/png, image/jpeg" />
                                </label>
                                <button type="button" class="btn btn-outline-secondary image-reset">
                                    <i class="bx bx-reset d-block d-sm-none"></i>
                                    <span class="d-none d-sm-block">{{ __('Reset') }}</span>
                                </button>
                                {{-- <small class="text-muted mb-0">Allowed JPG, GIF or PNG. Max size of 800K</small> --}}
                            </div>
                        </div>
                    </div>
                    <hr class="my-0">
                    <div class="card-body">
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label for="firstName" class="form-label">{{ __('Name') }}</label>
                                <input class="form-control" type="text" id="name" name="name"
                                    value="{{ auth()->user()->name }}" autofocus />
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="email" class="form-label">{{ __('E-mail') }}</label>
                                <input class="form-control" type="text" id="email" name="email"
                                    value="{{ auth()->user()->email }}" />
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="phoneNumber">{{ __('Phone Number') }}</label>
                                <input type="text" id="phoneNumber" name="phoneNumber" class="form-control"
                                    value="{{ auth()->user()->phone }}" />
                            </div>
                        </div>
                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary me-2">{{ __('Save changes') }}</button>
                            <button type="reset" class="btn btn-outline-secondary">{{ __('Cancel') }}</button>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Change Password -->
            <div class="card mb-4">
                <h5 class="card-header">{{ __('Change Password') }}</h5>
                <div class="card-body">
                    <form id="formChangePassword" method="POST" onsubmit="event.preventDefault()" action="#"
                        enctype="multipart/form-data">
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label for="old_password" class="form-label">{{ __('Current Password') }}</label>
                                <input class="form-control" type="password" id="old_password" name="old_password" />
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="new_password" class="form-label">{{ __('New Password') }}</label>
                                <input class="form-control" type="password" id="new_password" name="new_password" />
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="new_password_confirmation"
                                    class="form-label">{{ __('Confirm New Password') }}</label>
                                <input class="form-control" type="password" id="new_password_confirmation"
                                    name="new_password_confirmation" />
                            </div>
                        </div>
                        <div class="mt-2">
                            <button type="submit" id="btnChangePassword"
                                class="btn btn-primary me-2">{{ __('Change Password') }}</button>
                            <button type="reset" class="btn btn-outline-secondary">{{ __('Cancel') }}</button>
                        </div>
                    </form>
                </div>
            </div>

            @if(auth()->user()->role)

            <!-- Delete Account -->
              <div class="card">
                  <h5 class="card-header">{{ __('Delete Account') }}</h5>
                  <div class="card-body">
                      <div class="mb-3 col-12 mb-0">
                          <div class="alert alert-warning">
                              <h6 class="alert-heading fw-bold mb-1">
                                  {{ __('Are you sure you want to delete your account?') }}</h6>
                              <p class="mb-0">
                                  {{ __('Once you delete your account, there is no going back. Please be certain.') }}</p>
                          </div>
                      </div>
                      <form id="formAccountDeactivation">
                          <div class="form-check mb-3">
                              <input class="form-check-input" type="checkbox" name="accountActivation"
                                  id="accountActivation" />
                              <label class="form-check-label"
                                  for="accountActivation">{{ __('I confirm my account deletion') }}</label>
                          </div>
                          <button type="submit"
                              class="btn btn-danger deactivate-account">{{ __('Delete Account') }}</button>
                      </form>
                  </div>
              </div>
            @endif
        </div>
    </div>
@endsection

@section('page-script')
    <script>
        $(document).ready(function() {
            // Profile Update Form
            $('#formAccountSettings').on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData(this);

                $.ajax({
                    url: "{{ url('account/update') }}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'POST',
                    data: formData,
                    dataType: 'JSON',
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.status == 1) {
                            Swal.fire({
                                title: "{{ __('Success') }}",
                                text: "{{ __('Profile updated successfully') }}",
                                icon: 'success',
                                confirmButtonText: "{{ __('Ok') }}"
                            });
                        } else {
                            Swal.fire(
                                "{{ __('Error') }}",
                                response.message,
                                'error'
                            );
                        }
                    },
                    error: function(data) {
                        var errors = data.responseJSON;
                        Swal.fire(
                            "{{ __('Error') }}",
                            errors.message,
                            'error'
                        );
                    }
                });
            });

            // Change Password Form
            $('#btnChangePassword').on('click', function() {
                var formData = new FormData($('#formChangePassword')[0]);

                $.ajax({
                    url: "{{ url('account/password/change') }}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'POST',
                    data: formData,
                    dataType: 'JSON',
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.status == 1) {
                            Swal.fire({
                                title: "{{ __('Success') }}",
                                text: "{{ __('Password changed successfully') }}",
                                icon: 'success',
                                confirmButtonText: "{{ __('Ok') }}"
                            }).then((result) => {
                                $('#formChangePassword')[0].reset();
                            });
                        } else {
                            Swal.fire(
                                "{{ __('Error') }}",
                                response.message,
                                'error'
                            );
                        }
                    },
                    error: function(data) {
                        var errors = data.responseJSON;
                        Swal.fire(
                            "{{ __('Error') }}",
                            errors.message,
                            'error'
                        );
                    }
                });
            });

            // Delete Account Form
            $('#formAccountDeactivation').on('submit', function(e) {
                e.preventDefault();

                if (!$('#accountActivation').is(':checked')) {
                    Swal.fire(
                        "{{ __('Warning') }}",
                        "{{ __('Please confirm account deactivation by checking the checkbox') }}",
                        'warning'
                    );
                    return false;
                }

                Swal.fire({
                    title: "{{ __('Warning') }}",
                    text: "{{ __('Are you sure you want to delete your account? This action cannot be undone.') }}",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: "{{ __('Delete Account') }}",
                    cancelButtonText: "{{ __('Cancel') }}"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ url('account/delete') }}",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type: 'POST',
                            dataType: 'JSON',
                            success: function(response) {
                                if (response.status == 1) {
                                    Swal.fire({
                                        title: "{{ __('Success') }}",
                                        text: "{{ __('Account deleted successfully') }}",
                                        icon: 'success',
                                        confirmButtonText: "{{ __('Ok') }}"
                                    }).then((result) => {
                                        window.location.href =
                                            "{{ url('auth/logout') }}";
                                    });
                                } else {
                                    Swal.fire(
                                        "{{ __('Error') }}",
                                        response.message,
                                        'error'
                                    );
                                }
                            },
                            error: function(data) {
                                var errors = data.responseJSON;
                                Swal.fire(
                                    "{{ __('Error') }}",
                                    errors.message,
                                    'error'
                                );
                            }
                        });
                    }
                });
            });

            $(document.body).on('change', '.image-input', function() {
                const fileInput = document.querySelector('.image-input');
                if (fileInput.files[0]) {
                    document.getElementById('uploaded-image').src = window.URL.createObjectURL(fileInput
                        .files[0]);
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
