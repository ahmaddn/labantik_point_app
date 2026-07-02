@extends('layouts.app')
@section('content')
    <div
        class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu group-data-[sidebar-size=md]:ltr:ml-vertical-menu-md group-data-[sidebar-size=md]:rtl:mr-vertical-menu-md group-data-[sidebar-size=sm]:ltr:ml-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:mr-vertical-menu-sm pt-[calc(theme('spacing.header')_*_1)] pb-[calc(theme('spacing.header')_*_0.8)] px-4 group-data-[navbar=bordered]:pt-[calc(theme('spacing.header')_*_1.3)] group-data-[navbar=hidden]:pt-0 group-data-[layout=horizontal]:mx-auto group-data-[layout=horizontal]:max-w-screen-2xl group-data-[layout=horizontal]:px-0 group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:ltr:md:ml-auto group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:rtl:md:mr-auto group-data-[layout=horizontal]:md:pt-[calc(theme('spacing.header')_*_1.6)] group-data-[layout=horizontal]:px-3 group-data-[layout=horizontal]:group-data-[navbar=hidden]:pt-[calc(theme('spacing.header')_*_0.9)]">
        <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">

            <div class="flex flex-col gap-2 py-4 md:flex-row md:items-center print:hidden">
                <div class="grow">
                    <h5 class="text-16">Dashboard Pelanggaran</h5>
                </div>
                <ul class="flex items-center gap-2 text-sm font-normal shrink-0">
                    <li
                        class="relative before:content-['\ea54'] before:font-remix ltr:before:-right-1 rtl:before:-left-1 before:absolute before:text-[18px] before:-top-[3px] ltr:pr-4 rtl:pl-4 before:text-slate-400 dark:text-zink-200">
                        <a href="#!" class="text-slate-400 dark:text-zink-200">Dashboards</a>
                    </li>
                    <li class="text-slate-700 dark:text-zink-100">
                        Super Admin
                    </li>
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
                <div class="order-0 md:col-span-6 lg:col-span-3 col-span-12 bg-blue-100 dark:bg-blue-500/20 card relative overflow-hidden">
                    <div class="card-body">
                        <i data-lucide="users" class="absolute top-0 stroke-1 size-32 text-blue-200/50 dark:text-blue-500/20 ltr:-right-10 rtl:-left-10"></i>
                        <div class="flex items-center justify-center bg-blue-500 rounded-md size-12 text-15 text-blue-50">
                            <i data-lucide="users"></i>
                        </div>
                        <h5 class="mt-5 mb-2">
                            <span class="counter-value" data-target="{{ $totalActiveStudents }}">0</span>
                        </h5>
                        <p class="text-slate-500 dark:text-slate-200">Total Siswa Aktif</p>
                    </div>
                </div>

                <!-- Total Pelanggaran -->
                <div
                    class="order-1 md:col-span-6 lg:col-span-3 col-span-12 bg-red-100 dark:bg-red-500/20 card relative overflow-hidden">
                    <div class="card-body">
                        <i data-lucide="alert-triangle"
                            class="absolute top-0 stroke-1 size-32 text-red-200/50 dark:text-red-500/20 ltr:-right-10 rtl:-left-10"></i>
                        <div class="flex items-center justify-center bg-red-500 rounded-md size-12 text-15 text-red-50">
                            <i data-lucide="alert-circle"></i>
                        </div>
                        <h5 class="mt-5 mb-2">
                            <span class="counter-value" data-target="{{ $totalViolations }}">0</span>
                        </h5>
                        <p class="text-slate-500 dark:text-slate-200">Total Pelanggaran</p>
                    </div>
                </div>

                <!-- Siswa Tanpa Pelanggaran -->
                <div
                    class="order-2 md:col-span-6 lg:col-span-3 col-span-12 bg-green-100 dark:bg-green-500/20 card relative overflow-hidden">
                    <div class="card-body">
                        <i data-lucide="check-circle"
                            class="absolute top-0 stroke-1 size-32 text-green-200/50 dark:text-green-500/20 ltr:-right-10 rtl:-left-10"></i>
                        <div class="flex items-center justify-center bg-green-500 rounded-md size-12 text-15 text-green-50">
                            <i data-lucide="user-check"></i>
                        </div>
                        <h5 class="mt-5 mb-2">
                            <span class="counter-value" data-target="{{ $studentsWithoutViolations }}">0</span>
                        </h5>
                        <p class="text-slate-500 dark:text-slate-200">Siswa Tanpa Pelanggaran</p>
                    </div>
                </div>

                <!-- Menunggu Verifikasi -->
                <div
                    class="order-3 md:col-span-6 lg:col-span-3 col-span-12 bg-orange-100 dark:bg-orange-500/20 card relative overflow-hidden">
                    <div class="card-body">
                        <i data-lucide="clock"
                            class="absolute top-0 stroke-1 size-32 text-orange-200/50 dark:text-orange-500/20 ltr:-right-10 rtl:-left-10"></i>
                        <div
                            class="flex items-center justify-center bg-orange-500 rounded-md size-12 text-15 text-orange-50">
                            <i data-lucide="clock"></i>
                        </div>
                        <h5 class="mt-5 mb-2">
                            <span class="counter-value" data-target="{{ $pendingViolationsCount }}">0</span>
                        </h5>
                        <p class="text-slate-500 dark:text-slate-200">Menunggu Verifikasi</p>
                    </div>
                </div>

                <!-- Kelas dengan Poin Terbanyak -->
                <div
                    class="order-4 md:col-span-6 lg:col-span-4 col-span-12 bg-yellow-100 dark:bg-yellow-500/20 card relative overflow-hidden">
                    <div class="card-body">
                        <i data-lucide="home"
                            class="absolute top-0 stroke-1 size-32 text-yellow-200/50 dark:text-yellow-500/20 ltr:-right-10 rtl:-left-10"></i>
                        <div
                            class="flex items-center justify-center bg-yellow-500 rounded-md size-12 text-15 text-yellow-50">
                            <i data-lucide="home"></i>
                        </div>
                        @if ($topClass)
                            <h5 class="mt-5 mb-1">{{ $topClass->class_name }}</h5>
                            <p class="text-lg font-semibold text-yellow-600">
                                <span class="counter-value" data-target="{{ $topClass->total_points }}">0</span> Poin
                            </p>
                        @else
                            <h5 class="mt-5 mb-1">-</h5>
                            <p class="text-lg font-semibold text-yellow-600">0 Poin</p>
                        @endif
                        <p class="text-slate-500 dark:text-slate-200">Kelas Poin Terbanyak</p>
                    </div>
                </div>
                <!-- Siswa dengan Poin Terbanyak -->
                <div
                    class="order-5 md:col-span-6 lg:col-span-4 col-span-12 bg-purple-100 dark:bg-purple-500/20 card relative overflow-hidden">
                    <div class="card-body">
                        <i data-lucide="user-x"
                            class="absolute top-0 stroke-1 size-32 text-purple-200/50 dark:text-purple-500/20 ltr:-right-10 rtl:-left-10"></i>
                        <div
                            class="flex items-center justify-center bg-purple-500 rounded-md size-12 text-15 text-purple-50">
                            <i data-lucide="user-x"></i>
                        </div>
                        @if ($topStudent)
                            <h5 class="mt-5 mb-1">{{ $topStudent->student_name }}</h5>
                            <p class="text-sm text-slate-600 dark:text-slate-300 mb-1">
                                {{ $topStudent->nis }} | {{ $topStudent->class_name }}
                            </p>
                            <p class="text-lg font-semibold text-purple-600">
                                <span class="counter-value" data-target="{{ $topStudent->total_points }}">0</span> Poin
                            </p>
                        @else
                            <h5 class="mt-5 mb-1">-</h5>
                            <p class="text-sm text-slate-600 dark:text-slate-300 mb-1">NIS: - | Kelas: -</p>
                            <p class="text-lg font-semibold text-purple-600">0 Poin</p>
                        @endif
                        <p class="text-slate-500 dark:text-slate-200">Siswa Poin Terbanyak</p>
                    </div>
                </div>

                <!-- Pelanggaran Paling Sering -->
                <div
                    class="order-6 md:col-span-6 lg:col-span-4 col-span-12 bg-sky-100 dark:bg-sky-500/20 card relative overflow-hidden">
                    <div class="card-body">
                        <i data-lucide="trending-up"
                            class="absolute top-0 stroke-1 size-32 text-sky-200/50 dark:text-sky-500/20 ltr:-right-10 rtl:-left-10"></i>
                        <div class="flex items-center justify-center rounded-md size-12 bg-sky-500 text-15 text-sky-50">
                            <i data-lucide="bar-chart-3"></i>
                        </div>
                        @if ($mostFrequentViolation)
                            <h5 class="mt-5 mb-1">{{ $mostFrequentViolation->violation_name }}</h5>
                            <p class="text-sm text-slate-600 dark:text-slate-300 mb-1">
                                {{ $mostFrequentViolation->category_name }} | {{ $mostFrequentViolation->point }} Poin
                            </p>
                            <p class="text-lg font-semibold text-sky-600">
                                <span class="counter-value"
                                    data-target="{{ $mostFrequentViolation->violation_count }}">0</span> Kali
                            </p>
                        @else
                            <h5 class="mt-5 mb-1">-</h5>
                            <p class="text-sm text-slate-600 dark:text-slate-300 mb-1">- | 0 Poin</p>
                            <p class="text-lg font-semibold text-sky-600">0 Kali</p>
                        @endif
                        <p class="text-slate-500 dark:text-slate-200">Pelanggaran Tersering</p>
                    </div>
                </div>
            </div>

            <!-- Additional Dashboard Section (Chart and Lists) -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-x-5 mt-5">
                <!-- Chart Distribusi Pelanggaran -->
                <div class="lg:col-span-2 bg-white dark:bg-zink-700 card relative overflow-hidden">
                    <div class="card-body">
                        <h5 class="mb-4 text-16">Distribusi Kategori Pelanggaran</h5>
                        @if(array_sum($categoryDistribution) > 0)
                            <div id="violationCategoryChart" class="apex-charts" dir="ltr"></div>
                        @else
                            <div class="flex items-center justify-center h-64">
                                <p class="text-slate-500">Belum ada data pelanggaran.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Kelas Perlu Dievaluasi -->
                <div class="lg:col-span-1 bg-white dark:bg-zink-700 card relative overflow-hidden">
                    <div class="card-body">
                        <h5 class="mb-4 text-16">Kelas Perlu Dievaluasi</h5>
                        <div class="flex flex-col gap-3">
                            @forelse ($classesToEvaluate as $className => $points)
                                @if($points > 0)
                                <div class="flex items-center justify-between p-3 rounded-md bg-slate-50 dark:bg-zink-600 border border-slate-100 dark:border-zink-500">
                                    <div class="font-medium">{{ $className }}</div>
                                    <div class="text-orange-500 font-semibold">{{ $points }} Poin</div>
                                </div>
                                @endif
                            @empty
                                <div class="text-center text-slate-500 py-4">Belum ada data pelanggaran.</div>
                            @endforelse
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
            series: [{{ $categoryDistribution['Ringan'] ?? 0 }}, {{ $categoryDistribution['Sedang'] ?? 0 }}, {{ $categoryDistribution['Berat'] ?? 0 }}],
            labels: ['Ringan', 'Sedang', 'Berat'],
            chart: {
                type: 'donut',
                height: 300,
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
