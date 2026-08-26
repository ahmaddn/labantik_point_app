@extends('layouts.app')

@section('content')
    <div class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu group-data-[sidebar-size=md]:ltr:ml-vertical-menu-md group-data-[sidebar-size=md]:rtl:mr-vertical-menu-md group-data-[sidebar-size=sm]:ltr:ml-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:mr-vertical-menu-sm px-4 pb-[calc(theme('spacing.header')_*_0.8)] pt-[calc(theme('spacing.header')_*_1)] group-data-[layout=horizontal]:mx-auto group-data-[layout=horizontal]:max-w-screen-2xl group-data-[layout=horizontal]:px-0 group-data-[layout=horizontal]:px-3 group-data-[layout=horizontal]:group-data-[navbar=hidden]:pt-[calc(theme('spacing.header')_*_0.9)] group-data-[navbar=bordered]:pt-[calc(theme('spacing.header')_*_1.3)] group-data-[navbar=hidden]:pt-0 group-data-[layout=horizontal]:md:pt-[calc(theme('spacing.header')_*_1.6)] group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:ltr:md:ml-auto group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:rtl:md:mr-auto">
        <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">

            <!-- Judul Halaman -->
            <div class="flex flex-col gap-2 py-4 md:flex-row md:items-center print:hidden">
                <div class="grow">
                    <h5 class="text-16 font-semibold">Dashboard Siswa</h5>
                </div>
                <ul class="flex shrink-0 items-center gap-2 text-sm font-normal">
                    <li class="dark:text-zink-200 text-slate-400">
                        Portal Siswa
                    </li>
                    <li class="text-slate-400">/</li>
                    <li class="dark:text-zink-100 text-slate-700 font-medium">
                        Dashboard
                    </li>
                </ul>
            </div>

            <!-- Identitas Siswa -->
            <div class="card mb-5 border-none shadow-md bg-gradient-to-r from-blue-900 to-indigo-800 text-white rounded-xl">
                <div class="card-body p-6">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div>
                            <h4 class="text-22 font-bold mb-1">{{ $student->full_name }}</h4>
                            <p class="text-slate-200 text-14 mb-2">NISN: {{ $student->national_student_number ?? '-' }} | NIS: {{ $student->student_number ?? '-' }}</p>
                            <span class="px-3 py-1 bg-white/20 text-white rounded-full text-12 font-medium">
                                Kelas: {{ $studentAcademicYear && $studentAcademicYear->class ? $studentAcademicYear->class->academic_level . ' ' . $studentAcademicYear->class->name : '-' }}
                            </span>
                        </div>
                        <div class="text-right">
                            <p class="text-slate-300 text-12 uppercase tracking-wider font-semibold">Tahun Akademik</p>
                            <p class="text-18 font-bold">{{ $activeConfig ? str_replace('-', '/', $activeConfig->academic_year) : '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Baris Statistik Utama -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-5">
                
                <!-- Card Total Poin Pelanggaran -->
                <div class="card border-none shadow-md rounded-xl bg-white dark:bg-zink-700">
                    <div class="card-body p-5">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <p class="text-slate-500 dark:text-zink-200 text-14 font-medium">Akumulasi Poin Pelanggaran</p>
                                <h3 class="text-28 font-bold mt-1 text-slate-800 dark:text-zink-50">
                                    {{ $totalPoints }} <span class="text-14 font-normal text-slate-500 dark:text-zink-200">/ 100</span>
                                </h3>
                            </div>
                            <div class="p-3 rounded-lg {{ $totalPoints >= 75 ? 'bg-red-500/10 text-red-500' : ($totalPoints >= 30 ? 'bg-amber-500/10 text-amber-500' : 'bg-green-500/10 text-green-500') }}">
                                <i data-lucide="shield-alert" class="w-6 h-6"></i>
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        <div class="w-full bg-slate-100 dark:bg-zink-600 rounded-full h-2.5">
                            <div class="h-2.5 rounded-full {{ $totalPoints >= 75 ? 'bg-red-500' : ($totalPoints >= 30 ? 'bg-amber-500' : 'bg-green-500') }}" style="width: {{ min($totalPoints, 100) }}%"></div>
                        </div>
                        <div class="flex justify-between items-center mt-3 text-12 text-slate-500 dark:text-zink-200">
                            <span>Status: 
                                <strong class="{{ $totalPoints >= 75 ? 'text-red-500' : ($totalPoints >= 30 ? 'text-amber-500' : 'text-green-500') }}">
                                    {{ $totalPoints >= 75 ? 'Sangat Kritis' : ($totalPoints >= 50 ? 'Kritis' : ($totalPoints >= 30 ? 'Waspada' : 'Aman')) }}
                                </strong>
                            </span>
                            <span>Batas: 100 Poin</span>
                        </div>
                    </div>
                </div>

                <!-- Card Status Poin Terverifikasi & Pending -->
                <div class="card border-none shadow-md rounded-xl bg-white dark:bg-zink-700">
                    <div class="card-body p-5">
                        <p class="text-slate-500 dark:text-zink-200 text-14 font-medium mb-3">Status Verifikasi Poin</p>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-3 bg-slate-50 dark:bg-zink-600 rounded-lg">
                                <span class="text-12 text-slate-500 dark:text-zink-200 block">Terverifikasi</span>
                                <span class="text-20 font-bold text-slate-800 dark:text-zink-50 block mt-1">{{ $verifiedPoints }} Poin</span>
                            </div>
                            <div class="p-3 bg-slate-50 dark:bg-zink-600 rounded-lg">
                                <span class="text-12 text-slate-500 dark:text-zink-200 block">Menunggu</span>
                                <span class="text-20 font-bold text-slate-800 dark:text-zink-50 block mt-1">{{ $pendingPoints }} Poin</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card Penanganan / Tindakan Terakhir -->
                <div class="card border-none shadow-md rounded-xl bg-white dark:bg-zink-700">
                    <div class="card-body p-5">
                        <p class="text-slate-500 dark:text-zink-200 text-14 font-medium mb-2">Tindakan / Penanganan Terakhir</p>
                        @if ($latestAction)
                            <div class="bg-blue-50/50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800/30 p-3 rounded-lg">
                                <h6 class="text-14 font-bold text-blue-900 dark:text-blue-200 mb-1">
                                    {{ $latestAction->handling->handling_name ?? 'Pembinaan' }}
                                </h6>
                                <p class="text-12 text-slate-600 dark:text-zink-200 line-clamp-2">
                                    {{ $latestAction->description ?? 'Tidak ada catatan deskripsi.' }}
                                </p>
                                <span class="text-10 text-slate-400 dark:text-zink-300 block mt-2">
                                    Diberikan oleh: {{ $latestAction->handle->name ?? '-' }}
                                </span>
                            </div>
                        @else
                            <div class="bg-green-50/50 dark:bg-green-900/20 border border-green-100 dark:border-green-800/30 p-3 rounded-lg flex items-center gap-3">
                                <div class="p-2 bg-green-500/10 text-green-500 rounded-full">
                                    <i data-lucide="check-circle" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <h6 class="text-14 font-bold text-green-800 dark:text-green-200">Tidak Ada Tindakan</h6>
                                    <p class="text-12 text-slate-500 dark:text-zink-300">Belum ada tindakan pembinaan khusus.</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

            </div>

            <!-- Baris Detail & Grafik -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
                
                <!-- Grafik Distribusi Pelanggaran -->
                <div class="card border-none shadow-md rounded-xl bg-white dark:bg-zink-700 col-span-1 lg:col-span-1">
                    <div class="card-body p-5">
                        <h6 class="text-15 font-semibold mb-4 text-slate-800 dark:text-zink-50">Kategori Pelanggaran</h6>
                        <div id="violationCategoryChart" class="mx-auto"></div>
                    </div>
                </div>

                <!-- Informasi Batas Tindak Lanjut -->
                <div class="card border-none shadow-md rounded-xl bg-white dark:bg-zink-700 col-span-1 lg:col-span-2">
                    <div class="card-body p-5 flex flex-col justify-between h-full">
                        <div>
                            <h6 class="text-15 font-semibold mb-3 text-slate-800 dark:text-zink-50">Informasi Tindak Lanjut Terdekat</h6>
                            @if ($nextHandling)
                                <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-100 dark:border-amber-800/30 p-4 rounded-xl mb-4">
                                    <div class="flex items-start gap-3">
                                        <div class="p-2 bg-amber-500/10 text-amber-600 rounded-lg shrink-0 mt-0.5">
                                            <i data-lucide="info" class="w-5 h-5"></i>
                                        </div>
                                        <div>
                                            <h6 class="text-14 font-bold text-amber-800 dark:text-amber-200">
                                                {{ $nextHandling->handling_name }}
                                            </h6>
                                            <p class="text-13 text-slate-600 dark:text-zink-200 mt-1">
                                                Tindakan ini akan aktif jika poin terverifikasi Anda mencapai atau melewati <strong>{{ $nextHandling->handling_point }} Poin</strong>.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <p class="text-13 text-slate-500 dark:text-zink-200">
                                    Poin terverifikasi saat ini: <strong>{{ $verifiedPoints }} Poin</strong>. Selisih menuju tindakan berikutnya: <strong>{{ $nextHandling->handling_point - $verifiedPoints }} Poin</strong>. Harap jaga perilaku agar tidak menambah poin pelanggaran.
                                </p>
                            @else
                                <div class="bg-green-50 dark:bg-green-900/20 border border-green-100 dark:border-green-800/30 p-4 rounded-xl mb-4">
                                    <div class="flex items-start gap-3">
                                        <div class="p-2 bg-green-500/10 text-green-600 rounded-lg shrink-0 mt-0.5">
                                            <i data-lucide="check-circle" class="w-5 h-5"></i>
                                        </div>
                                        <div>
                                            <h6 class="text-14 font-bold text-green-800 dark:text-green-200">
                                                Semua Terkendali
                                            </h6>
                                            <p class="text-13 text-slate-600 dark:text-zink-200 mt-1">
                                                Tidak ada batas tindak lanjut berikutnya. Pastikan poin Anda tetap terjaga serendah mungkin.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <p class="text-13 text-slate-500 dark:text-zink-200">
                                    Terus pertahankan disiplin dan patuhi tata tertib sekolah demi kelancaran proses pembelajaran Anda.
                                </p>
                            @endif
                        </div>

                        <div class="mt-4 pt-4 border-t border-slate-100 dark:border-zink-600 flex justify-end gap-3">
                            <a href="{{ route('siswa.violations') }}" class="btn bg-blue-100 hover:bg-blue-200 text-blue-800 dark:bg-zink-600 dark:hover:bg-zink-500 dark:text-zink-50 text-12 font-medium px-4 py-2 rounded-lg">
                                Lihat Semua Pelanggaran
                            </a>
                            <a href="{{ route('siswa.actions') }}" class="btn bg-slate-100 hover:bg-slate-200 text-slate-800 dark:bg-zink-600 dark:hover:bg-zink-500 dark:text-zink-50 text-12 font-medium px-4 py-2 rounded-lg">
                                Lihat Semua Tindakan
                            </a>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var options = {
                series: [{{ $categoryDistribution['Ringan'] }}, {{ $categoryDistribution['Sedang'] }}, {{ $categoryDistribution['Berat'] }}],
                labels: ['Ringan', 'Sedang', 'Berat'],
                chart: {
                    type: 'donut',
                    height: 260,
                },
                colors: ['#3b82f6', '#f59e0b', '#ef4444'],
                plotOptions: {
                    pie: {
                        donut: {
                            size: '70%',
                        }
                    }
                },
                dataLabels: {
                    enabled: true
                },
                legend: {
                    position: 'bottom',
                    fontSize: '13px'
                }
            };

            if (document.getElementById("violationCategoryChart")) {
                var chart = new ApexCharts(document.querySelector("#violationCategoryChart"), options);
                chart.render();
            }
        });
    </script>
@endsection
