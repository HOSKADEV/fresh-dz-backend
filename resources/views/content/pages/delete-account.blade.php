<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>{{ __('Delete Your Account') }}</title>
    <meta name="description" content="">
    <meta name="keywords" content="">

    <!-- Favicons -->
    <link href="{{ asset('logo.png') }}" rel="icon">
    <link href="{{ asset('logo.png') }}" rel="apple-touch-icon">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@200;300;400;500;700;800;900&display=swap"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('pages/assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('pages/assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('pages/assets/vendor/aos/aos.css') }}" rel="stylesheet">
    <link href="{{ asset('pages/assets/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">
    <link href="{{ asset('pages/assets/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">

    <!-- Main CSS File -->
    <link href="{{ asset('pages/assets/css/main.css') }}" rel="stylesheet">
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
      <a href="{{url('/')}}" class="logo d-flex align-items-center">
        <img src="{{asset('logo-no-bg.png')}}" alt="Logo" id="logo">
      </a>

  </div>
</header>

<body class="index-page">
  <main class="main">
    <section id="account-deletion" class="account-deletion"   @if (Session::get('locale') == 'ar') dir="rtl" lang="ar" @endif>
        <div class="container">
            <div class="section-title">
                <h2>{{ __('Delete Your Account') }}</h2>
            </div>

            <div class="row">
                <div class="col-12">
                    <h3 class="my-3"><strong>{{ __('Important') }}:</strong></h3>
                    <h4>
                        {{ __('Deleting your account will permanently remove all your personal information from our databases and any pending cart items. This action cannot be undone.') }}
                    </h4>

                    <h4 class="mt-4">{{ __('To delete your account, follow these steps:') }}</h4>
                </div>
            </div>

            <div class="row justify-content-center mt-4">
                <div class="col-xl-5 text-center">
                    <div class="deletion-step">
                        <img src="{{ asset('pages/assets/img/delete/1.jpg') }}" class="img-fluid" alt="{{ __('Open side menu') }}">
                        <h4 class="my-3">{{ __('Step 1: On the home page, press the three lines to reveal the side menu') }}</h4>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center mt-5">
                <div class="col-xl-5 text-center">
                    <div class="deletion-step">
                        <img src="{{ asset('pages/assets/img/delete/2.jpg') }}" class="img-fluid" alt="{{ __('Select Edit Profile') }}">
                        <h4 class="my-3">{{ __('Step 2: In the side menu, click on Edit Profile') }}</h4>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center mt-5">
                <div class="col-xl-5 text-center">
                    <div class="deletion-step">
                        <img src="{{ asset('pages/assets/img/delete/3.jpg') }}" class="img-fluid" alt="{{ __('Press Delete Account') }}">
                        <h4 class="my-3">{{ __('Step 3: On the Edit Profile page, press Delete Account') }}</h4>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center mt-5">
                <div class="col-xl-5 text-center">
                    <div class="deletion-step">
                        <img src="{{ asset('pages/assets/img/delete/4.jpg') }}" class="img-fluid" alt="{{ __('Confirm deletion') }}">
                        <h4 class="my-3">{{ __('Step 4: When the pop-up appears, click Confirm to delete your account') }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </section>
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
