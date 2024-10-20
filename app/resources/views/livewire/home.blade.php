<div>
    <!-- banner section -->
    <div class="relative z-0 overflow-hidden bg-lightGrey11 dark:bg-lightGrey11-dark py-50px md:py-100px lg:pt-100px lg:pb-150px 2xl:pt-155px 2xl:pb-250px">
      <!-- animated icons -->
      <div>
        <img
          class="absolute left-10 bottom-[233px] md:left-[248px] md:bottom-[143px] lg:left-10 lg:bottom-[112px] 3xl:bottom-[233px] animate-move-var opacity-35 z-10"
          src="{{ asset('frontend/assets/images/herobanner/herobanner__1.png') }}"
          alt=""
        ><img
          class="absolute left-0 top-0 md:left-[50px] md:top-[110px] lg:left-[30px] lg:top-[75px] 2xl:left-[50px] 2xl:top-16 3xl:top-[110px] animate-spin-slow"
          src="{{ asset('frontend/assets/images/herobanner/herobanner__2.png') }}"
          alt=""
        ><img
          class="absolute md:left-[210px] md:top-[50px] animate-move-var2 hidden md:block"
          src="{{ asset('frontend/assets/images/herobanner/herobanner__3.png') }}"
          alt=""
        >
        <img
          class="absolute top-20 left-[872px] md:left-[872px] lg:left-[595px] 2xl:left-[872px] hidden md:block animate-move-hor"
          src="{{ asset('frontend/assets/images/herobanner/herobanner__4.png') }}"
          alt=""
        >
        <img
          class="absolute top-0 right-0 md:right-[110px] md:top-[100px] lg:right-[13px] lg:top[90px] 2xl:right-[82px] 2xl:top-[100px] 3xl:right-[110px] animate-move-hor"
          src="{{ asset('frontend/assets/images/herobanner/herobanner__5.png') }}"
          alt=""
        >
      </div>
      <div class="container relative overflow-hidden 2xl:container-secondary-md">
        <div class="grid items-center grid-cols-1 lg:grid-cols-2">
          <!-- banner Left -->
          <div data-aos="fade-up">
            <h3
              class="uppercase text-secondaryColor text-size-15 mb-5px md:mb-15px font-inter tracking-[4px] font-semibold"
            >
              ERUDIFY
            </h3>
            <h1
              class="text-3xl font-bold leading-10 text-blackColor dark:text-blackColor-dark md:text-6xl lg:text-size-50 2xl:text-6xl md:leading-18 lg:leading-62px 2xl:leading-18 md:tracking-half lg:tracking-normal 2xl:tracking-half mb-15px"
            >
              Digitalisasi <br class="hidden md:block" >
              Sekolah Indonesia
            </h1>
            <p
              class="font-medium text-size-15md:text-lg text-blackColor dark:text-blackColor-dark mb-45px"
            >
              Mewujudkan sekolah cerdas digital melek teknologi <br >
              Menuju Indonesia Emas 2045
            </p>

            <div>
              <a href="#sekolah" class="inline-block px-5 py-3 text-sm font-semibold border rounded md:text-size-15 text-whiteColor bg-primaryColor border-primaryColor md:px-30px md:py-4 hover:text-primaryColor hover:bg-whiteColor mr-6px md:mr-30px dark:hover:bg-whiteColor-dark dark:hover:text-whiteColor">
                DAFTAR SEKOLAH
              </a>
              <a target="_blank" href="https://wa.me/6285877159577" class="inline-block px-5 py-3 text-sm font-semibold border rounded md:text-size-15 text-whiteColor bg-secondaryColor border-secondaryColor md:px-30px md:py-4 hover:text-secondaryColor hover:bg-whiteColor mr-6px md:mr-30px dark:hover:bg-whiteColor-dark dark:hover:text-secondaryColor">
                HUBUNGI KAMI
              </a>
            </div>
          </div>
          <!-- banner right -->
          <div data-aos="fade-up">
            <div class="tilt">
              <img
                class="w-full"
                src="{{ asset('frontend/assets/images/about/about_10.png') }}"
                alt=""
              >
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- courses section -->
    <section>
        <div class="pb-10 pt-50px md:pt-70px md:pb-50px lg:pt-20 2xl:pt-100px 2xl:pb-70px bg-whiteColor dark:bg-whiteColor-dark" id="sekolah">
            <div class="container filter-container">
                <div class="flex flex-wrap items-center gap-15px lg:gap-30px lg:flex-nowrap">
                    <!-- courses Left -->
                    <div class="basis-full lg:basis-[500px]" data-aos="fade-up">
                        <span class="inline-block px-6 mb-5 text-sm font-semibold rounded-full text-primaryColor bg-whitegrey3 py-5px">
                            Daftar Sekolah
                        </span>
                        <h3 class="text-3xl md:text-[35px] lg:text-size-42 leading-[45px] 2xl:leading-[45px] md:leading-[50px] font-bold text-blackColor dark:text-blackColor-dark" data-aos="fade-up">
                            Temukan Sekolah Impianmu
                        </h3>
                    </div>
                </div>

            <!-- course cards -->

            <div class="container flex flex-wrap p-0 filter-contents sm:-mx-15px mt-7 lg:mt-10" data-aos="fade-up">
            <!-- card 1 -->
            @foreach ($admissions as $admission)
                <div class="w-full sm:w-1/2 lg:w-1/3 group grid-item filter1 filter3">
                    <div class="tab-content-wrapper sm:px-15px mb-30px">
                    <div class="p-15px bg-whiteColor shadow-brand dark:bg-darkdeep3-dark dark:shadow-brand-dark">
                        <!-- card image -->
                        <div class="relative mb-4">
                        <a href="{{ route('detail', $admission->slug) }}" class="w-full overflow-hidden rounded">
                            <img src="{{ Storage::url($admission->image) }}" alt="" class="w-full transition-all duration-300 group-hover:scale-110">
                        </a>
                        <div class="absolute left-0 flex items-center justify-between w-full px-2 top-1">
                            <div>
                            <p class="text-xs text-whiteColor px-4 py-[3px] bg-secondaryColor rounded font-semibold">
                                Sekolah
                            </p>
                            </div>
                            <a class="text-white bg-black rounded bg-opacity-15 hover:bg-primaryColor" href="#">
                                <i class="px-2 py-1 text-base icofont-heart-alt"></i>
                            </a>
                        </div>
                        </div>
                        <!-- card content -->
                        <div>
                        <div class="grid grid-cols-1 mb-15px">
                            <div class="flex items-center">
                            <div>
                                <i class="text-lg icofont-calendar pr-5px text-primaryColor"></i>
                            </div>
                            <div>
                                <span class="text-sm text-black dark:text-blackColor-dark">{{ $admission->admission_period_start->isoFormat('D MMM YYYY') . '-' . $admission->admission_period_end->isoFormat('D MMM YYYY') }}</span>
                            </div>
                            </div>
                        </div>
                        <a href="{{ route('detail', $admission->slug) }}" class="text-xl font-semibold text-blackColor mb-10px font-hind dark:text-blackColor-dark hover:text-primary-color dark:hover:text-primaryColor">{{ $admission->title }}</a>
                        <p class="text-base font-normal text-grey mb-5px">{!! \Illuminate\Support\Str::words(strip_tags($admission->desc), 10, '...') !!}</p>
                        <!-- author and rating-->
                        <div class="grid grid-cols-1 border-t md:grid-cols-2 pt-15px border-borderColor">
                            <div>
                                <a href="{{ route('detail', $admission->slug) }}" class="inline-block px-5 py-3 text-sm font-semibold border rounded md:text-size-15 text-whiteColor bg-primaryColor border-primaryColor md:px-30px md:py-4 hover:text-primaryColor hover:bg-whiteColor mr-6px md:mr-30px dark:hover:bg-whiteColor-dark dark:hover:text-whiteColor">
                                    DAFTAR
                                </a>
                            </div>
                        </div>
                        </div>
                    </div>
                    </div>
                </div>
            @endforeach
            </div>
          </div>
        </div>
    </section>
</div>
