@extends('layouts.app')
@section('content')
    <div
        class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu group-data-[sidebar-size=md]:ltr:ml-vertical-menu-md group-data-[sidebar-size=md]:rtl:mr-vertical-menu-md group-data-[sidebar-size=sm]:ltr:ml-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:mr-vertical-menu-sm px-4 pb-[calc(theme('spacing.header')_*_0.8)] pt-[calc(theme('spacing.header')_*_1)] group-data-[layout=horizontal]:mx-auto group-data-[layout=horizontal]:max-w-screen-2xl group-data-[layout=horizontal]:px-0 group-data-[layout=horizontal]:px-3 group-data-[layout=horizontal]:group-data-[navbar=hidden]:pt-[calc(theme('spacing.header')_*_0.9)] group-data-[navbar=bordered]:pt-[calc(theme('spacing.header')_*_1.3)] group-data-[navbar=hidden]:pt-0 group-data-[layout=horizontal]:md:pt-[calc(theme('spacing.header')_*_1.6)] group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:ltr:md:ml-auto group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:rtl:md:mr-auto">
        <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">

            <div class="flex flex-col gap-2 py-4 md:flex-row md:items-center print:hidden">
                <div class="grow">
                    <h5 class="text-16">Recap Point</h5>
                </div>
                <ul class="flex shrink-0 items-center gap-2 text-sm font-normal">
                    <li
                        class="before:font-remix dark:text-zink-200 relative before:absolute before:-top-[3px] before:text-[18px] before:text-slate-400 before:content-['\ea54'] ltr:pr-4 ltr:before:-right-1 rtl:pl-4 rtl:before:-left-1">
                        <a href="#!" class="dark:text-zink-200 text-slate-400">Dashboards</a>
                    </li>
                    <li class="dark:text-zink-100 text-slate-700">
                        Kesiswaan dan BK
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
                                <th>Aksi</th> <!-- TAMBAHAN BARU -->
                                <th>No</th>
                                <th>Total Poin Pelanggaran</th>
                                <th>Nama Lengkap</th>
                                <th>Kelas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recaps as $rec)
                                <tr class="student-row" data-class="{{ $rec->class->name }}"
                                    data-gender="{{ $rec->student->gender }}"
                                    data-points="{{ $rec->violations_sum_point ?? 0 }}">

                                    <!-- TAMBAHAN KOLOM AKSI BARU -->
                                    <td>
                                        <div class="flex gap-2">
                                            <!-- Tombol Detail (mata) -->
                                            <a href="{{ route('kesiswaan-bk.recaps.detail', $rec->id) }}"
                                                class="btn dark:bg-zink-700 flex size-[37.5px] items-center justify-center rounded-full border-slate-500 bg-white p-0 text-slate-500 hover:border-slate-600 hover:bg-slate-600 hover:text-white focus:border-slate-600 focus:bg-slate-600 focus:text-white focus:ring focus:ring-slate-100 active:border-slate-600 active:bg-slate-600 active:text-white active:ring active:ring-slate-100 dark:ring-slate-400/20 dark:hover:bg-slate-500 dark:focus:bg-slate-500"
                                                title="Lihat Detail">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path
                                                        d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0" />
                                                    <circle cx="12" cy="12" r="3" />
                                                </svg>
                                            </a>

                                            <!-- Tombol Konfirmasi (check-check) - HANYA MUNCUL JIKA ADA PENDING -->
                                            @php
                                                $hasPending = $rec->recaps->where('status', 'pending')->count() > 0;
                                            @endphp
                                            @if ($hasPending)
                                                <button data-modal-target="modal-confirm-{{ $rec->id }}" type="button"
                                                    class="btn dark:bg-zink-700 flex size-[37.5px] items-center justify-center rounded-full border-green-500 bg-white p-0 text-green-500 hover:border-green-600 hover:bg-green-600 hover:text-white focus:border-green-600 focus:bg-green-600 focus:text-white focus:ring focus:ring-green-100 active:border-green-600 active:bg-green-600 active:text-white active:ring active:ring-green-100 dark:ring-green-400/20 dark:hover:bg-green-500 dark:focus:bg-green-500"
                                                    title="Konfirmasi Pelanggaran">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M18 6 7 17l-5-5" />
                                                        <path d="m22 10-7.5 7.5L13 16" />
                                                    </svg>
                                                </button>
                                            @endif

                                            <!-- TAMBAHKAN TOMBOL INI SETELAH TOMBOL KONFIRMASI -->
                                            <button data-modal-target="modal-tindakan-{{ $rec->id }}" type="button"
                                                class="btn dark:bg-zink-700 flex size-[37.5px] items-center justify-center rounded-full border-custom-500 bg-white p-0 text-custom-500 hover:border-custom-600 hover:bg-custom-600 hover:text-white">
                                                <i data-lucide="settings" class="size-4"></i>
                                            </button>

                                            <!-- TAMBAHKAN MODAL TINDAKAN INI SETELAH PENUTUP MODAL KONFIRMASI -->
                                            <div id="modal-tindakan-{{ $rec->id }}" modal-center=""
                                                class="fixed flex flex-col hidden transition-all duration-300 ease-in-out left-2/4 z-drawer -translate-x-2/4 -translate-y-2/4 show">
                                                <div
                                                    class="w-screen md:w-[30rem] bg-white shadow rounded-md dark:bg-zink-600 flex flex-col h-full">
                                                    <div
                                                        class="flex items-center justify-between p-4 border-b border-slate-200 dark:border-zink-500">
                                                        <h5 class="text-16">Tindakan - {{ $rec->student->full_name }}</h5>
                                                        <button data-modal-close="modal-tindakan-{{ $rec->id }}"
                                                            class="transition-all duration-200 ease-linear text-slate-500 hover:text-red-500 dark:text-zink-200 dark:hover:text-red-500">
                                                            <i data-lucide="x" class="size-5"></i>
                                                        </button>
                                                    </div>
                                                    <div
                                                        class="max-h-[calc(theme('height.screen')_-_180px)] p-4 overflow-y-auto">
                                                        <form method="POST"
                                                            action="{{ route('kesiswaan-bk.actionConfirm-Recaps', $rec->id) }}">
                                                            @csrf
                                                            <input type="hidden" name="student_academic_year_id"
                                                                value="{{ $rec->id }}">

                                                            <div class="mb-4">
                                                                <label for="tindakanSelect-{{ $rec->id }}"
                                                                    class="inline-block mb-2 text-base font-medium">
                                                                    Pilih Tindakan <span class="text-red-500">*</span>
                                                                </label>
                                                                <select id="tindakanSelect-{{ $rec->id }}"
                                                                    name="handling_id" required
                                                                    class="tindakan-dropdown form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                                                    data-student-id="{{ $rec->id }}">
                                                                    <option value="">Pilih tindakan...</option>
                                                                    @foreach ($rec->available_handlings as $item)
                                                                        <option value="{{ $item->id }}"
                                                                            data-action="{{ e($item->handling_action) }}"
                                                                            data-point="{{ e($item->handling_point) }}">
                                                                            {{ $item->handling_action }} -
                                                                            {{ $item->handling_point }} Poin
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <div id="handlingDetails-{{ $rec->id }}" class="hidden">
                                                                <div class="mb-4">
                                                                    <label
                                                                        class="inline-block mb-2 text-base font-medium">Tindakan
                                                                        Terpilih</label>
                                                                    <input type="text"
                                                                        id="selectedAction-{{ $rec->id }}" readonly
                                                                        class="form-input border-slate-200 dark:border-zink-500 bg-slate-100 dark:bg-zink-600"
                                                                        value="">
                                                                </div>

                                                                <div class="mb-4">
                                                                    <label
                                                                        class="inline-block mb-2 text-base font-medium">Poin
                                                                        Tindakan</label>
                                                                    <input type="text"
                                                                        id="selectedPoint-{{ $rec->id }}" readonly
                                                                        class="form-input border-slate-200 dark:border-zink-500 bg-slate-100 dark:bg-zink-600"
                                                                        value="">
                                                                </div>

                                                                <div class="mb-4">
                                                                    <label for="keterangan-{{ $rec->id }}"
                                                                        class="inline-block mb-2 text-base font-medium">
                                                                        Keterangan
                                                                    </label>
                                                                    <textarea id="keterangan-{{ $rec->id }}" name="description" rows="4"
                                                                        class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                                                        placeholder="Masukkan keterangan tindakan..."></textarea>
                                                                </div>
                                                            </div>

                                                            <div class="flex items-center justify-end gap-2 mt-4">
                                                                <button
                                                                    data-modal-close="modal-tindakan-{{ $rec->id }}"
                                                                    type="button"
                                                                    class="text-slate-500 btn bg-slate-200 border-slate-200 hover:text-slate-600 hover:bg-slate-300 hover:border-slate-300 focus:text-slate-600 focus:bg-slate-300 focus:border-slate-300 focus:ring focus:ring-slate-100 active:text-slate-600 active:bg-slate-300 active:border-slate-300 active:ring active:ring-slate-100 dark:bg-zink-600 dark:hover:bg-zink-500 dark:border-zink-600 dark:hover:border-zink-500 dark:text-zink-200 dark:ring-zink-400/50">
                                                                    Batal
                                                                </button>
                                                                <button type="submit"
                                                                    class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20">
                                                                    Simpan
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="row-number">{{ $loop->iteration }}</td>
                                    <td class="text-red-500">
                                        {{ $rec->violations_sum_point ?? 0 }} Poin
                                    </td>
                                    <td>{{ $rec->student->full_name }}</td>
                                    <td>{{ $rec->class->academic_level }} {{ $rec->class->name }}</td>
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

            @foreach ($recaps as $rec)
                @php
                    $pendingRecaps = $rec->recaps->where('status', 'pending'); // ✅ BENAR! Gunakan nama berbeda
                @endphp

                @if ($pendingRecaps->count() > 0)
                    <!-- Modal Konfirmasi -->
                    <div id="modal-confirm-{{ $rec->id }}" modal-center=""
                        class="z-drawer show fixed left-2/4 top-2/4 flex hidden -translate-x-2/4 -translate-y-2/4 flex-col transition-all duration-300 ease-in-out">
                        <!-- Modal dengan ukuran yang lebih besar -->
                        <div class="modal-container dark:bg-zink-600 flex flex-col rounded-md bg-white shadow">
                            <!-- Header Modal - Fixed -->
                            <div
                                class="modal-header dark:border-zink-500 flex flex-shrink-0 items-center justify-between border-b border-slate-200 p-4">
                                <h5 class="text-16 font-semibold">
                                    Konfirmasi Pelanggaran - {{ $rec->student->full_name }}
                                </h5>
                                <button data-modal-close="modal-confirm-{{ $rec->id }}"
                                    class="dark:text-zink-200 text-slate-500 transition-all duration-200 ease-linear hover:text-red-500 dark:hover:text-red-500">
                                    ✕
                                </button>
                            </div>

                            <!-- Modal Content - Scrollable -->
                            <div class="overflow-y-auto p-4" style="max-height: 70vh">
                                <!-- Table Container -->
                                <div class="mb-4 overflow-x-auto">
                                    <table class="w-full text-left text-sm" id="confirmTable-{{ $rec->id }}">
                                        <thead class="dark:bg-zink-700 bg-slate-50 text-xs uppercase">
                                            <tr>
                                                <th class="px-4 py-3">Aksi</th>
                                                <th class="px-3 py-3">No</th>
                                                <th class="px-4 py-3">Tanggal</th>
                                                <th class="px-4 py-3">Pelanggaran</th>
                                                <th class="px-4 py-3">Kategori</th>
                                                <th class="px-4 py-3">Poin</th>
                                                <th class="px-4 py-3">Status</th>
                                                <th class="px-4 py-3">Dibuat oleh</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $confirmCounter = 1; @endphp
                                            @foreach ($pendingRecaps as $recapItem)
                                                <tr
                                                    class="dark:bg-zink-800 dark:border-zink-700 dark:hover:bg-zink-700 border-b bg-white hover:bg-slate-50">
                                                    <td class="px-4 py-4">
                                                        <div class="flex gap-2">
                                                            <!-- Form untuk verifikasi -->
                                                            <form method="POST"
                                                                action="{{ route('kesiswaan-bk.violation-status.update', $recapItem->id) }}"
                                                                class="inline-block">
                                                                @csrf
                                                                @method('PUT')

                                                                <button type="submit" value="verified" name="status"
                                                                    class="rounded-full p-2 text-green-600 transition-colors duration-200 hover:bg-green-50 hover:text-green-700"
                                                                    title="Verifikasi">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                        height="16" viewBox="0 0 24 24" fill="none"
                                                                        stroke="currentColor" stroke-width="2"
                                                                        stroke-linecap="round" stroke-linejoin="round">
                                                                        <polyline points="20,6 9,17 4,12"></polyline>
                                                                    </svg>
                                                                </button>

                                                                <button type="submit" value="not_verified"
                                                                    name="status"
                                                                    onclick="return confirm('Apakah Anda yakin ingin menolak pelanggaran ini?')"
                                                                    class="rounded-full p-2 text-red-600 transition-colors duration-200 hover:bg-red-50 hover:text-red-700"
                                                                    title="Tolak">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                        height="16" viewBox="0 0 24 24" fill="none"
                                                                        stroke="currentColor" stroke-width="2"
                                                                        stroke-linecap="round" stroke-linejoin="round">
                                                                        <line x1="18" y1="6"
                                                                            x2="6" y2="18"></line>
                                                                        <line x1="6" y1="6"
                                                                            x2="18" y2="18"></line>
                                                                    </svg>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                    <td class="px-3 py-3">{{ $confirmCounter++ }}</td>
                                                    <td class="whitespace-nowrap px-4 py-3">
                                                        {{ \Carbon\Carbon::parse($recapItem->created_at)->format('d/m/Y') }}
                                                    </td>
                                                    <td class="px-4 py-3">{{ $recapItem->violation->name }}</td>
                                                    <td class="px-4 py-3">
                                                        <span
                                                            class="@if (($recapItem->violation->category->name ?? '') === 'Berat') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300
                                                @elseif(($recapItem->violation->category->name ?? '') === 'Sedang') bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300
                                                @else bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300 @endif rounded-full px-2 py-1 text-xs font-medium">
                                                            {{ $recapItem->violation->category->name ?? 'Tidak Diketahui' }}
                                                        </span>
                                                    </td>
                                                    <td class="px-4 py-3">
                                                        <span class="font-semibold text-red-600 dark:text-red-400">
                                                            {{ $recapItem->violation->point ?? 0 }}
                                                        </span>
                                                    </td>
                                                    <td class="px-4 py-3">
                                                        <span
                                                            class="rounded-full bg-orange-100 px-2 py-1 text-xs font-medium text-orange-800 dark:bg-orange-900 dark:text-orange-300">
                                                            Pending
                                                        </span>
                                                    </td>
                                                    <td class="px-4 py-3 text-sm">{{ $recapItem->createdBy->name ?? '-' }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <!-- PAGINATION CONTROLS -->
                                    <div id="paginationControls-{{ $rec->id }}"
                                        class="mt-3 flex items-center justify-between border-t border-slate-200 pt-3 dark:border-zink-500">
                                        <div class="text-sm text-slate-600 dark:text-zink-300">
                                            <span class="page-info">1-5 dari {{ $pendingRecaps->count() }}</span>
                                        </div>

                                        <div class="flex items-center gap-2">
                                            <button
                                                class="pagination-btn first-page rounded px-2 py-1 text-sm transition-colors hover:bg-slate-100 disabled:cursor-not-allowed disabled:opacity-50"
                                                data-action="first" data-student-id="{{ $rec->id }}"
                                                title="Halaman Pertama">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2">
                                                    <polyline points="11 17 6 12 11 7"></polyline>
                                                    <polyline points="18 17 13 12 18 7"></polyline>
                                                </svg>
                                            </button>

                                            <button
                                                class="pagination-btn prev-page rounded px-2 py-1 text-sm transition-colors hover:bg-slate-100 disabled:cursor-not-allowed disabled:opacity-50"
                                                data-action="prev" data-student-id="{{ $rec->id }}"
                                                title="Sebelumnya">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2">
                                                    <polyline points="15 18 9 12 15 6"></polyline>
                                                </svg>
                                            </button>

                                            <span
                                                class="current-page-number rounded bg-slate-100 px-3 py-1 text-sm font-medium dark:bg-zink-600">
                                                Hal 1 dari 1
                                            </span>

                                            <button
                                                class="pagination-btn next-page rounded px-2 py-1 text-sm transition-colors hover:bg-slate-100 disabled:cursor-not-allowed disabled:opacity-50"
                                                data-action="next" data-student-id="{{ $rec->id }}"
                                                title="Selanjutnya">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2">
                                                    <polyline points="9 18 15 12 9 6"></polyline>
                                                </svg>
                                            </button>

                                            <button
                                                class="pagination-btn last-page rounded px-2 py-1 text-sm transition-colors hover:bg-slate-100 disabled:cursor-not-allowed disabled:opacity-50"
                                                data-action="last" data-student-id="{{ $rec->id }}"
                                                title="Halaman Terakhir">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2">
                                                    <polyline points="13 17 18 12 13 7"></polyline>
                                                    <polyline points="6 17 11 12 6 7"></polyline>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <!-- DEBUG: Tambahkan sebelum summary section -->
                                @php
                                    $totalPendingPoints = 0;
                                    foreach ($pendingRecaps as $item) {
                                        if ($item->violation && $item->violation->point) {
                                            $totalPendingPoints += $item->violation->point;
                                        }
                                    }
                                @endphp

                                <!-- HAPUS bagian debug setelah selesai testing -->

                                <!-- Summary Section - HANYA 1 INI SAJA -->
                                <div class="dark:bg-zink-700 rounded-lg bg-slate-50 p-3">
                                    <div class="flex items-center justify-between">
                                        <span class="dark:text-zink-300 text-sm font-medium text-slate-600">
                                            Total Pelanggaran Pending:
                                        </span>
                                        <span class="text-sm font-bold" id="totalCountPending-{{ $rec->id }}">
                                            {{ $pendingRecaps->count() }}
                                        </span>
                                    </div>
                                    <div class="mt-1 flex items-center justify-between">
                                        <span class="dark:text-zink-300 text-sm font-medium text-slate-600">
                                            Total Poin Pending:
                                        </span>
                                        <span class="text-sm font-bold text-orange-600 dark:text-orange-400"
                                            id="totalPointsPending-{{ $rec->id }}">
                                            {{ $totalPendingPoints }}
                                            Poin
                                        </span>
                                    </div>
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

        .pagination-btn:disabled {
            cursor: not-allowed;
            opacity: 0.5;
        }

        .pagination-btn:not(:disabled):hover {
            background-color: rgba(248, 250, 252, 1);
        }

        .dark .pagination-btn:not(:disabled):hover {
            background-color: rgba(63, 63, 70, 1);
        }

        .pagination-btn svg {
            display: inline-block;
        }

        .current-page-number {
            min-width: 100px;
            text-align: center;
        }
    </style>

    <script>
        const ITEMS_PER_PAGE = 5;
        const paginationState = {};

        function initPagination(studentId) {
            if (!paginationState[studentId]) {
                paginationState[studentId] = {
                    currentPage: 1,
                    itemsPerPage: ITEMS_PER_PAGE
                };
            }
        }

        function getVisibleRows(studentId) {
            const table = document.getElementById(`confirmTable-${studentId}`);
            if (!table) return [];

            const rows = Array.from(table.querySelectorAll('tbody tr'));
            return rows.filter(row => row.style.display !== 'none' && !row.classList.contains('no-data-row'));
        }

        function applyPagination(studentId) {
            initPagination(studentId);

            const visibleRows = getVisibleRows(studentId);
            const state = paginationState[studentId];
            const totalPages = Math.ceil(visibleRows.length / state.itemsPerPage);

            // Hide all rows first
            visibleRows.forEach(row => row.classList.add('hidden'));

            // Show only rows for current page
            const startIndex = (state.currentPage - 1) * state.itemsPerPage;
            const endIndex = startIndex + state.itemsPerPage;
            const rowsToShow = visibleRows.slice(startIndex, endIndex);

            rowsToShow.forEach(row => row.classList.remove('hidden'));

            // Update row numbers
            rowsToShow.forEach((row, index) => {
                const rowNumberElement = row.querySelector('td:nth-child(2)');
                if (rowNumberElement) {
                    rowNumberElement.textContent = startIndex + index + 1;
                }
            });

            updatePaginationControls(studentId, totalPages, visibleRows.length);
        }

        function updatePaginationControls(studentId, totalPages, totalItems) {
            const state = paginationState[studentId];
            const container = document.getElementById(`paginationControls-${studentId}`);

            if (!container) return;

            if (totalPages <= 1) {
                container.classList.add('hidden');
                return;
            }

            container.classList.remove('hidden');

            const pageInfo = container.querySelector('.page-info');
            if (pageInfo) {
                const start = (state.currentPage - 1) * state.itemsPerPage + 1;
                const end = Math.min(state.currentPage * state.itemsPerPage, totalItems);
                pageInfo.textContent = `${start}-${end} dari ${totalItems}`;
            }

            const prevBtn = container.querySelector('.prev-page');
            const nextBtn = container.querySelector('.next-page');
            const firstBtn = container.querySelector('.first-page');
            const lastBtn = container.querySelector('.last-page');

            if (prevBtn) prevBtn.disabled = state.currentPage === 1;
            if (nextBtn) nextBtn.disabled = state.currentPage === totalPages;
            if (firstBtn) firstBtn.disabled = state.currentPage === 1;
            if (lastBtn) lastBtn.disabled = state.currentPage === totalPages;

            const pageNumber = container.querySelector('.current-page-number');
            if (pageNumber) {
                pageNumber.textContent = `Hal ${state.currentPage} dari ${totalPages}`;
            }
        }

        function goToPage(studentId, page) {
            const state = paginationState[studentId];
            const visibleRows = getVisibleRows(studentId);
            const totalPages = Math.ceil(visibleRows.length / state.itemsPerPage);

            if (page < 1 || page > totalPages) return;

            state.currentPage = page;
            applyPagination(studentId);
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Main table filters
            const classFilter = document.getElementById('classFilter');
            const pointRangeFilter = document.getElementById('pointRangeFilter');
            const resetMainFilterBtn = document.getElementById('resetMainFilter');

            [classFilter, pointRangeFilter].forEach(filter => {
                if (filter) filter.addEventListener('change', filterMainTable);
            });

            if (resetMainFilterBtn) {
                resetMainFilterBtn.addEventListener('click', resetMainFilters);
            }

            updateFilterInfo();

            // Modal handlers
            document.querySelectorAll('[data-modal-target]').forEach(button => {
                button.addEventListener('click', function() {
                    const modalId = this.getAttribute('data-modal-target');
                    const modal = document.getElementById(modalId);
                    if (modal) {
                        modal.classList.remove('hidden');
                        document.body.style.overflow = 'hidden';

                        const studentId = modalId.replace('modal-confirm-', '');
                        initPagination(studentId);
                        paginationState[studentId].currentPage = 1;

                        setTimeout(() => {
                            applyPagination(studentId);
                        }, 100);
                    }
                });
            });

            // Pagination button handlers
            document.querySelectorAll('.pagination-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const action = this.getAttribute('data-action');
                    const studentId = this.getAttribute('data-student-id');
                    const state = paginationState[studentId];

                    if (!state) return;

                    switch (action) {
                        case 'first':
                            goToPage(studentId, 1);
                            break;
                        case 'prev':
                            goToPage(studentId, state.currentPage - 1);
                            break;
                        case 'next':
                            goToPage(studentId, state.currentPage + 1);
                            break;
                        case 'last':
                            const visibleRows = getVisibleRows(studentId);
                            const totalPages = Math.ceil(visibleRows.length / state.itemsPerPage);
                            goToPage(studentId, totalPages);
                            break;
                    }
                });
            });
        });

        // Main table filter functions (tetap seperti sebelumnya)
        function filterMainTable() {
            const classFilter = document.getElementById('classFilter');
            const pointRangeFilter = document.getElementById('pointRangeFilter');
            const mainTable = document.getElementById('hoverableTable');
            const noMainData = document.getElementById('noMainData');

            const classValue = classFilter ? classFilter.value : '';
            const pointRangeValue = pointRangeFilter ? pointRangeFilter.value : '';
            const rows = mainTable.querySelectorAll('.student-row');
            let visibleRows = 0;

            rows.forEach(row => {
                const rowClass = row.getAttribute('data-class');
                const rowPoints = parseInt(row.getAttribute('data-points')) || 0;
                let showRow = true;

                if (classValue && classValue !== rowClass) showRow = false;

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

                row.style.display = showRow ? '' : 'none';
                if (showRow) visibleRows++;
            });

            updateRowNumbers();

            const tbody = mainTable.querySelector('tbody');
            if (visibleRows === 0) {
                noMainData.classList.remove('hidden');
                tbody.style.display = 'none';
            } else {
                noMainData.classList.add('hidden');
                tbody.style.display = '';
            }

            updateFilterInfo(visibleRows);
        }

        function updateRowNumbers() {
            const mainTable = document.getElementById('hoverableTable');
            const visibleRows = mainTable.querySelectorAll('.student-row:not([style*="display: none"])');
            visibleRows.forEach((row, index) => {
                const rowNumberElement = row.querySelector('.row-number');
                if (rowNumberElement) rowNumberElement.textContent = index + 1;
            });
        }

        function updateFilterInfo(showing = null) {
            const mainTable = document.getElementById('hoverableTable');
            const filterInfo = document.getElementById('filterInfo');
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
            const classFilter = document.getElementById('classFilter');
            const pointRangeFilter = document.getElementById('pointRangeFilter');
            const mainTable = document.getElementById('hoverableTable');
            const noMainData = document.getElementById('noMainData');

            if (classFilter) classFilter.value = '';
            if (pointRangeFilter) pointRangeFilter.value = '';

            mainTable.querySelectorAll('.student-row').forEach(row => row.style.display = '');
            updateRowNumbers();

            noMainData.classList.add('hidden');
            mainTable.querySelector('tbody').style.display = '';
            updateFilterInfo();
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Event listener untuk semua dropdown tindakan
            document.querySelectorAll('.tindakan-dropdown').forEach(function(select) {
                select.addEventListener('change', function() {
                    const studentId = this.getAttribute('data-student-id');
                    const detailsDiv = document.getElementById('handlingDetails-' + studentId);
                    const selectedOption = this.options[this.selectedIndex];

                    if (this.value) {
                        const action = selectedOption.getAttribute('data-action');
                        const point = selectedOption.getAttribute('data-point');

                        document.getElementById('selectedAction-' + studentId).value = action;
                        document.getElementById('selectedPoint-' + studentId).value = point +
                            ' Poin';

                        detailsDiv.classList.remove('hidden');
                    } else {
                        detailsDiv.classList.add('hidden');
                    }
                });
            });
        });
    </script>
@endsection
