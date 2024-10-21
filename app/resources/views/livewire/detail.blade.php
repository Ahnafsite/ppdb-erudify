<div>
    <!-- breadcrumbarea__section__start -->
    <section>
        <!-- banner section -->
        <div
          class="bg-lightGrey10 dark:bg-lightGrey10-dark relative z-0 overflow-y-visible py-50px md:py-20 lg:py-100px 2xl:pb-150px 2xl:pt-40.5"
        >
          <!-- animated icons -->
          <div>
            <img
              class="absolute left-0 bottom-0 md:left-[14px] lg:left-[50px] lg:bottom-[21px] 2xl:left-[165px] 2xl:bottom-[60px] animate-move-var z-10"
              src="{{ asset('frontend/assets/images/herobanner/herobanner__1.png') }}"
              alt=""
            ><img
              class="absolute left-0 top-0 lg:left-[50px] lg:top-[100px] animate-spin-slow"
              src="{{ asset('frontend/assets/images/herobanner/herobanner__2.png') }}"
              alt=""
            ><img
              class="absolute right-[30px] top-0 md:right-10 lg:right-[575px] 2xl:top-20 animate-move-var2 opacity-50 hidden md:block"
              src="{{ asset('frontend/assets/images/herobanner/herobanner__3.png') }}"
              alt=""
            >

            <img
              class="absolute right-[30px] top-[212px] md:right-10 md:top-[157px] lg:right-[45px] lg:top-[100px] animate-move-hor"
              src="{{ asset('frontend/assets/images/herobanner/herobanner__5.png') }}"
              alt=""
            >
          </div>
          <div class="container">
            <div class="text-center">
              <h1
                class="pt-3 text-3xl font-bold md:text-size-40 2xl:text-size-55 text-blackColor dark:text-blackColor-dark mb-7 md:mb-6"
              >
                {{ ucwords(strtolower($admission->title)) }}
              </h1>
              <ul class="flex justify-center gap-1">
                {{-- {{ @yield('navigation') }} --}}
                <li>
                  <a
                    href="{{ url('/') }}"
                    class="text-lg text-blackColor2 dark:text-blackColor2-dark"
                    >Beranda<i class="icofont-simple-right"></i
                  ></a>
                </li>
                <li>
                  <span
                    class="text-lg text-blackColor2 dark:text-blackColor2-dark"
                    >Detail</span
                  >
                </li>
              </ul>
            </div>
          </div>
        </div>
    </section>
    <!-- breadcrumbarea__section__end-->
    <!-- event__section__start -->
    <section>
        <div class="container py-50px md:py-70px lg:py-20 2xl:py-100px">
          <div class="grid grid-cols-1 lg:grid-cols-12 gap-30px">
            <div class="lg:col-start-1 lg:col-span-8 space-y-[35px]">
              <!-- event heading -->
              <div>
                <h3
                  class="text-3xl md:text-size-40 leading-11 md:leading-13.5 text-blackColor dark:text-blackColor-dark mb-15px font-bold"
                  data-aos="fade-up"
                >
                {{ ucwords(strtolower($admission->title)) }}
                </h3>
              </div>
              <!-- event 1 -->
              <div data-aos="fade-up" class="mt-35px mb-30px">
                <!-- blog thumbnail -->
                <div class="relative overflow-hidden mb-35px">
                  <img
                    src="{{ Storage::url($admission->image) }}"
                    alt=""
                    class="w-full"
                  >
                </div>
                <!-- blog content -->
                <div>
                  <h4
                    class="text-size-26 font-bold text-blackColor dark:text-blackColor-dark mb-15px !leading-30px"
                    data-aos="fade-up"
                  >
                    Deskripsi
                  </h4>
                  <p
                    class="text-darkdeep4 mb-15px !leading-29px"
                    data-aos="fade-up"
                  >
                    {!! $admission->desc !!}
                  </p>

                  <h4
                    class="text-size-26 font-bold text-blackColor dark:text-blackColor-dark mb-15px !leading-30px"
                    data-aos="fade-up"
                  >
                    Program Studi :
                  </h4>
                  <ul data-aos="fade-up">
                    @foreach ($admission->programs as $program)
                    <li>
                        <p
                          class="text-contentColor dark:text-contentColor-dark mb-10px !leading-29px"
                        >
                          <i class="icofont-check text-primaryColor"></i>
                          {{ $program->title }}
                        </p>
                      </li>
                    @endforeach
                  </ul>
                </div>
              </div>
            </div>
            <!-- blog sidebar -->
            <div class="lg:col-start-9 lg:col-span-4">
              <!-- enroll section -->
              <div
                class="py-33px px-25px shadow-event mb-30px"
                data-aos="fade-up"
              >
                <ul>
                  <li
                    class="flex items-center border-b gap-x-10px mb-25px pb-22px border-borderColor dark:border-borderColor-dark"
                  >
                    <div>
                        <i class="h-18 icofont-calendar text-primaryColor"></i>
                    </div>
                    <div>
                      <p class="text-sm font-medium text-contentColor dark:text-contentColor-dark">
                        <span
                          class="mr-7px text-blackColor dark:text-blackColor-dark"
                        >
                          Pendaftaran:</span
                        >
                        {{ $admission->admission_period_start->isoFormat('D MMM YYYY') . '-' . $admission->admission_period_end->isoFormat('D MMM YYYY') }}
                      </p>
                    </div>
                  </li>
                  <li
                    class="flex items-center border-b gap-x-10px mb-25px pb-22px border-borderColor dark:border-borderColor-dark"
                  >
                    <div>
                        <i class="icofont-brand-whatsapp text-primaryColor h-18"></i>
                    </div>
                    <div>
                      <a href="{{ 'https://wa.me/62' . $admission->contact_person['no_hp'] }}" target="_blank"
                        class="text-sm font-medium text-contentColor dark:text-contentColor-dark"
                      >
                        <span
                          class="mr-7px text-blackColor dark:text-blackColor-dark"
                        >
                          CP:</span
                        >
                        {{ $admission->contact_person['name'] . '(' . $admission->contact_person['no_hp'] .')' }}
                      </a>
                    </div>
                  </li>
                </ul>
                <div class="mt-30px" data-aos="fade-up">
                    @if (auth()->user()?->student()->exists())
                        <a href="{{ url('/member/login') }}" class="inline-block border rounded text-size-15 text-whiteColor bg-primaryColor px-14 py-10px border-primaryColor hover:text-primaryColor hover:bg-whiteColor group dark:hover:text-whiteColor dark:hover:bg-whiteColor-dark">
                            Lanjutkan Pendaftaran
                        </a>
                    @else
                        <a href="{{ route('daftar', $admission->slug) }}" class="inline-block border rounded text-size-15 text-whiteColor bg-primaryColor px-14 py-10px border-primaryColor hover:text-primaryColor hover:bg-whiteColor group dark:hover:text-whiteColor dark:hover:bg-whiteColor-dark">
                            Daftar Sekarang
                        </a>
                    @endif
                </div>
              </div>
              <!-- sponsored section -->
              <div
                class="py-33px px-25px shadow-event mb-30px"
                data-aos="fade-up"
              >
                <div class="mb-2">
                  <img
                    src="{{ asset('images/logo_erudify.png') }}"
                    alt=""
                    class="h-20"
                  >
                </div>
                <p
                  class="font-base text-slate-600 dark:text-contentColor-dark leading-19px mb-15px"
                >
                    "Barangsiapa yang menelusuri jalan untuk mencari ilmu padanya, Allah akan memudahkan baginya jalan menuju surga." (HR.Muslim)
                </p>

              </div>
            </div>
          </div>
        </div>
    </section>
    <!-- event__section__end -->
</div>
