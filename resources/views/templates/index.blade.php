@extends('layouts.app')
@section('content')
    <div
        class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu group-data-[sidebar-size=md]:ltr:ml-vertical-menu-md group-data-[sidebar-size=md]:rtl:mr-vertical-menu-md group-data-[sidebar-size=sm]:ltr:ml-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:mr-vertical-menu-sm px-4 pb-[calc(theme('spacing.header')_*_0.8)] pt-[calc(theme('spacing.header')_*_1)] group-data-[layout=horizontal]:mx-auto group-data-[layout=horizontal]:max-w-screen-2xl group-data-[layout=horizontal]:px-0 group-data-[layout=horizontal]:px-3 group-data-[layout=horizontal]:group-data-[navbar=hidden]:pt-[calc(theme('spacing.header')_*_0.9)] group-data-[navbar=bordered]:pt-[calc(theme('spacing.header')_*_1.3)] group-data-[navbar=hidden]:pt-0 group-data-[layout=horizontal]:md:pt-[calc(theme('spacing.header')_*_1.6)] group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:ltr:md:ml-auto group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:rtl:md:mr-auto">
        <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">

            <!-- Breadcrumb -->
            <div class="flex flex-col gap-2 py-4 md:flex-row md:items-center print:hidden">
                <div class="grow">
                    <h5 class="text-16">Template PDF</h5>
                </div>
                <ul class="flex shrink-0 items-center gap-2 text-sm font-normal">
                    <li
                        class="before:font-remix dark:text-zink-200 relative before:absolute before:-top-[3px] before:text-[18px] before:text-slate-400 before:content-['\ea54'] ltr:pr-4 ltr:before:-right-1 rtl:pl-4 rtl:before:-left-1">
                        <a href="#!" class="dark:text-zink-200 text-slate-400">Dashboards</a>
                    </li>
                    <li
                        class="before:font-remix dark:text-zink-200 relative before:absolute before:-top-[3px] before:text-[18px] before:text-slate-400 before:content-['\ea54'] ltr:pr-4 ltr:before:-right-1 rtl:pl-4 rtl:before:-left-1">
                        <a href="#!" class="dark:text-zink-200 text-slate-400">Super Admin</a>
                    </li>
                    <li class="dark:text-zink-100 text-slate-700">
                        Template PDF
                    </li>
                </ul>
            </div>

            <!-- Data Table -->
            <div class="card">
                <div class="card-body">
                    <div class="mb-4 flex justify-between gap-2">
                        <h6 class="text-15 mb-4">Daftar Template Surat</h6>
                    </div>

                    @if (count($files) > 0)
                        <table id="hoverableTable" style="width: 100%" class="hover group">
                            <thead class="dark:bg-zink-700 bg-slate-50 text-xs uppercase">
                                <tr>
                                    <th scope="col" class="dark:text-zink-200 px-4 py-4 font-semibold text-slate-700">
                                        No
                                    </th>
                                    <th scope="col" class="dark:text-zink-200 px-4 py-4 font-semibold text-slate-700">
                                        Nama Template
                                    </th>
                                    <th scope="col"
                                        class="dark:text-zink-200 px-4 py-4 text-center font-semibold text-slate-700">
                                        Ukuran
                                    </th>
                                    <th scope="col"
                                        class="dark:text-zink-200 px-4 py-4 text-center font-semibold text-slate-700">
                                        Terakhir Diubah
                                    </th>
                                    <th scope="col"
                                        class="dark:text-zink-200 px-4 py-4 text-center font-semibold text-slate-700">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($files as $index => $file)
                                    <tr
                                        class="dark:bg-zink-800 dark:border-zink-700 dark:hover:bg-zink-700 border-b bg-white hover:bg-slate-50">
                                        <td class="px-4 py-4 font-medium">
                                            {{ $index + 1 }}
                                        </td>
                                        <td class="whitespace-normal px-4 py-4">
                                            <div class="dark:text-zink-200 font-medium text-slate-700">
                                                {{ ucwords($file['name']) }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 text-center">
                                            <span
                                                class="rounded-full bg-slate-50 px-3 py-1 text-sm text-slate-600 dark:bg-slate-900/30 dark:text-slate-400">
                                                {{ number_format($file['size'] / 1024, 2) }} KB
                                            </span>
                                        </td>
                                        <td class="px-4 py-4 text-center">
                                            <span class="text-sm text-slate-500 dark:text-slate-400">
                                                {{ date('d M Y H:i', $file['modified']) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-4">
                                            <div class="flex justify-center gap-2">
                                                <button data-modal-target="modal-download{{ $index }}"
                                                    class="btn dark:bg-zink-700 flex size-[37.5px] items-center justify-center rounded-full border-green-500 bg-white p-0 text-green-500 hover:border-green-600 hover:bg-green-600 hover:text-white"
                                                    title="Download">
                                                    <i data-lucide="download" class="size-4"></i>
                                                </button>
                                                <div id="modal-download{{ $index }}" modal-center=""
                                                    class="fixed flex flex-col hidden transition-all duration-300 ease-in-out left-2/4 z-drawer -translate-x-2/4 -translate-y-2/4 show">
                                                    <div
                                                        class="w-screen md:w-[30rem] bg-white shadow rounded-md dark:bg-zink-600 flex flex-col h-full">
                                                        <div
                                                            class="flex items-center justify-between p-4 border-b border-slate-200 dark:border-zink-500">
                                                            <h5 class="text-16">Tindakan -
                                                                {{ ucwords($file['name']) }}</h5>
                                                            <button data-modal-close="modal-download{{ $index }}"
                                                                class="transition-all duration-200 ease-linear text-slate-500 hover:text-red-500 dark:text-zink-200 dark:hover:text-red-500">
                                                                <i data-lucide="x" class="size-5"></i>
                                                            </button>
                                                        </div>
                                                        <div
                                                            class="max-h-[calc(theme('height.screen')_-_180px)] p-4 overflow-y-auto">
                                                            <form method="POST" target="_blank"
                                                                action="{{ route('superadmin.templates.download', $file['filename']) }}">
                                                                @csrf
                                                                <div class="mb-4">
                                                                    <label for="tindakanSelect-"
                                                                        class="inline-block mb-2 text-base font-medium">
                                                                        Nomor Surat <span class="text-red-500">*</span>
                                                                    </label>
                                                                    <input type="text" name="no_surat"
                                                                        class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 w-full">
                                                                </div>
                                                                @error('no_surat')
                                                                    <div class="text-sm text-red-500 mb-4">
                                                                        {{ $message }}
                                                                    </div>
                                                                @enderror

                                                                <div class="flex items-center justify-end gap-2 mt-4">
                                                                    <button
                                                                        data-modal-close="modal-download{{ $index }}"
                                                                        type="button"
                                                                        class="text-slate-500 btn bg-slate-200 border-slate-200 hover:text-slate-600 hover:bg-slate-300 hover:border-slate-300 focus:text-slate-600 focus:bg-slate-300 focus:border-slate-300 focus:ring focus:ring-slate-100 active:text-slate-600 active:bg-slate-300 active:border-slate-300 active:ring active:ring-slate-100 dark:bg-zink-600 dark:hover:bg-zink-500 dark:border-zink-600 dark:hover:border-zink-500 dark:text-zink-200 dark:ring-zink-400/50">
                                                                        Batal
                                                                    </button>
                                                                    <button type="submit"
                                                                        class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20">
                                                                        Simpan
                                                                    </button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
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
