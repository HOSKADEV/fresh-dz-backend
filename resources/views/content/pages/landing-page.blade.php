<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="icon" href="{{asset('pages/src/assets/images/logo-icon.png')}}" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="theme-color" content="#000000" />
    <meta name="description" content="Web site created using javascript" />
    <link rel="apple-touch-icon" href="{{asset('pages/src/assets/images/logo-icon.png')}}" />
    <link rel="manifest" href="{{asset('pages/manifest.json')}}" />
    <link rel="stylesheet" href="{{asset('pages/src/input.css')}}">
    <link rel="stylesheet" href="{{asset('pages/src/output.css')}}">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@300..700&display=swap');
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js" defer></script>
    <title> فراش ديزاد - الرئيسية</title>
</head>
<body>
    <!-- Navbar section -->
    <nav id="navBar" class="h-[70px] flex fixed left-0 right-0 top-0 z-[1000]">
        <div class="bg-white dark:bg-dark shadow-myShadow dark:shadow-mydarkShaow w-full">
            <div class="container h-full mx-auto px-4 flex justify-between">
                <!-- Logo -->
                <a href="#hero" class="flex items-center gap-4 ">
                    <img class="max-h-[50px] " src="{{asset('pages/src/assets/images/logo.png')}}" alt="Logo" />
                </a>
                <!-- Nav Links -->
                <ul class="hidden lg:flex">
                    <li class="flex m-0">
                        <a class="navLink text-md font-semibold flex items-center px-6 relative text-secondary dark:text-darksecondary hover:text-black dark:hover:text-white" href="#hero">الرئيسية</a>
                    </li>
                    <li class="flex m-0">
                        <a class="navLink text-md font-semibold flex items-center px-6 relative text-secondary dark:text-darksecondary hover:text-black dark:hover:text-white" href="#features">المميزات</a>
                    </li>
                    <li class="flex m-0">
                        <a class="navLink text-md font-semibold flex items-center px-6 relative text-secondary dark:text-darksecondary hover:text-black dark:hover:text-white" href="#faq">الأسئلة الشائعة</a>
                    </li>
                    <li class="flex m-0">
                        <a class="navLink text-md font-semibold flex items-center px-6 relative text-secondary dark:text-darksecondary hover:text-black dark:hover:text-white" href="#contact">اتصل بنا</a>
                    </li>
                </ul>
                <!-- Humburger Menu icon -->
                <div class="flex items-center lg:hidden" id="menuToggle">
                    <span class="text-2xl text-secondary dark:text-darksecondary cursor-pointer p-1 rounded-lg border dark:border-close-btn-bg">
                        <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 448 512" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                            <path d="M0 96C0 78.3 14.3 64 32 64H416c17.7 0 32 14.3 32 32s-14.3 32-32 32H32C14.3 128 0 113.7 0 96zM0 256c0-17.7 14.3-32 32-32H416c17.7 0 32 14.3 32 32s-14.3 32-32 32H32c-17.7 0-32-14.3-32-32zM448 416c0 17.7-14.3 32-32 32H32c-17.7 0-32-14.3-32-32s14.3-32 32-32H416c17.7 0 32 14.3 32 32z"></path>
                        </svg>
                    </span>
                </div>
                <!-- Close Menu icon -->
                <div id="closeMenu" class="hidden lg:hidden text-2xl text-secondary dark:text-darksecondary cursor-pointer p-1 dark:border-close-btn-bg content-center">
                    <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 512 512" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                        <path d="m289.94 256 95-95A24 24 0 0 0 351 127l-95 95-95-95a24 24 0 0 0-34 34l95 95-95 95a24 24 0 1 0 34 34l95-95 95 95a24 24 0 0 0 34-34z"></path>
                    </svg>
                </div>
                <!-- Nav links for Mobile -->
                <ul id="mobileMenu" class="opacity-0 scale-0 transition-all duration-500 container bg-navbar-bg dark:bg-dark border border-border-gray dark:border-close-btn-bg rounded-md px-4 pt-4 fixed z-[1000] top-48 left-1/2 -translate-x-1/2 -translate-y-1/2 lg:hidden hidden">
                    <li>
                        <a class="border-b text-secondary dark:text-darksecondary p-4 block hover:text-black dark:hover:text-white" href="#hero">الرئيسية</a>
                    </li>
                    <li>
                        <a class="border-b text-secondary dark:text-darksecondary p-4 block hover:text-black dark:hover:text-white" href="#features">المميزات</a>
                    </li>
                    <li>
                        <a class="border-b text-secondary dark:text-darksecondary p-4 block hover:text-black dark:hover:text-white" href="#faq">الأسئلة الشائعة</a>
                    </li>
                    <li>
                        <a class="border-b text-secondary dark:text-darksecondary p-4 block hover:text-black dark:hover:text-white" href="#contact">اتصل بنا</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Page  -->
    <main>
        <!-- Hero section  -->
        <section class="flex flex-col justify-between" id="hero">
            <div class="myContainer flex flex-col-reverse lg:flex-row lg:items-center h-full py-8 gap-8">
              <div class="flex flex-col items-center lg:items-start flex-1 ">
                <h1 class=" mainTitle h-24" id="title">
                  فراش ديزاد
                </h1>
                <p class=" leading-loose text-secondary text-center lg:text-start " id="description">
                  فراش ديزاد هو تطبيق متخصص يوفر خضروات، فواكه، لحوم، وبيض مع خدمة توصيل مباشرة إلى الموقع الذي تحدده. يمكنك من خلال التطبيق استعراض المنتجات بسهولة، اختيار احتياجاتك، وتحديد موعد التوصيل بكل مرونة.
                </p>
                <div class="flex">
                    <div class="flex">
                      <a href="https://play.google.com/store/apps/details?id=com.fresh.dz&hl=en" target="_blank">
                        <button class="download flex gap-2 items-center text-white px-2.5 py-2 border-none rounded-md my-8 cursor-pointer" id="downloadButton">
                            <span class="">حمل الان</span>
                            <span>
                                <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 512 512" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg"><path d="M325.3 234.3L104.6 13l280.8 161.2-60.1 60.1zM47 0C34 6.8 25.3 19.2 25.3 35.3v441.3c0 16.1 8.7 28.5 21.7 35.3l256.6-256L47 0zm425.2 225.6l-58.9-34.1-65.7 64.5 65.7 64.5 60.1-34.1c18-14.3 18-46.5-1.2-60.8zM104.6 499l280.8-161.2-60.1-60.1L104.6 499z"></path></svg>
                            </span>
                        </button>
                      </a>
                    </div>
                </div>
                <div class="flex gap-4" id="social-links">
                    <a href="#" target="_blank" rel="noopener noreferrer" class= "icon text-2xl gap-2">
                        <svg class="text-[#1877F2]" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 448 512"  height="28" width="28" xmlns="http://www.w3.org/2000/svg"><path d="M400 32H48A48 48 0 0 0 0 80v352a48 48 0 0 0 48 48h137.25V327.69h-63V256h63v-54.64c0-62.15 37-96.48 93.67-96.48 27.14 0 55.52 4.84 55.52 4.84v61h-31.27c-30.81 0-40.42 19.12-40.42 38.73V256h68.78l-11 71.69h-57.78V480H400a48 48 0 0 0 48-48V80a48 48 0 0 0-48-48z"></path></svg>
                    </a>
                    <a href="#" target="_blank" rel="noopener noreferrer" class= "icon text-2xl gap-2">
                        <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 448 512" class="text-[#C13584]" height="28" width="28" xmlns="http://www.w3.org/2000/svg"><path d="M224,202.66A53.34,53.34,0,1,0,277.36,256,53.38,53.38,0,0,0,224,202.66Zm124.71-41a54,54,0,0,0-30.41-30.41c-21-8.29-71-6.43-94.3-6.43s-73.25-1.93-94.31,6.43a54,54,0,0,0-30.41,30.41c-8.28,21-6.43,71.05-6.43,94.33S91,329.26,99.32,350.33a54,54,0,0,0,30.41,30.41c21,8.29,71,6.43,94.31,6.43s73.24,1.93,94.3-6.43a54,54,0,0,0,30.41-30.41c8.35-21,6.43-71.05,6.43-94.33S357.1,182.74,348.75,161.67ZM224,338a82,82,0,1,1,82-82A81.9,81.9,0,0,1,224,338Zm85.38-148.3a19.14,19.14,0,1,1,19.13-19.14A19.1,19.1,0,0,1,309.42,189.74ZM400,32H48A48,48,0,0,0,0,80V432a48,48,0,0,0,48,48H400a48,48,0,0,0,48-48V80A48,48,0,0,0,400,32ZM382.88,322c-1.29,25.63-7.14,48.34-25.85,67s-41.4,24.63-67,25.85c-26.41,1.49-105.59,1.49-132,0-25.63-1.29-48.26-7.15-67-25.85s-24.63-41.42-25.85-67c-1.49-26.42-1.49-105.61,0-132,1.29-25.63,7.07-48.34,25.85-67s41.47-24.56,67-25.78c26.41-1.49,105.59-1.49,132,0,25.63,1.29,48.33,7.15,67,25.85s24.63,41.42,25.85,67.05C384.37,216.44,384.37,295.56,382.88,322Z"></path></svg>
                    </a>
                    <a href="#" target="_blank" rel="noopener noreferrer" class= "icon text-2xl gap-2">
                        <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 448 512" class="text-[#1DA1F2]" height="28" width="28" xmlns="http://www.w3.org/2000/svg"><path d="M400 32H48C21.5 32 0 53.5 0 80v352c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48V80c0-26.5-21.5-48-48-48zm-48.9 158.8c.2 2.8.2 5.7.2 8.5 0 86.7-66 186.6-186.6 186.6-37.2 0-71.7-10.8-100.7-29.4 5.3.6 10.4.8 15.8.8 30.7 0 58.9-10.4 81.4-28-28.8-.6-53-19.5-61.3-45.5 10.1 1.5 19.2 1.5 29.6-1.2-30-6.1-52.5-32.5-52.5-64.4v-.8c8.7 4.9 18.9 7.9 29.6 8.3a65.447 65.447 0 0 1-29.2-54.6c0-12.2 3.2-23.4 8.9-33.1 32.3 39.8 80.8 65.8 135.2 68.6-9.3-44.5 24-80.6 64-80.6 18.9 0 35.9 7.9 47.9 20.7 14.8-2.8 29-8.3 41.6-15.8-4.9 15.2-15.2 28-28.8 36.1 13.2-1.4 26-5.1 37.8-10.2-8.9 13.1-20.1 24.7-32.9 34z"></path></svg>
                    </a>
                    <a href="#" target="_blank" rel="noopener noreferrer" class= "icon text-2xl gap-2">
                        <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 448 512" class="text-[#25D366]" height="28" width="28" xmlns="http://www.w3.org/2000/svg"><path d="M224 122.8c-72.7 0-131.8 59.1-131.9 131.8 0 24.9 7 49.2 20.2 70.1l3.1 5-13.3 48.6 49.9-13.1 4.8 2.9c20.2 12 43.4 18.4 67.1 18.4h.1c72.6 0 133.3-59.1 133.3-131.8 0-35.2-15.2-68.3-40.1-93.2-25-25-58-38.7-93.2-38.7zm77.5 188.4c-3.3 9.3-19.1 17.7-26.7 18.8-12.6 1.9-22.4.9-47.5-9.9-39.7-17.2-65.7-57.2-67.7-59.8-2-2.6-16.2-21.5-16.2-41s10.2-29.1 13.9-33.1c3.6-4 7.9-5 10.6-5 2.6 0 5.3 0 7.6.1 2.4.1 5.7-.9 8.9 6.8 3.3 7.9 11.2 27.4 12.2 29.4s1.7 4.3.3 6.9c-7.6 15.2-15.7 14.6-11.6 21.6 15.3 26.3 30.6 35.4 53.9 47.1 4 2 6.3 1.7 8.6-1 2.3-2.6 9.9-11.6 12.5-15.5 2.6-4 5.3-3.3 8.9-2 3.6 1.3 23.1 10.9 27.1 12.9s6.6 3 7.6 4.6c.9 1.9.9 9.9-2.4 19.1zM400 32H48C21.5 32 0 53.5 0 80v352c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48V80c0-26.5-21.5-48-48-48zM223.9 413.2c-26.6 0-52.7-6.7-75.8-19.3L64 416l22.5-82.2c-13.9-24-21.2-51.3-21.2-79.3C65.4 167.1 136.5 96 223.9 96c42.4 0 82.2 16.5 112.2 46.5 29.9 30 47.9 69.8 47.9 112.2 0 87.4-72.7 158.5-160.1 158.5z"></path></svg>
                    </a>
                </div>
              </div>

              <div class="flex flex-1 justify-center lg:justify-end">
                <img
                  src="{{asset('pages/src/assets/images/Hero.png')}}"
                  alt="hero" id="heroImage"/>
              </div>
            </div>

             <!-- Waves animation -->
              <div class="mywaves h-16 mt-44 w-full bg-[#26C755] p-0 relative">
                  <div class="mywave" id="wave1"></div>
                  <div class="mywave" id="wave2"></div>
                  <div class="mywave" id="wave3"></div>
                  <div class="mywave" id="wave4"></div>
              </div>

          </section>
        <!-- Features section  -->
        <section id="features" class="features">
            <div class="myContainer flex flex-col justify-center items-center gap-8">
                <div class="flex flex-col items-center gap-4">
                    <h1 id="featuresTitle" class="mainTitle">المميزات</h1>
                    <p id="featuresSubtitle" class="text-secondary r">
                        لمادا يجب عليك استخدام تطبيق فراش ديزاد
                        للحصول على خضروات، فواكه، لحوم، وبيض  بسهولة
                    </p>
                </div>
                <div class="flex flex-col lg:flex-row mt-16 gap-16">
                    <div class="flex flex-col items-start gap-8 lg:w-1/3">
                        <span id="featuresLogo" class="bg-gray-100  p-2 rounded-md flex items-center">
                            <img class="max-h-[50px]" src="{{asset('pages/src/assets/images/logo.png')}}" alt="logo" />
                        </span>
                        <h2 id="whyFresh" class="text-2xl font-semibold">
                            لماذا تطبيق <span class="bg-gradient-to-r from-[#8BB746] to-[#3CAB4F] bg-clip-text text-transparent font-bold">فراش ديزاد
                            </span>؟
                        </h2>
                        <p id="featuresDescription" class="text-secondary leading-loose ">
                            تطبيق فراش ديزاد يهدف إلى تسهيل عملية شراء الخضروات، الفواكه الطازجة، والمنتجات الغذائية الأخرى مباشرة من الموردين المحليين. يجمع التطبيق بين البساطة، الراحة، والجودة ليضمن للمستهلكين تجربة تسوق إلكتروني فريدة.
                        </p>
                    </div>
                    <!-- Cards  -->
                    <div id="featuresGrid" class="grid sm:grid-cols-2 flex-1 gap-8"></div>
                </div>
            </div>
        </section>

          <!-- FAQ section  -->

          <section id="faq">
            <div class="flex flex-col gap-16 pb-[100px] myContainer bg-[#f8fdf8;]">
              <div class="flex flex-col items-center gap-4 mt-10">
                <h1 id="title" class="mainTitle">
                  الاسئلة الشائعة
                </h1>
                <p id="description" class="text-secondary text-center">
                  هناك العديد من الأسئلة التي يمكن أن تكون لديك حول تطبيق  فراش ديزاد
                </p>
              </div>
              <div id="questions-container" class="flex flex-col items-center gap-12 "></div>
            </div>
          </section>

          <!-- Contact section  -->

          <section id="contact">
            <div class="myContainer flex flex-col justify-center gap-12">
              <div class="flex flex-col items-center gap-4">
                <h1 id="contactTitle" class="mainTitle font-bold text-center">
                  اتصل بنا
                </h1>
                <p id="contactSubtitle" class="text-secondary text-center">
                  اتصل بنا لأي استفسار او مشكل تواجهه اثناء استخدام التطبيق
                </p>
              </div>
              <div class="flex flex-col items-center gap-12">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 w-full sm:w-[80%] contact-details">
                  <div class="flex flex-col items-center bg-[#f1f3f6] px-5 py-10 gap-4">
                    <img src="{{asset('pages/src/assets/images/phone-call.png')}}" class="h-11 w-11" alt="">
                    <h3 class="font-bold text-xl">رقم الهاتف</h3>
                    <p class="text-secondary dark:text-darksecondary">
                      0551061149
                    </p>
                  </div>
                  <div class="flex flex-col items-center bg-[#f1f3f6] px-5 py-10 gap-4">
                    <img src="{{asset('pages/src/assets/images/mail.png')}}" class="w-11 h-11" alt="">
                    <h3 class="font-bold text-xl">البريد الالكتروني</h3>
                    <p class="text-secondary dark:text-darksecondary">
                      contact@freshdz.net
                    </p>
                  </div>
                  <div class="flex flex-col items-center bg-[#f1f3f6] px-5 py-10 gap-4">
                    <img src="{{asset('pages/src/assets/images/pin.png')}}" class="h-11 w-11" alt="">
                    <h3 class="font-bold text-xl">الموقع الجغرافي</h3>
                    <p class="text-secondary dark:text-darksecondary">سيدي عبد الله - الجزائر</p>
                    </div>
                  <div class="flex flex-col items-center bg-[#f1f3f6] px-5 py-10 gap-4">
                    <img src="{{asset('pages/src/assets/images/calendar.png')}}" class="w-11 h-11" alt="">
                    <h3 class="font-bold text-xl">ساعات العمل</h3>
                    <p class="text-secondary dark:text-darksecondary">السبت - الأربعاء: 09:00 إلى 18:00
                      الخميس: 09:00 إلى 12:00</p>
                  </div>
                </div>
              </div>
            </div>
          </section>

          <!-- Footer section  -->

          <footer class="mt-16 py-8 border-t border-border-gray ">
            <div class="myContainer flex justify-center ">
              <p class="text-center text-secondary">
                <a href="#" class="text-primary font-bold">
                  فراش ديزاد
                </a>
                كل الحقوق محفوظة
                <span id="date"></span>
                &copy;
              </p>
            </div>
          </footer>

    </main>
    <script src="{{asset('pages/src/index.js')}}" type="module" defer></script>
</body>
</html>
