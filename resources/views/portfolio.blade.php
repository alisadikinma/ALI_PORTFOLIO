@extends('layouts.web')

@section('isi')
    <!-- Portfolio Section -->
<section id="portfolio"
    class="w-full max-w-screen-2xl mx-auto px-4 sm:px-6 py-8 flex flex-col items-center gap-8 sm:gap-10">

    <!-- Heading -->
    <div class="flex flex-col gap-3 text-center">
        <h2 class="text-yellow-400 text-4xl sm:text-6xl font-bold leading-tight sm:leading-[67.2px] tracking-tight">
            Portfolio
        </h2>
        <p class="text-neutral-400 text-lg sm:text-2xl font-normal leading-6 sm:leading-7 tracking-tight">
            AI Technopreneur with 16+ years of expertise in driving innovation and digital transformation across
        </p>
    </div>

    <!-- Filter Buttons -->
    <div class="w-full max-w-full sm:max-w-5xl flex flex-wrap justify-center gap-4 sm:gap-6" id="portfolio-filters">
        <button
            class="filter-btn px-6 sm:px-8 py-3 bg-yellow-400 rounded-lg outline outline-[1.5px] outline-yellow-400 flex items-center gap-3 transition-all duration-300 ease-in-out"
            data-filter="all">
            <span class="text-neutral-900 text-base sm:text-lg font-semibold leading-[64px]">All</span>
        </button>
        @foreach ($jenis_projects as $jenis)
        <button
            class="filter-btn px-6 sm:px-8 py-3 bg-slate-800/60 rounded-lg outline outline-[0.5px] outline-slate-500 flex items-center gap-3 transition-all duration-300 ease-in-out"
            data-filter="{{ $jenis }}">
            <span
                class="text-white text-base sm:text-lg font-semibold capitalize leading-[64px]">{{ $jenis }}</span>
        </button>
        @endforeach
    </div>

    <!-- Grid -->
    <div class="w-full max-w-full sm:max-w-5xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8"
        id="portfolio-grid">
        @foreach ($projects as $project)
        <div class="portfolio-item p-6 sm:p-8 bg-slate-800/60 rounded-2xl outline outline-1 {{ $loop->iteration == 3 ? 'outline-yellow-400' : 'outline-neutral-900/40' }} backdrop-blur-xl flex flex-col gap-6 transition-opacity duration-300 ease-in-out"
            data-jenis="{{ $project->jenis_project }}">
            <a href="{{ route('portfolio.detail', $project->slug_project) }}">
                <img class="w-full max-w-[300px] sm:max-w-[400px] h-auto rounded-2xl aspect-[5/4] object-cover"
                    src="{{ asset('file/project/' . $project->gambar_project) }}"
                    alt="{{ $project->nama_project }}" />
            </a>
            <div class="flex flex-col gap-4">
                <div class="flex flex-col gap-3">
                    <div class="flex gap-2">
                        <span
                            class="text-gray-500 text-xs font-normal leading-none">{{ $project->jenis_project }}</span>
                    </div>
                    <h3
                        class="{{ $loop->iteration == 3 ? 'text-yellow-400' : 'text-white' }} text-xl sm:text-3xl font-bold leading-loose tracking-tight">
                        {{ $project->nama_project }}
                    </h3>
                    <p class="text-neutral-400 text-base font-normal leading-normal max-w-full sm:max-w-[400px]">
                        {!! Str::limit(strip_tags($project->keterangan_project), 120) !!}
                    </p>

                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('portfolio.detail', $project->slug_project) }}"
                        class="px-4 py-2 bg-yellow-400 rounded-lg text-neutral-900 text-base font-semibold leading-normal tracking-tight transition-all duration-300 ease-in-out hover:bg-yellow-500">Read
                        More</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>

<!-- Script Filter -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const filterBtns = document.querySelectorAll(".filter-btn");
        const items = document.querySelectorAll(".portfolio-item");

        filterBtns.forEach(btn => {
            btn.addEventListener("click", () => {
                // Reset active button
                filterBtns.forEach(b => b.classList.remove("bg-yellow-400",
                    "outline-yellow-400"));
                filterBtns.forEach(b => b.querySelector("span").classList.remove(
                    "text-neutral-900"));
                filterBtns.forEach(b => b.classList.add("bg-slate-800/60",
                    "outline-slate-500"));
                filterBtns.forEach(b => b.querySelector("span").classList.add("text-white"));

                // Set active button
                btn.classList.remove("bg-slate-800/60", "outline-slate-500");
                btn.classList.add("bg-yellow-400", "outline-yellow-400");
                btn.querySelector("span").classList.remove("text-white");
                btn.querySelector("span").classList.add("text-neutral-900");

                const filter = btn.getAttribute("data-filter");

                items.forEach(item => {
                    if (filter === "all" || item.getAttribute("data-jenis") ===
                        filter) {
                        item.classList.remove("hidden");
                        item.classList.add("flex");
                    } else {
                        item.classList.add("hidden");
                        item.classList.remove("flex");
                    }
                });
            });
        });
    });
</script>
@endsection
