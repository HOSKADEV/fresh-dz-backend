@extends('layouts/contentNavbarLayout')

@section('title', 'Dashboard - Analytics')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/apex-charts/apex-charts.css')}}">
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/apex-charts/apexcharts.js')}}"></script>
@endsection

@section('page-script')
<script src="{{asset('assets/js/dashboards-analytics.js')}}"></script>
@endsection

@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">TL;DR/</span> Text summarization</h4>

<!-- Basic Layout & Basic with Icons -->
<div class="row">
  <!-- Basic Layout -->
  <div class="col-xxl">
    <div class="card mb-4">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="mb-0">Original text</h5>
      </div>
      <div class="card-body">
        <form method="POST" action="{{ url('/tldr/action') }}" enctype="multipart/form-data">
          @csrf
          <div class="row mb-3">
            {{-- <label class="col-sm-2 col-form-label" for="basic-default-message">Text</label> --}}
            <div class="col-sm-12">
              <textarea id="text" name="text" class="form-control" placeholder="Type the text which you want to summarize here" aria-label="Hi, Do you have a moment to talk Joe?" aria-describedby="basic-icon-default-message2"></textarea>
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-sm-12">
              <button type="submit" class="btn btn-primary">Send</button>
              <button type="button" class="btn btn-primary">Random</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
