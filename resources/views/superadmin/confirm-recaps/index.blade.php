@extends('layouts.app')
@section('content')
    <div
        class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu group-data-[sidebar-size=md]:ltr:ml-vertical-menu-md group-data-[sidebar-size=md]:rtl:mr-vertical-menu-md group-data-[sidebar-size=sm]:ltr:ml-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:mr-vertical-menu-sm px-4 pb-[calc(theme('spacing.header')_*_0.8)] pt-[calc(theme('spacing.header')_*_1)] group-data-[layout=horizontal]:mx-auto group-data-[layout=horizontal]:max-w-screen-2xl group-data-[layout=horizontal]:px-0 group-data-[layout=horizontal]:px-3 group-data-[layout=horizontal]:group-data-[navbar=hidden]:pt-[calc(theme('spacing.header')_*_0.9)] group-data-[navbar=bordered]:pt-[calc(theme('spacing.header')_*_1.3)] group-data-[navbar=hidden]:pt-0 group-data-[layout=horizontal]:md:pt-[calc(theme('spacing.header')_*_1.6)] group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:ltr:md:ml-auto group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:rtl:md:mr-auto">
        <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">

            <div class="flex flex-col gap-2 py-4 md:flex-row md:items-center print:hidden">
                <div class="grow">
                    <h5 class="text-16">Rekap & Konfirmasi Pelanggaran</h5>
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
                        Rekap & Verifikasi Pelanggaran
                    </li>
                </ul>
            </div>

            @if ($studentAcademicYears->isEmpty())
                <!-- Card Empty State -->
                <div class="card">
                    <div class="card-body">
                        <div class="flex flex-col items-center justify-center py-16">
                            <div
                                class="dark:bg-zink-700 mb-6 flex h-24 w-24 items-center justify-center rounded-full bg-slate-100">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" class="dark:text-zink-300 text-slate-500">
                                    <path d="M9 11H3v2h6m-6-6h6v2H3v-2m0 10h6v2H3v-2m8-10v12l4-2 4 2V5h-8z" />
                                </svg>
                            </div>
                            <h5 class="dark:text-zink-100 mb-2 text-xl font-semibold text-slate-700">
                                Belum Ada Data Pelanggaran
                            </h5>
                            <p class="dark:text-zink-400 mb-6 max-w-md text-center text-slate-500">
                                Saat ini belum ada data pelanggaran yang perlu diverifikasi. Data akan muncul ketika ada
                                pelanggaran.
                            </p>
                            <div class="dark:bg-zink-700 rounded-lg bg-blue-50 p-4">
                                <div class="flex items-start gap-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="mt-0.5 flex-shrink-0 text-blue-600 dark:text-blue-400">
                                        <circle cx="12" cy="12" r="10" />
                                        <path d="M12 16v-4" />
                                        <path d="M12 8h.01" />
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-blue-800 dark:text-blue-300">
                                            Informasi
                                        </p>
                                        <p class="mt-1 text-xs text-blue-700 dark:text-blue-400">
                                            Halaman ini akan menampilkan daftar siswa dengan pelanggaran yang memerlukan
                                            verifikasi dari Super Admin.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Filter Section -->
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
                                        $groupedClasses = $studentAcademicYears
                                            ->pluck('class')
                                            ->unique('id')
                                            ->groupBy('academic_level')
                                            ->sortKeys();
                                    @endphp
                                    @foreach ($groupedClasses as $level => $classes)
                                        @foreach ($classes->sortBy('name') as $class)
                                            <option value="{{ $level }} {{ $class->name }}">
                                                {{ $level }} {{ $class->name }}
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
                                    <option value="Laki-laki">Laki-laki</option>
                                    <option value="Perempuan">Perempuan</option>
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
                        <h6 class="text-15 mb-4">Datatable Konfirmasi Pelanggaran</h6>

                        <!-- Info hasil filter -->
                        <div id="filterInfo" class="dark:text-zink-300 mb-3 hidden text-sm text-slate-600">
                            <span id="showingCount">0</span> dari <span id="totalCount">0</span> data ditampilkan
                        </div>

                        <table id="hoverableTable" style="width: 100%" class="hover group">
                            <thead>
                                <tr>
                                    <th>Aksi</th>
                                    <th>No</th>
                                    <th>NIS</th>
                                    <th>Nama Lengkap</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Kelas</th>
                                    <th>Total Poin</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($studentAcademicYears as $student)
                                    <tr class="student-row"
                                        data-class="{{ $student->class->academic_level }} {{ $student->class->name }}"
                                        data-gender="{{ $student->student->gender }}">
                                        <td>
                                            <div class="flex gap-2">
                                                <a href="{{ route('superadmin.detailConfirm-Recaps', $student->id) }}"
                                                    class="btn dark:bg-zink-700 flex size-[37.5px] items-center justify-center rounded-full border-slate-500 bg-white p-0 text-slate-500 hover:border-slate-600 hover:bg-slate-600 hover:text-white">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path
                                                            d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0" />
                                                        <circle cx="12" cy="12" r="3" />
                                                    </svg>
                                                </a>
                                                <button data-modal-target="modal-{{ $student->id }}" type="button"
                                                    class="btn dark:bg-zink-700 flex size-[37.5px] items-center justify-center rounded-full border-green-500 bg-white p-0 text-green-500 hover:border-green-600 hover:bg-green-600 hover:text-white">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M18 6 7 17l-5-5" />
                                                        <path d="m22 10-7.5 7.5L13 16" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $student->student->student_number }}</td>
                                        <td>{{ $student->student->full_name }}</td>
                                        <td>{{ $student->student->gender }}</td>
                                        <td>{{ $student->class->academic_level }} {{ $student->class->name }}</td>
                                        <td>
                                            <span class="whitespace-nowrap font-semibold text-red-600 dark:text-red-400">
                                                {{ $student->total_points_verified }} Poin
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <!-- Pesan jika tidak ada data setelah filter -->
                        <div id="noMainData" class="hidden py-8 text-center">
                            <div class="dark:text-zink-400 flex flex-col items-center text-slate-500">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" class="mb-2">
                                    <circle cx="11" cy="11" r="8"></circle>
                                    <path d="m21 21-4.35-4.35"></path>
                                </svg>
                                <p class="text-sm">Tidak ada data yang sesuai dengan filter</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal untuk setiap siswa (Konfirmasi) -->
                @foreach ($studentAcademicYears as $student)
                    @if ($student->recaps->count() > 0)
                        <!-- Modal Container -->
                        <div id="modal-{{ $student->id }}" modal-center=""
                            class="z-drawer show fixed left-2/4 top-2/4 flex hidden -translate-x-2/4 -translate-y-2/4 flex-col transition-all duration-300 ease-in-out">
                            <div class="modal-container dark:bg-zink-600 flex flex-col rounded-md bg-white shadow">

                                <!-- Header Modal -->
                                <div
                                    class="modal-header dark:border-zink-500 flex flex-shrink-0 items-center justify-between border-b border-slate-200 p-4">
                                    <h5 class="text-16 font-semibold">Daftar Pelanggaran -
                                        {{ $student->student->full_name }}</h5>
                                    <button data-modal-close="modal-{{ $student->id }}"
                                        class="dark:text-zink-200 text-slate-500 transition-all duration-200 ease-linear hover:text-red-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <line x1="18" y1="6" x2="6" y2="18"></line>
                                            <line x1="6" y1="6" x2="18" y2="18"></line>
                                        </svg>
                                    </button>
                                </div>

                                <!-- Content Modal -->
                                <div class="modal-content flex-1 overflow-hidden">
                                    <div class="flex h-full flex-col p-4">

                                        <!-- Filter Section (opsional - jika ada) -->
                                        <div class="filter-section mb-3 flex gap-2">
                                            <select id="categoryFilter-{{ $student->id }}"
                                                class="category-filter rounded border px-3 py-2"
                                                data-student-id="{{ $student->id }}">
                                                <option value="">Semua Kategori</option>
                                                <option value="Ringan">Ringan</option>
                                                <option value="Sedang">Sedang</option>
                                                <option value="Berat">Berat</option>
                                            </select>

                                            <button
                                                class="reset-filter-btn rounded bg-slate-200 px-4 py-2 hover:bg-slate-300"
                                                data-student-id="{{ $student->id }}">
                                                Reset Filter
                                            </button>
                                        </div>

                                        <div
                                            class="table-container dark:border-zink-500 flex-1 overflow-hidden rounded-lg border border-slate-200">
                                            <div class="table-scroll-wrapper h-full overflow-auto">
                                                <table class="table-violations w-full text-left text-sm"
                                                    id="violationsTable-{{ $student->id }}">
                                                    <thead
                                                        class="dark:bg-zink-700 sticky top-0 z-10 bg-slate-50 text-xs uppercase">
                                                        <tr>
                                                            <th class="dark:text-zink-200 w-32 px-4 py-3 font-semibold">
                                                                Aksi</th>
                                                            <th class="dark:text-zink-200 w-10 px-3 py-3 font-semibold">No
                                                            </th>
                                                            <th class="dark:text-zink-200 w-24 px-4 py-3 font-semibold">
                                                                Tanggal</th>
                                                            <th
                                                                class="dark:text-zink-200 min-w-[180px] px-4 py-3 font-semibold">
                                                                Pelanggaran</th>
                                                            <th class="dark:text-zink-200 w-20 px-4 py-3 font-semibold">
                                                                Kategori</th>
                                                            <th class="dark:text-zink-200 w-16 px-4 py-3 font-semibold">
                                                                Poin</th>
                                                            <th class="dark:text-zink-200 w-20 px-4 py-3 font-semibold">
                                                                Status</th>
                                                            <th class="dark:text-zink-200 w-24 px-4 py-3 font-semibold">
                                                                Dibuat oleh</th>
                                                            <th class="dark:text-zink-200 w-24 px-4 py-3 font-semibold">
                                                                Diverifikasi</th>
                                                            <th class="dark:text-zink-200 w-24 px-4 py-3 font-semibold">
                                                                Diupdate</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php $counter = 1; @endphp
                                                        @forelse ($student->recaps->whereIn('status', ['pending', 'verified']) as $recap)
                                                            <tr class="violation-row dark:bg-zink-800 dark:border-zink-700 dark:hover:bg-zink-700 border-b bg-white hover:bg-slate-50"
                                                                data-category="{{ $recap->violation->category->name ?? '' }}">

                                                                <!-- KOLOM AKSI -->
                                                                <td class="px-3 py-2">
                                                                    <div class="flex gap-1">
                                                                        <!-- Form untuk verifikasi -->
                                                                        <form method="POST"
                                                                            action="{{ route('superadmin.violation-status.update', $recap->id) }}"
                                                                            class="inline-block">
                                                                            @csrf
                                                                            @method('PUT')
                                                                            @if ($recap->status == 'pending')
                                                                                <button type="submit" value="verified"
                                                                                    name="status"
                                                                                    class="rounded-full p-1.5 text-green-600 transition-colors duration-200 hover:bg-green-50 hover:text-green-700"
                                                                                    title="Verifikasi">
                                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                                        width="14" height="14"
                                                                                        viewBox="0 0 24 24" fill="none"
                                                                                        stroke="currentColor"
                                                                                        stroke-width="2"
                                                                                        stroke-linecap="round"
                                                                                        stroke-linejoin="round">
                                                                                        <polyline points="20,6 9,17 4,12">
                                                                                        </polyline>
                                                                                    </svg>
                                                                                </button>
                                                                                <button type="submit"
                                                                                    value="not_verified" name="status"
                                                                                    onclick="return confirm('Apakah Anda yakin ingin menolak pelanggaran ini?')"
                                                                                    class="rounded-full p-1.5 text-red-600 transition-colors duration-200 hover:bg-red-50 hover:text-red-700"
                                                                                    title="Tolak">
                                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                                        width="14" height="14"
                                                                                        viewBox="0 0 24 24" fill="none"
                                                                                        stroke="currentColor"
                                                                                        stroke-width="2"
                                                                                        stroke-linecap="round"
                                                                                        stroke-linejoin="round">
                                                                                        <line x1="18"
                                                                                            y1="6" x2="6"
                                                                                            y2="18"></line>
                                                                                        <line x1="6"
                                                                                            y1="6" x2="18"
                                                                                            y2="18"></line>
                                                                                    </svg>
                                                                                </button>
                                                                            @else
                                                                                <button type="submit" value="pending"
                                                                                    name="status"
                                                                                    onclick="return confirm('Apakah Anda yakin ingin memverifikasi ulang pelanggaran ini?')"
                                                                                    class="rounded-full p-1.5 text-orange-600 transition-colors duration-200 hover:bg-orange-50 hover:text-orange-700"
                                                                                    title="Verifikasi Ulang">
                                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                                        width="14" height="14"
                                                                                        viewBox="0 0 24 24" fill="none"
                                                                                        stroke="currentColor"
                                                                                        stroke-width="2"
                                                                                        stroke-linecap="round"
                                                                                        stroke-linejoin="round">
                                                                                        <path d="m17 2 4 4-4 4" />
                                                                                        <path
                                                                                            d="M3 11v-1a4 4 0 0 1 4-4h14" />
                                                                                        <path d="m7 22-4-4 4-4" />
                                                                                        <path
                                                                                            d="M21 13v1a4 4 0 0 1-4 4H3" />
                                                                                    </svg>
                                                                                </button>
                                                                            @endif
                                                                        </form>

                                                                        @if ($recap->status == 'pending')
                                                                            <form method="POST"
                                                                                action="{{ route('superadmin.recaps.destroy', $recap->id) }}"
                                                                                class="inline-block">
                                                                                @csrf
                                                                                @method('DELETE')
                                                                                <button type="submit"
                                                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus pelanggaran ini?')"
                                                                                    class="rounded-full p-1.5 text-red-600 transition-colors duration-200 hover:bg-red-50 hover:text-red-700"
                                                                                    title="Hapus">
                                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                                        width="14" height="14"
                                                                                        viewBox="0 0 24 24" fill="none"
                                                                                        stroke="currentColor"
                                                                                        stroke-width="2"
                                                                                        stroke-linecap="round"
                                                                                        stroke-linejoin="round">
                                                                                        <path d="M3 6h18" />
                                                                                        <path
                                                                                            d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" />
                                                                                        <path d="M10 11v6" />
                                                                                        <path d="M14 11v6" />
                                                                                        <path
                                                                                            d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2" />
                                                                                    </svg>
                                                                                </button>
                                                                            </form>
                                                                        @endif
                                                                    </div>
                                                                </td>

                                                                <!-- NO -->
                                                                <td class="row-number px-3 py-2 text-center font-medium">
                                                                    {{ $counter++ }}</td>

                                                                <!-- TANGGAL -->
                                                                <td class="whitespace-nowrap px-3 py-2">
                                                                    {{ \Carbon\Carbon::parse($recap->created_at)->format('d/m/Y') }}
                                                                </td>

                                                                <!-- PELANGGARAN -->
                                                                <td class="px-3 py-2">
                                                                    <div class="violation-name">
                                                                        {{ $recap->violation->name }}</div>
                                                                </td>

                                                                <!-- KATEGORI -->
                                                                <td class="px-3 py-2 text-center">
                                                                    @if (($recap->violation->category->name ?? '') === 'Berat')
                                                                        <span
                                                                            class="rounded-full bg-red-100 px-2 py-1 text-xs font-medium text-red-800 dark:bg-red-900 dark:text-red-300">
                                                                            Berat
                                                                        </span>
                                                                    @elseif(($recap->violation->category->name ?? '') === 'Sedang')
                                                                        <span
                                                                            class="rounded-full bg-orange-100 px-2 py-1 text-xs font-medium text-orange-800 dark:bg-orange-900 dark:text-orange-300">
                                                                            Sedang
                                                                        </span>
                                                                    @else
                                                                        <span
                                                                            class="rounded-full bg-yellow-100 px-2 py-1 text-xs font-medium text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">
                                                                            Ringan
                                                                        </span>
                                                                    @endif
                                                                </td>

                                                                <!-- POIN -->
                                                                <td class="px-3 py-2 text-center">
                                                                    <span
                                                                        class="font-semibold text-red-600 dark:text-red-400">
                                                                        {{ $recap->violation->point ?? 0 }}
                                                                    </span>
                                                                </td>

                                                                <!-- STATUS -->
                                                                <td class="px-3 py-2 text-center">
                                                                    @if ($recap->status === 'verified')
                                                                        <span
                                                                            class="rounded-full bg-green-100 px-2 py-1 text-xs font-medium text-green-800 dark:bg-green-900 dark:text-green-300">
                                                                            Terverifikasi
                                                                        </span>
                                                                    @elseif($recap->status === 'not_verified')
                                                                        <span
                                                                            class="rounded-full bg-red-100 px-2 py-1 text-xs font-medium text-red-800 dark:bg-red-900 dark:text-red-300">
                                                                            Tidak Terverifikasi
                                                                        </span>
                                                                    @else
                                                                        <span
                                                                            class="rounded-full bg-orange-100 px-2 py-1 text-xs font-medium text-orange-800 dark:bg-orange-900 dark:text-orange-300">
                                                                            Pending
                                                                        </span>
                                                                    @endif
                                                                </td>

                                                                <!-- DIBUAT OLEH -->
                                                                <td class="px-3 py-2">
                                                                    <span
                                                                        class="text-xs text-slate-600 dark:text-zink-300">
                                                                        {{ $recap->createdBy->name ?? '-' }}
                                                                    </span>
                                                                </td>

                                                                <!-- DIVERIFIKASI OLEH -->
                                                                <td class="px-3 py-2">
                                                                    <span
                                                                        class="text-xs text-slate-600 dark:text-zink-300">
                                                                        {{ $recap->verifiedBy->name ?? '-' }}
                                                                    </span>
                                                                </td>

                                                                <!-- DIUPDATE OLEH -->
                                                                <td class="px-3 py-2">
                                                                    <span
                                                                        class="text-xs text-slate-600 dark:text-zink-300">
                                                                        {{ $recap->updatedBy->name ?? '-' }}
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                        @empty
                                                            <tr class="dark:bg-zink-800 no-data-row bg-white">
                                                                <td colspan="10"
                                                                    class="dark:text-zink-400 px-4 py-8 text-center text-slate-500">
                                                                    <div class="flex flex-col items-center">
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            width="48" height="48"
                                                                            viewBox="0 0 24 24" fill="none"
                                                                            stroke="currentColor" stroke-width="1.5"
                                                                            stroke-linecap="round" stroke-linejoin="round"
                                                                            class="mb-2">
                                                                            <circle cx="12" cy="12" r="10">
                                                                            </circle>
                                                                            <path d="M12 6v6l4 2"></path>
                                                                        </svg>
                                                                        <p class="text-sm">Tidak ada data pelanggaran untuk
                                                                            siswa ini</p>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>


                                        <!-- No Data Message -->
                                        <div id="noFilteredData-{{ $student->id }}" class="hidden py-8 text-center">
                                            <div class="dark:text-zink-400 flex flex-col items-center text-slate-500">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="1.5">
                                                    <circle cx="11" cy="11" r="8"></circle>
                                                    <path d="m21 21-4.35-4.35"></path>
                                                </svg>
                                                <p class="text-sm">Tidak ada data yang sesuai dengan filter</p>
                                            </div>
                                        </div>

                                        <!-- PAGINATION CONTROLS - TAMBAHKAN DI SINI -->
                                        <div id="paginationControls-{{ $student->id }}"
                                            class="mt-3 flex items-center justify-between border-t border-slate-200 pt-3 dark:border-zink-500">
                                            <div class="text-sm text-slate-600 dark:text-zink-300">
                                                <span class="page-info">1-10 dari 50</span>
                                            </div>

                                            <div class="flex items-center gap-2">
                                                <button
                                                    class="pagination-btn first-page rounded px-2 py-1 text-sm transition-colors hover:bg-slate-100 disabled:cursor-not-allowed disabled:opacity-50"
                                                    data-action="first" data-student-id="{{ $student->id }}"
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
                                                    data-action="prev" data-student-id="{{ $student->id }}"
                                                    title="Sebelumnya">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2">
                                                        <polyline points="15 18 9 12 15 6"></polyline>
                                                    </svg>
                                                </button>

                                                <span
                                                    class="current-page-number rounded bg-slate-100 px-3 py-1 text-sm font-medium dark:bg-zink-600">
                                                    Hal 1 dari 5
                                                </span>

                                                <button
                                                    class="pagination-btn next-page rounded px-2 py-1 text-sm transition-colors hover:bg-slate-100 disabled:cursor-not-allowed disabled:opacity-50"
                                                    data-action="next" data-student-id="{{ $student->id }}"
                                                    title="Selanjutnya">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2">
                                                        <polyline points="9 18 15 12 9 6"></polyline>
                                                    </svg>
                                                </button>

                                                <button
                                                    class="pagination-btn last-page rounded px-2 py-1 text-sm transition-colors hover:bg-slate-100 disabled:cursor-not-allowed disabled:opacity-50"
                                                    data-action="last" data-student-id="{{ $student->id }}"
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

                                        <!-- Summary Section -->
                                        @if ($student->recaps->count() > 0)
                                            <div class="summary-section dark:bg-zink-700 mt-4 flex-shrink-0 rounded-lg bg-slate-50 p-3"
                                                id="summary-{{ $student->id }}">
                                                <div class="flex items-center justify-between">
                                                    <span
                                                        class="dark:text-zink-300 text-sm font-medium text-slate-600">Total
                                                        Pelanggaran:</span>
                                                    <span class="text-sm font-bold" id="totalCount-{{ $student->id }}">
                                                        {{ $student->recaps->count() }}
                                                    </span>
                                                </div>
                                                <div class="mt-1 flex items-center justify-between">
                                                    <span
                                                        class="dark:text-zink-300 text-sm font-medium text-slate-600">Total
                                                        Poin:</span>
                                                    <span class="text-sm font-bold text-orange-600"
                                                        id="totalPoints-{{ $student->id }}">
                                                        {{ $student->recaps->sum(fn($r) => $r->violation->point ?? 0) }}
                                                        Poin
                                                    </span>
                                                </div>
                                            </div>
                                        @endif

                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            @endif

        </div>
        <!-- container-fluid -->
    </div>

    <style>
        /* Modal dengan ukuran tetap */
        .modal-container {
            width: 90vw;
            max-width: 1200px;
            height: 90vh;
            max-height: 850px;
            min-height: 600px;
        }

        /* Responsive untuk mobile */
        @media (max-width: 768px) {
            .modal-container {
                width: 95vw;
                height: 95vh;
                max-height: none;
                min-height: 500px;
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

        /* Content area dengan scroll */
        .modal-content {
            min-height: 0;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: rgba(156, 163, 175, 0.5) transparent;
        }

        .modal-content::-webkit-scrollbar {
            width: 8px;
        }

        .modal-content::-webkit-scrollbar-track {
            background: transparent;
        }

        .modal-content::-webkit-scrollbar-thumb {
            background-color: rgba(156, 163, 175, 0.5);
            border-radius: 4px;
        }

        .modal-content::-webkit-scrollbar-thumb:hover {
            background-color: rgba(156, 163, 175, 0.7);
        }

        /* Filter section tetap di atas */
        .filter-section {
            background-color: inherit;
            z-index: 15;
        }

        /* Container table dengan tinggi tetap */
        .table-container {
            background-color: white;
            height: 400px;
            max-height: 400px;
        }

        /* Scroll wrapper untuk table */
        .table-scroll-wrapper {
            height: 100%;
            overflow: auto;
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
        .table-violations,
        .table-detail-violations {
            border-collapse: separate;
            border-spacing: 0;
        }

        .table-violations th,
        .table-detail-violations th {
            background-color: rgb(248, 250, 252);
            position: sticky;
            top: 0;
            z-index: 10;
            border-bottom: 1px solid rgb(226, 232, 240);
        }

        .dark .table-violations th,
        .dark .table-detail-violations th {
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

        /* Summary section bisa di-scroll */
        .summary-section {
            background-color: inherit;
        }

        /* Filter dan reset button styling */
        .category-filter,
        .detail-category-filter,
        .detail-status-filter {
            transition: all 0.2s ease-in-out;
        }

        .category-filter:focus,
        .detail-category-filter:focus,
        .detail-status-filter:focus {
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

        /* Action buttons styling */
        .action-button {
            transition: all 0.2s ease-in-out;
        }

        .action-button:hover {
            transform: scale(1.1);
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

        /* Styling khusus untuk kolom aksi */
        .table-violations td:first-child {
            width: 120px;
            padding: 8px !important;
        }

        /* Button action lebih compact */
        .table-violations button {
            padding: 6px !important;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .table-violations button svg {
            width: 14px;
            height: 14px;
        }

        /* Gap antar button lebih kecil */
        .table-violations .flex.gap-1 {
            gap: 4px;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Main table filter functionality
            const classFilter = document.getElementById('classFilter');
            const genderFilter = document.getElementById('genderFilter');
            const resetMainFilterBtn = document.getElementById('resetMainFilter');
            const filterInfo = document.getElementById('filterInfo');
            const noMainData = document.getElementById('noMainData');
            const mainTable = document.getElementById('hoverableTable');

            // Add event listeners for main table filters
            [classFilter, genderFilter].forEach(filter => {
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

                const rows = mainTable.querySelectorAll('.student-row');
                let visibleRows = 0;

                rows.forEach(row => {
                    const rowClass = row.getAttribute('data-class');
                    const rowGender = row.getAttribute('data-gender');

                    let showRow = true;

                    // Filter by class
                    if (classValue && classValue !== rowClass) {
                        showRow = false;
                    }

                    // Filter by gender
                    if (genderValue && genderValue !== rowGender) {
                        showRow = false;
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

            // Filter functionality for confirmation modal tables
            document.querySelectorAll('.category-filter').forEach(filter => {
                filter.addEventListener('change', function() {
                    const studentId = this.getAttribute('data-student-id');
                    filterTable(studentId);
                });
            });

            // Filter functionality for detail modal tables
            document.querySelectorAll('.detail-category-filter').forEach(filter => {
                filter.addEventListener('change', function() {
                    const studentId = this.getAttribute('data-student-id');
                    filterDetailTable(studentId);
                });
            });

            document.querySelectorAll('.detail-status-filter').forEach(filter => {
                filter.addEventListener('change', function() {
                    const studentId = this.getAttribute('data-student-id');
                    filterDetailTable(studentId);
                });
            });

            // Reset filter functionality for confirmation modals
            document.querySelectorAll('.reset-filter-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const studentId = this.getAttribute('data-student-id');
                    clearFilters(studentId);
                });
            });

            // Reset filter functionality for detail modals
            document.querySelectorAll('.reset-detail-filter-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const studentId = this.getAttribute('data-student-id');
                    clearDetailFilters(studentId);
                });
            });
        });

        function updateHandlingAction(studentId, verifiedPoints) {
            const summarySection = document.getElementById(`detailSummary-${studentId}`);
            if (!summarySection) return;

            const handlingCard = summarySection.querySelector('.handling-action-card');
            const statusGoodCard = summarySection.querySelector('.status-good-card');
            if (!handlingCard || !statusGoodCard) return;

            // Get handling options from data attribute
            const handlingOptionsData = handlingCard.getAttribute('data-handling-options');
            if (!handlingOptionsData) return;

            const handlingOptions = JSON.parse(handlingOptionsData);

            // Find applicable handling (sort descending)
            let applicableHandling = null;
            for (let i = handlingOptions.length - 1; i >= 0; i--) {
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
        }

        // Filter function for confirmation modal (pending violations)
        function filterTable(studentId) {
            const categoryFilter = document.getElementById(`categoryFilter-${studentId}`);

            if (!categoryFilter) return;

            const categoryValue = categoryFilter.value;
            const table = document.getElementById(`violationsTable-${studentId}`);
            const rows = table.querySelectorAll('.violation-row');
            const noDataMsg = document.getElementById(`noFilteredData-${studentId}`);
            const tableContainer = table.closest('.table-container');

            let visibleRows = 0;
            let totalPoints = 0;

            rows.forEach(row => {
                const rowCategory = row.getAttribute('data-category');
                let showRow = true;

                // Filter by category
                if (categoryValue && categoryValue !== rowCategory) {
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

        // Filter function for detail modal (all violations)
        function filterDetailTable(studentId) {
            const categoryFilter = document.getElementById(`detailCategoryFilter-${studentId}`);
            const statusFilter = document.getElementById(`detailStatusFilter-${studentId}`);

            if (!categoryFilter || !statusFilter) return;

            const categoryValue = categoryFilter.value;
            const statusValue = statusFilter.value;
            const table = document.getElementById(`detailViolationsTable-${studentId}`);
            const rows = table.querySelectorAll('.detail-violation-row');
            const noDataMsg = document.getElementById(`noDetailFilteredData-${studentId}`);
            const tableContainer = table.closest('.table-container');

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
                    const pointsElement = row.querySelector('.font-semibold.text-red-600, .text-red-600');
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
            const totalCountElement = document.getElementById(`detailTotalCount-${studentId}`);
            const totalPointsElement = document.getElementById(`detailTotalPoints-${studentId}`);
            const verifiedPointsElement = document.getElementById(`detailVerifiedPoints-${studentId}`);

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
            updateHandlingAction(studentId, verifiedPoints);
        }

        // Function to update handling action based on verified points
        function updateHandlingAction(studentId, verifiedPoints) {
            const summarySection = document.getElementById(`detailSummary-${studentId}`);
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

        // Clear filters for confirmation modal
        function clearFilters(studentId) {
            // Reset filter values
            const categoryFilter = document.getElementById('categoryFilter-' + studentId);

            if (categoryFilter) {
                categoryFilter.value = '';
            }

            // Call filterTable to apply the reset
            filterTable(studentId);
        }

        // Clear filters for detail modal
        function clearDetailFilters(studentId) {
            // Reset filter values
            const categoryFilter = document.getElementById('detailCategoryFilter-' + studentId);
            const statusFilter = document.getElementById('detailStatusFilter-' + studentId);

            if (categoryFilter) {
                categoryFilter.value = '';
            }
            if (statusFilter) {
                statusFilter.value = '';
            }

            // Call filterDetailTable to apply the reset
            filterDetailTable(studentId);
        }
    </script>
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
            const table = document.getElementById(`violationsTable-${studentId}`);
            if (!table) return [];

            const rows = Array.from(table.querySelectorAll('.violation-row'));
            return rows.filter(row => row.style.display !== 'none');
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
                const rowNumberElement = row.querySelector('.row-number');
                if (rowNumberElement) {
                    rowNumberElement.textContent = startIndex + index + 1;
                }
            });

            updatePaginationControls(studentId, totalPages, visibleRows.length);
            updateSummaryWithPagination(studentId, visibleRows);
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

        function updateSummaryWithPagination(studentId, visibleRows) {
            let totalPoints = 0;

            visibleRows.forEach(row => {
                const pointsElement = row.querySelector('.font-semibold.text-red-600, .text-red-600');
                if (pointsElement) {
                    const pointsText = pointsElement.textContent;
                    const pointsMatch = pointsText.match(/(\d+)/);
                    const points = pointsMatch ? parseInt(pointsMatch[1]) : 0;
                    totalPoints += points;
                }
            });

            const totalCountElement = document.getElementById(`totalCount-${studentId}`);
            const totalPointsElement = document.getElementById(`totalPoints-${studentId}`);

            if (totalCountElement) {
                totalCountElement.textContent = visibleRows.length;
            }
            if (totalPointsElement) {
                totalPointsElement.textContent = `${totalPoints} Poin`;
            }
        }

        function goToPage(studentId, page) {
            const state = paginationState[studentId];
            const visibleRows = getVisibleRows(studentId);
            const totalPages = Math.ceil(visibleRows.length / state.itemsPerPage);

            if (page < 1 || page > totalPages) return;

            state.currentPage = page;
            applyPagination(studentId);

            const tableWrapper = document.querySelector(`#violationsTable-${studentId}`).closest('.table-scroll-wrapper');
            if (tableWrapper) {
                tableWrapper.scrollTop = 0;
            }
        }

        // FILTER FUNCTION WITH PAGINATION
        function filterTable(studentId) {
            const categoryFilter = document.getElementById(`categoryFilter-${studentId}`);
            if (!categoryFilter) return;

            const categoryValue = categoryFilter.value;
            const table = document.getElementById(`violationsTable-${studentId}`);
            const rows = table.querySelectorAll('.violation-row');
            const noDataMsg = document.getElementById(`noFilteredData-${studentId}`);
            const tableContainer = table.closest('.table-container');

            let visibleRows = 0;

            rows.forEach(row => {
                const rowCategory = row.getAttribute('data-category');
                let showRow = true;

                if (categoryValue && categoryValue !== rowCategory) {
                    showRow = false;
                }

                if (showRow) {
                    row.style.display = '';
                    visibleRows++;
                } else {
                    row.style.display = 'none';
                }
            });

            if (noDataMsg && tableContainer) {
                if (visibleRows === 0) {
                    noDataMsg.classList.remove('hidden');
                    tableContainer.style.display = 'none';
                    const paginationControls = document.getElementById(`paginationControls-${studentId}`);
                    if (paginationControls) paginationControls.classList.add('hidden');
                } else {
                    noDataMsg.classList.add('hidden');
                    tableContainer.style.display = '';
                }
            }

            // Reset to page 1 and apply pagination
            if (paginationState[studentId]) {
                paginationState[studentId].currentPage = 1;
            }
            applyPagination(studentId);
        }

        function clearFilters(studentId) {
            const categoryFilter = document.getElementById('categoryFilter-' + studentId);
            if (categoryFilter) {
                categoryFilter.value = '';
            }
            filterTable(studentId);
        }

        // INITIALIZE ON PAGE LOAD
        document.addEventListener('DOMContentLoaded', function() {
            // Modal open handler
            document.querySelectorAll('[data-modal-target]').forEach(button => {
                button.addEventListener('click', function() {
                    const modalId = this.getAttribute('data-modal-target');
                    const studentId = modalId.replace('modal-', '');

                    initPagination(studentId);
                    paginationState[studentId].currentPage = 1;

                    setTimeout(() => {
                        applyPagination(studentId);
                    }, 100);
                });
            });

            // Filter change handler
            document.querySelectorAll('.category-filter').forEach(filter => {
                filter.addEventListener('change', function() {
                    const studentId = this.getAttribute('data-student-id');
                    filterTable(studentId);
                });
            });

            // Reset filter handler
            document.querySelectorAll('.reset-filter-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const studentId = this.getAttribute('data-student-id');
                    clearFilters(studentId);
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
    </script>
@endsection
