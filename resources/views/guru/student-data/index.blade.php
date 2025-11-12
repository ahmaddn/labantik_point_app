@extends('layouts.app')
@section('content')
    <div
        class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu group-data-[sidebar-size=md]:ltr:ml-vertical-menu-md group-data-[sidebar-size=md]:rtl:mr-vertical-menu-md group-data-[sidebar-size=sm]:ltr:ml-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:mr-vertical-menu-sm pt-[calc(theme('spacing.header')_*_1)] pb-[calc(theme('spacing.header')_*_0.8)] px-4 group-data-[navbar=bordered]:pt-[calc(theme('spacing.header')_*_1.3)] group-data-[navbar=hidden]:pt-0 group-data-[layout=horizontal]:mx-auto group-data-[layout=horizontal]:max-w-screen-2xl group-data-[layout=horizontal]:px-0 group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:ltr:md:ml-auto group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:rtl:md:mr-auto group-data-[layout=horizontal]:md:pt-[calc(theme('spacing.header')_*_1.6)] group-data-[layout=horizontal]:px-3 group-data-[layout=horizontal]:group-data-[navbar=hidden]:pt-[calc(theme('spacing.header')_*_0.9)]">
        <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">

            <div class="flex flex-col gap-2 py-4 md:flex-row md:items-center print:hidden">
                <div class="grow">
                    <h5 class="text-16">Dashboard Super Admin</h5>
                </div>
                <ul class="flex items-center gap-2 text-sm font-normal shrink-0">
                    <li
                        class="relative before:content-['\ea54'] before:font-remix ltr:before:-right-1 rtl:before:-left-1  before:absolute before:text-[18px] before:-top-[3px] ltr:pr-4 rtl:pl-4 before:text-slate-400 dark:text-zink-200">
                        <a href="#!" class="text-slate-400 dark:text-zink-200">Dashboards</a>
                    </li>
                    <li class="text-slate-700 dark:text-zink-100">
                        Super Admin
                    </li>
                </ul>
            </div>

            {{-- Alert untuk error --}}
            @if ($errors->has('error'))
                <div
                    class="relative p-3 pr-12 text-sm text-red-500 border border-transparent rounded-md bg-red-50 dark:bg-red-400/20 mb-4">
                    <button
                        class="absolute top-0 bottom-0 right-0 p-3 text-red-200 transition hover:text-red-500 dark:text-red-400/50 dark:hover:text-red-500"
                        onclick="this.parentElement.style.display='none'">
                        <i data-lucide="x" class="h-5"></i>
                    </button>
                    <div>
                        <span class="font-bold">⚠️ Peringatan!</span>
                        {{ $errors->first('error') }}

                        {{-- Tampilkan detail poin jika ada --}}
                        @if (session('current_total_points') !== null)
                            <div class="mt-2 text-xs bg-red-100 dark:bg-red-500/10 p-2 rounded border-l-4 border-red-300">
                                <div class="font-semibold mb-1">📊 Detail Poin:</div>

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
                                    <div class="text-red-600 font-semibold">
                                        • <strong>Kelebihan:</strong>
                                        {{ session('excess_points') }} poin dari batas
                                        maksimal
                                    </div>
                                @endif

                                <div class="mt-2 pt-1 border-t border-red-200 dark:border-red-400/30 text-xs opacity-80">
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
                    class="relative p-3 pr-12 text-sm text-green-500 border border-transparent rounded-md bg-green-50 dark:bg-green-400/20 mb-4">
                    <button
                        class="absolute top-0 bottom-0 right-0 p-3 text-green-200 transition hover:text-green-500 dark:text-green-400/50 dark:hover:text-green-500"
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
                                            class="w-screen md:w-[30rem] bg-white shadow rounded-md dark:bg-zink-600 flex flex-col h-full">
                                            <div
                                                class=" flex items-center justify-between p-4 border-b border-slate-200 dark:border-zink-500">
                                                <h5 class="text-16 font-semibold">Tambah Pelanggaran -
                                                    {{ $murid->student->full_name }}
                                                </h5>
                                                <button data-modal-close="modal-{{ $murid->id }}"
                                                    class="transition-all duration-200 ease-linear text-slate-500 hover:text-red-500 dark:text-zink-200 dark:hover:text-red-500">✕</button>
                                            </div>

                                            <div class="p-4 overflow-y-auto" style="height: 475px">
                                                <form method="POST"
                                                    action="{{ route('superadmin.violations.store', $murid->id) }}">
                                                    @csrf

                                                    <div class="flex items-center justify-between mb-3">
                                                        <h5 class="text-16 font-medium">Pilih Pelanggaran:</h5>
                                                        <button type="submit"
                                                            class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20">
                                                            Submit
                                                        </button>
                                                    </div>

                                                    <!-- Search Input -->
                                                    <div class="mb-4">
                                                        <input type="text" id="searchViolation-{{ $murid->id }}"
                                                            placeholder="Cari pelanggaran..."
                                                            class="w-full px-3 py-2 text-sm border rounded-md border-slate-200 dark:border-zink-500 dark:bg-zink-700 dark:text-zink-100 focus:outline-none focus:border-custom-500">
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
                                                                            class="border rounded-sm appearance-none cursor-pointer size-4 bg-slate-100 border-slate-200 dark:bg-zink-600 dark:border-zink-500 checked:bg-red-500 checked:border-red-500 dark:checked:bg-red-500 dark:checked:border-red-500 checked:disabled:bg-red-400 checked:disabled:border-red-400">
                                                                        <label
                                                                            for="violation_{{ $violation->id }}_{{ $murid->id }}"
                                                                            class="ml-2 text-sm cursor-pointer select-none">
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
                                                        class="hidden p-4 text-center text-slate-500 dark:text-zink-300">
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
