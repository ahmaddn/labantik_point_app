@extends('layouts.app')

@section('content')
    <div class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu group-data-[sidebar-size=md]:ltr:ml-vertical-menu-md group-data-[sidebar-size=md]:rtl:mr-vertical-menu-md group-data-[sidebar-size=sm]:ltr:ml-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:mr-vertical-menu-sm px-4 pb-[calc(theme('spacing.header')_*_0.8)] pt-[calc(theme('spacing.header')_*_1)] group-data-[layout=horizontal]:mx-auto group-data-[layout=horizontal]:max-w-screen-2xl group-data-[layout=horizontal]:px-0 group-data-[layout=horizontal]:px-3 group-data-[layout=horizontal]:group-data-[navbar=hidden]:pt-[calc(theme('spacing.header')_*_0.9)] group-data-[navbar=bordered]:pt-[calc(theme('spacing.header')_*_1.3)] group-data-[navbar=hidden]:pt-0 group-data-[layout=horizontal]:md:pt-[calc(theme('spacing.header')_*_1.6)] group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:ltr:md:ml-auto group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:rtl:md:mr-auto">
        <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">

            <!-- Judul Halaman -->
            <div class="flex flex-col gap-2 py-4 md:flex-row md:items-center print:hidden">
                <div class="grow">
                    <h5 class="text-16 font-semibold">Riwayat Pelanggaran</h5>
                </div>
                <ul class="flex shrink-0 items-center gap-2 text-sm font-normal">
                    <li class="dark:text-zink-200 text-slate-400">
                        Portal Siswa
                    </li>
                    <li class="text-slate-400">/</li>
                    <li class="dark:text-zink-100 text-slate-700 font-medium">
                        Riwayat Pelanggaran
                    </li>
                </ul>
            </div>

            <!-- Tabel Riwayat Pelanggaran -->
            <div class="card border-none shadow-md rounded-xl bg-white dark:bg-zink-700">
                <div class="card-body p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h6 class="text-15 font-semibold text-slate-800 dark:text-zink-50">Daftar Pelanggaran Yang Tercatat</h6>
                        <span class="text-12 text-slate-500 dark:text-zink-300">
                            Total Catatan: {{ $violations->total() }}
                        </span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-slate-700 dark:text-zink-200">
                            <thead class="text-12 text-slate-500 uppercase bg-slate-50 dark:bg-zink-600 dark:text-zink-200">
                                <tr>
                                    <th scope="col" class="px-6 py-3 font-semibold rounded-l-lg">No</th>
                                    <th scope="col" class="px-6 py-3 font-semibold">Nama Pelanggaran</th>
                                    <th scope="col" class="px-6 py-3 font-semibold text-center">Kategori</th>
                                    <th scope="col" class="px-6 py-3 font-semibold text-center">Poin</th>
                                    <th scope="col" class="px-6 py-3 font-semibold text-center">Status</th>
                                    <th scope="col" class="px-6 py-3 font-semibold">Tanggal Dilaporkan</th>
                                    <th scope="col" class="px-6 py-3 font-semibold rounded-r-lg">Dilaporkan Oleh</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($violations as $index => $recap)
                                    <tr class="bg-white border-b dark:bg-zink-700 dark:border-zink-600 hover:bg-slate-50/50 dark:hover:bg-zink-600/50">
                                        <td class="px-6 py-4 font-medium">{{ $violations->firstItem() + $index }}</td>
                                        <td class="px-6 py-4 font-medium text-slate-900 dark:text-zink-50">
                                            {{ $recap->violation->name ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @php
                                                $category = $recap->violation->category->name ?? 'Lainnya';
                                                $catColor = 'bg-slate-100 text-slate-800 dark:bg-zink-600 dark:text-zink-200';
                                                if ($category === 'Ringan') {
                                                    $catColor = 'bg-blue-50 text-blue-800 dark:bg-blue-900/20 dark:text-blue-200';
                                                } elseif ($category === 'Sedang') {
                                                    $catColor = 'bg-amber-50 text-amber-800 dark:bg-amber-900/20 dark:text-amber-200';
                                                } elseif ($category === 'Berat') {
                                                    $catColor = 'bg-red-50 text-red-800 dark:bg-red-900/20 dark:text-red-200';
                                                }
                                            @endphp
                                            <span class="px-2.5 py-1 rounded text-11 font-medium {{ $catColor }}">
                                                {{ $category }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center font-semibold text-slate-850 dark:text-zink-100">
                                            {{ $recap->violation->point ?? 0 }}
                                        </td>
                                        <td class="px-6 py-4 text-center">
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
                                            <span class="px-2.5 py-1 rounded text-11 font-semibold {{ $statusColor }}">
                                                {{ $statusText }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-slate-500 dark:text-zink-200">
                                            {{ $recap->created_at ? $recap->created_at->translatedFormat('d F Y, H:i') : '-' }}
                                        </td>
                                        <td class="px-6 py-4 text-slate-600 dark:text-zink-200">
                                            {{ $recap->createdBy->name ?? '-' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-8 text-center text-slate-500 dark:text-zink-300">
                                            Belum ada riwayat pelanggaran yang tercatat untuk Anda.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $violations->links() }}
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
