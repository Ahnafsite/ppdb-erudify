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
                class="pt-3 mb-10 text-3xl font-bold md:text-size-40 2xl:text-size-55 text-blackColor dark:text-blackColor-dark md:mb-6"
              >
                Formulir Pendaftaran
              </h1>
              <ul class="flex justify-center gap-1">
                <li>
                  <a
                    href="{{ url('/') }}"
                    class="text-lg text-blackColor2 dark:text-blackColor2-dark"
                    >Beranda<i class="icofont-simple-right"></i
                  ></a>
                </li>
                <li>
                  <a
                    href="{{ route('detail', $admission->slug) }}"
                    class="text-lg text-blackColor2 dark:text-blackColor2-dark"
                    >{{ ucwords(strtolower($admission->title)) }}<i class="icofont-simple-right"></i
                  ></a>
                </li>
                <li>
                  <span
                    class="text-lg text-blackColor2 dark:text-blackColor2-dark"
                    >Formulir</span
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
                <div class="lg:col-start-1 lg:col-span-12 space-y-[35px]">
                  <div>
                    <form wire:submit="create">
                        <div>
                            {{ $this->form }}
                        </div>
                        <button type="submit" class="inline-flex items-center px-4 py-3 mt-10 mb-10 text-sm font-bold text-center text-white rounded-lg bg-primaryColor hover:bg-teal-800 focus:ring-4 focus:outline-none focus:ring-teal-300 dark:bg-teal-600 dark:hover:bg-teal-600 dark:focus:ring-teal-600">
                            Simpan
                        </button>
                    </form>
                  </div>
                </div>
              </div>
            </div>
        </section>
        <!-- event__section__end -->
</div>
