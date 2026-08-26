@extends('layouts.app')
@section('content')
    <div
        class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu group-data-[sidebar-size=md]:ltr:ml-vertical-menu-md group-data-[sidebar-size=md]:rtl:mr-vertical-menu-md group-data-[sidebar-size=sm]:ltr:ml-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:mr-vertical-menu-sm px-4 pb-[calc(theme('spacing.header')_*_0.8)] pt-[calc(theme('spacing.header')_*_1)] group-data-[layout=horizontal]:mx-auto group-data-[layout=horizontal]:max-w-screen-2xl group-data-[layout=horizontal]:px-0 group-data-[layout=horizontal]:px-3 group-data-[layout=horizontal]:group-data-[navbar=hidden]:pt-[calc(theme('spacing.header')_*_0.9)] group-data-[navbar=bordered]:pt-[calc(theme('spacing.header')_*_1.3)] group-data-[navbar=hidden]:pt-0 group-data-[layout=horizontal]:md:pt-[calc(theme('spacing.header')_*_1.6)] group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:ltr:md:ml-auto group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:rtl:md:mr-auto">
        <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">

            <!-- Breadcrumb -->
            <div class="flex flex-col gap-2 py-4 md:flex-row md:items-center print:hidden">
                <div class="grow">
                    <h5 class="text-16">Daftar Tindakan</h5>
                </div>
                <ul class="flex shrink-0 items-center gap-2 text-sm font-normal">
                    <li
                        class="before:font-remix dark:text-zink-200 relative before:absolute before:-top-[3px] before:text-[18px] before:text-slate-400 before:content-['\ea54'] ltr:pr-4 ltr:before:-right-1 rtl:pl-4 rtl:before:-left-1">
                        <a href="#!" class="dark:text-zink-200 text-slate-400">Dashboards</a>
                    </li>
                    <li
                        class="before:font-remix dark:text-zink-200 relative before:absolute before:-top-[3px] before:text-[18px] before:text-slate-400 before:content-['\ea54'] ltr:pr-4 ltr:before:-right-1 rtl:pl-4 rtl:before:-left-1">
                        <a href="#!" class="dark:text-zink-200 text-slate-400">Super Admin</a>
                    </li>
                    <li class="dark:text-zink-100 text-slate-700">
                        Daftar Tindakan
                    </li>
                </ul>
            </div>

            <!-- Data Table -->
            <div class="card">
                <div class="card-body">
                    <div class="mb-4 flex justify-between gap-2">
                        <h6 class="text-15 mb-4">Daftar Tindakan</h6>
                    </div>

                    <!-- Filters -->
                    <div class="mb-5 grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-4">
                        <div class="flex-1">
                            <label for="levelFilter" class="block text-sm font-medium text-slate-700 dark:text-zink-300 mb-2">Filter Tingkat</label>
                            <select id="levelFilter" onchange="filterClasses()" class="dark:bg-zink-600 dark:border-zink-500 dark:text-zink-100 w-full rounded-md border border-slate-200 px-3 py-2 text-sm">
                                <option value="">Semua Tingkat</option>
                                @foreach ($levels as $l)
                                    <option value="{{ $l }}">{{ $l }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex-1">
                            <label for="majorFilter" class="block text-sm font-medium text-slate-700 dark:text-zink-300 mb-2">Filter Jurusan</label>
                            <select id="majorFilter" onchange="filterClasses()" class="dark:bg-zink-600 dark:border-zink-500 dark:text-zink-100 w-full rounded-md border border-slate-200 px-3 py-2 text-sm">
                                <option value="">Semua Jurusan</option>
                                @foreach ($majors as $m)
                                    <option value="{{ $m->id }}">{{ $m->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    @if (count($classes) > 0)
                        <div id="classesGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach ($classes as $classData)
                                <a href="{{ route('superadmin.actions.class', $classData->id) }}" 
                                   data-level="{{ $classData->level }}" 
                                   data-major="{{ $classData->major_id }}" 
                                   class="class-card card border border-slate-200 hover:border-custom-500 dark:border-zink-600 dark:bg-zink-700 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-all">
                                    <div class="card-body p-5 flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-custom-50 text-custom-500 dark:bg-custom-500/20 dark:text-custom-400">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c0 2 2 3 6 3s6-1 6-3v-5"/></svg>
                                            </div>
                                            <div>
                                                <h6 class="mb-1 text-16 font-bold text-slate-800 dark:text-slate-100">{{ $classData->name }}</h6>
                                                <span class="text-xs text-slate-500 dark:text-zink-300 font-medium">{{ $classData->actions->count() }} Tindakan Pendisiplinan</span>
                                            </div>
                                        </div>
                                        <div class="text-slate-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>

                        <!-- No Filter Match Message -->
                        <div id="noFilteredData" class="py-8 text-center hidden">
                            <div class="dark:text-zink-400 flex flex-col items-center text-slate-500">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" class="mb-2">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                    <polyline points="14 2 14 8 20 8"></polyline>
                                </svg>
                                <p class="text-sm">Tidak ada kelas yang cocok dengan filter tingkat dan jurusan</p>
                            </div>
                        </div>

                        <script>
                            function filterClasses() {
                                const selectedLevel = document.getElementById('levelFilter').value;
                                const selectedMajor = document.getElementById('majorFilter').value;
                                const cards = document.querySelectorAll('.class-card');
                                let visibleCount = 0;

                                cards.forEach(card => {
                                    const levelMatch = !selectedLevel || card.getAttribute('data-level') === selectedLevel;
                                    const majorMatch = !selectedMajor || card.getAttribute('data-major') === selectedMajor;

                                    if (levelMatch && majorMatch) {
                                        card.style.display = 'block';
                                        visibleCount++;
                                    } else {
                                        card.style.display = 'none';
                                    }
                                });

                                const noDataEl = document.getElementById('noFilteredData');
                                const gridEl = document.getElementById('classesGrid');
                                
                                if (visibleCount === 0) {
                                    gridEl.classList.add('hidden');
                                    noDataEl.classList.remove('hidden');
                                } else {
                                    gridEl.classList.remove('hidden');
                                    noDataEl.classList.add('hidden');
                                }
                            }
                        </script>
                    @else
                        <div class="py-8 text-center">
                            <div class="dark:text-zink-400 flex flex-col items-center text-slate-500">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" class="mb-2">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                    <polyline points="14 2 14 8 20 8"></polyline>
                                </svg>
                                <p class="text-sm">Tidak ada data tindakan ditemukan</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
