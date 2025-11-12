@extends('layouts.app')
@section('content')
    <div
        class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu group-data-[sidebar-size=md]:ltr:ml-vertical-menu-md group-data-[sidebar-size=md]:rtl:mr-vertical-menu-md group-data-[sidebar-size=sm]:ltr:ml-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:mr-vertical-menu-sm px-4 pb-[calc(theme('spacing.header')_*_0.8)] pt-[calc(theme('spacing.header')_*_1)] group-data-[layout=horizontal]:mx-auto group-data-[layout=horizontal]:max-w-screen-2xl group-data-[layout=horizontal]:px-0 group-data-[layout=horizontal]:px-3 group-data-[layout=horizontal]:group-data-[navbar=hidden]:pt-[calc(theme('spacing.header')_*_0.9)] group-data-[navbar=bordered]:pt-[calc(theme('spacing.header')_*_1.3)] group-data-[navbar=hidden]:pt-0 group-data-[layout=horizontal]:md:pt-[calc(theme('spacing.header')_*_1.6)] group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:ltr:md:ml-auto group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:rtl:md:mr-auto">
        <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">

            <div class="flex flex-col gap-2 py-4 md:flex-row md:items-center print:hidden">
                <div class="grow">
                    <h5 class="text-16">Dashboard Pelanggaran</h5>
                </div>
                <ul class="flex shrink-0 items-center gap-2 text-sm font-normal">
                    <li
                        class="before:font-remix dark:text-zink-200 relative before:absolute before:-top-[3px] before:text-[18px] before:text-slate-400 before:content-['\ea54'] ltr:pr-4 ltr:before:-right-1 rtl:pl-4 rtl:before:-left-1">
                        <a href="#!" class="dark:text-zink-200 text-slate-400">Dashboards</a>
                    </li>
                    <li class="dark:text-zink-100 text-slate-700">
                        BK
                    </li>
                </ul>
            </div>

            <div class="grid grid-cols-12 gap-x-5">
                <!-- Total Pelanggaran -->
                <div
                    class="card relative order-1 col-span-12 overflow-hidden bg-red-100 md:col-span-6 lg:col-span-4 dark:bg-red-500/20">
                    <div class="card-body">
                        <i data-lucide="alert-triangle"
                            class="absolute top-0 size-32 stroke-1 text-red-200/50 ltr:-right-10 rtl:-left-10 dark:text-red-500/20"></i>
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
                <div
                    class="card relative order-2 col-span-12 overflow-hidden bg-green-100 md:col-span-6 lg:col-span-4 dark:bg-green-500/20">
                    <div class="card-body">
                        <i data-lucide="check-circle"
                            class="absolute top-0 size-32 stroke-1 text-green-200/50 ltr:-right-10 rtl:-left-10 dark:text-green-500/20"></i>
                        <div class="text-15 flex size-12 items-center justify-center rounded-md bg-green-500 text-green-50">
                            <i data-lucide="user-check"></i>
                        </div>
                        <h5 class="mb-2 mt-5">
                            <span class="counter-value" data-target="{{ $studentsWithoutViolations }}">0</span>
                        </h5>
                        <p class="text-slate-500 dark:text-slate-200">Siswa Tanpa Pelanggaran</p>
                    </div>
                </div>

                <!-- Kelas dengan Poin Terbanyak -->
                <div
                    class="card relative order-3 col-span-12 overflow-hidden bg-orange-100 md:col-span-6 lg:col-span-4 dark:bg-orange-500/20">
                    <div class="card-body">
                        <i data-lucide="users"
                            class="absolute top-0 size-32 stroke-1 text-orange-200/50 ltr:-right-10 rtl:-left-10 dark:text-orange-500/20"></i>
                        <div
                            class="text-15 flex size-12 items-center justify-center rounded-md bg-orange-500 text-orange-50">
                            <i data-lucide="home"></i>
                        </div>
                        @if ($topClass)
                            <h5 class="mb-1 mt-5">{{ $topClass->class_name }}</h5>
                            <p class="text-lg font-semibold text-orange-600">
                                <span class="counter-value" data-target="{{ $topClass->total_points }}">0</span> Poin
                            </p>
                        @else
                            <h5 class="mb-1 mt-5">-</h5>
                            <p class="text-lg font-semibold text-orange-600">0 Poin</p>
                        @endif
                        <p class="text-slate-500 dark:text-slate-200">Kelas Poin Terbanyak</p>
                    </div>
                </div>
                <!-- Siswa dengan Poin Terbanyak -->
                <div
                    class="card relative order-4 col-span-12 overflow-hidden bg-purple-100 md:col-span-6 lg:col-span-6 dark:bg-purple-500/20">
                    <div class="card-body">
                        <i data-lucide="user-x"
                            class="absolute top-0 size-32 stroke-1 text-purple-200/50 ltr:-right-10 rtl:-left-10 dark:text-purple-500/20"></i>
                        <div
                            class="text-15 flex size-12 items-center justify-center rounded-md bg-purple-500 text-purple-50">
                            <i data-lucide="user-x"></i>
                        </div>
                        @if ($topStudent)
                            <h5 class="mb-1 mt-5">{{ $topStudent->student_name }}</h5>
                            <p class="mb-1 text-sm text-slate-600 dark:text-slate-300">
                                {{ $topStudent->nis }} | {{ $topStudent->class_name }}
                            </p>
                            <p class="text-lg font-semibold text-purple-600">
                                <span class="counter-value" data-target="{{ $topStudent->total_points }}">0</span> Poin
                            </p>
                        @else
                            <h5 class="mb-1 mt-5">-</h5>
                            <p class="mb-1 text-sm text-slate-600 dark:text-slate-300">NIS: - | Kelas: -</p>
                            <p class="text-lg font-semibold text-purple-600">0 Poin</p>
                        @endif
                        <p class="text-slate-500 dark:text-slate-200">Siswa Poin Terbanyak</p>
                    </div>
                </div>

                <!-- Pelanggaran Paling Sering -->
                <div
                    class="card relative order-5 col-span-12 overflow-hidden bg-sky-100 md:col-span-6 lg:col-span-6 dark:bg-sky-500/20">
                    <div class="card-body">
                        <i data-lucide="trending-up"
                            class="absolute top-0 size-32 stroke-1 text-sky-200/50 ltr:-right-10 rtl:-left-10 dark:text-sky-500/20"></i>
                        <div class="text-15 flex size-12 items-center justify-center rounded-md bg-sky-500 text-sky-50">
                            <i data-lucide="bar-chart-3"></i>
                        </div>
                        @if ($mostFrequentViolation)
                            <h5 class="mb-1 mt-5">{{ $mostFrequentViolation->violation_name }}</h5>
                            <p class="mb-1 text-sm text-slate-600 dark:text-slate-300">
                                {{ $mostFrequentViolation->category_name }} | {{ $mostFrequentViolation->point }} Poin
                            </p>
                            <p class="text-lg font-semibold text-sky-600">
                                <span class="counter-value"
                                    data-target="{{ $mostFrequentViolation->violation_count }}">0</span> Kali
                            </p>
                        @else
                            <h5 class="mb-1 mt-5">-</h5>
                            <p class="mb-1 text-sm text-slate-600 dark:text-slate-300">- | 0 Poin</p>
                            <p class="text-lg font-semibold text-sky-600">0 Kali</p>
                        @endif
                        <p class="text-slate-500 dark:text-slate-200">Pelanggaran Tersering</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
