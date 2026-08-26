@extends('layouts.app')
@section('content')
    <div
        class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu group-data-[sidebar-size=md]:ltr:ml-vertical-menu-md group-data-[sidebar-size=md]:rtl:mr-vertical-menu-md group-data-[sidebar-size=sm]:ltr:ml-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:mr-vertical-menu-sm px-4 pb-[calc(theme('spacing.header')_*_0.8)] pt-[calc(theme('spacing.header')_*_1)] group-data-[layout=horizontal]:mx-auto group-data-[layout=horizontal]:max-w-screen-2xl group-data-[layout=horizontal]:px-0 group-data-[layout=horizontal]:px-3 group-data-[layout=horizontal]:group-data-[navbar=hidden]:pt-[calc(theme('spacing.header')_*_0.9)] group-data-[navbar=bordered]:pt-[calc(theme('spacing.header')_*_1.3)] group-data-[navbar=hidden]:pt-0 group-data-[layout=horizontal]:md:pt-[calc(theme('spacing.header')_*_1.6)] group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:ltr:md:ml-auto group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:rtl:md:mr-auto">
        <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">

            <div class="flex flex-col gap-2 py-4 md:flex-row md:items-center print:hidden">
                <div class="grow">
                    <h5 class="text-16 font-semibold">Dashboard Wali Kelas: {{ $class->academic_level }} {{ $class->name }}</h5>
                </div>
                <ul class="flex shrink-0 items-center gap-2 text-sm font-normal">
                    <li class="dark:text-zink-200 text-slate-400">Portal Wali Kelas</li>
                    <li class="text-slate-400">/</li>
                    <li class="dark:text-zink-100 text-slate-700 font-medium">Dashboard</li>
                </ul>
            </div>

            <style>
                .text-blue-200\/50 { color: rgb(191 219 254 / 0.5) !important; }
                .text-red-200\/50 { color: rgb(254 202 202 / 0.5) !important; }
                .text-yellow-200\/50 { color: rgb(254 240 138 / 0.5) !important; }
                html.dark .dark\:text-blue-500\/20 { color: rgb(59 130 246 / 0.2) !important; }
                html.dark .dark\:text-red-500\/20 { color: rgb(239 68 68 / 0.2) !important; }
                html.dark .dark\:text-yellow-500\/20 { color: rgb(234 179 8 / 0.2) !important; }
            </style>

            <div class="grid grid-cols-12 gap-x-5">
                <!-- Total Siswa Aktif -->
                <div class="card relative order-0 col-span-12 overflow-hidden bg-blue-100 md:col-span-6 lg:col-span-3 dark:bg-blue-500/20">
                    <div class="card-body">
                        <i data-lucide="users" class="absolute top-0 size-32 stroke-1 text-blue-200/50 ltr:-right-10 rtl:-left-10 dark:text-blue-500/20"></i>
                        <div class="text-15 flex size-12 items-center justify-center rounded-md bg-blue-500 text-blue-50">
                            <i data-lucide="users"></i>
                        </div>
                        <h5 class="mb-2 mt-5">
                            <span class="counter-value" data-target="{{ $totalActiveStudents }}">0</span>
                        </h5>
                        <p class="text-slate-500 dark:text-slate-200">Total Siswa Bimbingan</p>
                    </div>
                </div>

                <!-- Total Pelanggaran -->
                <div class="card relative order-1 col-span-12 overflow-hidden bg-red-100 md:col-span-6 lg:col-span-3 dark:bg-red-500/20">
                    <div class="card-body">
                        <i data-lucide="alert-triangle" class="absolute top-0 size-32 stroke-1 text-red-200/50 ltr:-right-10 rtl:-left-10 dark:text-red-500/20"></i>
                        <div class="text-15 flex size-12 items-center justify-center rounded-md bg-red-500 text-red-50">
                            <i data-lucide="alert-circle"></i>
                        </div>
                        <h5 class="mb-2 mt-5">
                            <span class="counter-value" data-target="{{ $totalViolations }}">0</span>
                        </h5>
                        <p class="text-slate-500 dark:text-slate-200">Total Pelanggaran</p>
                    </div>
                </div>

                <!-- Siswa Tanpa Pelanggaran -->
                <div class="card relative order-2 col-span-12 overflow-hidden bg-green-100 md:col-span-6 lg:col-span-3 dark:bg-green-500/20">
                    <div class="card-body">
                        <i data-lucide="check-circle" class="absolute top-0 size-32 stroke-1 text-green-200/50 ltr:-right-10 rtl:-left-10 dark:text-green-500/20"></i>
                        <div class="text-15 flex size-12 items-center justify-center rounded-md bg-green-500 text-green-50">
                            <i data-lucide="user-check"></i>
                        </div>
                        <h5 class="mb-2 mt-5">
                            <span class="counter-value" data-target="{{ $studentsWithoutViolations }}">0</span>
                        </h5>
                        <p class="text-slate-500 dark:text-slate-200">Siswa Tanpa Pelanggaran</p>
                    </div>
                </div>

                <!-- Menunggu Verifikasi -->
                <div class="card relative order-3 col-span-12 overflow-hidden bg-orange-100 md:col-span-6 lg:col-span-3 dark:bg-orange-500/20">
                    <div class="card-body">
                        <i data-lucide="clock" class="absolute top-0 size-32 stroke-1 text-orange-200/50 ltr:-right-10 rtl:-left-10 dark:text-orange-500/20"></i>
                        <div class="text-15 flex size-12 items-center justify-center rounded-md bg-orange-500 text-orange-50">
                            <i data-lucide="clock"></i>
                        </div>
                        <h5 class="mb-2 mt-5">
                            <span class="counter-value" data-target="{{ $pendingViolationsCount }}">0</span>
                        </h5>
                        <p class="text-slate-500 dark:text-slate-200">Menunggu Verifikasi</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-12 gap-x-5 mt-5">
                <!-- Siswa dengan Poin Terbanyak -->
                <div class="card col-span-12 md:col-span-6 lg:col-span-4 dark:bg-zink-700 bg-white shadow-md rounded-xl">
                    <div class="card-body p-5">
                        <h6 class="text-15 font-semibold mb-4 text-slate-800 dark:text-zink-50">Siswa dengan Poin Terbanyak</h6>
                        @if ($topStudent)
                            <div class="flex items-center gap-3">
                                <div class="p-3 bg-red-500/10 text-red-500 rounded-lg">
                                    <i data-lucide="user" class="w-6 h-6"></i>
                                </div>
                                <div>
                                    <h6 class="text-15 font-bold text-slate-900 dark:text-zink-50">{{ $topStudent->student_name }}</h6>
                                    <p class="text-12 text-slate-500 dark:text-zink-300">NIS: {{ $topStudent->nis }}</p>
                                    <span class="mt-1 block text-14 font-semibold text-red-500">{{ $topStudent->total_points }} Poin</span>
                                </div>
                            </div>
                        @else
                            <div class="text-center text-slate-500 py-6">Belum ada data siswa dengan poin.</div>
                        @endif
                    </div>
                </div>

                <!-- Pelanggaran Tersering -->
                <div class="card col-span-12 md:col-span-6 lg:col-span-4 dark:bg-zink-700 bg-white shadow-md rounded-xl">
                    <div class="card-body p-5">
                        <h6 class="text-15 font-semibold mb-4 text-slate-800 dark:text-zink-50">Pelanggaran Tersering</h6>
                        @if ($mostFrequentViolation)
                            <div class="flex items-center gap-3">
                                <div class="p-3 bg-amber-500/10 text-amber-500 rounded-lg">
                                    <i data-lucide="alert-triangle" class="w-6 h-6"></i>
                                </div>
                                <div>
                                    <h6 class="text-14 font-bold text-slate-900 dark:text-zink-50 line-clamp-1">{{ $mostFrequentViolation->violation_name }}</h6>
                                    <p class="text-12 text-slate-500 dark:text-zink-300">Bobot: {{ $mostFrequentViolation->point }} Poin</p>
                                    <span class="mt-1 block text-13 font-semibold text-amber-500">Telah Dilakukan {{ $mostFrequentViolation->violation_count }} Kali</span>
                                </div>
                            </div>
                        @else
                            <div class="text-center text-slate-500 py-6">Belum ada pelanggaran terverifikasi.</div>
                        @endif
                    </div>
                </div>

                <!-- Grafik Distribusi -->
                <div class="card col-span-12 lg:col-span-4 dark:bg-zink-700 bg-white shadow-md rounded-xl">
                    <div class="card-body p-5">
                        <h6 class="text-15 font-semibold mb-4 text-slate-800 dark:text-zink-50">Distribusi Kategori Pelanggaran</h6>
                        <div id="violationCategoryChart" class="mx-auto"></div>
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
            series: [{{ $categoryDistribution['Ringan'] ?? 0 }}, {{ $categoryDistribution['Sedang'] ?? 0 }}, {{ $categoryDistribution['Berat'] ?? 0 }}],
            labels: ['Ringan', 'Sedang', 'Berat'],
            chart: {
                type: 'donut',
                height: 240,
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
                position: 'bottom'
            }
        };

        if (document.getElementById("violationCategoryChart")) {
            var chart = new ApexCharts(document.querySelector("#violationCategoryChart"), options);
            chart.render();
        }
    });
</script>
@endsection
