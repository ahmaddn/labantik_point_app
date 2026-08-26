@extends('layouts.app')
@section('content')
    <div
        class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu group-data-[sidebar-size=md]:ltr:ml-vertical-menu-md group-data-[sidebar-size=md]:rtl:mr-vertical-menu-md group-data-[sidebar-size=sm]:ltr:ml-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:mr-vertical-menu-sm px-4 pb-[calc(theme('spacing.header')_*_0.8)] pt-[calc(theme('spacing.header')_*_1)] group-data-[layout=horizontal]:mx-auto group-data-[layout=horizontal]:max-w-screen-2xl group-data-[layout=horizontal]:px-0 group-data-[layout=horizontal]:px-3 group-data-[layout=horizontal]:group-data-[navbar=hidden]:pt-[calc(theme('spacing.header')_*_0.9)] group-data-[navbar=bordered]:pt-[calc(theme('spacing.header')_*_1.3)] group-data-[navbar=hidden]:pt-0 group-data-[layout=horizontal]:md:pt-[calc(theme('spacing.header')_*_1.6)] group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:ltr:md:ml-auto group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:rtl:md:mr-auto">
        <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">

            <!-- Breadcrumb -->
            <div class="flex flex-col gap-2 py-4 md:flex-row md:items-center print:hidden">
                <div class="grow">
                    <h5 class="text-16 font-semibold">Daftar Tindakan Kelas {{ $class->academic_level }} {{ $class->name }}</h5>
                </div>
                <ul class="flex shrink-0 items-center gap-2 text-sm font-normal">
                    <li class="dark:text-zink-200 text-slate-400">Portal Wali Kelas</li>
                    <li class="text-slate-400">/</li>
                    <li class="dark:text-zink-100 text-slate-700 font-medium">Daftar Tindakan</li>
                </ul>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="mb-4 flex justify-between gap-2">
                        <h6 class="text-15 font-semibold text-slate-800 dark:text-zink-50">Tabel Riwayat Tindakan Pembinaan</h6>
                    </div>

                    @if (count($actions) > 0)
                        <table id="hoverableTable" style="width: 100%" class="hover group">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Siswa</th>
                                    <th>Tindakan / Penanganan</th>
                                    <th>Aktivitas</th>
                                    <th>Catatan / Deskripsi</th>
                                    <th>Petugas Pembina</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($actions as $index => $act)
                                    <tr>
                                        <td>{{ $actions->firstItem() + $index }}</td>
                                        <td class="font-semibold text-slate-900 dark:text-zink-50">
                                            {{ $act->academicYear->student->full_name ?? '-' }}
                                            <span class="block text-10 font-normal text-slate-400 dark:text-zink-300">
                                                NIS: {{ $act->academicYear->student->student_number ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="font-bold text-blue-900 dark:text-blue-200">
                                            {{ $act->handling->handling_name ?? 'Pembinaan' }}
                                        </td>
                                        <td class="font-semibold text-slate-800 dark:text-zink-100">
                                            {{ $act->activity ?? '-' }}
                                        </td>
                                        <td class="text-13 text-slate-650 dark:text-zink-200 max-w-xs truncate" title="{{ $act->description }}">
                                            {{ $act->description ?? '-' }}
                                        </td>
                                        <td class="text-slate-600 dark:text-zink-200">
                                            {{ $act->handle->name ?? '-' }}
                                        </td>
                                        <td class="whitespace-nowrap text-slate-500 dark:text-zink-300">
                                            {{ $act->created_at ? $act->created_at->format('d/m/Y H:i') : '-' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="mt-4">
                            {{ $actions->links() }}
                        </div>
                    @else
                        <div class="text-center text-slate-500 py-8">Belum ada riwayat tindakan penanganan untuk siswa di kelas Anda.</div>
                    @endif
                </div>
            </div>

        </div>
    </div>
@endsection
