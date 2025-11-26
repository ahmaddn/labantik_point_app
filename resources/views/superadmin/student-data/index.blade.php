@extends('layouts.app')
@section('content')
    <div
        class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu group-data-[sidebar-size=md]:ltr:ml-vertical-menu-md group-data-[sidebar-size=md]:rtl:mr-vertical-menu-md group-data-[sidebar-size=sm]:ltr:ml-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:mr-vertical-menu-sm px-4 pb-[calc(theme('spacing.header')_*_0.8)] pt-[calc(theme('spacing.header')_*_1)] group-data-[layout=horizontal]:mx-auto group-data-[layout=horizontal]:max-w-screen-2xl group-data-[layout=horizontal]:px-0 group-data-[layout=horizontal]:px-3 group-data-[layout=horizontal]:group-data-[navbar=hidden]:pt-[calc(theme('spacing.header')_*_0.9)] group-data-[navbar=bordered]:pt-[calc(theme('spacing.header')_*_1.3)] group-data-[navbar=hidden]:pt-0 group-data-[layout=horizontal]:md:pt-[calc(theme('spacing.header')_*_1.6)] group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:ltr:md:ml-auto group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:rtl:md:mr-auto">
        <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">

            <div class="flex flex-col gap-2 py-4 md:flex-row md:items-center print:hidden">
                <div class="grow">
                    <h5 class="text-16">Dashboard Super Admin</h5>
                </div>
                <ul class="flex shrink-0 items-center gap-2 text-sm font-normal">
                    <li
                        class="before:font-remix dark:text-zink-200 relative before:absolute before:-top-[3px] before:text-[18px] before:text-slate-400 before:content-['\ea54'] ltr:pr-4 ltr:before:-right-1 rtl:pl-4 rtl:before:-left-1">
                        <a href="#!" class="dark:text-zink-200 text-slate-400">Dashboards</a>
                    </li>
                    <li class="dark:text-zink-100 text-slate-700">
                        Super Admin
                    </li>
                </ul>
            </div>

            {{-- Alert untuk error --}}
            @if ($errors->has('error'))
                <div
                    class="relative mb-4 rounded-md border border-transparent bg-red-50 p-3 pr-12 text-sm text-red-500 dark:bg-red-400/20">
                    <button
                        class="absolute bottom-0 right-0 top-0 p-3 text-red-200 transition hover:text-red-500 dark:text-red-400/50 dark:hover:text-red-500"
                        onclick="this.parentElement.style.display='none'">
                        <i data-lucide="x" class="h-5"></i>
                    </button>
                    <div>
                        <span class="font-bold">⚠️ Peringatan!</span>
                        {{ $errors->first('error') }}

                        {{-- Tampilkan detail poin jika ada --}}
                        @if (session('current_total_points') !== null)
                            <div class="mt-2 rounded border-l-4 border-red-300 bg-red-100 p-2 text-xs dark:bg-red-500/10">
                                <div class="mb-1 font-semibold">📊 Detail Poin:</div>

                                {{-- Poin saat ini --}}
                                <div class="mb-1">
                                    • <strong>Total poin saat ini:</strong>
                                    {{ session('current_total_points') }} poin
                                    <div class="ml-4 text-xs opacity-75">
                                        - Terverifikasi:
                                        {{ session('current_verified_points') ?? 0 }}
                                        poin<br>
                                        - Pending:
                                        {{ session('current_pending_points') ?? 0 }} poin
                                    </div>
                                </div>

                                {{-- Poin yang akan ditambah --}}
                                @if (session('new_points') > 0)
                                    <div class="mb-1">
                                        • <strong>Poin yang akan ditambah:</strong>
                                        {{ session('new_points') }} poin
                                    </div>
                                @endif

                                {{-- Total setelah penambahan (jika ada) --}}
                                @if (session('total_points_after'))
                                    <div class="mb-1">
                                        • <strong>Total setelah penambahan:</strong>
                                        {{ session('total_points_after') }} poin
                                    </div>
                                @endif

                                {{-- Kelebihan poin (jika ada) --}}
                                @if (session('excess_points'))
                                    <div class="font-semibold text-red-600">
                                        • <strong>Kelebihan:</strong>
                                        {{ session('excess_points') }} poin dari batas
                                        maksimal
                                    </div>
                                @endif

                                <div class="mt-2 border-t border-red-200 pt-1 text-xs opacity-80 dark:border-red-400/30">
                                    <strong>Batas maksimal:</strong> 100 poin
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Alert untuk success --}}
            @if (session('success') && !$errors->has('error'))
                <div
                    class="relative mb-4 rounded-md border border-transparent bg-green-50 p-3 pr-12 text-sm text-green-500 dark:bg-green-400/20">
                    <button
                        class="absolute bottom-0 right-0 top-0 p-3 text-green-200 transition hover:text-green-500 dark:text-green-400/50 dark:hover:text-green-500"
                        onclick="this.parentElement.style.display='none'">
                        <i data-lucide="x" class="h-5"></i>
                    </button>
                    <div>
                        <span class="font-bold">✅ Berhasil!</span> {{ session('success') }}
                        @if (session('verified_points') !== null && session('pending_points') !== null)
                            <div class="mt-2 text-xs">
                                • Poin verified: {{ session('verified_points') }}<br>
                                • Poin pending: {{ session('pending_points') }}<br>
                                • Total semua poin: {{ session('total_all_points') }}
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    {{-- Filter Kelas --}}
                    <form method="GET" action="{{ route('superadmin.student-data') }}" id="filterForm" class="mb-4">
                        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2 xl:grid-cols-12">
                            <div class="xl:col-span-3">
                                <label class="mb-2 inline-block text-base font-medium">Filter Kelas</label>
                                <select name="class_id" id="classFilter"
                                    class="form-input dark:border-zink-500 focus:border-custom-500 dark:disabled:bg-zink-600 dark:disabled:border-zink-500 dark:disabled:text-zink-200 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 dark:placeholder:text-zink-200 border-slate-200 placeholder:text-slate-400 focus:outline-none disabled:border-slate-300 disabled:bg-slate-100 disabled:text-slate-500">
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

                    <h6 class="text-15 mb-4">Datatable Siswa</h6>

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
                                                class="btn bg-custom-500 border-custom-500 hover:bg-custom-600 hover:border-custom-600 focus:bg-custom-600 focus:border-custom-600 focus:ring-custom-100 active:bg-custom-600 active:border-custom-600 active:ring-custom-100 dark:ring-custom-400/20 flex size-[37.5px] items-center justify-center rounded-full p-0 text-white hover:text-white focus:text-white focus:ring active:text-white active:ring"><svg
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
                                        class="z-drawer show fixed left-2/4 flex hidden -translate-x-2/4 -translate-y-2/4 flex-col transition-all duration-300 ease-in-out">
                                        <div
                                            class="dark:bg-zink-600 flex h-full w-screen flex-col rounded-md bg-white shadow md:w-[30rem]">
                                            <div
                                                class="dark:border-zink-500 flex items-center justify-between border-b border-slate-200 p-4">
                                                <h5 class="text-16 font-semibold">Tambah Pelanggaran -
                                                    {{ $murid->student->full_name }}
                                                </h5>
                                                <button data-modal-close="modal-{{ $murid->id }}"
                                                    class="dark:text-zink-200 text-slate-500 transition-all duration-200 ease-linear hover:text-red-500 dark:hover:text-red-500">✕</button>
                                            </div>

                                            <div class="overflow-y-auto p-4" style="height: 475px">
                                                <form method="POST"
                                                    action="{{ route('superadmin.violations.store', $murid->id) }}">
                                                    @csrf

                                                    <div class="mb-3 flex items-center justify-between">
                                                        <h5 class="text-16 font-medium">Pilih Pelanggaran:</h5>
                                                        <button type="submit"
                                                            class="btn bg-custom-500 border-custom-500 hover:bg-custom-600 hover:border-custom-600 focus:bg-custom-600 focus:border-custom-600 focus:ring-custom-100 active:bg-custom-600 active:border-custom-600 active:ring-custom-100 dark:ring-custom-400/20 text-white hover:text-white focus:text-white focus:ring active:text-white active:ring">
                                                            Submit
                                                        </button>
                                                    </div>

                                                    <!-- Search Input -->
                                                    <div class="mb-4">
                                                        <input type="text" id="searchViolation-{{ $murid->id }}"
                                                            placeholder="Cari pelanggaran..."
                                                            class="dark:border-zink-500 dark:bg-zink-700 dark:text-zink-100 focus:border-custom-500 w-full rounded-md border border-slate-200 px-3 py-2 text-sm focus:outline-none">
                                                    </div>

                                                    <div class="space-y-4" id="violationList-{{ $murid->id }}">
                                                        @foreach ($vals as $violation)
                                                            <div class="violation-item"
                                                                data-violation-name="{{ strtolower($violation->name) }}">
                                                                <div class="ml-2">
                                                                    <div class="flex items-center py-1">
                                                                        <input type="checkbox" name="violations[]"
                                                                            value="{{ $violation->id }}"
                                                                            id="violation_{{ $violation->id }}_{{ $murid->id }}"
                                                                            class="dark:bg-zink-600 dark:border-zink-500 size-4 cursor-pointer appearance-none rounded-sm border border-slate-200 bg-slate-100 checked:border-red-500 checked:bg-red-500 checked:disabled:border-red-400 checked:disabled:bg-red-400 dark:checked:border-red-500 dark:checked:bg-red-500">
                                                                        <label
                                                                            for="violation_{{ $violation->id }}_{{ $murid->id }}"
                                                                            class="ml-2 cursor-pointer select-none text-sm">
                                                                            {{ $violation->name }}
                                                                            ({{ $violation->point }} poin)
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>

                                                    <!-- No Results Message -->
                                                    <div id="noResults-{{ $murid->id }}"
                                                        class="dark:text-zink-300 hidden p-4 text-center text-slate-500">
                                                        Tidak ada pelanggaran yang ditemukan
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Script untuk fitur search -->
                                    <script>
                                        document.addEventListener('DOMContentLoaded', function() {
                                            const searchInput = document.getElementById('searchViolation-{{ $murid->id }}');
                                            const violationList = document.getElementById('violationList-{{ $murid->id }}');
                                            const noResults = document.getElementById('noResults-{{ $murid->id }}');

                                            if (searchInput && violationList && noResults) {
                                                const violationItems = violationList.querySelectorAll('.violation-item');

                                                searchInput.addEventListener('input', function() {
                                                    const searchTerm = this.value.toLowerCase().trim();
                                                    let visibleCount = 0;

                                                    violationItems.forEach(function(item) {
                                                        const violationName = item.getAttribute('data-violation-name');

                                                        if (violationName.includes(searchTerm)) {
                                                            item.style.display = '';
                                                            visibleCount++;
                                                        } else {
                                                            item.style.display = 'none';
                                                        }
                                                    });

                                                    // Show/hide no results message
                                                    if (visibleCount === 0) {
                                                        noResults.classList.remove('hidden');
                                                    } else {
                                                        noResults.classList.add('hidden');
                                                    }
                                                });
                                            }
                                        });
                                    </script>
                                @endforeach
                            </tbody>
                        </table>
                    @elseif ($selectedClassId && $studentAcademicYears->count() == 0)
                        <div class="dark:text-zink-300 py-12 text-center text-slate-500">
                            <div class="mb-4 flex justify-center">
                                <div
                                    class="dark:bg-zink-600 flex h-20 w-20 items-center justify-center rounded-full bg-slate-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="dark:text-zink-500 text-slate-400">
                                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                        <circle cx="9" cy="7" r="4" />
                                        <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                                        <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                    </svg>
                                </div>
                            </div>
                            <p class="text-base font-medium">Tidak ada siswa di kelas ini</p>
                            <p class="dark:text-zink-400 mt-2 text-sm text-slate-400">Kelas yang dipilih belum memiliki
                                data siswa</p>
                        </div>
                    @else
                        <div class="dark:text-zink-300 py-12 text-center text-slate-500">
                            <div class="mb-4 flex justify-center">
                                <div
                                    class="bg-custom-100 dark:bg-custom-500/10 flex h-20 w-20 items-center justify-center rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" class="text-custom-500">
                                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                                        <polyline points="9 22 9 12 15 12 15 22" />
                                    </svg>
                                </div>
                            </div>
                            <p class="text-base font-medium">Pilih Kelas Terlebih Dahulu</p>
                            <p class="dark:text-zink-400 mt-2 text-sm text-slate-400">Silakan pilih kelas dari dropdown di
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
