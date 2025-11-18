@extends('layouts.app')
@section('content')
    <div
        class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu group-data-[sidebar-size=md]:ltr:ml-vertical-menu-md group-data-[sidebar-size=md]:rtl:mr-vertical-menu-md group-data-[sidebar-size=sm]:ltr:ml-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:mr-vertical-menu-sm pt-[calc(theme('spacing.header')_*_1)] pb-[calc(theme('spacing.header')_*_0.8)] px-4 group-data-[navbar=bordered]:pt-[calc(theme('spacing.header')_*_1.3)] group-data-[navbar=hidden]:pt-0 group-data-[layout=horizontal]:mx-auto group-data-[layout=horizontal]:max-w-screen-2xl group-data-[layout=horizontal]:px-0 group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:ltr:md:ml-auto group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:rtl:md:mr-auto group-data-[layout=horizontal]:md:pt-[calc(theme('spacing.header')_*_1.6)] group-data-[layout=horizontal]:px-3 group-data-[layout=horizontal]:group-data-[navbar=hidden]:pt-[calc(theme('spacing.header')_*_0.9)]">
        <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">

            <!-- Breadcrumb -->
            <div class="flex flex-col gap-2 py-4 md:flex-row md:items-center print:hidden">
                <div class="grow">
                    <h5 class="text-16">Data Pelanggaran</h5>
                </div>
                <ul class="flex items-center gap-2 text-sm font-normal shrink-0">
                    <li
                        class="relative before:content-['\ea54'] before:font-remix ltr:before:-right-1 rtl:before:-left-1 before:absolute before:text-[18px] before:-top-[3px] ltr:pr-4 rtl:pl-4 before:text-slate-400 dark:text-zink-200">
                        <a href="#!" class="text-slate-400 dark:text-zink-200">Dashboards</a>
                    </li>
                    <li
                        class="relative before:content-['\ea54'] before:font-remix ltr:before:-right-1 rtl:before:-left-1 before:absolute before:text-[18px] before:-top-[3px] ltr:pr-4 rtl:pl-4 before:text-slate-400 dark:text-zink-200">
                        <a href="#!" class="text-slate-400 dark:text-zink-200">Super Admin</a>
                    </li>
                    <li class="text-slate-700 dark:text-zink-100">
                        Data Pelanggaran
                    </li>
                </ul>
            </div>

            <!-- Filter Section -->
            <div class="card mb-4">
                <div class="card-body">
                    <h6 class="mb-4 text-15">Filter Data Pelanggaran</h6>
                    <form method="GET" action="{{ route('superadmin.violations') }}" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <!-- Filter Kategori -->
                            <div>
                                <label for="category"
                                    class="block text-sm font-medium text-slate-700 dark:text-zink-300 mb-2">
                                    Filter Kategori
                                </label>
                                <select id="category" name="category"
                                    class="w-full px-3 py-2 text-sm border border-slate-200 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-zink-600 dark:border-zink-500 dark:text-zink-100">
                                    <option value="">Semua Kategori</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->name }}"
                                            {{ request('category') == $category->name ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Filter Poin Minimum -->
                            <div>
                                <label for="min_point"
                                    class="block text-sm font-medium text-slate-700 dark:text-zink-300 mb-2">
                                    Poin Minimum
                                </label>
                                <input type="number" id="min_point" name="min_point" value="{{ request('min_point') }}"
                                    placeholder="0"
                                    class="w-full px-3 py-2 text-sm border border-slate-200 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-zink-600 dark:border-zink-500 dark:text-zink-100">
                            </div>

                            <!-- Filter Poin Maximum -->
                            <div>
                                <label for="max_point"
                                    class="block text-sm font-medium text-slate-700 dark:text-zink-300 mb-2">
                                    Poin Maximum
                                </label>
                                <input type="number" id="max_point" max="100" name="max_point"
                                    value="{{ request('max_point') }}" placeholder="100"
                                    class="w-full px-3 py-2 text-sm border border-slate-200 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-zink-600 dark:border-zink-500 dark:text-zink-100">
                            </div>
                        </div>

                        <div class="flex justify-end gap-2 mt-4">
                            <button type="submit"
                                class="text-custom-500 btn bg-custom-100 hover:text-white hover:bg-custom-600 focus:text-white focus:bg-custom-600 focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:ring active:ring-custom-100 dark:bg-custom-500/20 dark:text-custom-500 dark:hover:bg-custom-500 dark:hover:text-white dark:focus:bg-custom-500 dark:focus:text-white dark:active:bg-custom-500 dark:active:text-white dark:ring-custom-400/20">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="inline-block mr-1">
                                    <circle cx="11" cy="11" r="8"></circle>
                                    <path d="m21 21-4.35-4.35"></path>
                                </svg>
                                Terapkan Filter
                            </button>
                            <a href="{{ route('superadmin.violations') }}"
                                class="text-slate-500 btn bg-slate-100 hover:text-white hover:bg-slate-600 focus:text-white focus:bg-slate-600 focus:ring focus:ring-slate-100 active:text-white active:bg-slate-600 active:ring active:ring-slate-100 dark:bg-slate-500/20 dark:text-slate-400 dark:hover:bg-slate-500 dark:hover:text-white dark:focus:bg-slate-500 dark:focus:text-white dark:active:bg-slate-500 dark:active:text-white dark:ring-slate-400/20">
                                Reset Filter
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Info hasil filter -->
            @if (request()->hasAny(['category', 'min_point', 'max_point', 'search']))
                <div class="mb-3 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                    <div class="flex items-center gap-2 text-sm text-blue-800 dark:text-blue-300">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10" />
                            <path d="M12 16v-4" />
                            <path d="M12 8h.01" />
                        </svg>
                        <span>
                            Menampilkan <strong>{{ $violations->total() }}</strong> hasil
                            @if (request('category'))
                                dengan kategori <strong>{{ request('category') }}</strong>
                            @endif
                            @if (request('min_point') || request('max_point'))
                                dengan poin
                                @if (request('min_point'))
                                    dari <strong>{{ request('min_point') }}</strong>
                                @endif
                                @if (request('max_point'))
                                    sampai <strong>{{ request('max_point') }}</strong>
                                @endif
                            @endif
                            @if (request('search'))
                                dengan pencarian "<strong>{{ request('search') }}</strong>"
                            @endif
                        </span>
                    </div>
                </div>
            @endif

            <!-- Data Table -->
            <div class="card">
                <div class="card-body">
                    <div class="flex gap-2 justify-between mb-4">


                        <h6 class="mb-4 text-15">Daftar Pelanggaran</h6>
                        <button data-modal-target="addViolationModal" type="button"
                            class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20"><i
                                data-lucide="plus" class="inline-block size-4"></i> <span class="align-middle">Tambah
                                Pelanggaran</span></button>
                    </div>


                    @if ($violations->count() > 0)
                        <table id="hoverableTable" style="width: 100%" class="hover group">
                            <thead class="text-xs uppercase bg-slate-50 dark:bg-zink-700">
                                <tr>
                                    <th scope="col" class="px-4 py-4 font-semibold text-slate-700 dark:text-zink-200">
                                        No
                                    </th>
                                    <th scope="col" class="px-4 py-4 font-semibold text-slate-700 dark:text-zink-200">
                                        Nama Pelanggaran
                                    </th>
                                    <th scope="col" class="px-4 py-4 font-semibold text-slate-700 dark:text-zink-200">
                                        Kategori
                                    </th>
                                    <th scope="col"
                                        class="px-4 py-4 font-semibold text-slate-700 dark:text-zink-200 text-center">
                                        Poin
                                    </th>
                                    <th scope="col"
                                        class="px-4 py-4 font-semibold text-slate-700 dark:text-zink-200 text-center">
                                        Jumlah Kasus
                                        ( Terverifikasi )
                                    </th>
                                    <th scope="col"
                                        class="px-4 py-4 font-semibold text-slate-700 dark:text-zink-200 text-center">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($violations as $index => $violation)
                                    <tr
                                        class="bg-white border-b dark:bg-zink-800 dark:border-zink-700 hover:bg-slate-50 dark:hover:bg-zink-700">
                                        <td class="px-4 py-4 font-medium">
                                            {{ $index + 1 }}
                                        </td>
                                        <td class="whitespace-normal px-4 py-4">
                                            <div class="font-medium text-slate-700 dark:text-zink-200">
                                                {{ $violation->name }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-4">
                                            <span
                                                class="px-2 py-1 text-xs font-medium rounded-full whitespace-nowrap
                                        @if ($violation->category->name === 'Berat') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300
                                        @elseif($violation->category->name === 'Sedang') bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300
                                        @else bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300 @endif">
                                                {{ $violation->category->name ?? 'Tidak Diketahui' }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-4 text-center">
                                            <span
                                                class="px-3 py-1 text-sm font-bold text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/30 rounded-full">
                                                {{ $violation->point ?? 0 }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-4 text-center">
                                            <div class="flex flex-col items-center gap-1">
                                                <span
                                                    class="px-3 py-1 text-sm font-bold text-green-600 dark:text-green-400 bg-green-50 dark:bg-green-900/30 rounded-full">
                                                    {{ $violation->verified_cases_count ?? 0 }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-4">
                                            <div class="flex gap-2 justify-center">

                                                <button data-modal-target="editViolationModal{{ $violation->id }}"
                                                    class="flex rounded-full items-center justify-center size-[37.5px] p-0 bg-white text-sky-500 btn border-sky-500 hover:text-white hover:bg-sky-600 hover:border-sky-600 focus:text-white focus:bg-sky-600 focus:border-sky-600 focus:ring focus:ring-sky-100 active:text-white active:bg-sky-600 active:border-sky-600 active:ring active:ring-sky-100 dark:bg-zink-700 dark:hover:bg-sky-500 dark:ring-sky-400/20 dark:focus:bg-sky-500"
                                                    title="Edit">
                                                    <i data-lucide="pencil" class="size-4"></i>
                                                </button>

                                                <!-- Edit Modal -->
                                                <div id="editViolationModal{{ $violation->id }}" modal-center
                                                    class="fixed flex flex-col hidden transition-all duration-300 ease-in-out left-2/4 z-drawer -translate-x-2/4 -translate-y-2/4 show">
                                                    <div
                                                        class="w-screen md:w-[30rem] bg-white shadow rounded-md dark:bg-zink-600">
                                                        <div
                                                            class="flex items-center justify-between p-4 border-b dark:border-zink-500">
                                                            <h5 class="text-16">Edit Pelanggaran</h5>
                                                            <button
                                                                data-modal-close="editViolationModal{{ $violation->id }}"
                                                                class="transition-all duration-200 ease-linear text-slate-400 hover:text-red-500">
                                                                <i data-lucide="x" class="size-5"></i>
                                                            </button>
                                                        </div>
                                                        <div
                                                            class="max-h-[calc(theme('height.screen')_-_180px)] p-4 overflow-y-auto">
                                                            <form method="POST"
                                                                action="{{ route('superadmin.violations.update', $violation->id) }}">
                                                                @csrf
                                                                @method('PUT')
                                                                <div id="alert-error-msg"
                                                                    class="hidden px-4 py-3 text-sm text-red-500 border border-transparent rounded-md bg-red-50 dark:bg-red-500/20">
                                                                </div>
                                                                <div class="grid grid-cols-1 gap-4">

                                                                    <div>
                                                                        <label for="categorySelect{{ $violation->id }}"
                                                                            class="inline-block mb-2 text-base font-medium">
                                                                            Kategori
                                                                        </label>
                                                                        <select name="category_id"
                                                                            id="categorySelect{{ $violation->id }}"
                                                                            class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                                                            data-choices data-choices-search-false required>
                                                                            @foreach ($categories as $category)
                                                                                <option value="{{ $category->id }}"
                                                                                    {{ $violation->p_category_id == $category->id ? 'selected' : '' }}>
                                                                                    {{ $category->name }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>

                                                                    <div>
                                                                        <label for="violationName{{ $violation->id }}"
                                                                            class="inline-block mb-2 text-base font-medium">
                                                                            Nama Pelanggaran
                                                                        </label>
                                                                        <input type="text" name="name"
                                                                            id="violationName{{ $violation->id }}"
                                                                            value="{{ $violation->name }}"
                                                                            class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                                                            placeholder="Masukkan nama pelanggaran"
                                                                            required>
                                                                    </div>


                                                                    <div>
                                                                        <label for="pointInput{{ $violation->id }}"
                                                                            class="inline-block mb-2 text-base font-medium">
                                                                            Poin Pelanggaran
                                                                        </label>
                                                                        <input type="number" name="point"
                                                                            id="pointInput{{ $violation->id }}"
                                                                            value="{{ $violation->point }}"
                                                                            min="0" max="100"
                                                                            class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                                                            placeholder="0" required>
                                                                    </div>
                                                                </div>

                                                                <div class="flex justify-end gap-2 mt-4">
                                                                    <button type="reset"
                                                                        data-modal-close="editViolationModal{{ $violation->id }}"
                                                                        class="text-red-500 bg-white btn hover:text-red-500 hover:bg-red-100 focus:text-red-500 focus:bg-red-100 active:text-red-500 active:bg-red-100 dark:bg-zink-600 dark:hover:bg-red-500/10 dark:focus:bg-red-500/10 dark:active:bg-red-500/10">
                                                                        Batal
                                                                    </button>
                                                                    <button type="submit"
                                                                        class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20">
                                                                        Update Pelanggaran
                                                                    </button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>


                                                <button data-modal-target="deleteViolationModal{{ $violation->id }}"
                                                    class="flex rounded-full items-center justify-center size-[37.5px] p-0 text-red-500 bg-white border-red-500 btn hover:text-white hover:bg-red-600 hover:border-red-600 focus:text-white focus:bg-red-600 focus:border-red-600 focus:ring focus:ring-red-100 active:text-white active:bg-red-600 active:border-red-600 active:ring active:ring-red-100 dark:bg-zink-700 dark:hover:bg-red-500 dark:ring-red-400/20 dark:focus:bg-red-500"
                                                    title="Hapus">
                                                    <i data-lucide="trash-2" class="size-4"></i>
                                                </button>

                                                <!-- Delete Confirmation Modal -->
                                                <div id="deleteViolationModal{{ $violation->id }}" modal-center
                                                    class="fixed flex flex-col hidden transition-all duration-300 ease-in-out left-2/4 z-drawer -translate-x-2/4 -translate-y-2/4 show">
                                                    <div
                                                        class="w-screen md:w-[30rem] bg-white shadow rounded-md dark:bg-zink-600">
                                                        <div
                                                            class="flex items-center justify-between p-4 border-b dark:border-zink-300/20">
                                                            <h5 class="text-16">Konfirmasi Hapus</h5>
                                                            <button
                                                                data-modal-close="deleteViolationModal{{ $violation->id }}"
                                                                class="transition-all duration-200 ease-linear text-slate-400 hover:text-red-500">
                                                                <i data-lucide="x" class="size-5"></i>
                                                            </button>
                                                        </div>
                                                        <div class="p-4">
                                                            <div class="text-center">
                                                                <i data-lucide="alert-triangle"
                                                                    class="w-12 h-12 mx-auto mb-4 text-red-500"></i>
                                                                <h5 class="mb-2">Apakah Anda yakin?</h5>
                                                                <p class="text-slate-500 dark:text-zink-200 mb-4">
                                                                    Anda akan menghapus Pelanggaran ini
                                                                    <strong id="deleteConfigName"></strong>.<br>
                                                                    Tindakan ini tidak dapat dibatalkan.
                                                                </p>
                                                            </div>
                                                            <form
                                                                action="{{ route('superadmin.violations.destroy', $violation->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <div class="flex justify-center gap-2">
                                                                    <button type="button"
                                                                        data-modal-close="deleteViolationModal{{ $violation->id }}"
                                                                        class="text-slate-500 bg-white border-slate-300 btn hover:text-slate-600 dark:bg-zink-500 dark:border-zink-500">
                                                                        Batal
                                                                    </button>
                                                                    <button type="submit"
                                                                        class="text-white btn bg-red-500 border-red-500 hover:bg-red-600">
                                                                        Ya, Hapus
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
                        <!-- Pesan jika tidak ada data -->
                        <div class="text-center py-8">
                            <div class="flex flex-col items-center text-slate-500 dark:text-zink-400">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" class="mb-2">
                                    <circle cx="11" cy="11" r="8"></circle>
                                    <path d="m21 21-4.35-4.35"></path>
                                </svg>
                                <p class="text-sm">
                                    @if (request()->hasAny(['category', 'min_point', 'max_point', 'search']))
                                        Tidak ada data yang sesuai dengan filter
                                    @else
                                        Tidak ada data pelanggaran
                                    @endif
                                </p>
                            </div>
                        </div>
                    @endif

                    <div id="addViolationModal" modal-center
                        class="fixed flex flex-col hidden transition-all duration-300 ease-in-out left-2/4 z-drawer -translate-x-2/4 -translate-y-2/4 show">
                        <div class="w-screen md:w-[30rem] bg-white shadow rounded-md dark:bg-zink-600">
                            <div class="flex items-center justify-between p-4 border-b dark:border-zink-500">
                                <h5 class="text-16">Tambah Pelanggaran</h5>
                                <button data-modal-close="addViolationModal"
                                    class="transition-all duration-200 ease-linear text-slate-400 hover:text-red-500">
                                    <i data-lucide="x" class="size-5"></i>
                                </button>
                            </div>
                            <div class="max-h-[calc(theme('height.screen')_-_180px)] p-4 overflow-y-auto">
                                <form method="POST" action="{{ route('superadmin.violations.add', $violation->id) }}">
                                    @csrf
                                    <div id="alert-error-msg"
                                        class="hidden px-4 py-3 text-sm text-red-500 border border-transparent rounded-md bg-red-50 dark:bg-red-500/20">
                                    </div>
                                    <div class="grid grid-cols-1 gap-4">

                                        <div>
                                            <label for="categorySelect" class="inline-block mb-2 text-base font-medium">
                                                Kategori
                                            </label>
                                            <select name="category_id" id="categorySelect{{ $violation->id }}"
                                                class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                                data-choices data-choices-search-false required>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}"
                                                        {{ $violation->category_id == $category->id ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div>
                                            <label for="violationName" class="inline-block mb-2 text-base font-medium">
                                                Nama Pelanggaran
                                            </label>
                                            <input type="text" name="name" id="violationName"
                                                class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                                placeholder="Masukkan nama pelanggaran" required>
                                        </div>


                                        <div>
                                            <label for="pointInput" class="inline-block mb-2 text-base font-medium">
                                                Poin Pelanggaran
                                            </label>
                                            <input type="number" name="point" id="pointInput" min="0"
                                                max="100"
                                                class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                                placeholder="0" required>
                                        </div>
                                    </div>

                                    <div class="flex justify-end gap-2 mt-4">
                                        <button type="reset" data-modal-close="addViolationModal"
                                            class="text-red-500 bg-white btn hover:text-red-500 hover:bg-red-100 focus:text-red-500 focus:bg-red-100 active:text-red-500 active:bg-red-100 dark:bg-zink-600 dark:hover:bg-red-500/10 dark:focus:bg-red-500/10 dark:active:bg-red-500/10">
                                            Batal
                                        </button>
                                        <button type="submit"
                                            class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20">
                                            Simpan Pelanggaran
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- container-fluid -->
    </div>

    <style>
        /* Filter section styling */
        .card-body select:focus,
        .card-body input:focus {
            outline: none;
        }

        /* Table responsive */
        @media (max-width: 768px) {
            .overflow-x-auto {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
        }

        /* Hover effects */
        table tbody tr {
            transition: all 0.2s ease-in-out;
        }

        /* Button hover effects */
        button {
            transition: all 0.2s ease-in-out;
        }

        button:hover {
            transform: scale(1.05);
        }
    </style>
@endsection
