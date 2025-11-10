@extends('layouts.app')
@section('content')
    <div
        class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu group-data-[sidebar-size=md]:ltr:ml-vertical-menu-md group-data-[sidebar-size=md]:rtl:mr-vertical-menu-md group-data-[sidebar-size=sm]:ltr:ml-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:mr-vertical-menu-sm pt-[calc(theme('spacing.header')_*_1)] pb-[calc(theme('spacing.header')_*_0.8)] px-4 group-data-[navbar=bordered]:pt-[calc(theme('spacing.header')_*_1.3)] group-data-[navbar=hidden]:pt-0 group-data-[layout=horizontal]:mx-auto group-data-[layout=horizontal]:max-w-screen-2xl group-data-[layout=horizontal]:px-0 group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:ltr:md:ml-auto group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:rtl:md:mr-auto group-data-[layout=horizontal]:md:pt-[calc(theme('spacing.header')_*_1.6)] group-data-[layout=horizontal]:px-3 group-data-[layout=horizontal]:group-data-[navbar=hidden]:pt-[calc(theme('spacing.header')_*_0.9)]">
        <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">

            <div class="flex flex-col gap-2 py-4 md:flex-row md:items-center print:hidden">
                <div class="grow">
                    <h5 class="text-16">Recap Point</h5>
                </div>
                <ul class="flex items-center gap-2 text-sm font-normal shrink-0">
                    <li
                        class="relative before:content-['\ea54'] before:font-remix ltr:before:-right-1 rtl:before:-left-1  before:absolute before:text-[18px] before:-top-[3px] ltr:pr-4 rtl:pl-4 before:text-slate-400 dark:text-zink-200">
                        <a href="#!" class="text-slate-400 dark:text-zink-200">Dashboards</a>
                    </li>
                    <li class="text-slate-700 dark:text-zink-100">
                        BK
                    </li>
                </ul>
            </div>
            <!-- Tambahkan filter ini sebelum div dengan class "card" -->
            <div class="card mb-4">
                <div class="card-body">
                    <h6 class="mb-4 text-15">Filter Data</h6>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <div class="flex-1">
                            <label for="classFilter"
                                class="block text-sm font-medium text-slate-700 dark:text-zink-300 mb-2">
                                Filter Kelas
                            </label>
                            <select id="classFilter"
                                class="w-full px-3 py-2 text-sm border border-slate-200 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-zink-600 dark:border-zink-500 dark:text-zink-100">

                                <option value="">Semua Kelas</option>

                                @php
                                    $groupedClasses = $recaps
                                        ->groupBy('class.academic_level') // Grup berdasarkan tingkat (10, 11, 12)
                                        ->sortKeys() // Urutkan berdasarkan academic_level
                                        ->map(function ($classes) {
                                            return $classes
                                                ->unique('class.name') // Hilangkan duplikat class
                                                ->sortBy('class.name'); // Urut berdasarkan nama kelas (A, B, C)
                                        });
                                @endphp

                                @foreach ($groupedClasses as $level => $classes)
                                    @foreach ($classes as $item)
                                        <option value="{{ $item->class->name }}">
                                            {{ $level }} {{ $item->class->name }}
                                        </option>
                                    @endforeach
                                @endforeach
                            </select>
                        </div>
                        <div class="flex-1">
                            <label for="genderFilter"
                                class="block text-sm font-medium text-slate-700 dark:text-zink-300 mb-2">
                                Filter Jenis Kelamin
                            </label>
                            <select id="genderFilter"
                                class="w-full px-3 py-2 text-sm border border-slate-200 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-zink-600 dark:border-zink-500 dark:text-zink-100">
                                <option value="">Semua Jenis Kelamin</option>
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>
                        <div class="flex-1">
                            <label for="pointRangeFilter"
                                class="block text-sm font-medium text-slate-700 dark:text-zink-300 mb-2">
                                Filter Range Poin
                            </label>
                            <select id="pointRangeFilter"
                                class="w-full px-3 py-2 text-sm border border-slate-200 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-zink-600 dark:border-zink-500 dark:text-zink-100">
                                <option value="">Semua Range</option>
                                <option value="0">0 Poin</option>
                                <option value="1-10">1-10 Poin</option>
                                <option value="11-25">11-25 Poin</option>
                                <option value="26-50">26-50 Poin</option>
                                <option value="51+">51+ Poin</option>
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button type="button" id="resetMainFilter"
                                class="px-4 py-2 text-sm font-medium text-slate-600 bg-white border border-slate-300 rounded-md hover:bg-slate-50 focus:ring-2 focus:ring-blue-500 dark:bg-zink-600 dark:border-zink-500 dark:text-zink-300 dark:hover:bg-zink-700 transition-colors duration-200">
                                Reset Filter
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h6 class="mb-4 text-15">Datatable Rekap Pelanggaran</h6>

                    <!-- Info hasil filter -->
                    <div id="filterInfo" class="mb-3 text-sm text-slate-600 dark:text-zink-300 hidden">
                        <span id="showingCount">0</span> dari <span id="totalCount">0</span> data ditampilkan
                    </div>

                    <table id="hoverableTable" style="width: 100%" class="hover group">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Total Poin Pelanggaran</th>
                                <th>Nama Lengkap</th>
                                <th>NIS</th>
                                <th>NISN</th>
                                <th>Jenis Kelamin</th>
                                <th>Kelas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recaps as $rec)
                                <tr class="student-row" data-class="{{ $rec->class->name }}"
                                    data-gender="{{ $rec->student->gender }}"
                                    data-points="{{ $rec->violations_sum_point ?? 0 }}">
                                    <td class="row-number">{{ $loop->iteration }}</td>
                                    <td>
                                        <button data-modal-target="modal-{{ $rec->id }}" type="button"
                                            class="font-semibold text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-200 underline cursor-pointer transition-colors duration-200">
                                            {{ $rec->violations_sum_point ?? 0 }} Poin
                                        </button>
                                    </td>
                                    <td>{{ $rec->student->full_name }}</td>
                                    <td>{{ $rec->student->student_number }}</td>
                                    <td>{{ $rec->student->national_student_number }}</td>
                                    <td>{{ $rec->student->gender }}</td>
                                    <td>{{ $rec->class->name }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Pesan jika tidak ada data -->
                    <div id="noMainData" class="hidden text-center py-8">
                        <div class="flex flex-col items-center text-slate-500 dark:text-zink-400">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" class="mb-2">
                                <circle cx="11" cy="11" r="8"></circle>
                                <path d="m21 21-4.35-4.35"></path>
                            </svg>
                            <p class="text-sm">Tidak ada data yang sesuai dengan filter</p>
                        </div>
                    </div>
                </div>
            </div><!--end card-->

            <!-- Modal untuk setiap siswa -->
            @foreach ($recaps as $rec)
                @if ($rec->recaps->count() > 0)
                    <div id="modal-{{ $rec->id }}" modal-center=""
                        class="fixed flex flex-col hidden transition-all duration-300 ease-in-out left-2/4 z-drawer -translate-x-2/4 -translate-y-2/4 top-2/4 show">
                        <!-- Modal dengan ukuran yang lebih besar -->
                        <div class="modal-container bg-white shadow rounded-md dark:bg-zink-600 flex flex-col">
                            <!-- Header Modal - Fixed -->
                            <div
                                class="modal-header flex items-center justify-between p-4 border-b border-slate-200 dark:border-zink-500 flex-shrink-0">
                                <h5 class="text-16 font-semibold">Detail Rekap Pelanggaran - {{ $rec->student->full_name }}
                                </h5>
                                <button data-modal-close="modal-{{ $rec->id }}"
                                    class="transition-all duration-200 ease-linear text-slate-500 hover:text-red-500 dark:text-zink-200 dark:hover:text-red-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                    </svg>
                                </button>
                            </div>

                            <!-- Content Modal dengan Filter dan Tabel - Scrollable -->
                            <div class="modal-content flex-1 overflow-y-auto">
                                <div class="p-4">
                                    <!-- Filter Section -->
                                    <div class="filter-section mb-4 p-3 bg-slate-50 dark:bg-zink-700 rounded-lg">
                                        <div class="flex flex-col sm:flex-row gap-4">
                                            <div class="flex-1">
                                                <label for="categoryFilter-{{ $rec->id }}"
                                                    class="block text-sm font-medium text-slate-700 dark:text-zink-300 mb-2">
                                                    Filter Kategori
                                                </label>
                                                <select id="categoryFilter-{{ $rec->id }}"
                                                    data-student-id="{{ $rec->id }}"
                                                    class="category-filter w-full px-3 py-2 text-sm border border-slate-200 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-zink-600 dark:border-zink-500 dark:text-zink-100">
                                                    <option value="">Semua Kategori</option>
                                                    <option value="Ringan">Ringan</option>
                                                    <option value="Sedang">Sedang</option>
                                                    <option value="Berat">Berat</option>
                                                </select>
                                            </div>
                                            <div class="flex-1">
                                                <label for="statusFilter-{{ $rec->id }}"
                                                    class="block text-sm font-medium text-slate-700 dark:text-zink-300 mb-2">
                                                    Filter Status
                                                </label>
                                                <select id="statusFilter-{{ $rec->id }}"
                                                    data-student-id="{{ $rec->id }}"
                                                    class="status-filter w-full px-3 py-2 text-sm border border-slate-200 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-zink-600 dark:border-zink-500 dark:text-zink-100">
                                                    <option value="">Semua Status</option>
                                                    <option value="pending">Pending</option>
                                                    <option value="verified">Verifikasi</option>
                                                    <option value="not_verified">Tidak Terverifikasi</option>
                                                </select>
                                            </div>
                                            <div class="flex items-end">
                                                <button type="button" data-student-id="{{ $rec->id }}"
                                                    class="reset-filter-btn px-4 py-2 text-sm font-medium text-slate-600 bg-white border border-slate-300 rounded-md hover:bg-slate-50 focus:ring-2 focus:ring-blue-500 dark:bg-zink-600 dark:border-zink-500 dark:text-zink-300 dark:hover:bg-zink-700">
                                                    Reset Filter
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Table Container dengan tinggi tetap - Scrollable -->
                                    <div
                                        class="table-container overflow-hidden border border-slate-200 rounded-lg dark:border-zink-500 mb-4">
                                        <div class="table-scroll-wrapper">
                                            <table class="table-violations w-full text-sm text-left"
                                                id="violationsTable-{{ $rec->id }}">
                                                <thead
                                                    class="text-xs uppercase bg-slate-50 dark:bg-zink-700 sticky top-0 z-10">
                                                    <tr>
                                                        <th scope="col"
                                                            class="px-3 py-3 w-10 font-semibold text-slate-700 dark:text-zink-200">
                                                            No</th>
                                                        <th scope="col"
                                                            class="px-4 py-4 w-24 font-semibold text-slate-700 dark:text-zink-200">
                                                            Tanggal</th>
                                                        <th scope="col"
                                                            class="px-4 py-4 min-w-[200px] font-semibold text-slate-700 dark:text-zink-200">
                                                            Pelanggaran</th>
                                                        <th scope="col"
                                                            class="px-4 py-4 w-20 font-semibold text-slate-700 dark:text-zink-200">
                                                            Kategori</th>
                                                        <th scope="col"
                                                            class="px-4 py-4 w-16 font-semibold text-slate-700 dark:text-zink-200">
                                                            Poin</th>
                                                        <th scope="col"
                                                            class="px-4 py-4 w-20 font-semibold text-slate-700 dark:text-zink-200">
                                                            Status</th>
                                                        <th scope="col"
                                                            class="px-4 py-4 w-24 font-semibold text-slate-700 dark:text-zink-200">
                                                            Dibuat oleh</th>
                                                        <th scope="col"
                                                            class="px-4 py-4 w-24 font-semibold text-slate-700 dark:text-zink-200">
                                                            Diverifikasi oleh</th>
                                                        <th scope="col"
                                                            class="px-4 py-4 w-24 font-semibold text-slate-700 dark:text-zink-200">
                                                            Diupdate oleh</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php $counter = 1; @endphp
                                                    @forelse ($rec->recaps as $recapsViol)
                                                        <tr class="violation-row bg-white border-b dark:bg-zink-800 dark:border-zink-700 hover:bg-slate-50 dark:hover:bg-zink-700"
                                                            data-category="{{ $recapsViol->violation->category->name ?? '' }}"
                                                            data-status="{{ $recapsViol->status }}">
                                                            <td class="px-3 py-3 font-medium row-number">
                                                                {{ $counter++ }}</td>
                                                            <td class="px-4 py-4 whitespace-nowrap">
                                                                {{ \Carbon\Carbon::parse($recapsViol->created_at)->format('d/m/Y') }}
                                                            </td>
                                                            <td class="px-4 py-4">
                                                                <div class="violation-name">
                                                                    {{ $recapsViol->violation->name }}</div>
                                                            </td>
                                                            <td class="px-4 py-4">
                                                                <span
                                                                    class="px-2 py-1 text-xs font-medium rounded-full whitespace-nowrap
                                                        @if (($recapsViol->violation->category->name ?? '') === 'Berat') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300
                                                        @elseif(($recapsViol->violation->category->name ?? '') === 'Sedang') bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300
                                                        @else bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300 @endif">
                                                                    {{ $recapsViol->violation->category->name ?? 'Tidak Diketahui' }}
                                                                </span>
                                                            </td>
                                                            <td class="px-4 py-4">
                                                                <span
                                                                    class="font-semibold text-red-600 dark:text-red-400 whitespace-nowrap">
                                                                    {{ $recapsViol->violation->point ?? 0 }}
                                                                </span>
                                                            </td>
                                                            <td class="px-4 py-4">
                                                                @if ($recapsViol->status === 'pending')
                                                                    <span
                                                                        class="px-2 py-1 text-xs font-medium bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300 rounded-full">
                                                                        Pending
                                                                    </span>
                                                                @elseif($recapsViol->status === 'verified')
                                                                    <span
                                                                        class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300 rounded-full">
                                                                        Terverifikasi
                                                                    </span>
                                                                @elseif($recapsViol->status === 'not_verified')
                                                                    <span
                                                                        class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300 rounded-full">
                                                                        Tidak Terverifikasi
                                                                    </span>
                                                                @endif
                                                            </td>
                                                            <td class="px-4 py-4">
                                                                <span class="text-sm text-slate-600 dark:text-zink-300">
                                                                    {{ $recapsViol->createdBy->name ?? '-' }}
                                                                </span>
                                                            </td>
                                                            <td class="px-4 py-4">
                                                                <span class="text-sm text-slate-600 dark:text-zink-300">
                                                                    {{ $recapsViol->verifiedBy->name ?? '-' }}
                                                                </span>
                                                            </td>
                                                            <td class="px-4 py-4">
                                                                <span class="text-sm text-slate-600 dark:text-zink-300">
                                                                    {{ $recapsViol->updatedBy->name ?? '-' }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr class="bg-white dark:bg-zink-800 no-data-row">
                                                            <td colspan="9"
                                                                class="px-4 py-8 text-center text-slate-500 dark:text-zink-400">
                                                                <div class="flex flex-col items-center">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="48"
                                                                        height="48" viewBox="0 0 24 24" fill="none"
                                                                        stroke="currentColor" stroke-width="1.5"
                                                                        stroke-linecap="round" stroke-linejoin="round"
                                                                        class="mb-2">
                                                                        <circle cx="12" cy="12" r="10">
                                                                        </circle>
                                                                        <path d="M12 6v6l4 2"></path>
                                                                    </svg>
                                                                    <p class="text-sm">Tidak ada data pelanggaran untuk
                                                                        siswa ini
                                                                    </p>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- No data filtered message -->
                                    <div id="noFilteredData-{{ $rec->id }}" class="hidden text-center py-8 mb-4">
                                        <div class="flex flex-col items-center text-slate-500 dark:text-zink-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                                                class="mb-2">
                                                <circle cx="11" cy="11" r="8"></circle>
                                                <path d="m21 21-4.35-4.35"></path>
                                            </svg>
                                            <p class="text-sm">Tidak ada data yang sesuai dengan filter</p>
                                        </div>
                                    </div>

                                    <!-- Summary Section -->
                                    @if ($rec->recaps->count() > 0)
                                        @php
                                            // Hitung total poin verified saja
                                            $totalVerifiedPoints = $rec->violations_sum_point ?? 0;
                                            $totalAllPoints = $rec->recaps->sum(function ($recapsViol) {
                                                return $recapsViol->violation->point ?? 0;
                                            });

                                            // Cari handling action yang sesuai
                                            $applicableHandling = null;
                                            foreach ($handlingOptions->sortByDesc('handling_point') as $handling) {
                                                if ($totalVerifiedPoints >= $handling->handling_point) {
                                                    $applicableHandling = $handling;
                                                    break;
                                                }
                                            }
                                        @endphp

                                        <div class="summary-section space-y-3" id="summary-{{ $rec->id }}">
                                            <!-- Total Pelanggaran Card -->
                                            <div class="p-3 bg-slate-50 dark:bg-zink-700 rounded-lg">
                                                <div class="flex justify-between items-center">
                                                    <span class="text-sm font-medium text-slate-600 dark:text-zink-300">
                                                        Total Pelanggaran:
                                                    </span>
                                                    <span class="text-sm font-bold" id="totalCount-{{ $rec->id }}">
                                                        {{ $rec->recaps->count() }}
                                                    </span>
                                                </div>
                                                <div class="flex justify-between items-center mt-1">
                                                    <span class="text-sm font-medium text-slate-600 dark:text-zink-300">
                                                        Total Poin (Semua):
                                                    </span>
                                                    <span class="text-sm font-bold text-slate-600 dark:text-slate-400"
                                                        id="totalPoints-{{ $rec->id }}">
                                                        {{ $totalAllPoints }} Poin
                                                    </span>
                                                </div>
                                                <div
                                                    class="flex justify-between items-center mt-1 pt-2 border-t border-slate-200 dark:border-zink-600">
                                                    <span class="text-sm font-medium text-slate-600 dark:text-zink-300">
                                                        Total Poin Terverifikasi:
                                                    </span>
                                                    <span class="text-sm font-bold text-red-600 dark:text-red-400"
                                                        id="verifiedPoints-{{ $rec->id }}">
                                                        {{ $totalVerifiedPoints }} Poin
                                                    </span>
                                                </div>
                                            </div>

                                            <!-- Handling Action Card (jika ada) -->
                                            <div class="handling-action-card {{ $applicableHandling ? '' : 'hidden' }} p-4 bg-gradient-to-r from-orange-50 to-red-50 dark:from-orange-900/20 dark:to-red-900/20 rounded-lg border-l-4 border-orange-500"
                                                data-handling-options='@json($handlingOptions)'>
                                                <div class="flex items-start gap-3">
                                                    <div class="flex-shrink-0 mt-0.5">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                            height="20" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="text-orange-600 dark:text-orange-400">
                                                            <path
                                                                d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z" />
                                                            <path d="M12 9v4" />
                                                            <path d="M12 17h.01" />
                                                        </svg>
                                                    </div>
                                                    <div class="flex-1">
                                                        <h6
                                                            class="text-sm font-semibold text-orange-800 dark:text-orange-300 mb-1">
                                                            ⚠️ Tindakan Diperlukan
                                                        </h6>
                                                        <p class="text-xs text-slate-600 dark:text-slate-400 mb-2">
                                                            Siswa telah mencapai <span
                                                                class="font-bold text-orange-700 dark:text-orange-400 current-points">{{ $totalVerifiedPoints }}</span>
                                                            poin pelanggaran terverifikasi
                                                        </p>
                                                        <div
                                                            class="bg-white dark:bg-zink-800 rounded-md p-3 border border-orange-200 dark:border-orange-800">
                                                            <div class="flex items-start gap-2">
                                                                <span
                                                                    class="text-xs font-medium text-slate-500 dark:text-slate-400 whitespace-nowrap">
                                                                    Tindakan:
                                                                </span>
                                                                <span
                                                                    class="text-sm font-semibold text-orange-700 dark:text-orange-300 action-text">
                                                                    {{ $applicableHandling->handling_action ?? '' }}
                                                                </span>
                                                            </div>
                                                            <div
                                                                class="flex items-center gap-2 mt-2 text-xs text-slate-500 dark:text-slate-400">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                    height="14" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round">
                                                                    <circle cx="12" cy="12" r="10" />
                                                                    <path d="M12 6v6l4 2" />
                                                                </svg>
                                                                <span>Threshold: ≥<span
                                                                        class="threshold-text">{{ $applicableHandling->handling_point ?? '' }}</span>
                                                                    poin</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Status Aman Card -->
                                            <div
                                                class="status-good-card {{ $applicableHandling ? 'hidden' : '' }} p-3 bg-green-50 dark:bg-green-900/20 rounded-lg border-l-4 border-green-500">
                                                <div class="flex items-center gap-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="text-green-600 dark:text-green-400">
                                                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                                                        <polyline points="22 4 12 14.01 9 11.01" />
                                                    </svg>
                                                    <div class="flex-1">
                                                        <p class="text-sm font-medium text-green-800 dark:text-green-300">
                                                            Status Baik
                                                        </p>
                                                        <p class="text-xs text-green-600 dark:text-green-400">
                                                            Belum mencapai threshold tindakan
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Info Handling Options -->
                                            @if ($handlingOptions->count() > 0)
                                                <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                                                    <details class="group">
                                                        <summary
                                                            class="flex items-center justify-between cursor-pointer text-sm font-medium text-blue-800 dark:text-blue-300">
                                                            <span class="flex items-center gap-2">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                    height="16" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round">
                                                                    <circle cx="12" cy="12" r="10" />
                                                                    <path d="M12 16v-4" />
                                                                    <path d="M12 8h.01" />
                                                                </svg>
                                                                Daftar Tindakan Berdasarkan Poin
                                                            </span>
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                height="16" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="transition-transform group-open:rotate-180">
                                                                <polyline points="6 9 12 15 18 9" />
                                                            </svg>
                                                        </summary>
                                                        <div class="mt-3 space-y-2">
                                                            @foreach ($handlingOptions->sortBy('handling_point') as $handling)
                                                                <div
                                                                    class="flex items-start gap-2 text-xs bg-white dark:bg-zink-800 p-2 rounded border border-blue-100 dark:border-blue-800">
                                                                    <span
                                                                        class="font-semibold text-blue-700 dark:text-blue-400 whitespace-nowrap">
                                                                        ≥{{ $handling->handling_point }} poin:
                                                                    </span>
                                                                    <span class="text-slate-600 dark:text-slate-400">
                                                                        {{ $handling->handling_action }}
                                                                    </span>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </details>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
        <!-- container-fluid -->
    </div>

    <style>
        /* Modal dengan ukuran tetap */
        .modal-container {
            width: 90vw;
            max-width: 1000px;
            height: 80vh;
            max-height: 600px;
            min-height: 400px;
        }

        /* Responsive untuk mobile */
        @media (max-width: 768px) {
            .modal-container {
                width: 95vw;
                height: 85vh;
                max-height: none;
                min-height: 300px;
            }
        }

        /* Modal backdrop */
        [modal-center] {
            backdrop-filter: blur(4px);
            background-color: rgba(0, 0, 0, 0.5);
        }

        /* Header tetap di atas */
        .modal-header {
            background-color: inherit;
            z-index: 20;
        }

        /* Content area yang bisa di-scroll */
        .modal-content {
            min-height: 0;
            /* Penting untuk flexbox */
        }

        /* Filter section tetap di atas */
        .filter-section {
            background-color: inherit;
            z-index: 15;
        }

        /* Container table dengan scroll */
        .table-container {
            background-color: white;
        }


        /* Scroll wrapper untuk table */
        .table-scroll-wrapper {
            scrollbar-width: thin;
            scrollbar-color: rgba(156, 163, 175, 0.5) transparent;
        }

        .table-scroll-wrapper::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        .table-scroll-wrapper::-webkit-scrollbar-track {
            background: transparent;
        }

        .table-scroll-wrapper::-webkit-scrollbar-thumb {
            background-color: rgba(156, 163, 175, 0.5);
            border-radius: 3px;
        }

        .table-scroll-wrapper::-webkit-scrollbar-thumb:hover {
            background-color: rgba(156, 163, 175, 0.7);
        }

        /* Table styling */
        .table-violations {
            border-collapse: separate;
            border-spacing: 0;
        }

        .table-violations th {
            background-color: rgb(248, 250, 252);
            position: sticky;
            top: 0;
            z-index: 10;
            border-bottom: 1px solid rgb(226, 232, 240);
        }

        .dark .table-violations th {
            background-color: rgb(39, 39, 42);
            border-bottom: 1px solid rgb(63, 63, 70);
        }

        /* Violation name dengan word wrap */
        .violation-name {
            word-wrap: break-word;
            word-break: break-word;
            hyphens: auto;
            max-width: 200px;
        }

        /* Summary section tetap di bawah */
        .summary-section {
            background-color: inherit;
            z-index: 15;
        }

        /* Filter dan reset button styling */
        .category-filter,
        .status-filter {
            transition: all 0.2s ease-in-out;
        }

        .category-filter:focus,
        .status-filter:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        /* Main table styling */
        .card-body select:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .student-row {
            transition: all 0.2s ease-in-out;
        }

        .student-row:hover {
            background-color: rgba(248, 250, 252, 0.8);
        }

        .dark .student-row:hover {
            background-color: rgba(39, 39, 42, 0.8);
        }

        /* Loading state */
        .filter-loading {
            opacity: 0.6;
            pointer-events: none;
        }

        /* Smooth scroll behavior */
        .table-scroll-wrapper {
            scroll-behavior: smooth;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Main table filter functionality
            const classFilter = document.getElementById('classFilter');
            const genderFilter = document.getElementById('genderFilter');
            const pointRangeFilter = document.getElementById('pointRangeFilter');
            const resetMainFilterBtn = document.getElementById('resetMainFilter');
            const filterInfo = document.getElementById('filterInfo');
            const noMainData = document.getElementById('noMainData');
            const mainTable = document.getElementById('hoverableTable');

            // Add event listeners for main table filters
            [classFilter, genderFilter, pointRangeFilter].forEach(filter => {
                if (filter) {
                    filter.addEventListener('change', filterMainTable);
                }
            });

            if (resetMainFilterBtn) {
                resetMainFilterBtn.addEventListener('click', resetMainFilters);
            }

            // Initialize total count
            updateFilterInfo();

            function filterMainTable() {
                const classValue = classFilter ? classFilter.value : '';
                const genderValue = genderFilter ? genderFilter.value : '';
                const pointRangeValue = pointRangeFilter ? pointRangeFilter.value : '';

                const rows = mainTable.querySelectorAll('.student-row');
                let visibleRows = 0;

                rows.forEach(row => {
                    const rowClass = row.getAttribute('data-class');
                    const rowGender = row.getAttribute('data-gender');
                    const rowPoints = parseInt(row.getAttribute('data-points')) || 0;

                    let showRow = true;

                    // Filter by class
                    if (classValue && classValue !== rowClass) {
                        showRow = false;
                    }

                    // Filter by gender
                    if (genderValue && genderValue !== rowGender) {
                        showRow = false;
                    }

                    // Filter by point range
                    if (pointRangeValue) {
                        switch (pointRangeValue) {
                            case '0':
                                if (rowPoints !== 0) showRow = false;
                                break;
                            case '1-10':
                                if (rowPoints < 1 || rowPoints > 10) showRow = false;
                                break;
                            case '11-25':
                                if (rowPoints < 11 || rowPoints > 25) showRow = false;
                                break;
                            case '26-50':
                                if (rowPoints < 26 || rowPoints > 50) showRow = false;
                                break;
                            case '51+':
                                if (rowPoints < 51) showRow = false;
                                break;
                        }
                    }

                    if (showRow) {
                        row.style.display = '';
                        visibleRows++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                // Update row numbers for visible rows
                updateRowNumbers();

                // Show/hide no data message
                const tbody = mainTable.querySelector('tbody');
                if (visibleRows === 0) {
                    noMainData.classList.remove('hidden');
                    tbody.style.display = 'none';
                } else {
                    noMainData.classList.add('hidden');
                    tbody.style.display = '';
                }

                // Update filter info
                updateFilterInfo(visibleRows);
            }

            function updateRowNumbers() {
                const visibleRows = mainTable.querySelectorAll('.student-row:not([style*="display: none"])');
                visibleRows.forEach((row, index) => {
                    const rowNumberElement = row.querySelector('.row-number');
                    if (rowNumberElement) {
                        rowNumberElement.textContent = index + 1;
                    }
                });
            }

            function updateFilterInfo(showing = null) {
                const totalRows = mainTable.querySelectorAll('.student-row').length;
                const showingRows = showing !== null ? showing : totalRows;

                const showingCount = document.getElementById('showingCount');
                const totalCount = document.getElementById('totalCount');

                if (showingCount && totalCount) {
                    showingCount.textContent = showingRows;
                    totalCount.textContent = totalRows;

                    if (showingRows < totalRows) {
                        filterInfo.classList.remove('hidden');
                    } else {
                        filterInfo.classList.add('hidden');
                    }
                }
            }

            function resetMainFilters() {
                // Reset all filter values
                if (classFilter) classFilter.value = '';
                if (genderFilter) genderFilter.value = '';
                if (pointRangeFilter) pointRangeFilter.value = '';

                // Show all rows
                const rows = mainTable.querySelectorAll('.student-row');
                rows.forEach(row => {
                    row.style.display = '';
                });

                // Update row numbers
                updateRowNumbers();

                // Hide no data message
                noMainData.classList.add('hidden');
                mainTable.querySelector('tbody').style.display = '';

                // Update filter info
                updateFilterInfo();
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle modal open
            document.querySelectorAll('[data-modal-target]').forEach(button => {
                button.addEventListener('click', function() {
                    const modalId = this.getAttribute('data-modal-target');
                    const modal = document.getElementById(modalId);
                    if (modal) {
                        modal.classList.remove('hidden');
                        document.body.style.overflow = 'hidden';

                        // Scroll table to top when modal opens
                        const tableWrapper = modal.querySelector('.table-scroll-wrapper');
                        if (tableWrapper) {
                            tableWrapper.scrollTop = 0;
                        }
                    }
                });
            });

            // Handle modal close
            document.querySelectorAll('[data-modal-close]').forEach(button => {
                button.addEventListener('click', function() {
                    const modalId = this.getAttribute('data-modal-close');
                    const modal = document.getElementById(modalId);
                    if (modal) {
                        modal.classList.add('hidden');
                        document.body.style.overflow = 'auto';
                    }
                });
            });

            // Close modal when clicking outside
            document.querySelectorAll('[modal-center]').forEach(modal => {
                modal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        this.classList.add('hidden');
                        document.body.style.overflow = 'auto';
                    }
                });
            });

            // Close modal with Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    document.querySelectorAll('[modal-center]:not(.hidden)').forEach(modal => {
                        modal.classList.add('hidden');
                        document.body.style.overflow = 'auto';
                    });
                }
            });

            // Filter functionality
            document.querySelectorAll('.category-filter, .status-filter').forEach(filter => {
                filter.addEventListener('change', function() {
                    const studentId = this.getAttribute('data-student-id');
                    filterTable(studentId);
                });
            });

            // Reset filter functionality
            document.querySelectorAll('.reset-filter-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const studentId = this.getAttribute('data-student-id');
                    clearFilters(studentId);
                });
            });
        });

        function filterTable(studentId) {
            const categoryFilter = document.getElementById(`categoryFilter-${studentId}`);
            const statusFilter = document.getElementById(`statusFilter-${studentId}`);

            if (!categoryFilter || !statusFilter) return;

            const categoryValue = categoryFilter.value;
            const statusValue = statusFilter.value;
            const table = document.getElementById(`violationsTable-${studentId}`);
            const rows = table.querySelectorAll('.violation-row');
            const noDataMsg = document.getElementById(`noFilteredData-${studentId}`);
            const tableContainer = table.closest('.table-container');

            let visibleRows = 0;
            let totalPoints = 0;

            rows.forEach(row => {
                const rowCategory = row.getAttribute('data-category');
                const rowStatus = row.getAttribute('data-status');

                let showRow = true;

                // Filter by category
                if (categoryValue && categoryValue !== rowCategory) {
                    showRow = false;
                }

                // Filter by status
                if (statusValue && statusValue !== rowStatus) {
                    showRow = false;
                }

                if (showRow) {
                    row.style.display = '';
                    visibleRows++;
                    // Calculate points for visible rows
                    const pointsElement = row.querySelector('.font-semibold.text-red-600, .text-red-600');
                    if (pointsElement) {
                        const pointsText = pointsElement.textContent;
                        const pointsMatch = pointsText.match(/(\d+)/);
                        const points = pointsMatch ? parseInt(pointsMatch[1]) : 0;
                        totalPoints += points;
                    }
                } else {
                    row.style.display = 'none';
                }
            });

            // Update row numbers for visible rows
            let counter = 1;
            rows.forEach(row => {
                if (row.style.display !== 'none') {
                    const rowNumberElement = row.querySelector('.row-number');
                    if (rowNumberElement) {
                        rowNumberElement.textContent = counter++;
                    }
                }
            });

            // Show/hide no data message
            if (noDataMsg && tableContainer) {
                if (visibleRows === 0) {
                    noDataMsg.classList.remove('hidden');
                    tableContainer.style.display = 'none';
                } else {
                    noDataMsg.classList.add('hidden');
                    tableContainer.style.display = '';
                }
            }

            // Update summary
            const totalCountElement = document.getElementById(`totalCount-${studentId}`);
            const totalPointsElement = document.getElementById(`totalPoints-${studentId}`);

            if (totalCountElement) {
                totalCountElement.textContent = visibleRows;
            }
            if (totalPointsElement) {
                totalPointsElement.textContent = `${totalPoints} Poin`;
            }
        }

        function clearFilters(studentId) {
            // Reset filter values
            const categoryFilter = document.getElementById('categoryFilter-' + studentId);
            const statusFilter = document.getElementById('statusFilter-' + studentId);

            if (categoryFilter) {
                categoryFilter.value = '';
            }
            if (statusFilter) {
                statusFilter.value = '';
            }

            // Call filterTable to apply the reset
            filterTable(studentId);
        }

        // Utility function to handle table scroll position
        function saveScrollPosition(tableId) {
            const wrapper = document.querySelector(`#${tableId}`).closest('.table-scroll-wrapper');
            if (wrapper) {
                wrapper.dataset.scrollTop = wrapper.scrollTop;
            }
        }

        function restoreScrollPosition(tableId) {
            const wrapper = document.querySelector(`#${tableId}`).closest('.table-scroll-wrapper');
            if (wrapper && wrapper.dataset.scrollTop) {
                wrapper.scrollTop = parseInt(wrapper.dataset.scrollTop);
            }
        }
    </script>
@endsection
