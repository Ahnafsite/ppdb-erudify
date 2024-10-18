<div>
  <div class="absolute inset-x-0 overflow-hidden -top-40 -z-10 transform-gpu blur-3xl sm:-top-80" aria-hidden="true">
    <div class="relative left-[calc(50%-11rem)] aspect-[1155/678] w-[36.125rem] -translate-x-1/2 rotate-[30deg] bg-gradient-to-tr from-[#ff80b5] to-[#9089fc] opacity-30 sm:left-[calc(50%-30rem)] sm:w-[72.1875rem]" style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)"></div>
  </div>
  <div class="max-w-2xl py-24 mx-auto sm:py-34 lg:py-44">
    <div class="hidden sm:mb-8 sm:flex sm:justify-center">
      <div class="relative px-3 py-1 text-sm leading-6 text-gray-600 rounded-full ring-1 ring-gray-900/10 hover:ring-gray-900/20">
        Selamat Datang di <a href="#" class="font-semibold text-indigo-600"><span class="absolute inset-0" aria-hidden="true"></span>SMP Muhammadiyah 2 Sragen</a>
      </div>
    </div>
    <div class="text-center">
      <h1 class="text-4xl font-bold tracking-tight text-slate-900 text-balance sm:text-6xl">Penerimaan Peserta Didik Baru</h1>
      <p class="mt-6 text-lg leading-8 text-slate-600">Bersama Erudify menjadi sekolah yang Unggul dalam prestasi, Mandiri, dan Mendunia</p>
    </div>
    <div class="grid grid-cols-1 gap-6 mt-10 sm:grid-cols-2 md:grid-cols-2">
      @foreach ($admissions as $admission)
      <div class="max-w-full bg-white border rounded-lg shadow border-slate-100 dark:bg-slate-800 dark:border-slate-700">
          <a href="#">
              <img class="rounded-t-lg w-full h-auto object-cover aspect-[4/3]" src="{{ Storage::url($admission->image) }}" alt="" />
          </a>
          <div class="p-5">
              <a href="#">
                  <h5 class="h-16 mb-2 overflow-hidden text-2xl font-bold tracking-tight text-slate-900 dark:text-white">
                      {{ $admission->title }}
                  </h5>
              </a>
              <p class="h-16 mb-4 overflow-hidden text-base leading-8 text-slate-600">
                  {!! \Illuminate\Support\Str::words(strip_tags($admission->desc), 10, '...') !!}
              </p>
              <a href="{{ route('detail', $admission->slug) }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-600 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                  Selengkapnya
              </a>
          </div>
      </div>
      @endforeach
  </div>

  </div>
</div>
