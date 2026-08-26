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

            @if ($activeStudents->isEmpty() && $historyStudents->isEmpty())
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
                                        $groupedClasses = $activeStudents->concat($historyStudents)
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
                        <!-- Navigation Tabs -->
                        <div class="mb-5 flex border-b border-slate-200 dark:border-zink-500 print:hidden">
                            <button type="button" id="tabActiveBtn" onclick="switchTab('active')"
                                class="tab-btn px-5 py-3 text-sm font-semibold border-b-2 border-custom-500 text-custom-500 dark:text-custom-400 dark:border-custom-400">
                                Perlu Tindakan / Aktif ({{ $activeStudents->count() }})
                            </button>
                            <button type="button" id="tabHistoryBtn" onclick="switchTab('history')"
                                class="tab-btn px-5 py-3 text-sm font-semibold border-b-2 border-transparent text-slate-500 hover:text-slate-700 dark:text-zink-200 dark:hover:text-zink-50">
                                Riwayat Tindakan ({{ $historyStudents->count() }})
                            </button>
                        </div>

                        {{-- Validation / flash messages for the table area --}}
                        <div id="hoverableTableAlerts">
                            @if ($errors->any())
                                <div class="mb-4 rounded-md bg-red-50 p-3 text-sm text-red-700" role="alert">
                                    <strong class="font-semibold">Terjadi kesalahan:</strong>
                                    <ul class="mt-1 list-inside list-disc">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if (session('error'))
                                <div class="mb-4 rounded-md bg-red-50 p-3 text-sm text-red-700" role="alert">
                                    {{ session('error') }}
                                </div>
                            @endif

                            @if (session('success'))
                                <div class="mb-4 rounded-md bg-green-50 p-3 text-sm text-green-700" role="status">
                                    {{ session('success') }}
                                </div>
                            @endif
                        </div>

                        <div id="tabActiveContent" class="tab-pane">
                            <h6 class="text-15 mb-4">Daftar Pelanggaran Aktif</h6>

                            <!-- Info hasil filter -->
                            <div id="filterInfo" class="dark:text-zink-300 mb-3 hidden text-sm text-slate-600">
                                <span id="showingCount">0</span> dari <span id="totalCount">0</span> data ditampilkan
                            </div>

                            <table id="hoverableTable" style="width: 100%" class="hover group">
                                <thead>
                                    <tr>
                                        <th>Aksi</th>
                                        <th>NIS</th>
                                        <th>Nama Lengkap</th>
                                        <th>Jenis Kelamin</th>
                                        <th>Kelas</th>
                                        <th>Total Poin Terverifikasi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($activeStudents as $student)
                                        <tr class="student-row"
                                        data-class="{{ $student->class->academic_level }} {{ $student->class->name }}"
                                        data-gender="{{ $student->student->gender }}">
                                        <td>
                                            <div class="flex gap-2">
                                                <a href="{{ route('superadmin.detailConfirm-Recaps', $student->id) }}"
                                                    class="btn dark:bg-zink-700 flex size-[37.5px] items-center justify-center rounded-full border-slate-500 bg-white p-0 text-slate-500 hover:border-slate-600 hover:bg-slate-600 hover:text-white"
                                                    title="Lihat Detail">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path
                                                            d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0" />
                                                        <circle cx="12" cy="12" r="3" />
                                                    </svg>
                                                </a>
                                                @if ($student->recaps->where('status', 'pending')->count() > 0)
                                                    <a href="{{ route('superadmin.confirm-recaps.approve', $student->id) }}" target="_blank"
                                                        class="btn dark:bg-zink-700 flex size-[37.5px] items-center justify-center rounded-full border-green-500 bg-white p-0 text-green-500 hover:border-green-600 hover:bg-green-600 hover:text-white"
                                                        title="Konfirmasi Pelanggaran">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                            <path d="M18 6 7 17l-5-5" />
                                                            <path d="m22 10-7.5 7.5L13 16" />
                                                        </svg>
                                                    </a>
                                                @endif
                                                <button data-modal-target="modal-tindakan-{{ $student->id }}"
                                                    type="button"
                                                    class="btn dark:bg-zink-700 border-custom-500 text-custom-500 hover:border-custom-600 hover:bg-custom-600 flex size-[37.5px] items-center justify-center rounded-full bg-white p-0 hover:text-white"
                                                    title="Tindakan">
                                                    <i data-lucide="settings" class="size-4"></i>
                                                </button>


                                                <div id="modal-tindakan-{{ $student->id }}" modal-center=""
                                                    class="z-drawer show fixed left-2/4 flex hidden -translate-x-2/4 -translate-y-2/4 flex-col transition-all duration-300 ease-in-out">
                                                    <div
                                                        class="dark:bg-zink-600 flex h-full w-screen flex-col rounded-md bg-white shadow md:w-[40rem]">
                                                        <div
                                                            class="dark:border-zink-500 flex items-center justify-between border-b border-slate-200 p-4">
                                                            <h5 class="text-16">Tindakan -
                                                                {{ $student->student->full_name }}</h5>
                                                            <button data-modal-close="modal-tindakan-{{ $student->id }}"
                                                                class="dark:text-zink-200 text-slate-500 transition-all duration-200 ease-linear hover:text-red-500 dark:hover:text-red-500">
                                                                <i data-lucide="x" class="size-5"></i>
                                                            </button>
                                                        </div>
                                                        <div
                                                            class="max-h-[calc(theme('height.screen')_-_180px)] overflow-y-auto p-4">
                                                            <form method="POST"
                                                                action="{{ route('superadmin.actionConfirm-Recaps', $student->id) }}">
                                                                @csrf
                                                                <input type="hidden" name="student_academic_year_id"
                                                                    value="{{ $student->id }}">

                                                                <div class="mb-4">
                                                                    <label for="tindakanSelect-{{ $student->id }}"
                                                                        class="mb-2 inline-block text-base font-medium">
                                                                        Pilih Tindakan <span class="text-red-500">*</span>
                                                                    </label>
                                                                    @php
                                                                        $applicableHandling = $handlingOptions->where('handling_point', '<=', $student->total_points_verified)->sortByDesc('handling_point')->first();
                                                                    @endphp
                                                                    <select id="tindakanSelect-{{ $student->id }}"
                                                                        name="handling_id" required
                                                                        class="tindakan-dropdown form-input w-full dark:border-zink-500 focus:border-custom-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 dark:placeholder:text-zink-200 border-slate-200 placeholder:text-slate-400 focus:outline-none"
                                                                        data-student-id="{{ $student->id }}">
                                                                        <option value="">Pilih tindakan...</option>
                                                                        @foreach ($handlingOptions as $item)
                                                                            <option value="{{ $item->id }}"
                                                                                data-action="{{ e($item->handling_action) }}"
                                                                                data-point="{{ e($item->handling_point) }}"
                                                                                {{ (!$applicableHandling || $item->id !== $applicableHandling->id) ? 'disabled' : '' }}>
                                                                                {{ $item->handling_action }} -
                                                                                {{ $item->handling_point }} Poin
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>

                                                                <div class="mb-4" id="kepsek-container-{{ $student->id }}">
                                                                    <label for="kepsek-{{ $student->id }}"
                                                                        class="mb-2 inline-block text-base font-medium">Pilih Kepala Sekolah <span class="text-red-500">*</span></label>
                                                                    <select id="kepsek-{{ $student->id }}" name="kepala_sekolah_id" required
                                                                        class="form-input w-full dark:border-zink-500 focus:border-custom-500 border-slate-200 focus:outline-none">
                                                                        <option value="">Pilih Kepala Sekolah...</option>
                                                                        @foreach ($kepalaSekolahList as $kepsekOption)
                                                                            <option value="{{ $kepsekOption->id }}" {{ ($student->action_detail?->detail?->kepala_sekolah_id ?? '') == $kepsekOption->id ? 'selected' : '' }}>
                                                                                {{ $kepsekOption->employee->full_name ?? $kepsekOption->name }} (NIP. {{ $kepsekOption->employee->nip ?? '-' }})
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>

                                                                <div id="handlingDetails-{{ $student->id }}"
                                                                    class="hidden">
                                                                    {{-- Resolve RefStudent: prefer loaded relation when it has data, otherwise try id or student_id lookups --}}
                                                                    @php
                                                                        $relStudent = $student->student ?? null;
                                                                        $refStudent = null;

                                                                        if (
                                                                            $relStudent &&
                                                                            (!empty($relStudent->guardian_name) ||
                                                                                !empty($relStudent->full_name))
                                                                        ) {
                                                                            $refStudent = $relStudent;
                                                                        } else {
                                                                            $refStudent = \App\Models\RefStudent::find(
                                                                                $student->student_id ?? null,
                                                                            );

                                                                            if (
                                                                                !$refStudent &&
                                                                                !empty($student->student_id)
                                                                            ) {
                                                                                // sometimes ref_student_academic_years.student_id stores a different key (student_id column)
                                                                                $refStudent = \App\Models\RefStudent::where(
                                                                                    'student_id',
                                                                                    $student->student_id,
                                                                                )->first();
                                                                            }
                                                                        }
                                                                    @endphp
                                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                                        <div class="mb-4">
                                                                            <label
                                                                                class="mb-2 inline-block text-base font-medium">Nama
                                                                                Siswa</label>
                                                                            <input type="text" name="student_name"
                                                                                value="{{ $student->action_detail?->detail?->student_name ?? ($refStudent->full_name ?? ($student->student->full_name ?? '')) }}"
                                                                                class="form-input dark:border-zink-500 focus:border-custom-500 w-full border-slate-200 focus:outline-none">
                                                                        </div>

                                                                        <div class="mb-4">
                                                                            <label
                                                                                class="mb-2 inline-block text-base font-medium">Nama
                                                                                Wali</label>
                                                                            <input type="text" name="parent_name"
                                                                                value="{{ $student->action_detail?->detail?->parent_name ?? ($refStudent->guardian_name ?? ($student->student->guardian_name ?? '')) }}"
                                                                                class="form-input dark:border-zink-500 focus:border-custom-500 w-full border-slate-200 focus:outline-none">
                                                                        </div>

                                                                        <div class="mb-4">
                                                                            <label
                                                                                class="mb-2 inline-block text-base font-medium">Tindakan
                                                                                Terpilih</label>
                                                                            <input type="text"
                                                                                id="selectedAction-{{ $student->id }}"
                                                                                readonly
                                                                                class="form-input dark:border-zink-500 dark:bg-zink-600 w-full border-slate-200 bg-slate-100"
                                                                                value="">
                                                                        </div>

                                                                        <div class="mb-4">
                                                                            <label
                                                                                class="mb-2 inline-block text-base font-medium">Poin
                                                                                Tindakan</label>
                                                                            <input type="text"
                                                                                id="selectedPoint-{{ $student->id }}"
                                                                                readonly
                                                                                class="form-input dark:border-zink-500 dark:bg-zink-600 w-full border-slate-200 bg-slate-100"
                                                                                value="">
                                                                        </div>

                                                                        <div class="mb-4">
                                                                            <label for="prey-{{ $student->id }}"
                                                                                class="mb-2 inline-block text-base font-medium">Titimangsa
                                                                                (prey)
                                                                            </label>
                                                                            <input type="date"
                                                                                id="prey-{{ $student->id }}" name="prey"
                                                                                value="{{ $student->action_detail?->detail?->prey ?? '' }}"
                                                                                class="form-input dark:border-zink-500 focus:border-custom-500 w-full border-slate-200 focus:outline-none">
                                                                        </div>

                                                                        <div class="mb-4">
                                                                            <label for="action_date-{{ $student->id }}"
                                                                                class="mb-2 inline-block text-base font-medium">Hari,
                                                                                Tanggal (action_date)</label>
                                                                            <input type="date"
                                                                                id="action_date-{{ $student->id }}"
                                                                                name="action_date"
                                                                                value="{{ $student->action_detail?->detail?->action_date ?? '' }}"
                                                                                class="form-input dark:border-zink-500 focus:border-custom-500 w-full border-slate-200 focus:outline-none">
                                                                        </div>

                                                                        <div class="mb-4">
                                                                            <label for="reference_number-{{ $student->id }}"
                                                                                class="mb-2 inline-block text-base font-medium">Nomor
                                                                                Surat <span id="ref-asterisk-{{ $student->id }}" class="text-red-500 hidden">*</span></label>
                                                                            <input type="text"
                                                                                id="reference_number-{{ $student->id }}"
                                                                                name="reference_number"
                                                                                value="{{ $student->action_detail?->detail?->reference_number ?? '' }}"
                                                                                class="form-input dark:border-zink-500 focus:border-custom-500 w-full border-slate-200 focus:outline-none"
                                                                                placeholder="Masukkan nomor surat">
                                                                        </div>

                                                                        <div class="mb-4">
                                                                            <label for="time-{{ $student->id }}"
                                                                                class="mb-2 inline-block text-base font-medium">Jam
                                                                                (time)</label>
                                                                            <input type="text"
                                                                                id="time-{{ $student->id }}" name="time"
                                                                                value="{{ $student->action_detail?->detail?->time ?? '' }}"
                                                                                class="form-input dark:border-zink-500 focus:border-custom-500 w-full border-slate-200 focus:outline-none"
                                                                                placeholder="08:30">
                                                                        </div>

                                                                        <div class="mb-4">
                                                                            <label for="room-{{ $student->id }}"
                                                                                class="mb-2 inline-block text-base font-medium">Ruangan
                                                                                (room)</label>
                                                                            <input type="text"
                                                                                id="room-{{ $student->id }}" name="room"
                                                                                value="{{ $student->action_detail?->detail?->room ?? '' }}"
                                                                                class="form-input dark:border-zink-500 focus:border-custom-500 w-full border-slate-200 focus:outline-none"
                                                                                placeholder="Ruang A">
                                                                        </div>

                                                                        <div class="mb-4">
                                                                            <label for="facing-{{ $student->id }}"
                                                                                class="mb-2 inline-block text-base font-medium">Menghadap
                                                                                Ke (facing)</label>
                                                                            <input type="text"
                                                                                id="facing-{{ $student->id }}"
                                                                                name="facing"
                                                                                value="{{ $student->action_detail?->detail?->facing ?? '' }}"
                                                                                class="form-input dark:border-zink-500 focus:border-custom-500 w-full border-slate-200 focus:outline-none"
                                                                                placeholder="Guru / Papan Tulis">
                                                                        </div>



                                                                        <div class="mb-4 md:col-span-2">
                                                                            <label for="violation_count-{{ $student->id }}"
                                                                                class="mb-2 inline-block text-base font-medium">Jumlah
                                                                                Pelanggaran
                                                                            </label>
                                                                            <input type="number" min="0"
                                                                                max="10"
                                                                                id="violation_count-{{ $student->id }}"
                                                                                name="violation_count"
                                                                                value="{{ $student->action_detail?->detail?->violation_count ?? 0 }}"
                                                                                class="form-input dark:border-zink-500 focus:border-custom-500 w-full border-slate-200 focus:outline-none"
                                                                                placeholder="0"
                                                                                onchange="generateViolationForms(this, '{{ $student->id }}')">
                                                                        </div>

                                                                        <!-- Form Pelanggaran Dinamis -->
                                                                        <div id="violations-container-{{ $student->id }}"
                                                                            class="mb-4 md:col-span-2">
                                                                            @if ($student->action_detail?->detail?->violations)
                                                                                @foreach ($student->action_detail->detail->violations as $index => $violation)
                                                                                    <div
                                                                                        class="mb-3 border-l-4 border-orange-500 bg-orange-50 p-3 dark:bg-orange-900/20">
                                                                                        <label
                                                                                            class="mb-2 inline-block text-sm font-medium">Pelanggaran
                                                                                            ke-{{ $index + 1 }}</label>
                                                                                        <input type="text"
                                                                                            name="violations[{{ $index }}]"
                                                                                            class="form-input dark:border-zink-500 focus:border-custom-500 w-full border-slate-200 focus:outline-none"
                                                                                            placeholder="Masukkan pelanggaran"
                                                                                            value="{{ $violation }}">
                                                                                    </div>
                                                                                @endforeach
                                                                            @endif
                                                                        </div>

                                                                        <div class="mb-4 md:col-span-2">
                                                                            <label
                                                                                class="mb-2 inline-block text-base font-medium">Deskripsi</label>
                                                                            <textarea id="descDetailsTextarea-{{ $student->id }}" name="description" rows="3"
                                                                                class="form-input dark:border-zink-500 focus:border-custom-500 w-full border-slate-200 focus:outline-none">{{ $student->action_detail?->description ?? '' }}</textarea>
                                                                        </div>
                                                                    </div>
                                                                    <button type="submit"
                                                                        class="dark:bg-custom-600 dark:hover:bg-custom-700 bg-custom-500 hover:bg-custom-600 rounded-md px-4 py-2 text-white transition-colors duration-200">
                                                                        Simpan Tindakan
                                                                    </button>
                                                                </div>
                                                            </form>
                                        </td>

                                        <td>{{ $student->student->student_number ?? '-' }}</td>
                                        <td>{{ $student->student->full_name ?? '-' }}</td>
                                        <td>{{ $student->student->gender ?? '-' }}</td>
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
                        </div> <!-- End tabActiveContent -->

                        <div id="tabHistoryContent" class="tab-pane hidden">
                            <h6 class="text-15 mb-4">Daftar Riwayat Tindakan</h6>
                            <div class="table-wrapper">
                                <table id="historyTable" style="width: 100%" class="hover group">
                                    <thead>
                                        <tr>
                                            <th>Aksi</th>
                                            <th>NIS</th>
                                            <th>Nama Lengkap</th>
                                            <th>Kelas</th>
                                            <th>Total Poin Terverifikasi</th>
                                            <th>Tindakan Terakhir</th>
                                            <th>Diberikan Oleh</th>
                                            <th>Tanggal Penindakan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($historyStudents as $student)
                                            <tr class="student-row"
                                                data-class="{{ $student->class->academic_level }} {{ $student->class->name }}"
                                                data-gender="{{ $student->student->gender }}">
                                                <td>
                                                    <div class="flex gap-2">
                                                        <a href="{{ route('superadmin.detailConfirm-Recaps', $student->id) }}"
                                                            class="btn dark:bg-zink-700 flex size-[37.5px] items-center justify-center rounded-full border-slate-500 bg-white p-0 text-slate-500 hover:border-slate-600 hover:bg-slate-600 hover:text-white"
                                                            title="Lihat Detail">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                <path
                                                                    d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0" />
                                                                <circle cx="12" cy="12" r="3" />
                                                            </svg>
                                                        </a>
                                                        <form method="POST" action="{{ route('superadmin.confirm-recaps.reset', $student->id) }}" class="inline-block" onsubmit="confirmReset(event, this)">
                                                            @csrf
                                                            <button type="submit" class="btn dark:bg-zink-700 flex size-[37.5px] items-center justify-center rounded-full border-red-500 bg-white p-0 text-red-500 hover:border-red-600 hover:bg-red-600 hover:text-white" title="Reset Poin ke 0 (Tobat)">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                                <td>{{ $student->student->student_number ?? '-' }}</td>
                                                <td>{{ $student->student->full_name ?? '-' }}</td>
                                                <td>{{ $student->class->academic_level }} {{ $student->class->name }}</td>
                                                <td>
                                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                                        {{ $student->total_points_verified ?? 0 }} Poin
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                                                        {{ $student->action_detail->handling->handling_action ?? $student->action_detail->handling->handling_name ?? '-' }}
                                                    </span>
                                                </td>
                                                <td>{{ $student->action_detail->handle->name ?? '-' }}</td>
                                                <td>{{ $student->action_detail->created_at->format('d M Y') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div> <!-- End tabHistoryContent -->

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

            @endif

        </div>
        <!-- container-fluid -->
    </div>

    <style>
        /* Modal dengan ukuran tetap */
        .modal-container {
            width: 90vw;
            max-width: 1100px;
            height: 85vh;
            max-height: 800px;
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
            if (typeof DataTable !== 'undefined') {
                new DataTable('#historyTable');
            }

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

                        // === TAMBAHAN SCRIPT BARU - DYNAMIC FORM ===
                        // Ambil elemen-elemen form
                        const preyField = document.getElementById(`prey-${studentId}`)?.closest(
                            '.mb-4');
                        const actionDateField = document.getElementById(`action_date-${studentId}`)
                            ?.closest('.mb-4');
                        const referenceNumberField = document.getElementById(
                            `reference_number-${studentId}`)?.closest('.mb-4');
                        const timeField = document.getElementById(`time-${studentId}`)?.closest(
                            '.mb-4');
                        const roomField = document.getElementById(`room-${studentId}`)?.closest(
                            '.mb-4');
                        const facingField = document.getElementById(`facing-${studentId}`)?.closest(
                            '.mb-4');
                        const kepsekContainer = document.getElementById(`kepsek-container-${studentId}`);
                        const kepsekSelect = document.getElementById(`kepsek-${studentId}`);

                        // Cek apakah tindakan adalah "Teguran Tertulis dan Pemanggilan Orang Tua"
                        const isTeguranTertulisOrPemanggilan = action &&
                            (action.toLowerCase().includes('teguran tertulis') ||
                                action.toLowerCase().includes('pemanggilan orang tua'));
                        const isKegiatanSosial = action &&
                            (action.toLowerCase().includes('kegiatan sosial') ||
                                action.toLowerCase().includes('kegiatan sosial'));
                        const isLisan = action && action.toLowerCase().includes('lisan');

                        const refNumberInput = document.getElementById(`reference_number-${studentId}`);
                        const refAsterisk = document.getElementById(`ref-asterisk-${studentId}`);

                        if (isLisan) {
                            if (kepsekContainer) kepsekContainer.classList.add('hidden');
                            if (kepsekSelect) {
                                kepsekSelect.required = false;
                                kepsekSelect.value = '';
                            }
                            if (refNumberInput) {
                                refNumberInput.required = false;
                                refNumberInput.value = '';
                            }
                            if (refAsterisk) refAsterisk.classList.add('hidden');
                            select.closest('form').target = '_self';
                        } else {
                            if (kepsekContainer) kepsekContainer.classList.remove('hidden');
                            if (kepsekSelect) kepsekSelect.required = true;
                            if (refNumberInput) refNumberInput.required = true;
                            if (refAsterisk) refAsterisk.classList.remove('hidden');
                            select.closest('form').target = '_blank';
                        }

                        // Tampilkan/sembunyikan field berdasarkan tindakan
                        if (!isLisan) {
                            // Tampilkan semua field
                            if (preyField) preyField.classList.remove('hidden');
                            if (actionDateField) actionDateField.classList.remove('hidden');
                            if (referenceNumberField) referenceNumberField.classList.remove(
                                'hidden');
                            if (timeField) timeField.classList.remove('hidden');
                            if (roomField) roomField.classList.remove('hidden');
                            if (facingField) facingField.classList.remove('hidden');
                        } else {
                            // Sembunyikan semua field kecuali deskripsi
                            if (preyField) preyField.classList.add('hidden');
                            if (actionDateField) actionDateField.classList.add('hidden');
                            if (referenceNumberField) referenceNumberField.classList.add('hidden');
                            if (timeField) timeField.classList.add('hidden');
                            if (roomField) roomField.classList.add('hidden');
                            if (facingField) facingField.classList.add('hidden');

                            // Reset nilai field yang disembunyikan
                            if (preyField) document.getElementById(`prey-${studentId}`).value = '';
                            if (actionDateField) document.getElementById(`action_date-${studentId}`)
                                .value = '';
                            if (referenceNumberField) document.getElementById(
                                `reference_number-${studentId}`).value = '';
                            if (timeField) document.getElementById(`time-${studentId}`).value = '';
                            if (roomField) document.getElementById(`room-${studentId}`).value = '';
                            if (facingField) document.getElementById(`facing-${studentId}`).value =
                                '';
                        }
                        // === AKHIR TAMBAHAN SCRIPT BARU ===
                    } else {
                        detailsDiv.classList.add('hidden');
                    }
                });
            });
        });

        // Generate violation forms dynamically
        function generateViolationForms(input, studentId) {
            const count = parseInt(input.value) || 0;
            const container = document.getElementById(`violations-container-${studentId}`);

            if (!container) return;

            // Clear existing forms
            container.innerHTML = '';

            // Generate new forms based on count
            for (let i = 0; i < count; i++) {
                const formHtml = `
                    <div class="mb-3 border-l-4 border-orange-500 bg-orange-50 p-3 dark:bg-orange-900/20">
                        <label class="mb-2 inline-block text-sm font-medium">Pelanggaran ke-${i + 1}</label>
                        <input type="text"
                            name="violations[${i}]"
                            class="form-input dark:border-zink-500 focus:border-custom-500 w-full border-slate-200 focus:outline-none"
                            placeholder="Masukkan pelanggaran"
                            value="">
                    </div>
                `;
                container.insertAdjacentHTML('beforeend', formHtml);
            }
        }

        function switchTab(tab) {
            const activeBtn = document.getElementById('tabActiveBtn');
            const historyBtn = document.getElementById('tabHistoryBtn');
            const activeContent = document.getElementById('tabActiveContent');
            const historyContent = document.getElementById('tabHistoryContent');

            if (tab === 'active') {
                activeBtn.classList.add('border-custom-500', 'text-custom-500', 'dark:text-custom-400', 'dark:border-custom-400');
                activeBtn.classList.remove('border-transparent', 'text-slate-500');
                
                historyBtn.classList.remove('border-custom-500', 'text-custom-500', 'dark:text-custom-400', 'dark:border-custom-400');
                historyBtn.classList.add('border-transparent', 'text-slate-500');

                activeContent.classList.remove('hidden');
                historyContent.classList.add('hidden');
            } else {
                historyBtn.classList.add('border-custom-500', 'text-custom-500', 'dark:text-custom-400', 'dark:border-custom-400');
                historyBtn.classList.remove('border-transparent', 'text-slate-500');
                
                activeBtn.classList.remove('border-custom-500', 'text-custom-500', 'dark:text-custom-400', 'dark:border-custom-400');
                activeBtn.classList.add('border-transparent', 'text-slate-500');

                historyContent.classList.remove('hidden');
                activeContent.classList.add('hidden');
            }
        }

        function confirmReset(event, form) {
            event.preventDefault();
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Semua poin pelanggaran dan riwayat tindakan siswa ini akan dihapus dan direset ke 0!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Reset ke 0!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    </script>
@endsection
