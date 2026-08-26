@extends('layouts.app')

@section('content')
    <div class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu group-data-[sidebar-size=md]:ltr:ml-vertical-menu-md group-data-[sidebar-size=md]:rtl:mr-vertical-menu-md group-data-[sidebar-size=sm]:ltr:ml-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:mr-vertical-menu-sm px-4 pb-[calc(theme('spacing.header')_*_0.8)] pt-[calc(theme('spacing.header')_*_1)] group-data-[layout=horizontal]:mx-auto group-data-[layout=horizontal]:max-w-screen-2xl group-data-[layout=horizontal]:px-0 group-data-[layout=horizontal]:px-3 group-data-[layout=horizontal]:group-data-[navbar=hidden]:pt-[calc(theme('spacing.header')_*_0.9)] group-data-[navbar=bordered]:pt-[calc(theme('spacing.header')_*_1.3)] group-data-[navbar=hidden]:pt-0 group-data-[layout=horizontal]:md:pt-[calc(theme('spacing.header')_*_1.6)] group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:ltr:md:ml-auto group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:rtl:md:mr-auto">
        <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">

            <!-- Judul Halaman -->
            <div class="flex flex-col gap-2 py-4 md:flex-row md:items-center print:hidden">
                <div class="grow">
                    <h5 class="text-16 font-semibold">Tindakan dan Penanganan</h5>
                </div>
                <ul class="flex shrink-0 items-center gap-2 text-sm font-normal">
                    <li class="dark:text-zink-200 text-slate-400">
                        Portal Siswa
                    </li>
                    <li class="text-slate-400">/</li>
                    <li class="dark:text-zink-100 text-slate-700 font-medium">
                        Tindakan dan Penanganan
                    </li>
                </ul>
            </div>

            <!-- Tabel Riwayat Penanganan -->
            <div class="card border-none shadow-md rounded-xl bg-white dark:bg-zink-700">
                <div class="card-body p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h6 class="text-15 font-semibold text-slate-800 dark:text-zink-50">Riwayat Penanganan dan Pembinaan</h6>
                        <span class="text-12 text-slate-500 dark:text-zink-300">
                            Total Penanganan: {{ $actions->total() }}
                        </span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-slate-700 dark:text-zink-200">
                            <thead class="text-12 text-slate-500 uppercase bg-slate-50 dark:bg-zink-600 dark:text-zink-200">
                                <tr>
                                    <th scope="col" class="px-6 py-3 font-semibold rounded-l-lg w-16">No</th>
                                    <th scope="col" class="px-6 py-3 font-semibold w-1/4">Nama Tindakan</th>
                                    <th scope="col" class="px-6 py-3 font-semibold">Catatan Pembinaan</th>
                                    <th scope="col" class="px-6 py-3 font-semibold w-1/4">Petugas Pembina</th>
                                    <th scope="col" class="px-6 py-3 font-semibold rounded-r-lg w-48">Tanggal Pelaksanaan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($actions as $index => $action)
                                    <tr class="bg-white border-b dark:bg-zink-700 dark:border-zink-600 hover:bg-slate-50/50 dark:hover:bg-zink-600/50">
                                        <td class="px-6 py-4 font-medium">{{ $actions->firstItem() + $index }}</td>
                                        <td class="px-6 py-4 font-bold text-blue-900 dark:text-blue-200">
                                            {{ $action->handling->handling_name ?? 'Pembinaan Biasa' }}
                                            <span class="block text-10 font-normal text-slate-400 dark:text-zink-300">
                                                Batas Poin: {{ $action->handling->handling_point ?? 0 }} Poin
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-slate-700 dark:text-zink-100">
                                            <div class="font-semibold text-slate-800 dark:text-zink-50 mb-1">
                                                Aktivitas: {{ $action->activity ?? '-' }}
                                            </div>
                                            <p class="text-13 text-slate-650 dark:text-zink-200 leading-relaxed">
                                                {{ $action->description ?? '-' }}
                                            </p>
                                        </td>
                                        <td class="px-6 py-4 text-slate-900 dark:text-zink-50 font-medium">
                                            {{ $action->handle->name ?? '-' }}
                                            <span class="block text-10 font-normal text-slate-450 dark:text-zink-300">
                                                {{ $action->handle->email ?? '' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-slate-500 dark:text-zink-200">
                                            {{ $action->created_at ? $action->created_at->translatedFormat('d F Y, H:i') : '-' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-8 text-center text-slate-500 dark:text-zink-300">
                                            Belum ada riwayat penanganan pembinaan yang tercatat untuk Anda.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $actions->links() }}
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
