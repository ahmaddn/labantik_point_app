@extends('layouts.app')
@section('content')
    <div
        class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu group-data-[sidebar-size=md]:ltr:ml-vertical-menu-md group-data-[sidebar-size=md]:rtl:mr-vertical-menu-md group-data-[sidebar-size=sm]:ltr:ml-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:mr-vertical-menu-sm px-4 pb-[calc(theme('spacing.header')_*_0.8)] pt-[calc(theme('spacing.header')_*_1)] group-data-[layout=horizontal]:mx-auto group-data-[layout=horizontal]:max-w-screen-2xl group-data-[layout=horizontal]:px-0 group-data-[layout=horizontal]:px-3 group-data-[layout=horizontal]:group-data-[navbar=hidden]:pt-[calc(theme('spacing.header')_*_0.9)] group-data-[navbar=bordered]:pt-[calc(theme('spacing.header')_*_1.3)] group-data-[navbar=hidden]:pt-0 group-data-[layout=horizontal]:md:pt-[calc(theme('spacing.header')_*_1.6)] group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:ltr:md:ml-auto group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:rtl:md:mr-auto">
        <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">

            <!-- Breadcrumb -->
            <div class="flex flex-col gap-2 py-4 md:flex-row md:items-center">
                <div class="grow">
                    <h5 class="text-16">Konfirmasi & Verifikasi Pelanggaran</h5>
                </div>
                <ul class="flex shrink-0 items-center gap-2 text-sm font-normal">
                    <li
                        class="before:font-remix dark:text-zink-200 relative before:absolute before:-top-[3px] before:text-[18px] before:text-slate-400 before:content-['\ea54'] ltr:pr-4 ltr:before:-right-1 rtl:pl-4 rtl:before:-left-1">
                        <a href="#!" class="dark:text-zink-200 text-slate-400">Dashboards</a>
                    </li>
                    <li
                        class="before:font-remix dark:text-zink-200 relative before:absolute before:-top-[3px] before:text-[18px] before:text-slate-400 before:content-['\ea54'] ltr:pr-4 ltr:before:-right-1 rtl:pl-4 rtl:before:-left-1">
                        <a href="{{ route('superadmin.confirm-recaps') }}" class="dark:text-zink-200 text-slate-400">Rekap & Verifikasi Pelanggaran</a>
                    </li>
                    <li class="dark:text-zink-100 text-slate-700">
                        Konfirmasi Pelanggaran
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
                        <a href="{{ route('superadmin.confirm-recaps') }}"
                            class="shrink-0 flex items-center justify-center gap-2 rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-600 transition-all hover:bg-slate-50 hover:shadow-sm dark:bg-zink-700 dark:border-zink-500 dark:text-zink-200 dark:hover:bg-zink-600">
                            <i data-lucide="arrow-left" class="h-4 w-4"></i>
                            <span class="ml-1">Kembali</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Warning if student already has a handling action -->
            @if ($studentAcademicYear->action_detail)
                <div class="mb-4 rounded-md bg-blue-50 border border-blue-200 p-4 text-sm text-blue-800 dark:bg-blue-900/20 dark:border-blue-800/30 dark:text-blue-200">
                    <div class="flex items-center gap-2 font-semibold mb-1">
                        <i data-lucide="info" class="h-4 w-4"></i>
                        Tindakan Penanganan Sudah Dilakukan
                    </div>
                    Siswa ini telah diberikan tindakan <strong>{{ $studentAcademicYear->action_detail->handling?->handling_action ?? $studentAcademicYear->action_detail->handling?->handling_name ?? '-' }}</strong> oleh <strong>{{ $studentAcademicYear->action_detail->handle->name ?? '-' }}</strong> pada tanggal {{ $studentAcademicYear->action_detail->created_at->format('d M Y') }}.
                </div>
            @endif

            <!-- Alert notification messages -->
            @if (session('success'))
                <div class="mb-4 rounded-md bg-green-50 p-3 text-sm text-green-700" role="status">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Table Card -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="mb-4 flex items-center justify-between">
                        <h6 class="text-15">Daftar Pelanggaran Pending & Terverifikasi</h6>
                    </div>

                    <!-- Table Container -->
                    <div class="table-wrapper">
                        <table id="hoverableTable" style="width: 100%" class="hover group">
                            <thead>
                                <tr>
                                    <th class="w-32">Aksi</th>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Pelanggaran</th>
                                    <th>Kategori</th>
                                    <th>Poin</th>
                                    <th>Status</th>
                                    <th>Dibuat oleh</th>
                                    <th>Diverifikasi oleh</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $detailCounter = 1; @endphp
                                @forelse ($studentAcademicYear->recaps->whereIn('status', ['pending', 'verified']) as $pRecap)
                                    <tr class="detail-violation-row dark:bg-zink-800 dark:border-zink-700 dark:hover:bg-zink-700 border-b bg-white hover:bg-slate-50">
                                        <!-- KOLOM AKSI -->
                                        <td class="px-3 py-2">
                                            <div class="flex gap-2">
                                                <!-- Form untuk verifikasi -->
                                                <form method="POST"
                                                    action="{{ route('superadmin.violation-status.update', $pRecap->id) }}"
                                                    class="inline-flex gap-2">
                                                    @csrf
                                                    @method('PUT')
                                                    @if ($pRecap->status == 'pending')
                                                        <button type="submit" value="verified"
                                                            name="status"
                                                            class="btn dark:bg-zink-700 flex size-[37.5px] items-center justify-center rounded-full border-green-500 bg-white p-0 text-green-500 hover:border-green-600 hover:bg-green-600 hover:text-white"
                                                            title="Verifikasi">
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                width="18" height="18"
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
                                                            onclick="confirmSubmit(event, this, 'Apakah Anda yakin ingin menolak pelanggaran ini?')"
                                                            class="btn dark:bg-zink-700 flex size-[37.5px] items-center justify-center rounded-full border-red-500 bg-white p-0 text-red-500 hover:border-red-600 hover:bg-red-600 hover:text-white"
                                                            title="Tolak">
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                width="18" height="18"
                                                                viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor"
                                                                stroke-width="2"
                                                                stroke-linecap="round"
                                                                stroke-linejoin="round">
                                                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                                                <line x1="6" y1="6" x2="18" y2="18"></line>
                                                            </svg>
                                                        </button>
                                                    @else
                                                        <button type="submit" value="pending"
                                                            name="status"
                                                            onclick="confirmSubmit(event, this, 'Apakah Anda yakin ingin memverifikasi ulang pelanggaran ini?')"
                                                            class="btn dark:bg-zink-700 flex size-[37.5px] items-center justify-center rounded-full border-orange-500 bg-white p-0 text-orange-500 hover:border-orange-600 hover:bg-orange-600 hover:text-white"
                                                            title="Verifikasi Ulang">
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                width="18" height="18"
                                                                viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor"
                                                                stroke-width="2"
                                                                stroke-linecap="round"
                                                                stroke-linejoin="round">
                                                                <path d="m17 2 4 4-4 4" />
                                                                <path d="M3 11v-1a4 4 0 0 1 4-4h14" />
                                                                <path d="m7 22-4-4 4-4" />
                                                                <path d="M21 13v1a4 4 0 0 1-4 4H3" />
                                                            </svg>
                                                        </button>
                                                    @endif
                                                </form>

                                                @if ($pRecap->status == 'pending')
                                                    <form method="POST"
                                                        action="{{ route('superadmin.recaps.destroy', $pRecap->id) }}"
                                                        class="inline-block">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            onclick="confirmSubmit(event, this, 'Apakah Anda yakin ingin menghapus pelanggaran ini?')"
                                                            class="btn dark:bg-zink-700 flex size-[37.5px] items-center justify-center rounded-full border-red-500 bg-white p-0 text-red-500 hover:border-red-600 hover:bg-red-600 hover:text-white"
                                                            title="Hapus">
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                width="18" height="18"
                                                                viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor"
                                                                stroke-width="2"
                                                                stroke-linecap="round"
                                                                stroke-linejoin="round">
                                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                                <line x1="10" y1="11" x2="10" y2="17"></line>
                                                                <line x1="14" y1="11" x2="14" y2="17"></line>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-3 py-3 font-medium">{{ $detailCounter++ }}</td>
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
                                        <td class="px-4 py-4 font-semibold text-red-600 dark:text-red-400">
                                            {{ $pRecap->violation->point ?? 0 }}
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
                                    <tr class="dark:bg-zink-800 bg-white">
                                        <td colspan="9" class="dark:text-zink-400 px-4 py-8 text-center text-slate-500">
                                            Tidak ada data pelanggaran pending atau terverifikasi.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Summary Stats block -->
                    <div class="mt-6 flex flex-col md:flex-row justify-end items-center gap-4 border-t border-slate-200 dark:border-zink-500 pt-4">
                        <div class="text-right">
                            <span class="text-sm font-medium text-slate-500 dark:text-zink-300">Total Pelanggaran:</span>
                            <span class="text-base font-bold text-slate-800 dark:text-zink-50 ml-1">
                                {{ $studentAcademicYear->recaps->whereIn('status', ['pending', 'verified'])->count() }}
                            </span>
                        </div>
                        <div class="text-right">
                            <span class="text-sm font-medium text-slate-500 dark:text-zink-300">Total Poin:</span>
                            <span class="text-lg font-extrabold text-orange-600 dark:text-orange-400 ml-1">
                                {{ $studentAcademicYear->recaps->whereIn('status', ['pending', 'verified'])->sum(fn($r) => $r->violation->point ?? 0) }} Poin
                            </span>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
@endsection
