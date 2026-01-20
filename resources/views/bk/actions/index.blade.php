@extends('layouts.app')
@section('content')
    <div
        class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu group-data-[sidebar-size=md]:ltr:ml-vertical-menu-md group-data-[sidebar-size=md]:rtl:mr-vertical-menu-md group-data-[sidebar-size=sm]:ltr:ml-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:mr-vertical-menu-sm px-4 pb-[calc(theme('spacing.header')_*_0.8)] pt-[calc(theme('spacing.header')_*_1)] group-data-[layout=horizontal]:mx-auto group-data-[layout=horizontal]:max-w-screen-2xl group-data-[layout=horizontal]:px-0 group-data-[layout=horizontal]:px-3 group-data-[layout=horizontal]:group-data-[navbar=hidden]:pt-[calc(theme('spacing.header')_*_0.9)] group-data-[navbar=bordered]:pt-[calc(theme('spacing.header')_*_1.3)] group-data-[navbar=hidden]:pt-0 group-data-[layout=horizontal]:md:pt-[calc(theme('spacing.header')_*_1.6)] group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:ltr:md:ml-auto group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:rtl:md:mr-auto">
        <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">

            <!-- Breadcrumb -->
            <div class="flex flex-col gap-2 py-4 md:flex-row md:items-center print:hidden">
                <div class="grow">
                    <h5 class="text-16">Daftar Tindakan</h5>
                </div>
                <ul class="flex shrink-0 items-center gap-2 text-sm font-normal">
                    <li
                        class="before:font-remix dark:text-zink-200 relative before:absolute before:-top-[3px] before:text-[18px] before:text-slate-400 before:content-['\ea54'] ltr:pr-4 ltr:before:-right-1 rtl:pl-4 rtl:before:-left-1">
                        <a href="#!" class="dark:text-zink-200 text-slate-400">Dashboards</a>
                    </li>
                    <li
                        class="before:font-remix dark:text-zink-200 relative before:absolute before:-top-[3px] before:text-[18px] before:text-slate-400 before:content-['\ea54'] ltr:pr-4 ltr:before:-right-1 rtl:pl-4 rtl:before:-left-1">
                        <a href="#!" class="dark:text-zink-200 text-slate-400">Kesiswaan & BK</a>
                    </li>
                    <li class="dark:text-zink-100 text-slate-700">
                        Daftar Tindakan
                    </li>
                </ul>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="mb-4 flex justify-between gap-2">
                        <h6 class="text-15 mb-4">Daftar Tindakan</h6>
                    </div>

                    @if (count($actions) > 0)
                        <table id="hoverableTable" style="width: 100%" class="hover group">
                            <thead class="dark:bg-zink-700 bg-slate-50 text-xs uppercase">
                                <tr>
                                    <th scope="col" class="dark:text-zink-200 px-4 py-4 font-semibold text-slate-700">
                                        No
                                    </th>
                                    <th scope="col" class="dark:text-zink-200 px-4 py-4 font-semibold text-slate-700">
                                        Nama Siswa
                                    </th>
                                    <th scope="col"
                                        class="dark:text-zink-200 px-4 py-4 text-center font-semibold text-slate-700">
                                        Kelas
                                    </th>
                                    <th scope="col"
                                        class="dark:text-zink-200 px-4 py-4 text-center font-semibold text-slate-700">
                                        Pelanggaran
                                    </th>
                                    <th scope="col"
                                        class="dark:text-zink-200 px-4 py-4 text-center font-semibold text-slate-700">
                                        Tindakan
                                    </th>
                                    <th scope="col"
                                        class="dark:text-zink-200 px-4 py-4 text-center font-semibold text-slate-700">
                                        Dibuat Oleh / Penanggung Jawab
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($actions as $index => $act)
                                    <tr
                                        class="dark:bg-zink-800 dark:border-zink-700 dark:hover:bg-zink-700 border-b bg-white hover:bg-slate-50">
                                        <td class="px-4 py-4 font-medium">
                                            {{ $index + 1 }}
                                        </td>
                                        <td class="whitespace-normal px-4 py-4">
                                            <div class="dark:text-zink-200 font-medium text-slate-700">
                                                {{ $act->academicYear->student->full_name ?? '-' }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 text-center">
                                            <span
                                                class="rounded-full bg-slate-50 px-3 py-1 text-sm text-slate-600 dark:bg-slate-900/30 dark:text-slate-400">
                                                {{ $act->academicYear->class->academic_level ?? '-' }}
                                                {{ $act->academicYear->class->name ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-4 text-center">
                                            <span class="text-sm text-slate-500 dark:text-slate-400">
                                                @forelse($act->academicYear->pRecaps as $recap)
                                                    <div>{{ $recap->violation->name }}
                                                    </div>
                                                @empty
                                                    <div>-</div>
                                                @endforelse
                                            </span>
                                        </td>
                                        <td class="px-4 py-4 text-center">
                                            <span class="text-sm text-slate-500 dark:text-slate-400">
                                                {{ $act->handling->handling_action ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-4 text-center">
                                            <span class="text-sm text-slate-500 dark:text-slate-400">
                                                {{ $act->handle->name ?? '-' }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="py-8 text-center">
                            <div class="dark:text-zink-400 flex flex-col items-center text-slate-500">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" class="mb-2">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                    <polyline points="14 2 14 8 20 8"></polyline>
                                </svg>
                                <p class="text-sm">Tidak ada template surat ditemukan</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
