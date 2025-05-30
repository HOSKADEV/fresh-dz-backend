<!-- BEGIN: Theme CSS-->
<!-- Fonts -->
{{-- <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet"> --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@200;300;400;500;700;800;900&display=swap" rel="stylesheet">

<link rel="stylesheet" href="{{ asset(mix('assets/vendor/fonts/boxicons.css')) }}" />

<!-- Core CSS -->
@if (Session::get('theme') == 'dark')
  @if (Session::get('locale') == 'ar')
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/core-dark.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/theme-default-dark.css') }}" />
  @else
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core-dark.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/theme-default-dark.css') }}" />
  @endif
@else
  @if (Session::get('locale') == 'ar')
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/core.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/theme-default.css') }}" />
  @else
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/theme-default.css') }}" />
  @endif
@endif
<link rel="stylesheet" href="{{ asset('assets/vendor/css/colors.css') }}">
<link rel="stylesheet" href="{{ asset(mix('assets/css/demo.css')) }}" />
<link rel="stylesheet" href="{{ asset('assets/css/app.css') }}" />
<link rel="stylesheet" href="{{ asset(mix('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css')) }}" />
{{-- <link rel="stylesheet" href="//cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" /> --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css" />

<!-- Vendor Styles -->
@yield('vendor-style')


<!-- Page Styles -->
@yield('page-style')
