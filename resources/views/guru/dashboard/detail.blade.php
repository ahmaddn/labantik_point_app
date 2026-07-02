@extends('layouts.app')
@section('content')
    <div
        class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu group-data-[sidebar-size=md]:ltr:ml-vertical-menu-md group-data-[sidebar-size=md]:rtl:mr-vertical-menu-md group-data-[sidebar-size=sm]:ltr:ml-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:mr-vertical-menu-sm px-4 pb-[calc(theme('spacing.header')_*_0.8)] pt-[calc(theme('spacing.header')_*_1)] group-data-[layout=horizontal]:mx-auto group-data-[layout=horizontal]:max-w-screen-2xl group-data-[layout=horizontal]:px-0 group-data-[layout=horizontal]:px-3 group-data-[layout=horizontal]:group-data-[navbar=hidden]:pt-[calc(theme('spacing.header')_*_0.9)] group-data-[navbar=bordered]:pt-[calc(theme('spacing.header')_*_1.3)] group-data-[navbar=hidden]:pt-0 group-data-[layout=horizontal]:md:pt-[calc(theme('spacing.header')_*_1.6)] group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:ltr:md:ml-auto group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:rtl:md:mr-auto">
        <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">

            <!-- Breadcrumb -->
            <div class="flex flex-col gap-2 py-4 md:flex-row md:items-center print:hidden">
                <div class="grow">
                    <h5 class="text-16">Detail Rekap Pelanggaran</h5>
                </div>
                <ul class="flex shrink-0 items-center gap-2 text-sm font-normal">
                    <li
                        class="before:font-remix dark:text-zink-200 relative before:absolute before:-top-[3px] before:text-[18px] before:text-slate-400 before:content-['\ea54'] ltr:pr-4 ltr:before:-right-1 rtl:pl-4 rtl:before:-left-1">
                        <a href="#!" class="dark:text-zink-200 text-slate-400">Dashboards</a>
                    </li>
                    <li
                        class="before:font-remix dark:text-zink-200 relative before:absolute before:-top-[3px] before:text-[18px] before:text-slate-400 before:content-['\ea54'] ltr:pr-4 ltr:before:-right-1 rtl:pl-4 rtl:before:-left-1">
                        <a href="#!" class="dark:text-zink-200 text-slate-400">Guru</a>
                    </li>
                    <li
                        class="before:font-remix dark:text-zink-200 relative before:absolute before:-top-[3px] before:text-[18px] before:text-slate-400 before:content-['\ea54'] ltr:pr-4 ltr:before:-right-1 rtl:pl-4 rtl:before:-left-1">
                        <a href="#" class="dark:text-zink-200 text-slate-400">Rekap Pelanggaran</a>
                    </li>
                    <li class="dark:text-zink-100 text-slate-700">
                        Detail Pelanggaran
                    </li>
                </ul>
            </div>

            <!-- Student Info Card -->
            <div class="card mb-4 shadow-sm relative overflow-hidden">
                <div class="card-body p-5">
                    <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
                        <div>
                            <h5 class="text-xl font-bold text-slate-800 dark:text-zink-50 mb-2">{{ $studentAcademicYear->student->full_name }}</h5>
                            <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4 text-sm text-slate-600 dark:text-zink-300">
                                <div><span class="font-semibold text-slate-700 dark:text-zink-200">NIS:</span> {{ $studentAcademicYear->student->student_number }}</div>
                                <div class="hidden sm:block text-slate-300 dark:text-zink-500">•</div>
                                <div><span class="font-semibold text-slate-700 dark:text-zink-200">Kelas:</span> {{ $studentAcademicYear->class->academic_level }} {{ $studentAcademicYear->class->name }}</div>
                                <div class="hidden sm:block text-slate-300 dark:text-zink-500">•</div>
                                <div><span class="font-semibold text-slate-700 dark:text-zink-200">Jenis Kelamin:</span> {{ $studentAcademicYear->student->gender }}</div>
                            </div>
                        </div>
                        <a href="{{ route('guru.recaps') }}"
                            class="shrink-0 flex items-center justify-center gap-2 rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-600 transition-all hover:bg-slate-50 hover:shadow-sm dark:bg-zink-700 dark:border-zink-500 dark:text-zink-200 dark:hover:bg-zink-600">
                            <i data-lucide="arrow-left" class="h-4 w-4"></i>
                            <span class="ml-1">Kembali</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="card mb-4">
                <div class="card-body">
                    <h6 class="text-15 mb-4">Filter Data</h6>
                    <div class="flex flex-col gap-4 sm:flex-row">
                        <div class="flex-1">
                            <label for="detailCategoryFilter"
                                class="dark:text-zink-300 mb-2 block text-sm font-medium text-slate-700">
                                Filter Kategori
                            </label>
                            <select id="detailCategoryFilter"
                                class="detail-category-filter dark:bg-zink-600 dark:border-zink-500 dark:text-zink-100 w-full rounded-md border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                                <option value="">Semua Kategori</option>
                                <option value="Ringan">Ringan</option>
                                <option value="Sedang">Sedang</option>
                                <option value="Berat">Berat</option>
                            </select>
                        </div>
                        <div class="flex-1">
                            <label for="detailStatusFilter"
                                class="dark:text-zink-300 mb-2 block text-sm font-medium text-slate-700">
                                Filter Status
                            </label>
                            <select id="detailStatusFilter"
                                class="detail-status-filter dark:bg-zink-600 dark:border-zink-500 dark:text-zink-100 w-full rounded-md border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                                <option value="">Semua Status</option>
                                <option value="pending">Pending</option>
                                <option value="verified">Terverifikasi</option>
                                <option value="not_verified">Tidak Terverifikasi</option>
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button type="button" id="resetDetailFilter"
                                class="dark:bg-zink-600 dark:border-zink-500 dark:text-zink-300 dark:hover:bg-zink-700 rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50 focus:ring-2 focus:ring-blue-500">
                                Reset Filter
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table Card -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="mb-4 flex items-center justify-between">
                        <h6 class="text-15">Daftar Pelanggaran</h6>
                    </div>

                    <!-- Table Container -->
                    <div class="table-wrapper">
                        <table id="hoverableTable" style="width: 100%" class="hover group">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal
                                    </th>
                                    <th>Pelanggaran</th>
                                    <th>Kategori
                                    </th>
                                    <th>Poin
                                    </th>
                                    <th>Status
                                    </th>
                                    <th>Dibuat
                                        oleh
                                    </th>
                                    <th>
                                        Diverifikasi
                                        oleh</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $detailCounter = 1; @endphp
                                @forelse ($studentAcademicYear->pRecaps as $pRecap)
                                    <tr class="detail-violation-row dark:bg-zink-800 dark:border-zink-700 dark:hover:bg-zink-700 border-b bg-white hover:bg-slate-50"
                                        data-category="{{ $pRecap->violation->category->name ?? '' }}"
                                        data-status="{{ $pRecap->status }}">
                                        <td class="detail-row-number px-3 py-3 font-medium">{{ $detailCounter++ }}</td>
                                        <td class="whitespace-nowrap px-4 py-4">
                                            {{ \Carbon\Carbon::parse($pRecap->created_at)->translatedFormat('d M Y') }}
                                        </td>
                                        <td class="whitespace-normal px-4 py-4">
                                            {{ $pRecap->violation->name }}
                                        </td>

                                        <td class="whitespace-nowrap px-4 py-4">
                                            <span
                                                class="@if (($pRecap->violation->category->name ?? '') === 'Berat') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300
                                            @elseif(($pRecap->violation->category->name ?? '') === 'Sedang') bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300
                                            @else bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300 @endif whitespace-nowrap rounded-full px-2 py-1 text-xs font-medium">
                                                {{ $pRecap->violation->category->name ?? 'Tidak Diketahui' }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-4">
                                            <span class="whitespace-nowrap font-semibold text-red-600 dark:text-red-400">
                                                {{ $pRecap->violation->point ?? 0 }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-4">
                                            @if ($pRecap->status === 'pending')
                                                <span
                                                    class="rounded-full bg-orange-100 px-2 py-1 text-xs font-medium text-orange-800 dark:bg-orange-900 dark:text-orange-300">
                                                    Pending
                                                </span>
                                            @elseif($pRecap->status === 'verified')
                                                <span
                                                    class="rounded-full bg-green-100 px-2 py-1 text-xs font-medium text-green-800 dark:bg-green-900 dark:text-green-300">
                                                    Terverifikasi
                                                </span>
                                            @elseif($pRecap->status === 'not_verified')
                                                <span
                                                    class="rounded-full bg-red-100 px-2 py-1 text-xs font-medium text-red-800 dark:bg-red-900 dark:text-red-300">
                                                    Tidak Terverifikasi
                                                </span>
                                            @endif
                                        </td>
                                        <td class="whitespace-nowrap px-4 py-4">
                                            {{ $pRecap->createdBy->name ?? '-' }}
                                        </td>
                                        <td class="px-4 py-4">
                                            {{ $pRecap->verifiedBy->name ?? '-' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="dark:bg-zink-800 no-detail-data-row bg-white">
                                        <td colspan="9" class="dark:text-zink-400 px-4 py-8 text-center text-slate-500">
                                            <div class="flex flex-col items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                                                    class="mb-2">
                                                    <circle cx="12" cy="12" r="10"></circle>
                                                    <path d="M12 6v6l4 2"></path>
                                                </svg>
                                                <p class="text-sm">Tidak ada data pelanggaran untuk siswa ini</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <!-- No data filtered message -->
                    <div id="noDetailFilteredData" class="hidden py-8 text-center">
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
            </div>



            <!-- Summary Section -->
            @if ($studentAcademicYear->pRecaps->count() > 0)
                <div class="card shadow-sm mb-4 border-0">
                    <div class="card-body p-5">
                        <div class="mb-5 flex items-center gap-2 border-b border-slate-200 pb-3 dark:border-zink-500">
                            <i data-lucide="pie-chart" class="h-5 w-5 text-custom-500"></i>
                            <h6 class="mb-0 text-16 font-semibold">Ringkasan Pelanggaran</h6>
                        </div>

                        <div class="mb-5 grid grid-cols-1 gap-4 md:grid-cols-3" id="detailSummary">
                            <!-- Total Pelanggaran Card -->
                            <div class="flex items-center gap-4 rounded-xl border border-slate-200 bg-white p-4 transition-all hover:shadow-md dark:border-zink-600 dark:bg-zink-700">
                                <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-blue-50 text-blue-500 dark:bg-blue-500/20">
                                    <i data-lucide="file-text" class="h-6 w-6"></i>
                                </div>
                                <div>
                                    <p class="mb-1 text-sm text-slate-500 dark:text-slate-400">Total Pelanggaran</p>
                                    <h5 class="mb-0 text-xl font-bold" id="detailTotalCount">
                                        {{ $studentAcademicYear->pRecaps->count() }}
                                    </h5>
                                </div>
                            </div>

                            <!-- Total Poin Semua -->
                            <div class="flex items-center gap-4 rounded-xl border border-slate-200 bg-white p-4 transition-all hover:shadow-md dark:border-zink-600 dark:bg-zink-700">
                                <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-orange-50 text-orange-500 dark:bg-orange-500/20">
                                    <i data-lucide="hash" class="h-6 w-6"></i>
                                </div>
                                <div>
                                    <p class="mb-1 text-sm text-slate-500 dark:text-slate-400">Total Poin (Semua)</p>
                                    <h5 class="mb-0 text-xl font-bold text-slate-700 dark:text-slate-200" id="detailTotalPoints">
                                        {{ $studentAcademicYear->pRecaps->sum(function ($pRecap) {
                                            return $pRecap->violation->point ?? 0;
                                        }) }}
                                        <span class="text-sm font-normal text-slate-400">Poin</span>
                                    </h5>
                                </div>
                            </div>

                            <!-- Total Poin Terverifikasi -->
                            <div class="flex items-center gap-4 rounded-xl border border-slate-200 bg-white p-4 transition-all hover:shadow-md dark:border-zink-600 dark:bg-zink-700">
                                <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-50 text-red-500 dark:bg-red-500/20">
                                    <i data-lucide="shield-alert" class="h-6 w-6"></i>
                                </div>
                                <div>
                                    <p class="mb-1 text-sm text-slate-500 dark:text-slate-400">Poin Terverifikasi</p>
                                    <h5 class="mb-0 text-xl font-bold text-red-500" id="detailVerifiedPoints">
                                        {{ $totalVerifiedPoints }}
                                        <span class="text-sm font-normal text-red-300">Poin</span>
                                    </h5>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <!-- Handling Action Card (jika ada) -->
                            <div class="handling-action-card {{ $applicableHandling ? '' : 'hidden' }} relative overflow-hidden rounded-xl border border-orange-200 bg-orange-50/50 p-5 dark:border-orange-500/30 dark:bg-orange-500/10"
                                data-handling-options='@json($handlingPointOptions)'>
                                <div class="pointer-events-none absolute right-0 top-0 opacity-10">
                                    <i data-lucide="alert-triangle" class="-mr-6 -mt-6 h-32 w-32 text-orange-500"></i>
                                </div>
                                <div class="relative z-10 flex items-start gap-4">
                                    <div class="mt-1 flex-shrink-0">
                                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-orange-500 text-white shadow-sm">
                                            <i data-lucide="alert-triangle" class="h-5 w-5"></i>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <h6 class="mb-1 text-base font-bold text-orange-800 dark:text-orange-400">
                                            Tindakan Diperlukan
                                        </h6>
                                        <p class="mb-3 text-sm text-orange-700/80 dark:text-orange-300">
                                            Siswa telah mencapai <span class="current-points font-bold text-orange-700 dark:text-orange-400">{{ $totalVerifiedPoints }}</span> poin pelanggaran terverifikasi.
                                        </p>
                                        <div class="inline-block w-full min-w-[300px] rounded-lg border border-orange-200 bg-white p-3 md:w-auto dark:border-orange-500/30 dark:bg-zink-800">
                                            <div class="mb-2 flex items-center justify-between border-b border-orange-100 pb-2 dark:border-orange-500/20">
                                                <span class="text-sm font-medium text-slate-500 dark:text-slate-400">Tindakan Khusus:</span>
                                                <span class="action-text text-sm font-bold text-orange-700 dark:text-orange-400">
                                                    {{ $applicableHandling->handling_action ?? '' }}
                                                </span>
                                            </div>
                                            <div class="flex items-center justify-between">
                                                <span class="text-xs text-slate-500 dark:text-slate-400">Threshold Pelanggaran:</span>
                                                <span class="text-xs font-semibold text-slate-600 dark:text-slate-300">
                                                    ≥<span class="threshold-text">{{ $applicableHandling->handling_point ?? '' }}</span> poin
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Status Aman Card -->
                            <div class="status-good-card {{ $applicableHandling ? 'hidden' : '' }} relative overflow-hidden rounded-xl border border-green-200 bg-green-50/50 p-5 dark:border-green-500/30 dark:bg-green-500/10">
                                <div class="pointer-events-none absolute right-0 top-0 opacity-10">
                                    <i data-lucide="check-circle" class="-mr-6 -mt-6 h-32 w-32 text-green-500"></i>
                                </div>
                                <div class="relative z-10 flex items-center gap-4">
                                    <div class="flex-shrink-0">
                                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-green-500 text-white shadow-sm">
                                            <i data-lucide="check-circle" class="h-5 w-5"></i>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <h6 class="mb-1 text-base font-bold text-green-800 dark:text-green-400">
                                            Status Baik
                                        </h6>
                                        <p class="text-sm text-green-700/80 dark:text-green-300">
                                            Belum mencapai threshold tindakan pendisiplinan.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Info Handling Options -->
                            @if ($handlingPointOptions->count() > 0)
                                <div class="overflow-hidden rounded-xl border border-slate-200 bg-white dark:border-zink-600 dark:bg-zink-700">
                                    <details class="group">
                                        <summary class="list-none [&::-webkit-details-marker]:hidden flex cursor-pointer items-center justify-between bg-slate-50 p-4 transition-colors hover:bg-slate-100 dark:bg-zink-700 dark:hover:bg-zink-600">
                                            <div class="flex items-center gap-3">
                                                <div class="flex h-8 w-8 items-center justify-center rounded bg-blue-100 text-blue-600 dark:bg-blue-500/20 dark:text-blue-400">
                                                    <i data-lucide="info" class="h-4 w-4"></i>
                                                </div>
                                                <span class="text-sm font-semibold text-slate-700 dark:text-slate-200">
                                                    Daftar Tindakan Berdasarkan Poin
                                                </span>
                                            </div>
                                            <div class="transition-transform duration-300 group-open:rotate-180 text-slate-400">
                                                <i data-lucide="chevron-down" class="h-5 w-5"></i>
                                            </div>
                                        </summary>
                                        <div class="space-y-3 border-t border-slate-200 p-4 dark:border-zink-600">
                                            @foreach ($handlingPointOptions->sortBy('handling_point') as $handling)
                                                <div class="flex items-center rounded-lg border border-slate-100 bg-slate-50/50 p-3 transition hover:border-blue-200 dark:border-zink-600/50 dark:bg-zink-800/50 dark:hover:border-blue-500/30">
                                                    <div class="mr-3 w-20 flex-shrink-0 border-r border-slate-200 pr-3 text-right dark:border-zink-600">
                                                        <span class="text-sm font-bold text-blue-600 dark:text-blue-400">
                                                            ≥ {{ $handling->handling_point }}
                                                        </span>
                                                    </div>
                                                    <div class="flex-1">
                                                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300">
                                                            {{ $handling->handling_action }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </details>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

        </div>
        <!-- container-fluid -->
    </div>

    <style>
        /* Violation name dengan word wrap */
        .violation-name {
            word-wrap: break-word;
            word-break: break-word;
            hyphens: auto;
            max-width: 300px;
        }

        /* Table styling */
        .table-detail-violations {
            border-collapse: separate;
            border-spacing: 0;
        }

        .table-detail-violations th {
            background-color: rgb(248, 250, 252);
            border-bottom: 1px solid rgb(226, 232, 240);
        }

        .dark .table-detail-violations th {
            background-color: rgb(39, 39, 42);
            border-bottom: 1px solid rgb(63, 63, 70);
        }

        /* Filter styling */
        .detail-category-filter:focus,
        .detail-status-filter:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        /* Row hover effect */
        .detail-violation-row {
            transition: all 0.2s ease-in-out;
        }

        /* Pagination styling */
        .page-number {
            min-width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.375rem;
            border: 1px solid rgb(226, 232, 240);
            background-color: white;
            color: rgb(71, 85, 105);
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease-in-out;
        }

        .page-number:hover {
            background-color: rgb(248, 250, 252);
        }

        .page-number.active {
            background-color: rgb(59, 130, 246);
            border-color: rgb(59, 130, 246);
            color: white;
        }

        .dark .page-number {
            background-color: rgb(39, 39, 42);
            border-color: rgb(63, 63, 70);
            color: rgb(212, 212, 216);
        }

        .dark .page-number:hover {
            background-color: rgb(63, 63, 70);
        }

        .dark .page-number.active {
            background-color: rgb(59, 130, 246);
            border-color: rgb(59, 130, 246);
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const categoryFilter = document.getElementById('detailCategoryFilter');
            const statusFilter = document.getElementById('detailStatusFilter');
            const resetFilterBtn = document.getElementById('resetDetailFilter');
            const table = document.getElementById('hoverableTable');
            const noDataMsg = document.getElementById('noDetailFilteredData');

            // Add event listeners for filters
            [categoryFilter, statusFilter].forEach(filter => {
                if (filter) {
                    filter.addEventListener('change', filterDetailTable);
                }
            });

            if (resetFilterBtn) {
                resetFilterBtn.addEventListener('click', resetDetailFilters);
            }

            function filterDetailTable() {
                const categoryValue = categoryFilter ? categoryFilter.value : '';
                const statusValue = statusFilter ? statusFilter.value : '';

                const rows = table.querySelectorAll('.detail-violation-row');
                let visibleRows = 0;
                let totalPoints = 0;
                let verifiedPoints = 0;

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
                        const pointsElement = row.querySelector(
                            '.font-semibold.text-red-600, .text-red-600');
                        if (pointsElement) {
                            const pointsText = pointsElement.textContent;
                            const pointsMatch = pointsText.match(/(\d+)/);
                            const points = pointsMatch ? parseInt(pointsMatch[1]) : 0;
                            totalPoints += points;

                            // Calculate verified points only
                            if (rowStatus === 'verified') {
                                verifiedPoints += points;
                            }
                        }
                    } else {
                        row.style.display = 'none';
                    }
                });

                // Update row numbers for visible rows
                let counter = 1;
                rows.forEach(row => {
                    if (row.style.display !== 'none') {
                        const rowNumberElement = row.querySelector('.detail-row-number');
                        if (rowNumberElement) {
                            rowNumberElement.textContent = counter++;
                        }
                    }
                });

                // Show/hide no data message
                const tableContainer = table.closest('.overflow-x-auto');
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
                const totalCountElement = document.getElementById('detailTotalCount');
                const totalPointsElement = document.getElementById('detailTotalPoints');
                const verifiedPointsElement = document.getElementById('detailVerifiedPoints');

                if (totalCountElement) {
                    totalCountElement.textContent = visibleRows;
                }
                if (totalPointsElement) {
                    totalPointsElement.textContent = `${totalPoints} Poin`;
                }
                if (verifiedPointsElement) {
                    verifiedPointsElement.textContent = `${verifiedPoints} Poin`;
                }

                // Update handling action display dynamically
                updateHandlingAction(verifiedPoints);
            }

            function updateHandlingAction(verifiedPoints) {
                const summarySection = document.getElementById('detailSummary');
                if (!summarySection) return;

                const handlingCard = summarySection.querySelector('.handling-action-card');
                const statusGoodCard = summarySection.querySelector('.status-good-card');
                if (!handlingCard || !statusGoodCard) return;

                // Get handling options from data attribute
                const handlingOptionsData = handlingCard.getAttribute('data-handling-options');
                if (!handlingOptionsData) return;

                try {
                    const handlingOptions = JSON.parse(handlingOptionsData);

                    // Sort by handling_point descending
                    handlingOptions.sort((a, b) => b.handling_point - a.handling_point);

                    // Find applicable handling
                    let applicableHandling = null;
                    for (let i = 0; i < handlingOptions.length; i++) {
                        if (verifiedPoints >= handlingOptions[i].handling_point) {
                            applicableHandling = handlingOptions[i];
                            break;
                        }
                    }

                    // Update display
                    if (applicableHandling) {
                        // Show warning card
                        const currentPointsEl = handlingCard.querySelector('.current-points');
                        const actionTextEl = handlingCard.querySelector('.action-text');
                        const thresholdTextEl = handlingCard.querySelector('.threshold-text');

                        if (currentPointsEl) currentPointsEl.textContent = verifiedPoints;
                        if (actionTextEl) actionTextEl.textContent = applicableHandling.handling_action;
                        if (thresholdTextEl) thresholdTextEl.textContent = applicableHandling.handling_point;

                        handlingCard.classList.remove('hidden');
                        statusGoodCard.classList.add('hidden');
                    } else {
                        // Show good status
                        handlingCard.classList.add('hidden');
                        statusGoodCard.classList.remove('hidden');
                    }
                } catch (error) {
                    console.error('Error updating handling action:', error);
                }
            }

            function resetDetailFilters() {
                // Reset filter values
                if (categoryFilter) categoryFilter.value = '';
                if (statusFilter) statusFilter.value = '';

                // Show all rows
                const rows = table.querySelectorAll('.detail-violation-row');
                rows.forEach(row => {
                    row.style.display = '';
                });

                // Update row numbers
                let counter = 1;
                rows.forEach(row => {
                    const rowNumberElement = row.querySelector('.detail-row-number');
                    if (rowNumberElement) {
                        rowNumberElement.textContent = counter++;
                    }
                });

                // Hide no data message
                const tableContainer = table.closest('.overflow-x-auto');
                if (noDataMsg && tableContainer) {
                    noDataMsg.classList.add('hidden');
                    tableContainer.style.display = '';
                }

                // Reset summary to original values
                const totalCountElement = document.getElementById('detailTotalCount');
                const totalPointsElement = document.getElementById('detailTotalPoints');
                const verifiedPointsElement = document.getElementById('detailVerifiedPoints');

                if (totalCountElement) {
                    totalCountElement.textContent = {{ $studentAcademicYear->pRecaps->count() }};
                }
                if (totalPointsElement) {
                    totalPointsElement.textContent =
                        '{{ $studentAcademicYear->pRecaps->sum(function ($pRecap) {return $pRecap->violation->point ?? 0;}) }} Poin';
                }
                if (verifiedPointsElement) {
                    verifiedPointsElement.textContent = '{{ $totalVerifiedPoints }} Poin';
                }

                // Update handling action with original verified points
                updateHandlingAction({{ $totalVerifiedPoints }});
            }
        });
    </script>
@endsection
