<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>{{$title}}</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link href="{{asset('logo.png')}}" rel="icon">
  <link href="{{asset('logo.png')}}" rel="apple-touch-icon">

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@200;300;400;500;700;800;900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{asset('pages/assets/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
  <link href="{{asset('pages/assets/vendor/bootstrap-icons/bootstrap-icons.css')}}" rel="stylesheet">
  <link href="{{asset('pages/assets/vendor/aos/aos.css')}}" rel="stylesheet">
  <link href="{{asset('pages/assets/vendor/swiper/swiper-bundle.min.css')}}" rel="stylesheet">
  <link href="{{asset('pages/assets/vendor/glightbox/css/glightbox.min.css')}}" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="{{asset('pages/assets/css/main.css')}}" rel="stylesheet">
</head>

<header id="header" class="header d-flex align-items-center">
  {{-- <div class="container-fluid container-xl position-relative d-flex align-items-center justify-content-between">

    <nav id="navmenu" class="navmenu">
      <ul>
        <li><a href="#hero" class="active">الرئيسية</a></li>
        <li><a href="#features">الميزات</a></li>
        <li><a href="#gallery">المعرض</a></li>
        <li><a href="#contact">التواصل</a></li>

      </ul>
      <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
    </nav>
    <div class="header-container d-flex align-items-center justify-content-end">


      <div class="custom-dropdown">
        <button class="dropdown-toggle" id="dropdownButton">
          <img src="{{asset('pages/assets/img/united-kingdom.png')}}" alt="English" id="selectedLanguageImg">
          <span id="selectedLanguageText">English</span>
        </button>
        <div class="dropdown-menu" id="languageDropdown">
          <div class="dropdown-item" onclick="switchToLanguage('en')">
            English <img src="{{asset('pages/assets/img/united-kingdom.png')}}" alt="English">
          </div>
          <div class="dropdown-item" onclick="switchToLanguage('ar')">
            العربية <img src="{{asset('pages/assets/img/algeria.png')}}" alt="Arabic">
          </div>
        </div>
      </div>
      <a href="index.html" class="logo d-flex align-items-center ">
        <img src="{{asset('pages/assets/img/sobol.png')}}" alt="Logo" id="logo">
      </a>
    </div>
  </div> --}}

  <div class="container-fluid container-xl position-relative d-flex align-items-center justify-content-between">

      <div class="custom-dropdown">
        @if(session('locale') == 'en')
        <button class="dropdown-toggle" id="dropdownButton">
          <img src="{{asset('pages/assets/img/united-kingdom.png')}}" alt="English" id="selectedLanguageImg">
          <span id="selectedLanguageText">English</span>
        </button>
        @else
        <button class="dropdown-toggle" id="dropdownButton">
          <img src="{{asset('pages/assets/img/algeria.png')}}" alt="Arabic" id="selectedLanguageImg">
          <span id="selectedLanguageText">العربية</span>
        </button>
        @endif
        <div class="dropdown-menu" id="languageDropdown">
          <a class="dropdown-item" href="{{ url('lang/en') }}">
            English <img src="{{asset('pages/assets/img/united-kingdom.png')}}" alt="English">
          </a>
          <a class="dropdown-item" href="{{ url('lang/ar') }}">
            العربية <img src="{{asset('pages/assets/img/algeria.png')}}" alt="Arabic">
          </a>
        </div>
      </div>
      <a href="{{url('/dashboard')}}" class="logo d-flex align-items-center">
        <img src="{{asset('logo-no-bg.png')}}" alt="Logo" id="logo">
      </a>

  </div>
</header>

<body class="index-page">
  <main class="main">
      <div class="content-wrapper documentation"   @if (Session::get('locale') == 'ar') dir="rtl" lang="ar" @endif>
          {!! $data !!}
      </div>
  </main>

<footer id="footer" class="footer dark-background">
  <div class="container">
      <img class="footerLogo rounded" src="{{asset('logo.png')}}" alt="logo">

      <div class="social-links d-flex justify-content-center">
          <a href=""><i class="bi bi-twitter-x"></i></a>
          <a href=""><i class="bi bi-facebook"></i></a>
          <a href=""><i class="bi bi-instagram"></i></a>
          <a href=""><i class="bi bi-linkedin"></i></a>
      </div>
      <div class="container">
          <div class="copyright">
              <span>{{__('Copyright')}}</span> <strong>{{__('Fresh Dz')}}</strong> <span>{{__('All rights reserved')}}</span>
          </div>
      </div>
  </div>
</footer>
</body>
</html>
