@extends('layouts.app')
@section('content')
    <div
        class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu group-data-[sidebar-size=md]:ltr:ml-vertical-menu-md group-data-[sidebar-size=md]:rtl:mr-vertical-menu-md group-data-[sidebar-size=sm]:ltr:ml-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:mr-vertical-menu-sm px-4 pb-[calc(theme('spacing.header')_*_0.8)] pt-[calc(theme('spacing.header')_*_1)] group-data-[layout=horizontal]:mx-auto group-data-[layout=horizontal]:max-w-screen-2xl group-data-[layout=horizontal]:px-0 group-data-[layout=horizontal]:px-3 group-data-[layout=horizontal]:group-data-[navbar=hidden]:pt-[calc(theme('spacing.header')_*_0.9)] group-data-[navbar=bordered]:pt-[calc(theme('spacing.header')_*_1.3)] group-data-[navbar=hidden]:pt-0 group-data-[layout=horizontal]:md:pt-[calc(theme('spacing.header')_*_1.6)] group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:ltr:md:ml-auto group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:rtl:md:mr-auto">
        <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">

            <div class="flex flex-col gap-2 py-4 md:flex-row md:items-center print:hidden">
                <div class="grow">
                    <h5 class="text-16">Rekap Poin Pelanggaran</h5>
                </div>
                <ul class="flex shrink-0 items-center gap-2 text-sm font-normal">
                    <li
                        class="before:font-remix dark:text-zink-200 relative before:absolute before:-top-[3px] before:text-[18px] before:text-slate-400 before:content-['\ea54'] ltr:pr-4 ltr:before:-right-1 rtl:pl-4 rtl:before:-left-1">
                        <a href="#!" class="dark:text-zink-200 text-slate-400">Dashboards</a>
                    </li>
                    <li class="dark:text-zink-100 text-slate-700">
                        Kesiswaan
                    </li>
                </ul>
            </div>
            <!-- Tambahkan filter ini sebelum div dengan class "card" -->
            <div class="card mb-4">
                <div class="card-body">
                    <h6 class="text-15 mb-4">Filter Data</h6>
                    <div class="flex flex-col gap-4 sm:flex-row">
                        <div class="flex-1">
                            <label for="classFilter"
                                class="dark:text-zink-300 mb-2 block text-sm font-medium text-slate-700">
                                Filter Kelas
                            </label>
                            <select id="classFilter"
                                class="dark:bg-zink-600 dark:border-zink-500 dark:text-zink-100 w-full rounded-md border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500">

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
                                class="dark:text-zink-300 mb-2 block text-sm font-medium text-slate-700">
                                Filter Jenis Kelamin
                            </label>
                            <select id="genderFilter"
                                class="dark:bg-zink-600 dark:border-zink-500 dark:text-zink-100 w-full rounded-md border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                                <option value="">Semua Jenis Kelamin</option>
                                <option value="Laki - Laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>
                        <div class="flex-1">
                            <label for="pointRangeFilter"
                                class="dark:text-zink-300 mb-2 block text-sm font-medium text-slate-700">
                                Filter Range Poin
                            </label>
                            <select id="pointRangeFilter"
                                class="dark:bg-zink-600 dark:border-zink-500 dark:text-zink-100 w-full rounded-md border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
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
                                class="dark:bg-zink-600 dark:border-zink-500 dark:text-zink-300 dark:hover:bg-zink-700 rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-600 transition-colors duration-200 hover:bg-slate-50 focus:ring-2 focus:ring-blue-500">
                                Reset Filter
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h6 class="text-15 mb-4">Datatable Rekap Pelanggaran</h6>

                    <!-- Info hasil filter -->
                    <div id="filterInfo" class="dark:text-zink-300 mb-3 hidden text-sm text-slate-600">
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
                                            class="cursor-pointer font-semibold text-red-600 underline transition-colors duration-200 hover:text-red-800 dark:text-red-400 dark:hover:text-red-200">
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
                    <div id="noMainData" class="hidden py-8 text-center">
                        <div class="dark:text-zink-400 flex flex-col items-center text-slate-500">
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
                @if ($rec->recaps->count() >= 0)
                    <div id="modal-{{ $rec->id }}" modal-center=""
                        class="z-drawer show fixed left-2/4 top-2/4 flex hidden -translate-x-2/4 -translate-y-2/4 flex-col transition-all duration-300 ease-in-out">
                        <!-- Modal dengan ukuran yang lebih besar -->
                        <div class="modal-container dark:bg-zink-600 flex flex-col rounded-md bg-white shadow">
                            <!-- Header Modal - Fixed -->
                            <div
                                class="modal-header dark:border-zink-500 flex flex-shrink-0 items-center justify-between border-b border-slate-200 p-4">
                                <h5 class="text-16 font-semibold">Detail Rekap Pelanggaran - {{ $rec->student->full_name }}
                                </h5>
                                <button data-modal-close="modal-{{ $rec->id }}"
                                    class="dark:text-zink-200 text-slate-500 transition-all duration-200 ease-linear hover:text-red-500 dark:hover:text-red-500">
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
                                    <div class="filter-section dark:bg-zink-700 mb-4 rounded-lg bg-slate-50 p-3">
                                        <div class="flex flex-col gap-4 sm:flex-row">
                                            <div class="flex-1">
                                                <label for="categoryFilter-{{ $rec->id }}"
                                                    class="dark:text-zink-300 mb-2 block text-sm font-medium text-slate-700">
                                                    Filter Kategori
                                                </label>
                                                <select id="categoryFilter-{{ $rec->id }}"
                                                    data-student-id="{{ $rec->id }}"
                                                    class="category-filter dark:bg-zink-600 dark:border-zink-500 dark:text-zink-100 w-full rounded-md border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                                                    <option value="">Semua Kategori</option>
                                                    <option value="Ringan">Ringan</option>
                                                    <option value="Sedang">Sedang</option>
                                                    <option value="Berat">Berat</option>
                                                </select>
                                            </div>
                                            <div class="flex-1">
                                                <label for="statusFilter-{{ $rec->id }}"
                                                    class="dark:text-zink-300 mb-2 block text-sm font-medium text-slate-700">
                                                    Filter Status
                                                </label>
                                                <select id="statusFilter-{{ $rec->id }}"
                                                    data-student-id="{{ $rec->id }}"
                                                    class="status-filter dark:bg-zink-600 dark:border-zink-500 dark:text-zink-100 w-full rounded-md border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                                                    <option value="">Semua Status</option>
                                                    <option value="pending">Pending</option>
                                                    <option value="verified">Verifikasi</option>
                                                    <option value="not_verified">Tidak Terverifikasi</option>
                                                </select>
                                            </div>
                                            <div class="flex items-end">
                                                <button type="button" data-student-id="{{ $rec->id }}"
                                                    class="reset-filter-btn dark:bg-zink-600 dark:border-zink-500 dark:text-zink-300 dark:hover:bg-zink-700 rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50 focus:ring-2 focus:ring-blue-500">
                                                    Reset Filter
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Table Container dengan tinggi tetap - Scrollable -->
                                    <div
                                        class="table-container dark:border-zink-500 mb-4 overflow-hidden rounded-lg border border-slate-200">
                                        <div class="table-scroll-wrapper">
                                            <table class="table-violations w-full text-left text-sm"
                                                id="violationsTable-{{ $rec->id }}">
                                                <thead
                                                    class="dark:bg-zink-700 sticky top-0 z-10 bg-slate-50 text-xs uppercase">
                                                    <tr>
                                                        <th scope="col"
                                                            class="dark:text-zink-200 w-10 px-3 py-3 font-semibold text-slate-700">
                                                            No</th>
                                                        <th scope="col"
                                                            class="dark:text-zink-200 w-24 px-4 py-4 font-semibold text-slate-700">
                                                            Tanggal</th>
                                                        <th scope="col"
                                                            class="dark:text-zink-200 min-w-[200px] px-4 py-4 font-semibold text-slate-700">
                                                            Pelanggaran</th>
                                                        <th scope="col"
                                                            class="dark:text-zink-200 w-20 px-4 py-4 font-semibold text-slate-700">
                                                            Kategori</th>
                                                        <th scope="col"
                                                            class="dark:text-zink-200 w-16 px-4 py-4 font-semibold text-slate-700">
                                                            Poin</th>
                                                        <th scope="col"
                                                            class="dark:text-zink-200 w-20 px-4 py-4 font-semibold text-slate-700">
                                                            Status</th>
                                                        <th scope="col"
                                                            class="dark:text-zink-200 w-24 px-4 py-4 font-semibold text-slate-700">
                                                            Dibuat oleh</th>
                                                        <th scope="col"
                                                            class="dark:text-zink-200 w-24 px-4 py-4 font-semibold text-slate-700">
                                                            Diverifikasi oleh</th>
                                                        <th scope="col"
                                                            class="dark:text-zink-200 w-24 px-4 py-4 font-semibold text-slate-700">
                                                            Diupdate oleh</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php $counter = 1; @endphp
                                                    @forelse ($rec->recaps as $recapsViol)
                                                        <tr class="violation-row dark:bg-zink-800 dark:border-zink-700 dark:hover:bg-zink-700 border-b bg-white hover:bg-slate-50"
                                                            data-category="{{ $recapsViol->violation->category->name ?? '' }}"
                                                            data-status="{{ $recapsViol->status }}">
                                                            <td class="row-number px-3 py-3 font-medium">
                                                                {{ $counter++ }}</td>
                                                            <td class="whitespace-nowrap px-4 py-4">
                                                                {{ \Carbon\Carbon::parse($recapsViol->created_at)->format('d/m/Y') }}
                                                            </td>
                                                            <td class="px-4 py-4">
                                                                <div class="violation-name">
                                                                    {{ $recapsViol->violation->name }}</div>
                                                            </td>
                                                            <td class="px-4 py-4">
                                                                <span
                                                                    class="@if (($recapsViol->violation->category->name ?? '') === 'Berat') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300
                                                        @elseif(($recapsViol->violation->category->name ?? '') === 'Sedang') bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300
                                                        @else bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300 @endif whitespace-nowrap rounded-full px-2 py-1 text-xs font-medium">
                                                                    {{ $recapsViol->violation->category->name ?? 'Tidak Diketahui' }}
                                                                </span>
                                                            </td>
                                                            <td class="px-4 py-4">
                                                                <span
                                                                    class="whitespace-nowrap font-semibold text-red-600 dark:text-red-400">
                                                                    {{ $recapsViol->violation->point ?? 0 }}
                                                                </span>
                                                            </td>
                                                            <td class="px-4 py-4">
                                                                @if ($recapsViol->status === 'pending')
                                                                    <span
                                                                        class="rounded-full bg-orange-100 px-2 py-1 text-xs font-medium text-orange-800 dark:bg-orange-900 dark:text-orange-300">
                                                                        Pending
                                                                    </span>
                                                                @elseif($recapsViol->status === 'verified')
                                                                    <span
                                                                        class="rounded-full bg-green-100 px-2 py-1 text-xs font-medium text-green-800 dark:bg-green-900 dark:text-green-300">
                                                                        Terverifikasi
                                                                    </span>
                                                                @elseif($recapsViol->status === 'not_verified')
                                                                    <span
                                                                        class="rounded-full bg-red-100 px-2 py-1 text-xs font-medium text-red-800 dark:bg-red-900 dark:text-red-300">
                                                                        Tidak Terverifikasi
                                                                    </span>
                                                                @endif
                                                            </td>
                                                            <td class="px-4 py-4">
                                                                <span class="dark:text-zink-300 text-sm text-slate-600">
                                                                    {{ $recapsViol->createdBy->name ?? '-' }}
                                                                </span>
                                                            </td>
                                                            <td class="px-4 py-4">
                                                                <span class="dark:text-zink-300 text-sm text-slate-600">
                                                                    {{ $recapsViol->verifiedBy->name ?? '-' }}
                                                                </span>
                                                            </td>
                                                            <td class="px-4 py-4">
                                                                <span class="dark:text-zink-300 text-sm text-slate-600">
                                                                    {{ $recapsViol->updatedBy->name ?? '-' }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr class="dark:bg-zink-800 no-data-row bg-white">
                                                            <td colspan="9"
                                                                class="dark:text-zink-400 px-4 py-8 text-center text-slate-500">
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
                                    <div id="noFilteredData-{{ $rec->id }}" class="mb-4 hidden py-8 text-center">
                                        <div class="dark:text-zink-400 flex flex-col items-center text-slate-500">
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
                                            <div class="dark:bg-zink-700 rounded-lg bg-slate-50 p-3">
                                                <div class="flex items-center justify-between">
                                                    <span class="dark:text-zink-300 text-sm font-medium text-slate-600">
                                                        Total Pelanggaran:
                                                    </span>
                                                    <span class="text-sm font-bold" id="totalCount-{{ $rec->id }}">
                                                        {{ $rec->recaps->count() }}
                                                    </span>
                                                </div>
                                                <div class="mt-1 flex items-center justify-between">
                                                    <span class="dark:text-zink-300 text-sm font-medium text-slate-600">
                                                        Total Poin (Semua):
                                                    </span>
                                                    <span class="text-sm font-bold text-slate-600 dark:text-slate-400"
                                                        id="totalPoints-{{ $rec->id }}">
                                                        {{ $totalAllPoints }} Poin
                                                    </span>
                                                </div>
                                                <div
                                                    class="dark:border-zink-600 mt-1 flex items-center justify-between border-t border-slate-200 pt-2">
                                                    <span class="dark:text-zink-300 text-sm font-medium text-slate-600">
                                                        Total Poin Terverifikasi:
                                                    </span>
                                                    <span class="text-sm font-bold text-red-600 dark:text-red-400"
                                                        id="verifiedPoints-{{ $rec->id }}">
                                                        {{ $totalVerifiedPoints }} Poin
                                                    </span>
                                                </div>
                                            </div>

                                            <!-- Handling Action Card (jika ada) -->
                                            <div class="handling-action-card {{ $applicableHandling ? '' : 'hidden' }} rounded-lg border-l-4 border-orange-500 bg-gradient-to-r from-orange-50 to-red-50 p-4 dark:from-orange-900/20 dark:to-red-900/20"
                                                data-handling-options='@json($handlingOptions)'>
                                                <div class="flex items-start gap-3">
                                                    <div class="mt-0.5 flex-shrink-0">
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
                                                            class="mb-1 text-sm font-semibold text-orange-800 dark:text-orange-300">
                                                            ⚠️ Tindakan Diperlukan
                                                        </h6>
                                                        <p class="mb-2 text-xs text-slate-600 dark:text-slate-400">
                                                            Siswa telah mencapai <span
                                                                class="current-points font-bold text-orange-700 dark:text-orange-400">{{ $totalVerifiedPoints }}</span>
                                                            poin pelanggaran terverifikasi
                                                        </p>
                                                        <div
                                                            class="dark:bg-zink-800 rounded-md border border-orange-200 bg-white p-3 dark:border-orange-800">
                                                            <div class="flex items-start gap-2">
                                                                <span
                                                                    class="whitespace-nowrap text-xs font-medium text-slate-500 dark:text-slate-400">
                                                                    Tindakan:
                                                                </span>
                                                                <span
                                                                    class="action-text text-sm font-semibold text-orange-700 dark:text-orange-300">
                                                                    {{ $applicableHandling->handling_action ?? '' }}
                                                                </span>
                                                            </div>
                                                            <div
                                                                class="mt-2 flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
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
                                                class="status-good-card {{ $applicableHandling ? 'hidden' : '' }} rounded-lg border-l-4 border-green-500 bg-green-50 p-3 dark:bg-green-900/20">
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
                                                <div class="rounded-lg bg-blue-50 p-3 dark:bg-blue-900/20">
                                                    <details class="group">
                                                        <summary
                                                            class="flex cursor-pointer items-center justify-between text-sm font-medium text-blue-800 dark:text-blue-300">
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
                                                                    class="dark:bg-zink-800 flex items-start gap-2 rounded border border-blue-100 bg-white p-2 text-xs dark:border-blue-800">
                                                                    <span
                                                                        class="whitespace-nowrap font-semibold text-blue-700 dark:text-blue-400">
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
                    // Normalisasi gender: hapus semua spasi dan ubah ke lowercase
                    const rowGender = row.getAttribute('data-gender') ?
                        row.getAttribute('data-gender').replace(/\s+/g, '').toLowerCase().trim() : '';
                    const rowPoints = parseInt(row.getAttribute('data-points')) || 0;

                    let showRow = true;

                    // Filter by class
                    if (classValue && classValue !== rowClass) {
                        showRow = false;
                    }

                    // Filter by gender
                    if (genderValue) {
                        // Normalisasi gender filter juga
                        const normalizedGenderValue = genderValue.replace(/\s+/g, '').toLowerCase().trim();
                        if (normalizedGenderValue !== rowGender) {
                            showRow = false;
                        }
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
