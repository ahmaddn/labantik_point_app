@extends('layouts.app')
@section('content')
    <div
        class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu group-data-[sidebar-size=md]:ltr:ml-vertical-menu-md group-data-[sidebar-size=md]:rtl:mr-vertical-menu-md group-data-[sidebar-size=sm]:ltr:ml-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:mr-vertical-menu-sm px-4 pb-[calc(theme('spacing.header')_*_0.8)] pt-[calc(theme('spacing.header')_*_1)] group-data-[layout=horizontal]:mx-auto group-data-[layout=horizontal]:max-w-screen-2xl group-data-[layout=horizontal]:px-0 group-data-[layout=horizontal]:px-3 group-data-[layout=horizontal]:group-data-[navbar=hidden]:pt-[calc(theme('spacing.header')_*_0.9)] group-data-[navbar=bordered]:pt-[calc(theme('spacing.header')_*_1.3)] group-data-[navbar=hidden]:pt-0 group-data-[layout=horizontal]:md:pt-[calc(theme('spacing.header')_*_1.6)] group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:ltr:md:ml-auto group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:rtl:md:mr-auto">
        <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">

            <!-- Breadcrumb -->
            <div class="flex flex-col gap-2 py-4 md:flex-row md:items-center print:hidden">
                <div class="grow">
                    <h5 class="text-16 font-semibold">Detail Rekap Pelanggaran</h5>
                </div>
                <ul class="flex shrink-0 items-center gap-2 text-sm font-normal">
                    <li class="dark:text-zink-200 text-slate-400">Portal Wali Kelas</li>
                    <li class="text-slate-400">/</li>
                    <li class="dark:text-zink-200 text-slate-400"><a href="{{ route('wakel.recaps') }}">Rekap Pelanggaran</a></li>
                    <li class="text-slate-400">/</li>
                    <li class="dark:text-zink-100 text-slate-700 font-medium">Detail</li>
                </ul>
            </div>

            <!-- Student Info Card -->
            <div class="card mb-4 shadow-sm relative overflow-hidden bg-white dark:bg-zink-700 rounded-xl border-none">
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
                        <a href="{{ route('wakel.recaps') }}"
                            class="shrink-0 flex items-center justify-center gap-2 rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-600 transition-all hover:bg-slate-50 hover:shadow-sm dark:bg-zink-700 dark:border-zink-500 dark:text-zink-200 dark:hover:bg-zink-600">
                            <i data-lucide="arrow-left" class="h-4 w-4"></i>
                            <span class="ml-1">Kembali</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Table Card -->
            <div class="card mb-4">
                <div class="card-body">
                    <h6 class="text-15 font-semibold mb-4 text-slate-800 dark:text-zink-50">Daftar Pelanggaran Siswa</h6>
                    
                    <div class="overflow-x-auto">
                        <table style="width: 100%" class="hover group">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Pelanggaran</th>
                                    <th>Kategori</th>
                                    <th>Poin</th>
                                    <th>Status</th>
                                    <th>Pelapor</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($studentAcademicYear->recaps as $recap)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $recap->created_at->format('d/m/Y') }}</td>
                                        <td>{{ $recap->violation->name }}</td>
                                        <td>
                                            @php
                                                $category = $recap->violation->category->name ?? 'Lainnya';
                                                $catColor = 'bg-slate-100 text-slate-850 dark:bg-zink-600 dark:text-zink-200';
                                                if ($category === 'Ringan') {
                                                    $catColor = 'bg-blue-50 text-blue-800 dark:bg-blue-900/20 dark:text-blue-200';
                                                } elseif ($category === 'Sedang') {
                                                    $catColor = 'bg-amber-50 text-amber-800 dark:bg-amber-900/20 dark:text-amber-200';
                                                } elseif ($category === 'Berat') {
                                                    $catColor = 'bg-red-50 text-red-800 dark:bg-red-900/20 dark:text-red-200';
                                                }
                                            @endphp
                                            <span class="px-2 py-0.5 rounded text-11 font-medium {{ $catColor }}">
                                                {{ $category }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="font-bold text-red-500">{{ $recap->violation->point }}</span>
                                        </td>
                                        <td>
                                            @php
                                                $status = $recap->status;
                                                $statusText = 'Tidak Diketahui';
                                                $statusColor = 'bg-slate-100 text-slate-800 dark:bg-zink-600 dark:text-zink-200';
                                                
                                                if ($status === 'verified') {
                                                    $statusText = 'Terverifikasi';
                                                    $statusColor = 'bg-green-50 text-green-800 dark:bg-green-900/20 dark:text-green-200';
                                                } elseif ($status === 'pending') {
                                                    $statusText = 'Menunggu';
                                                    $statusColor = 'bg-amber-50 text-amber-800 dark:bg-amber-900/20 dark:text-amber-200';
                                                } elseif ($status === 'not_verified') {
                                                    $statusText = 'Ditolak';
                                                    $statusColor = 'bg-red-50 text-red-800 dark:bg-red-900/20 dark:text-red-200';
                                                }
                                            @endphp
                                            <span class="px-2 py-0.5 rounded text-11 font-semibold {{ $statusColor }}">
                                                {{ $statusText }}
                                            </span>
                                        </td>
                                        <td>{{ $recap->createdBy->name ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Form / Detail Tindakan -->
            <div class="card mb-4 bg-white dark:bg-zink-700">
                <div class="card-body p-5">
                    @if ($studentAcademicYear->action_detail)
                        <h6 class="text-15 font-semibold mb-4 text-slate-800 dark:text-zink-50">Tindakan Penanganan yang Telah Dilakukan</h6>
                        <div class="bg-blue-50/50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800/30 p-4 rounded-xl">
                            <div class="flex justify-between items-start mb-2">
                                <h6 class="text-15 font-bold text-blue-900 dark:text-blue-200">
                                    {{ $studentAcademicYear->action_detail->handling?->handling_name ?? $studentAcademicYear->action_detail->handling?->handling_action ?? '-' }}
                                </h6>
                                <span class="text-11 text-slate-500 dark:text-zink-300">
                                    {{ $studentAcademicYear->action_detail->created_at->format('d/m/Y H:i') }}
                                </span>
                            </div>
                            <div class="text-13 text-slate-700 dark:text-zink-100 space-y-2">
                                <div><strong>Aktivitas:</strong> {{ $studentAcademicYear->action_detail->activity }}</div>
                                <div><strong>Catatan:</strong> {{ $studentAcademicYear->action_detail->description }}</div>
                                <div><strong>Petugas Pembina:</strong> {{ $studentAcademicYear->action_detail->handle->name }}</div>
                            </div>
                        </div>
                    @else
                        @if ($applicableHandling)
                            <h6 class="text-15 font-semibold mb-4 text-slate-800 dark:text-zink-50">Ambil Tindakan Penanganan</h6>
                            <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-100 dark:border-amber-800/30 p-4 rounded-xl mb-4 text-13 text-amber-800 dark:text-amber-200">
                                Siswa ini telah melampaui batas poin untuk penanganan: <strong>{{ $applicableHandling->handling_name }}</strong> (Batas: {{ $applicableHandling->handling_point }} Poin). Harap lakukan pembinaan dan simpan catatannya di bawah ini.
                            </div>

                            <form action="{{ route('wakel.actionConfirm-Recaps', $studentAcademicYear->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="handling_id" value="{{ $applicableHandling->id }}">

                                <div class="mb-4">
                                    <label class="block text-13 font-semibold mb-2">Aktivitas Tindakan</label>
                                    <input type="text" name="activity" placeholder="Contoh: Pemanggilan Siswa & Konseling Individual" class="w-full rounded-md border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 dark:bg-zink-700 dark:border-zink-500 dark:text-zink-100" required>
                                </div>

                                <div class="mb-4">
                                    <label class="block text-13 font-semibold mb-2">Deskripsi Tindakan dan Pembinaan</label>
                                    <textarea name="description" rows="4" placeholder="Detail pembinaan yang diberikan dan komitmen siswa..." class="w-full rounded-md border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 dark:bg-zink-700 dark:border-zink-500 dark:text-zink-100" required></textarea>
                                </div>

                                <div class="mb-4">
                                    <label class="block text-13 font-semibold mb-2">Pelanggaran Terkait</label>
                                    <div class="space-y-1 bg-slate-50 dark:bg-zink-600/50 p-3 rounded-lg border border-slate-100 dark:border-zink-500">
                                        @foreach ($studentAcademicYear->recaps->where('status', 'verified') as $vRecap)
                                            <label class="flex items-center gap-2 cursor-pointer text-12 text-slate-700 dark:text-zink-100">
                                                <input type="checkbox" name="violation_details[]" value="{{ $vRecap->violation->name }}" checked class="border rounded-sm appearance-none size-4 bg-slate-100 border-slate-300 dark:bg-zink-600 dark:border-zink-500 checked:bg-custom-500 checked:border-custom-500 transition-all">
                                                <span>{{ $vRecap->violation->name }} ({{ $vRecap->violation->point }} Poin)</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="flex justify-end">
                                    <button type="submit" class="btn bg-blue-500 hover:bg-blue-600 border-blue-500 text-white font-semibold px-4 py-2 rounded-lg text-13">
                                        Simpan Tindakan
                                    </button>
                                </div>
                            </form>
                        @else
                            <div class="bg-green-50 dark:bg-green-900/20 border border-green-100 dark:border-green-800/30 p-4 rounded-xl text-center text-13 text-green-800 dark:text-green-200">
                                Poin terverifikasi siswa ini belum melampaui batas penanganan minimum. Belum ada tindakan yang perlu dilakukan.
                            </div>
                        @endif
                    @endif
                </div>
            </div>

            <!-- Handling History Section -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="mb-4 flex items-center justify-between border-b border-slate-200 pb-3 dark:border-zink-500">
                        <div class="flex items-center gap-2">
                            <i data-lucide="history" class="h-5 w-5 text-custom-500"></i>
                            <h6 class="mb-0 text-15 font-semibold">Riwayat Tindakan Pendisiplinan</h6>
                        </div>
                        <span class="rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-semibold text-slate-800 dark:bg-zink-600 dark:text-zink-200">
                            {{ $handlingHistory->count() }} Kali Ditindak
                        </span>
                    </div>

                    @if($handlingHistory->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm text-slate-500 dark:text-zink-200">
                                <thead class="bg-slate-50 text-xs uppercase text-slate-700 dark:bg-zink-600 dark:text-zink-200">
                                    <tr>
                                        <th class="px-4 py-3">No</th>
                                        <th class="px-4 py-3">Tanggal Penindakan</th>
                                        <th class="px-4 py-3">Tindakan</th>
                                        <th class="px-4 py-3">Diberikan Oleh</th>
                                        <th class="px-4 py-3">Detail Penanganan / Deskripsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($handlingHistory as $history)
                                        <tr class="border-b bg-white hover:bg-slate-50/50 dark:border-zink-600 dark:bg-zink-700 dark:hover:bg-zink-600/50">
                                            <td class="px-4 py-3 font-medium">{{ $loop->iteration }}</td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                {{ \Carbon\Carbon::parse($history->created_at)->translatedFormat('d M Y H:i') }}
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="rounded bg-blue-100 px-2.5 py-0.5 text-xs font-semibold text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                                                    {{ $history->handling->handling_action ?? '-' }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                {{ $history->handle->name ?? '-' }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-normal">
                                                {{ $history->description ?? '-' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="py-8 text-center text-slate-400 dark:text-zink-400">
                            <i data-lucide="check-circle" class="mx-auto mb-2 h-10 w-10 text-green-500"></i>
                            <p class="text-sm">Siswa ini belum pernah ditindak.</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
@endsection
