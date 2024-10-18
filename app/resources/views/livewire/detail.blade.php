<div>
    <div class="max-w-2xl py-10 mx-auto sm:py-10 lg:py-20">
        <div class="text-center">
            <h1 class="text-4xl font-bold tracking-tight text-slate-900 text-balance sm:text-6xl">{{ $admission->title }}</h1>
            <h1 class="mt-2 text-xl tracking-tight font-base text-slate-900 text-balance sm:text-xl">{{ 'Tanggal Pendaftaran :' . $admission->admission_period_start->format('d F Y') .' - ' . $admission->admission_period_end->format('d F Y') }}</h1>
            <p class="mt-6 text-lg leading-8 text-slate-600">{!! $admission->desc !!}</p>
            @if (auth()->user()?->student()->exists())
                <a href="{{ url('/app/login') }}" class="inline-flex items-center px-4 py-3 mt-10 text-sm font-bold text-center text-white bg-blue-600 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Lanjutkan Pendaftaran
                </a>
            @else
                <a href="{{ route('daftar', $admission->slug) }}" class="inline-flex items-center px-4 py-3 mt-10 text-sm font-bold text-center text-white bg-blue-600 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                Daftar Sekarang
                </a>
            @endif
        </div>
    </div>
</div>
