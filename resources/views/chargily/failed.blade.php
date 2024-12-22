@extends('layouts/blankLayout')

@section('title', __('Purchase success'))

@section('page-style')
<!-- Page -->
<link rel="stylesheet" href="{{asset('assets/vendor/css/pages/page-auth.css')}}">
@endsection

@section('content')
<div class="container-xxl">
  <div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner">
      <!-- Register -->
      <div class="card">
        <div class="card-body" style="align-itens: center !important">
          <h2 class="text-center">{{__('Purchase status')}}</h2>
          <p class="text-center">{{__('An error has occured while completing your purchase')}}</p>

          <div class="mt-3">
            <img src="{{asset('assets/img/illustrations/purchase-failed.png')}}" alt="page-misc-error-light" width="300" class="img-fluid">
          </div>

          <p class="text-center">
            <span>{{__('Have a problem? ')}}</span>
            <a href="{{ 'https://api.whatsapp.com/send?phone=' . $number}}">
              <span>{{__('contact us')}}</span>
            </a>
          </p>
        </div>
      </div>
    </div>
    <!-- /Register -->
  </div>
</div>
</div>
@endsection

