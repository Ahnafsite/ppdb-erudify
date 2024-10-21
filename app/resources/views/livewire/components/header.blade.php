<header>
    <!-- header top start -->
    <div class="hidden bg-blackColor2 dark:bg-lightGrey10-dark lg:block">
      <div class="container mx-auto 3xl:container2-lg 4xl:container text-whiteColor text-size-12 xl:text-sm py-5px xl:py-9px">
        <div class="flex items-center justify-between">
          <div>
            <a href="https://wa.me/6285877159577" target="_blank" style="color: white">Hubungi kami : 085877159577</a>
          </div>
          <div class="flex items-center gap-37px">
            <div>
              <p>
                <i class="icofont-location-pin text-primaryColor text-size-15 mr-5px"></i>
                <a href="mail_to=ahnafsite@gmail.com" target="_blank" style="color: white">ahnafsite@gmail.com</a>
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- header top end -->

    <!-- navbar start -->
    <div
      class="transition-all duration-500 bg-white sticky-header z-medium dark:bg-whiteColor-dark"
    >
      <nav>
        <div
          class="relative mx-auto py-15px lg:py-0 px-15px lg:container 3xl:container2-lg 4xl:container"
        >
          <div class="grid items-center grid-cols-2 lg:grid-cols-12 gap-15px">
            <!-- navbar left -->
            <div class="lg:col-start-1 lg:col-span-2">
              <a href="{{ url('/') }}" class="block"
                ><img
                  src="{{ asset('images/logo_erudify.png') }}"
                  alt="Logo"
                  class="w-auto h-20 py-2 lg:w-auto"
              ></a>
            </div>
            <!-- Main menu -->
            <div class="hidden lg:block lg:col-start-3 lg:col-span-7">
            </div>
            <!-- navbar right -->
            <div class="lg:col-start-10 lg:col-span-3">
                <ul class="relative flex items-center justify-end nav-list">
                <li class="hidden lg:block">
                    @if (!request()->is('daftar/*'))
                                @if (Auth::user())
                                    <a href="{{ url('/member') }}" class="block py-2 border text-size-12 2xl:text-size-15 text-whiteColor bg-primaryColor border-primaryColor hover:text-primaryColor hover:bg-white px-15px rounded-standard dark:hover:bg-whiteColor-dark dark: dark:hover:text-whiteColor">Dashboard</a>
                                @else
                                    <a href="{{ url('/member/login') }}" class="block py-2 border text-size-12 2xl:text-size-15 text-whiteColor bg-primaryColor border-primaryColor hover:text-primaryColor hover:bg-white px-15px rounded-standard dark:hover:bg-whiteColor-dark dark: dark:hover:text-whiteColor">Masuk</a>
                                @endif
                            @endif
                </li>
                <li class="block lg:hidden">
                  <button class="text-3xl open-mobile-menu text-darkdeep1 hover:text-secondaryColor dark:text-whiteColor dark:hover:text-secondaryColor">
                    <i class="icofont-navigation-menu"></i>
                  </button>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </nav>
    </div>
    <!-- navbar end -->

    <!-- mobile menu -->
    <div class="mobile-menu w-mobile-menu-sm md:w-mobile-menu-lg fixed top-0 -right-[280px] md:-right-[330px] transition-all duration-500 w-mobile-menu h-full shadow-dropdown-secodary bg-whiteColor dark:bg-whiteColor-dark z-high block lg:hidden">
      <button class="close-mobile-menu text-lg bg-darkdeep1 hover:bg-secondaryColor text-white px-[11px] py-[6px] absolute top-0 right-full hidden">
        <i class="icofont icofont-close-line"></i>
      </button>
      <!-- mobile menu wrapper -->
      <div class="h-full px-5 pt-5 overflow-y-auto md:px-30px md:pt-10 pb-50px">
        <!-- my account accordion -->
        <div>
          <ul
            class="border-b accordion-container mt-9 mb-30px pb-9 border-borderColor dark:border-borderColor-dark"
          >
            <li class="accordion group">
              <!-- accordion header -->
              <div
                class="flex items-center justify-between accordion-controller"
              >
                <a
                  class="font-medium leading-1 text-darkdeep1 group-hover:text-secondaryColor dark:text-whiteColor dark:hover:text-secondaryColor"
                  href="#"
                  >Akun</a
                >
                <button class="px-3 py-1">
                  <i
                    class="icofont-thin-down text-size-15 text-darkdeep1 group-hover:text-secondaryColor dark:text-whiteColor dark:hover:text-secondaryColor"
                  ></i>
                </button>
              </div>
              <!-- accordion content -->
              <div
                class="h-0 overflow-hidden transition-all duration-500 accordion-content shadow-standard"
              >
                <div class="content-wrapper">
                  <ul>
                    <li>
                      <!-- accordion header -->
                      <div class="flex items-center gap-1">
                        @if (!request()->is('daftar/*'))
                            @if (Auth::user())
                                <a href="{{ url('/member') }}" class="pb-3 text-sm font-medium leading-1 text-darkdeep1 pl-30px pt-7 hover:text-secondaryColor dark:text-whiteColor dark:hover:text-secondaryColor">Dashboard</a>
                            @else
                                <a href="{{ url('/member/login') }}" class="pb-3 text-sm font-medium leading-1 text-darkdeep1 pl-30px pt-7 hover:text-secondaryColor dark:text-whiteColor dark:hover:text-secondaryColor">Masuk</a>
                            @endif
                        @endif
                      </div>
                    </li>
                  </ul>
                </div>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>
</header>
