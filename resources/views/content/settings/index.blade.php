@extends('layouts/contentNavbarLayout')

@section('title', __('Settings'))

@section('content')
    <form class="form-horizontal" onsubmit="event.preventDefault()" action="#" enctype="multipart/form-data" id="form">

        <h4 class="fw-bold py-3 mb-3">
            <span class="text-muted fw-light"></span> {{ __('Version') }}
        </h4>


        <div class="row">
            <div class="col-xl">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ __('Android') }}</h5>
                        <small class="text-muted float-end">{{ __('Android version') }}</small>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label" for="android_version_number">{{ __('Version number') }}</label>
                            <input type="text" class="form-control" id="android_version_number"
                                name="android_version_number" value="{{ $settings['android_version_number'] ?? '' }}" />
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="android_build_number">{{ __('Build number') }}</label>
                            <input type="text" class="form-control" id="android_build_number" name="android_build_number"
                            value="{{ $settings['android_build_number'] ?? '' }}" />
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="android_priority">{{ __('Priority') }}</label>
                            <select class="form-select" id="android_priority" name="android_priority">
                                <option value="0">{{ __('Optional') }}</option>
                                <option value="1" @if ($settings['android_priority'] ?? '') selected @endif>
                                    {{ __('Required') }}</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="android_link">{{ __('Link') }}</label>
                            <textarea class="form-control" id="android_link" name="android_link">{{ $settings['android_link'] ?? '' }}</textarea>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-xl">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ __('iOS') }}</h5>
                        <small class="text-muted float-end">{{ __('iOS version') }}</small>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label" for="ios_version_number">{{ __('Version number') }}</label>
                            <input type="text" class="form-control" id="ios_version_number" name="ios_version_number"
                            value="{{ $settings['ios_version_number'] ?? '' }}" />
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="ios_build_number">{{ __('Build number') }}</label>
                            <input type="text" class="form-control" id="ios_build_number" name="ios_build_number"
                            value="{{ $settings['ios_build_number'] ?? '' }}" />
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="ios_priority">{{ __('Priority') }}</label>
                            <select class="form-select" id="ios_priority" name="ios_priority">
                                <option value="0">{{ __('Optional') }}</option>
                                <option value="1" @if ($settings['ios_priority'] ?? '')  selected @endif>
                                    {{ __('Required') }}</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="ios_link">{{ __('Link') }}</label>
                            <textarea class="form-control" id="ios_link" name="ios_link">{{ $settings['android_link'] ?? '' }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <h4 class="fw-bold py-3 mb-3">
          <span class="text-muted fw-light"></span> {{ __('Contact Information') }}
      </h4>
        <div class="card mb-4">
          <div class="card-body">
              <div class="row mb-3">
                  <div class="col-md-6">
                      <label class="form-label" for="phone">{{ __('Phone Number') }}</label>
                      <input type="tel" class="form-control" id="phone" name="phone" value="{{ $settings['phone'] ?? '' }}">
                  </div>
                  <div class="col-md-6">
                      <label class="form-label" for="email">{{ __('Email') }}</label>
                      <input type="email" class="form-control" id="email" name="email" value="{{ $settings['email'] ?? '' }}">
                  </div>
              </div>
              <div class="row mb-3">
                  <div class="col-md-6">
                      <label class="form-label" for="facebook">{{ __('Facebook') }}</label>
                      <input type="url" class="form-control" id="facebook" name="facebook" value="{{ $settings['facebook'] ?? '' }}">
                  </div>
                  <div class="col-md-6">
                      <label class="form-label" for="instagram">{{ __('Instagram') }}</label>
                      <input type="url" class="form-control" id="instagram" name="instagram" value="{{ $settings['instagram'] ?? '' }}">
                  </div>
              </div>
          </div>
      </div>

      <!-- Financial Information -->
      <h4 class="fw-bold py-3 mb-3">
        <span class="text-muted fw-light"></span> {{ __('Financial Information') }}
    </h4>
      <div class="card mb-4">
          <div class="card-body">
              <div class="row mb-3">
                  <div class="col-md-6">
                      <label class="form-label" for="ccp">{{ __('CCP Account') }}</label>
                      <input type="text" class="form-control" id="ccp" name="ccp" value="{{ $settings['ccp'] ?? '' }}">
                  </div>
                  <div class="col-md-6">
                      <label class="form-label" for="baridi">{{ __('Baridi Mob') }}</label>
                      <input type="text" class="form-control" id="baridi" name="baridi" value="{{ $settings['baridi'] ?? '' }}">
                  </div>
              </div>
              <div class="row mb-3">
                  <div class="col-md-6">
                      <label class="form-label" for="chargily_pk">{{ __('Chargily Public Key') }}</label>
                      <input type="text" class="form-control" id="chargily_pk" name="chargily_pk" value="{{ $settings['chargily_pk'] ?? '' }}">
                  </div>
                  <div class="col-md-6">
                      <label class="form-label" for="chargily_sk">{{ __('Chargily Secret Key') }}</label>
                      <input type="password" class="form-control" id="chargily_sk" name="chargily_sk" value="{{ $settings['chargily_sk'] ?? '' }}">
                  </div>
              </div>
          </div>
      </div>

      <!-- Discount Information -->
      <h4 class="fw-bold py-3 mb-3">
        <span class="text-muted fw-light"></span> {{ __('Discount Information') }}
    </h4>
      <div class="card mb-4">
          <div class="card-body">
              <div class="row mb-3">
                  <div class="col-md-6">
                      <label class="form-label" for="order_threshold">{{ __('Order Threshold') }}</label>
                      <div class="input-group">
                          <input type="number" class="form-control" id="order_threshold" name="order_threshold" value="{{ $settings['order_threshold'] ?? '' }}">
                          <span class="input-group-text">{{ __('DA') }}</span>
                      </div>
                  </div>
                  <div class="col-md-6">
                      <label class="form-label" for="coupon_code">{{ __('Coupon Code') }}</label>
                      <input type="text" class="form-control" id="coupon_code" name="coupon_code" value="{{ $settings['coupon_code'] ?? '' }}">
                  </div>
              </div>
          </div>
      </div>
        <div class="mb-3" style="text-align: center">
            <button type="submit" id="submit" name="submit" class="btn btn-primary">{{ __('Send') }}</button>
        </div>
    </form>

@endsection

@section('page-script')
    <script>
        $(document).ready(function() {

            $('#submit').on('click', function() {
                var queryString = new FormData($("#form")[0]);
                //console.log(queryString);
                $.ajax({
                    url: '{{ url('setting/update') }}',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'POST',
                    data: queryString,
                    dataType: 'JSON',
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.status == 1) {
                            Swal.fire(
                                "{{ __('Success') }}",
                                "{{ __('success') }}",
                                'success'
                            );
                        } else {
                            console.log(response.message);
                            Swal.fire(
                                "{{ __('Error') }}",
                                response.message,
                                'error'
                            );
                        }
                    },
                    error: function(data) {
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
        });
    </script>
@endsection
