@extends('layouts.app')
@section('content')
    <div
        class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu group-data-[sidebar-size=md]:ltr:ml-vertical-menu-md group-data-[sidebar-size=md]:rtl:mr-vertical-menu-md group-data-[sidebar-size=sm]:ltr:ml-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:mr-vertical-menu-sm pt-[calc(theme('spacing.header')_*_1)] pb-[calc(theme('spacing.header')_*_0.8)] px-4 group-data-[navbar=bordered]:pt-[calc(theme('spacing.header')_*_1.3)] group-data-[navbar=hidden]:pt-0 group-data-[layout=horizontal]:mx-auto group-data-[layout=horizontal]:max-w-screen-2xl group-data-[layout=horizontal]:px-0 group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:ltr:md:ml-auto group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:rtl:md:mr-auto group-data-[layout=horizontal]:md:pt-[calc(theme('spacing.header')_*_1.6)] group-data-[layout=horizontal]:px-3 group-data-[layout=horizontal]:group-data-[navbar=hidden]:pt-[calc(theme('spacing.header')_*_0.9)]">
        <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">

            <div class="flex flex-col gap-2 py-4 md:flex-row md:items-center print:hidden">
                <div class="grow">
                    <h5 class="text-16">Dashboard Guru</h5>
                </div>
                <ul class="flex items-center gap-2 text-sm font-normal shrink-0">
                    <li
                        class="relative before:content-['\ea54'] before:font-remix ltr:before:-right-1 rtl:before:-left-1  before:absolute before:text-[18px] before:-top-[3px] ltr:pr-4 rtl:pl-4 before:text-slate-400 dark:text-zink-200">
                        <a href="#!" class="text-slate-400 dark:text-zink-200">Dashboards</a>
                    </li>
                    <li class="text-slate-700 dark:text-zink-100">
                        Guru
                    </li>
                </ul>
            </div>

            {{-- Alert untuk error --}}
            @if ($errors->has('error'))
                <div class="relative mb-4 flex gap-3 rounded-md border border-red-200 bg-red-50 p-4 pr-12 text-sm text-red-700 dark:border-red-900/30 dark:bg-red-500/10 dark:text-red-400 shadow-sm transition-all duration-300">
                    <div class="shrink-0 flex items-center justify-center size-8 rounded-full bg-red-100 dark:bg-red-900/50 text-red-600 dark:text-red-400">
                        <i data-lucide="alert-triangle" class="size-5"></i>
                    </div>
                    <div class="grow">
                        <h6 class="font-semibold text-15 mb-0.5">Peringatan!</h6>
                        <p class="text-red-600 dark:text-red-400/90 leading-relaxed">{{ $errors->first('error') }}</p>

                        {{-- Tampilkan detail poin jika ada --}}
                        @if (session('current_total_points') !== null)
                            <div class="mt-3 border-t border-red-200/50 dark:border-red-800/30 pt-3">
                                <h6 class="font-semibold text-xs text-red-800 dark:text-red-400 mb-2 flex items-center gap-1.5">
                                    <i data-lucide="bar-chart-2" class="size-3.5"></i> Detail Poin
                                </h6>
                                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-2">
                                    <div class="p-2 rounded bg-white/50 dark:bg-zink-700/50 border border-red-100 dark:border-red-900/20">
                                        <span class="block text-xs text-slate-500 dark:text-zink-200">Total Poin Saat Ini</span>
                                        <span class="text-sm font-bold text-red-600 dark:text-red-400">
                                            {{ session('current_total_points') }} poin
                                            <span class="block text-[10px] font-normal text-slate-400">
                                                (Verif: {{ session('current_verified_points') ?? 0 }} | Pend: {{ session('current_pending_points') ?? 0 }})
                                            </span>
                                        </span>
                                    </div>
                                    @if (session('new_points') > 0)
                                        <div class="p-2 rounded bg-white/50 dark:bg-zink-700/50 border border-red-100 dark:border-red-900/20">
                                            <span class="block text-xs text-slate-500 dark:text-zink-200">Poin Akan Ditambah</span>
                                            <span class="text-sm font-bold text-red-600 dark:text-red-400">{{ session('new_points') }} poin</span>
                                        </div>
                                    @endif
                                    @if (session('total_points_after'))
                                        <div class="p-2 rounded bg-white/50 dark:bg-zink-700/50 border border-red-100 dark:border-red-900/20">
                                            <span class="block text-xs text-slate-500 dark:text-zink-200">Total Setelah Ditambah</span>
                                            <span class="text-sm font-bold text-red-600 dark:text-red-400">{{ session('total_points_after') }} poin</span>
                                        </div>
                                    @endif
                                    @if (session('excess_points'))
                                        <div class="p-2 rounded bg-white/50 dark:bg-zink-700/50 border border-red-100 dark:border-red-900/20">
                                            <span class="block text-xs text-slate-500 dark:text-zink-200">Kelebihan Poin</span>
                                            <span class="text-sm font-bold text-red-600 dark:text-red-400">{{ session('excess_points') }} poin</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="mt-2 text-[11px] text-red-500/80 flex items-center gap-1">
                                    <i data-lucide="info" class="size-3.5"></i>
                                    <span>Batas maksimal: 100 poin</span>
                                </div>
                            </div>
                        @endif
                    </div>
                    <button class="absolute top-4 right-4 text-red-400 hover:text-red-600 dark:hover:text-red-300 transition-colors duration-150"
                        onclick="this.parentElement.style.display='none'">
                        <i data-lucide="x" class="size-4"></i>
                    </button>
                </div>
            @endif

            {{-- Alert untuk success --}}
            @if (session('success') && !$errors->has('error'))
                <div class="relative mb-4 flex gap-3 rounded-md border border-green-200 bg-green-50 p-4 pr-12 text-sm text-green-700 dark:border-green-900/30 dark:bg-green-500/10 dark:text-green-400 shadow-sm transition-all duration-300">
                    <div class="shrink-0 flex items-center justify-center size-8 rounded-full bg-green-100 dark:bg-green-900/50 text-green-600 dark:text-green-400">
                        <i data-lucide="check-circle" class="size-5"></i>
                    </div>
                    <div class="grow">
                        <h6 class="font-semibold text-15 mb-0.5">Berhasil!</h6>
                        <p class="text-green-600 dark:text-green-400/90 leading-relaxed">{{ session('success') }}</p>
                        @if (session('verified_points') !== null && session('pending_points') !== null)
                            <div class="mt-3 grid grid-cols-1 sm:grid-cols-3 gap-2 border-t border-green-200/50 dark:border-green-800/30 pt-3">
                                <div class="p-2 rounded bg-white/50 dark:bg-zink-700/50 border border-green-100 dark:border-green-900/20">
                                    <span class="block text-xs text-slate-500 dark:text-zink-200">Poin Terverifikasi</span>
                                    <span class="text-base font-bold text-green-600 dark:text-green-400">{{ session('verified_points') }}</span>
                                </div>
                                <div class="p-2 rounded bg-white/50 dark:bg-zink-700/50 border border-green-100 dark:border-green-900/20">
                                    <span class="block text-xs text-slate-500 dark:text-zink-200">Poin Pending</span>
                                    <span class="text-base font-bold text-amber-500 dark:text-amber-400">{{ session('pending_points') }}</span>
                                </div>
                                <div class="p-2 rounded bg-white/50 dark:bg-zink-700/50 border border-green-100 dark:border-green-900/20">
                                    <span class="block text-xs text-slate-500 dark:text-zink-200">Total Semua Poin</span>
                                    <span class="text-base font-bold text-slate-700 dark:text-zink-50">{{ session('total_all_points') }}</span>
                                </div>
                            </div>
                        @endif
                    </div>
                    <button class="absolute top-4 right-4 text-green-400 hover:text-green-600 dark:hover:text-green-300 transition-colors duration-150"
                        onclick="this.parentElement.style.display='none'">
                        <i data-lucide="x" class="size-4"></i>
                    </button>
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    {{-- Filter Kelas --}}
                    <form method="GET" action="{{ route('guru.student-data') }}" id="filterForm" class="mb-4">
                        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2 xl:grid-cols-12">
                            <div class="xl:col-span-3">
                                <label class="inline-block mb-2 text-base font-medium">Filter Kelas</label>
                                <select name="class_id" id="classFilter"
                                    class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200">
                                    <option value="">-- Pilih Kelas --</option>
                                    @foreach ($classes as $class)
                                        <option value="{{ $class->id }}"
                                            {{ $selectedClassId == $class->id ? 'selected' : '' }}>
                                            {{ $class->academic_level }} {{ $class->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </form>

                    <h6 class="mb-4 text-15">Datatable Siswa</h6>

                    @if ($selectedClassId && $studentAcademicYears->count() > 0)
                        <table id="hoverableTable" style="width: 100%" class="hover group">
                            <thead>
                                <tr>
                                    <th>Aksi</th>
                                    <th>No</th>
                                    <th>Nama Lengkap</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Kelas</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($studentAcademicYears as $murid)
                                    <tr>
                                        <td>
                                            <!-- Tombol buka modal -->
                                            <button data-modal-target="modal-{{ $murid->id }}" type="button"
                                                class="flex rounded-full items-center justify-center size-[37.5px] p-0 text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20"><svg
                                                    xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                                                    class="lucide lucide-chevron-right-icon lucide-chevron-right">
                                                    <path d="m9 18 6-6-6-6" />
                                                </svg>
                                            </button>
                                        </td>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $murid->student->full_name }}</td>
                                        <td>{{ $murid->student->gender }}</td>
                                        <td>{{ $murid->class->name ?? '-' }}</td>
                                    </tr>

                                    <!-- Modal untuk siswa ini -->
                                    <div id="modal-{{ $murid->id }}" modal-center=""
                                        class="fixed flex flex-col hidden transition-all duration-300 ease-in-out left-2/4 z-drawer -translate-x-2/4 -translate-y-2/4 show">
                                        <div
                                            class="w-11/12 md:w-full md:max-w-lg lg:max-w-xl bg-white shadow rounded-md dark:bg-zink-600 flex flex-col max-h-[90vh]">
                                            <div
                                                class="flex items-center justify-between p-4 border-b border-slate-200 dark:border-zink-500 bg-slate-50 dark:bg-zink-700 rounded-t-md">
                                                <h5 class="text-16 font-bold text-slate-700 dark:text-zink-100">
                                                    Tambah Pelanggaran
                                                    <span class="block text-sm font-normal text-slate-500 mt-1">{{ $murid->student->full_name }}</span>
                                                </h5>
                                                <button data-modal-close="modal-{{ $murid->id }}"
                                                    class="transition-all duration-200 ease-linear text-slate-400 hover:text-red-500 dark:text-zink-200 dark:hover:text-red-500">
                                                    <i data-lucide="x" class="size-5"></i>
                                                </button>
                                            </div>

                                            <form method="POST"
                                                action="{{ route('guru.violations.store.student', $murid->id) }}" class="flex flex-col overflow-hidden h-full">
                                                @csrf

                                                <div class="p-4 border-b border-slate-100 dark:border-zink-500 shadow-sm z-10">
                                                    <!-- Search Input & Filter -->
                                                    <div class="flex flex-col gap-2">
                                                        <div class="relative w-full">
                                                            <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 size-4 text-slate-400"></i>
                                                            <input type="text" id="searchViolation-{{ $murid->id }}"
                                                                placeholder="Cari jenis pelanggaran..." style="padding-left: 2.25rem;"
                                                                class="w-full pr-3 py-2 text-sm border rounded-lg border-slate-200 dark:border-zink-500 dark:bg-zink-700 dark:text-zink-100 focus:outline-none focus:border-custom-500 focus:ring-1 focus:ring-custom-500 transition-all">
                                                        </div>
                                                        <select id="categoryFilter-{{ $murid->id }}"
                                                            class="w-full py-2 px-3 text-sm border rounded-lg border-slate-200 dark:border-zink-500 dark:bg-zink-700 dark:text-zink-100 focus:outline-none focus:border-custom-500 focus:ring-1 focus:ring-custom-500 transition-all">
                                                            <option value="">Semua Kategori</option>
                                                            <option value="ringan">Ringan</option>
                                                            <option value="sedang">Sedang</option>
                                                            <option value="berat">Berat</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="p-4 overflow-y-auto flex-1 bg-slate-50/50 dark:bg-zink-600/50" style="min-height: 300px; max-height: 50vh;">
                                                    <div class="space-y-2" id="violationList-{{ $murid->id }}">
                                                        @foreach ($vals as $violation)
                                                            <label class="violation-item flex items-start gap-3 p-3 rounded-lg border border-slate-200 bg-white dark:bg-zink-700 dark:border-zink-500 cursor-pointer hover:border-custom-500 hover:shadow-sm transition-all has-[:checked]:border-custom-500 has-[:checked]:bg-custom-50/50 dark:has-[:checked]:bg-custom-900/20"
                                                                data-violation-name="{{ strtolower($violation->name) }}"
                                                                data-violation-category="{{ strtolower($violation->category->name ?? '') }}">
                                                                
                                                                <div class="mt-0.5">
                                                                    <input type="checkbox" name="violations[]"
                                                                        value="{{ $violation->id }}"
                                                                        id="violation_{{ $violation->id }}_{{ $murid->id }}"
                                                                        class="border rounded-sm appearance-none cursor-pointer size-4 bg-slate-100 border-slate-300 dark:bg-zink-600 dark:border-zink-500 checked:bg-custom-500 checked:border-custom-500 dark:checked:bg-custom-500 dark:checked:border-custom-500 transition-all">
                                                                </div>
                                                                <div class="flex-1">
                                                                    <span class="block text-sm font-medium text-slate-700 dark:text-zink-100 leading-tight">
                                                                        {{ $violation->name }}
                                                                    </span>
                                                                </div>
                                                                <div class="flex-shrink-0">
                                                                    <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-semibold text-red-600 bg-red-100 rounded-md dark:bg-red-500/20 dark:text-red-400">
                                                                        {{ $violation->point }} Poin
                                                                    </span>
                                                                </div>
                                                            </label>
                                                        @endforeach
                                                    </div>

                                                    <!-- No Results Message -->
                                                    <div id="noResults-{{ $murid->id }}"
                                                        class="hidden py-8 text-center text-slate-500 dark:text-zink-300 flex-col items-center justify-center">
                                                        <i data-lucide="search-x" class="size-8 mb-2 text-slate-300 mx-auto"></i>
                                                        <p>Tidak ada pelanggaran yang ditemukan</p>
                                                    </div>
                                                </div>

                                                <div class="p-4 border-t border-slate-200 dark:border-zink-500 bg-white dark:bg-zink-600 rounded-b-md flex justify-end gap-2">
                                                    <button type="button" data-modal-close="modal-{{ $murid->id }}"
                                                        class="px-4 py-2 text-sm font-medium transition-all duration-200 ease-linear text-slate-500 bg-slate-100 border border-slate-100 rounded-md hover:text-slate-700 hover:bg-slate-200 dark:text-zink-200 dark:bg-zink-500 dark:border-zink-500 dark:hover:bg-zink-400">
                                                        Batal
                                                    </button>
                                                    <button type="submit"
                                                        class="px-4 py-2 text-sm font-medium text-white transition-all duration-200 ease-linear bg-custom-500 border border-custom-500 rounded-md hover:bg-custom-600 focus:ring focus:ring-custom-100 flex items-center gap-2">
                                                        <i data-lucide="save" class="size-4"></i>
                                                        Simpan Pelanggaran
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                    <!-- Script untuk fitur search & filter -->
                                    <script>
                                        document.addEventListener('DOMContentLoaded', function() {
                                            const searchInput = document.getElementById('searchViolation-{{ $murid->id }}');
                                            const categoryFilter = document.getElementById('categoryFilter-{{ $murid->id }}');
                                            const violationList = document.getElementById('violationList-{{ $murid->id }}');
                                            const noResults = document.getElementById('noResults-{{ $murid->id }}');

                                            if (searchInput && categoryFilter && violationList && noResults) {
                                                const violationItems = violationList.querySelectorAll('.violation-item');

                                                function filterViolations() {
                                                    const searchTerm = searchInput.value.toLowerCase().trim();
                                                    const filterCategory = categoryFilter.value.toLowerCase();
                                                    let visibleCount = 0;

                                                    violationItems.forEach(function(item) {
                                                        const violationName = item.getAttribute('data-violation-name') || '';
                                                        const violationCategory = item.getAttribute('data-violation-category') || '';

                                                        const matchesSearch = violationName.includes(searchTerm);
                                                        const matchesCategory = filterCategory === '' || violationCategory === filterCategory;

                                                        if (matchesSearch && matchesCategory) {
                                                            item.style.display = '';
                                                            visibleCount++;
                                                        } else {
                                                            item.style.display = 'none';
                                                        }
                                                    });

                                                    if (visibleCount === 0) {
                                                        noResults.classList.remove('hidden');
                                                    } else {
                                                        noResults.classList.add('hidden');
                                                    }
                                                }

                                                searchInput.addEventListener('input', filterViolations);
                                                categoryFilter.addEventListener('change', filterViolations);
                                            }
                                        });
                                    </script>
                                @endforeach
                            </tbody>
                        </table>
                    @elseif ($selectedClassId && $studentAcademicYears->count() == 0)
                        <div class="text-center py-12 text-slate-500 dark:text-zink-300">
                            <div class="flex justify-center mb-4">
                                <div
                                    class="flex items-center justify-center w-20 h-20 rounded-full bg-slate-100 dark:bg-zink-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="text-slate-400 dark:text-zink-500">
                                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                        <circle cx="9" cy="7" r="4" />
                                        <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                                        <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                    </svg>
                                </div>
                            </div>
                            <p class="text-base font-medium">Tidak ada siswa di kelas ini</p>
                            <p class="mt-2 text-sm text-slate-400 dark:text-zink-400">Kelas yang dipilih belum memiliki
                                data siswa</p>
                        </div>
                    @else
                        <div class="text-center py-12 text-slate-500 dark:text-zink-300">
                            <div class="flex justify-center mb-4">
                                <div
                                    class="flex items-center justify-center w-20 h-20 rounded-full bg-custom-100 dark:bg-custom-500/10">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" class="text-custom-500">
                                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                                        <polyline points="9 22 9 12 15 12 15 22" />
                                    </svg>
                                </div>
                            </div>
                            <p class="text-base font-medium">Pilih Kelas Terlebih Dahulu</p>
                            <p class="mt-2 text-sm text-slate-400 dark:text-zink-400">Silakan pilih kelas dari dropdown di
                                atas untuk menampilkan data siswa</p>
                        </div>
                    @endif

                    <style>
                        .modal-custom {
                            width: 700px;
                            height: 500px;
                            max-width: 100%;
                        }

                        @media (max-width: 768px) {
                            .modal-custom {
                                width: 95%;
                                height: auto;
                                max-height: 90vh;
                            }
                        }
                    </style>

                </div>
            </div><!--end card-->
        </div>
        <!-- container-fluid -->
    </div>

    {{-- Script untuk auto-submit filter --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const classFilter = document.getElementById('classFilter');
            const filterForm = document.getElementById('filterForm');

            if (classFilter && filterForm) {
                classFilter.addEventListener('change', function() {
                    filterForm.submit();
                });
            }
        });
    </script>
@endsection
