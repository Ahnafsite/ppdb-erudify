<div>
    <div class="mt-10 mb-5 text-center">
        <h1 class="text-3xl font-semibold tracking-tight text-slate-900 text-balance sm:text-3xl">Formulir Pendaftaran {{ $admission->title }}</h1>
    </div>
    <form wire:submit="create">
        {{ $this->form }}
        <button type="submit" class="inline-flex items-center px-4 py-3 mt-10 mb-10 text-sm font-bold text-center text-white bg-blue-600 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            Simpan
        </button>
    </form>
</div>
