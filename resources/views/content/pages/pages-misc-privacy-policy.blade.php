@extends('layouts/blankLayout')

@section('title', 'Privacy Policy')

@section('content')
<div class="background"></div>
  <div class="container">
    <div class="row">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header"  dir="rtl">
                    <h3>سياسة الخصوصية</h3>
                </div>
                <div class="card-body"  dir="rtl">
                    {!! nl2br($privacy_policy,false) !!}
                </div>
            </div>
        </div>
    </div>
  </div>
</div>
<style>
  .container {
      position: relative;
      min-width: 100%;
      z-index: 1;
      background-color: rgba(0,0,0,.5);
      color: #000000;
      /* padding: 20px;
      border-radius: 8px; */

  }
  .card {

      background-color: rgba(255, 255, 255, 0.7);
      color: #111419;
      font-size: 1.2rem;
      font-weight: 500;
      margin: auto;
  }
  .col-md-10{
    margin:auto;
  }
  .background {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: -1;
      /* Adjust the following properties to set the background image or video */
      /*background-image: url('assets/img/backgrounds/background.jpg');*/
      /* Uncomment the line below and replace 'path/to/your/video.mp4' with the path to your video file to use a video background */
      /* background-video: url('path/to/your/video.mp4'); */
      background-color: #000000;
      background-size: cover;
      background-position: center;

  }
</style>

@endsection
