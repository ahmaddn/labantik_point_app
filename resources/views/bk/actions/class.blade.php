@extends('layouts.app')
@section('content')
    <div
        class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu group-data-[sidebar-size=md]:ltr:ml-vertical-menu-md group-data-[sidebar-size=md]:rtl:mr-vertical-menu-md group-data-[sidebar-size=sm]:ltr:ml-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:mr-vertical-menu-sm px-4 pb-[calc(theme('spacing.header')_*_0.8)] pt-[calc(theme('spacing.header')_*_1)] group-data-[layout=horizontal]:mx-auto group-data-[layout=horizontal]:max-w-screen-2xl group-data-[layout=horizontal]:px-0 group-data-[layout=horizontal]:px-3 group-data-[layout=horizontal]:group-data-[navbar=hidden]:pt-[calc(theme('spacing.header')_*_0.9)] group-data-[navbar=bordered]:pt-[calc(theme('spacing.header')_*_1.3)] group-data-[navbar=hidden]:pt-0 group-data-[layout=horizontal]:md:pt-[calc(theme('spacing.header')_*_1.6)] group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:ltr:md:ml-auto group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:rtl:md:mr-auto">
        <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">

            <!-- Breadcrumb -->
            <div class="flex flex-col gap-2 py-4 md:flex-row md:items-center print:hidden">
                <div class="grow flex items-center gap-3">
                    <a href="{{ route('kesiswaan-bk.actions') }}" class="btn bg-slate-100 hover:bg-slate-200 text-slate-700 dark:bg-zink-600 dark:hover:bg-zink-500 dark:text-zink-200 px-3 py-1.5 rounded-lg text-sm flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                        Kembali
                    </a>
                    <h5 class="text-16">Daftar Tindakan Kelas {{ $class->academic_level }} {{ $class->name }}</h5>
                </div>
                <ul class="flex shrink-0 items-center gap-2 text-sm font-normal">
                    <li
                        class="before:font-remix dark:text-zink-200 relative before:absolute before:-top-[3px] before:text-[18px] before:text-slate-400 before:content-['\ea54'] ltr:pr-4 ltr:before:-right-1 rtl:pl-4 rtl:before:-left-1">
                        <a href="#!" class="dark:text-zink-200 text-slate-400">Dashboards</a>
                    </li>
                    <li
                        class="before:font-remix dark:text-zink-200 relative before:absolute before:-top-[3px] before:text-[18px] before:text-slate-400 before:content-['\ea54'] ltr:pr-4 ltr:before:-right-1 rtl:pl-4 rtl:before:-left-1">
                        <a href="{{ route('kesiswaan-bk.actions') }}" class="dark:text-zink-200 text-slate-400">Daftar Tindakan</a>
                    </li>
                    <li class="dark:text-zink-100 text-slate-700">
                        {{ $class->academic_level }} {{ $class->name }}
                    </li>
                </ul>
            </div>

            <!-- Data Table Card -->
            <div class="card">
                <div class="card-body">
                    <div class="mb-4">
                        <h6 class="text-15 font-bold text-slate-800 dark:text-slate-100">Detail Tindakan Pendisiplinan Kelas {{ $class->academic_level }} {{ $class->name }}</h6>
                    </div>

                    @if (count($students) > 0)
                        <div class="w-full overflow-x-hidden">
                            <table id="hoverableTable" style="width: 100%" class="hover group">
                                <thead class="dark:bg-zink-700 bg-slate-50 text-xs uppercase">
                                    <tr>
                                        <th scope="col" class="dark:text-zink-200 px-4 py-4 font-semibold text-slate-700">Nama Siswa</th>
                                        <th scope="col" class="dark:text-zink-200 px-4 py-4 font-semibold text-slate-700">NIS</th>
                                        <th scope="col" class="dark:text-zink-200 px-4 py-4 font-semibold text-slate-700 text-center">Total Poin Terverifikasi</th>
                                        <th scope="col" class="dark:text-zink-200 px-4 py-4 font-semibold text-slate-700 text-center">Frekuensi Penindakan</th>
                                        <th scope="col" class="dark:text-zink-200 px-4 py-4 font-semibold text-slate-700 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($students as $student)
                                        <tr class="dark:bg-zink-800 dark:border-zink-700 dark:hover:bg-zink-700 border-b bg-white hover:bg-slate-50">
                                            <td class="whitespace-normal px-4 py-4 font-bold text-slate-800 dark:text-slate-100">
                                                {{ $student->student->full_name ?? '-' }}
                                            </td>
                                            <td class="px-4 py-4 text-slate-600 dark:text-zink-300">
                                                {{ $student->student->student_number ?? '-' }}
                                            </td>
                                            <td class="px-4 py-4 text-center font-bold text-red-600 dark:text-red-400">
                                                {{ $student->total_points_verified }} Poin
                                            </td>
                                            <td class="px-4 py-4 text-center">
                                                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700 dark:bg-zink-600 dark:text-zink-200">
                                                    {{ $student->actions->count() }} Kali Ditindak
                                                </span>
                                            </td>
                                            <td class="px-4 py-4 text-center flex justify-center">
                                                <a href="{{ route('kesiswaan-bk.recaps.detail', $student->id) }}"
                                                    class="btn dark:bg-zink-700 flex size-[37.5px] items-center justify-center rounded-full border border-slate-500 bg-white p-0 text-slate-500 hover:border-slate-600 hover:bg-slate-600 hover:text-white"
                                                    title="Lihat Detail Tindakan">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0" />
                                                        <circle cx="12" cy="12" r="3" />
                                                    </svg>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="py-8 text-center">
                            <div class="dark:text-zink-400 flex flex-col items-center text-slate-500">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" class="mb-2">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                    <polyline points="14 2 14 8 20 8"></polyline>
                                </svg>
                                <p class="text-sm">Tidak ada data tindakan untuk kelas ini</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
