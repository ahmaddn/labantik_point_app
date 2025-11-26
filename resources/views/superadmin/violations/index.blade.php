@extends('layouts.app')
@section('content')
    <div
        class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu group-data-[sidebar-size=md]:ltr:ml-vertical-menu-md group-data-[sidebar-size=md]:rtl:mr-vertical-menu-md group-data-[sidebar-size=sm]:ltr:ml-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:mr-vertical-menu-sm px-4 pb-[calc(theme('spacing.header')_*_0.8)] pt-[calc(theme('spacing.header')_*_1)] group-data-[layout=horizontal]:mx-auto group-data-[layout=horizontal]:max-w-screen-2xl group-data-[layout=horizontal]:px-0 group-data-[layout=horizontal]:px-3 group-data-[layout=horizontal]:group-data-[navbar=hidden]:pt-[calc(theme('spacing.header')_*_0.9)] group-data-[navbar=bordered]:pt-[calc(theme('spacing.header')_*_1.3)] group-data-[navbar=hidden]:pt-0 group-data-[layout=horizontal]:md:pt-[calc(theme('spacing.header')_*_1.6)] group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:ltr:md:ml-auto group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:rtl:md:mr-auto">
        <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">

            <!-- Breadcrumb -->
            <div class="flex flex-col gap-2 py-4 md:flex-row md:items-center print:hidden">
                <div class="grow">
                    <h5 class="text-16">Data Pelanggaran</h5>
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
                        Data Pelanggaran
                    </li>
                </ul>
            </div>

            <!-- Filter Section -->
            <div class="card mb-4">
                <div class="card-body">
                    <h6 class="text-15 mb-4">Filter Data Pelanggaran</h6>
                    <form method="GET" action="{{ route('superadmin.violations') }}" class="space-y-4" id="filterForm">
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                            <!-- Filter Kategori -->
                            <div>
                                <label for="category"
                                    class="dark:text-zink-300 mb-2 block text-sm font-medium text-slate-700">
                                    Filter Kategori
                                </label>
                                <select id="categoryFilter" name="category"
                                    class="dark:bg-zink-600 dark:border-zink-500 dark:text-zink-100 @error('category') border-red-500 @enderror w-full rounded-md border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                                    <option value="">Semua Kategori</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->name }}"
                                            {{ old('category', request('category')) == $category->name ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Filter Poin Minimum -->
                            <div>
                                <label for="min_point"
                                    class="dark:text-zink-300 mb-2 block text-sm font-medium text-slate-700">
                                    Poin Minimum
                                </label>
                                <input type="number" id="min_point" name="min_point"
                                    value="{{ old('min_point', request('min_point')) }}" placeholder="0"
                                    class="dark:bg-zink-600 dark:border-zink-500 dark:text-zink-100 @error('min_point') border-red-500 @enderror w-full rounded-md border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                                @error('min_point')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Filter Poin Maximum -->
                            <div>
                                <label for="max_point"
                                    class="dark:text-zink-300 mb-2 block text-sm font-medium text-slate-700">
                                    Poin Maximum
                                </label>
                                <input type="number" id="max_point" max="100" name="max_point"
                                    value="{{ old('max_point', request('max_point')) }}" placeholder="100"
                                    class="dark:bg-zink-600 dark:border-zink-500 dark:text-zink-100 @error('max_point') border-red-500 @enderror w-full rounded-md border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                                @error('max_point')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>


                        <div class="mt-4 flex justify-end">
                            <a href="{{ route('superadmin.violations') }}"
                                class="btn bg-slate-100 text-slate-500 hover:bg-slate-600 hover:text-white focus:bg-slate-600 focus:text-white focus:ring focus:ring-slate-100 active:bg-slate-600 active:text-white active:ring active:ring-slate-100 dark:bg-slate-500/20 dark:text-slate-400 dark:ring-slate-400/20 dark:hover:bg-slate-500 dark:hover:text-white dark:focus:bg-slate-500 dark:focus:text-white dark:active:bg-slate-500 dark:active:text-white">
                                Reset Filter
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Info hasil filter -->
            @if (request()->hasAny(['category', 'min_point', 'max_point', 'search']))
                <div class="mb-3 rounded-lg border border-blue-200 bg-blue-50 p-3 dark:border-blue-800 dark:bg-blue-900/20">
                    <div class="flex items-center gap-2 text-sm text-blue-800 dark:text-blue-300">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10" />
                            <path d="M12 16v-4" />
                            <path d="M12 8h.01" />
                        </svg>
                        <span>
                            Menampilkan <strong>{{ $violations->count() }}</strong> hasil
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
                    <div class="mb-4 flex justify-between gap-2">


                        <h6 class="text-15 mb-4">Daftar Pelanggaran</h6>
                        <button data-modal-target="addViolationModal" type="button"
                            class="btn bg-custom-500 border-custom-500 hover:bg-custom-600 hover:border-custom-600 focus:bg-custom-600 focus:border-custom-600 focus:ring-custom-100 active:bg-custom-600 active:border-custom-600 active:ring-custom-100 dark:ring-custom-400/20 text-white hover:text-white focus:text-white focus:ring active:text-white active:ring"><i
                                data-lucide="plus" class="inline-block size-4"></i> <span class="align-middle">Tambah
                                Pelanggaran</span></button>
                    </div>


                    @if ($violations->count() > 0)
                        <table id="hoverableTable" style="width: 100%" class="hover group">
                            <thead class="dark:bg-zink-700 bg-slate-50 text-xs uppercase">
                                <tr>
                                    <th scope="col" class="dark:text-zink-200 px-4 py-4 font-semibold text-slate-700">
                                        No
                                    </th>
                                    <th scope="col" class="dark:text-zink-200 px-4 py-4 font-semibold text-slate-700">
                                        Nama Pelanggaran
                                    </th>
                                    <th scope="col" class="dark:text-zink-200 px-4 py-4 font-semibold text-slate-700">
                                        Kategori
                                    </th>
                                    <th scope="col"
                                        class="dark:text-zink-200 px-4 py-4 text-center font-semibold text-slate-700">
                                        Poin
                                    </th>
                                    <th scope="col"
                                        class="dark:text-zink-200 px-4 py-4 text-center font-semibold text-slate-700">
                                        Jumlah Kasus
                                        ( Terverifikasi )
                                    </th>
                                    <th scope="col"
                                        class="dark:text-zink-200 px-4 py-4 text-center font-semibold text-slate-700">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($violations as $index => $violation)
                                    <tr
                                        class="dark:bg-zink-800 dark:border-zink-700 dark:hover:bg-zink-700 border-b bg-white hover:bg-slate-50">
                                        <td class="px-4 py-4 font-medium">
                                            {{ $index + 1 }}
                                        </td>
                                        <td class="whitespace-normal px-4 py-4">
                                            <div class="dark:text-zink-200 font-medium text-slate-700">
                                                {{ $violation->name }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-4">
                                            <span
                                                class="@if ($violation->category->name === 'Berat') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300
                                        @elseif($violation->category->name === 'Sedang') bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300
                                        @else bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300 @endif whitespace-nowrap rounded-full px-2 py-1 text-xs font-medium">
                                                {{ $violation->category->name ?? 'Tidak Diketahui' }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-4 text-center">
                                            <span
                                                class="rounded-full bg-red-50 px-3 py-1 text-sm font-bold text-red-600 dark:bg-red-900/30 dark:text-red-400">
                                                {{ $violation->point ?? 0 }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-4 text-center">
                                            <div class="flex flex-col items-center gap-1">
                                                <span
                                                    class="rounded-full bg-green-50 px-3 py-1 text-sm font-bold text-green-600 dark:bg-green-900/30 dark:text-green-400">
                                                    {{ $violation->verified_cases_count ?? 0 }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-4">
                                            <div class="flex justify-center gap-2">

                                                <button data-modal-target="editViolationModal{{ $violation->id }}"
                                                    class="btn dark:bg-zink-700 flex size-[37.5px] items-center justify-center rounded-full border-sky-500 bg-white p-0 text-sky-500 hover:border-sky-600 hover:bg-sky-600 hover:text-white focus:border-sky-600 focus:bg-sky-600 focus:text-white focus:ring focus:ring-sky-100 active:border-sky-600 active:bg-sky-600 active:text-white active:ring active:ring-sky-100 dark:ring-sky-400/20 dark:hover:bg-sky-500 dark:focus:bg-sky-500"
                                                    title="Edit">
                                                    <i data-lucide="pencil" class="size-4"></i>
                                                </button>

                                                <!-- Edit Modal -->
                                                <div id="editViolationModal{{ $violation->id }}" modal-center
                                                    class="z-drawer show fixed left-2/4 flex hidden -translate-x-2/4 -translate-y-2/4 flex-col transition-all duration-300 ease-in-out">
                                                    <div
                                                        class="dark:bg-zink-600 w-screen rounded-md bg-white shadow md:w-[30rem]">
                                                        <div
                                                            class="dark:border-zink-500 flex items-center justify-between border-b p-4">
                                                            <h5 class="text-16">Edit Pelanggaran</h5>
                                                            <button
                                                                data-modal-close="editViolationModal{{ $violation->id }}"
                                                                class="text-slate-400 transition-all duration-200 ease-linear hover:text-red-500">
                                                                <i data-lucide="x" class="size-5"></i>
                                                            </button>
                                                        </div>
                                                        <div
                                                            class="max-h-[calc(theme('height.screen')_-_180px)] overflow-y-auto p-4">
                                                            <form method="POST"
                                                                action="{{ route('superadmin.violations.update', $violation->id) }}">
                                                                @csrf
                                                                @method('PUT')
                                                                <div id="alert-error-msg"
                                                                    class="hidden rounded-md border border-transparent bg-red-50 px-4 py-3 text-sm text-red-500 dark:bg-red-500/20">
                                                                </div>
                                                                <div class="grid grid-cols-1 gap-4">

                                                                    <div>
                                                                        <label for="categorySelect{{ $violation->id }}"
                                                                            class="mb-2 inline-block text-base font-medium">
                                                                            Kategori
                                                                        </label>
                                                                        <select name="category_id"
                                                                            id="categorySelect{{ $violation->id }}"
                                                                            class="form-input dark:border-zink-500 focus:border-custom-500 dark:disabled:bg-zink-600 dark:disabled:border-zink-500 dark:disabled:text-zink-200 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 dark:placeholder:text-zink-200 border-slate-200 placeholder:text-slate-400 focus:outline-none disabled:border-slate-300 disabled:bg-slate-100 disabled:text-slate-500"
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
                                                                            class="mb-2 inline-block text-base font-medium">
                                                                            Nama Pelanggaran
                                                                        </label>
                                                                        <input type="text" name="name"
                                                                            id="violationName{{ $violation->id }}"
                                                                            value="{{ $violation->name }}"
                                                                            class="form-input dark:border-zink-500 focus:border-custom-500 dark:disabled:bg-zink-600 dark:disabled:border-zink-500 dark:disabled:text-zink-200 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 dark:placeholder:text-zink-200 border-slate-200 placeholder:text-slate-400 focus:outline-none disabled:border-slate-300 disabled:bg-slate-100 disabled:text-slate-500"
                                                                            placeholder="Masukkan nama pelanggaran"
                                                                            required>
                                                                    </div>


                                                                    <div>
                                                                        <label for="pointInput{{ $violation->id }}"
                                                                            class="mb-2 inline-block text-base font-medium">
                                                                            Poin Pelanggaran
                                                                        </label>
                                                                        <input type="number" name="point"
                                                                            id="pointInput{{ $violation->id }}"
                                                                            value="{{ $violation->point }}"
                                                                            min="0" max="100"
                                                                            class="form-input dark:border-zink-500 focus:border-custom-500 dark:disabled:bg-zink-600 dark:disabled:border-zink-500 dark:disabled:text-zink-200 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 dark:placeholder:text-zink-200 border-slate-200 placeholder:text-slate-400 focus:outline-none disabled:border-slate-300 disabled:bg-slate-100 disabled:text-slate-500"
                                                                            placeholder="0" required>
                                                                    </div>
                                                                </div>

                                                                <div class="mt-4 flex justify-end gap-2">
                                                                    <button type="reset"
                                                                        data-modal-close="editViolationModal{{ $violation->id }}"
                                                                        class="btn dark:bg-zink-600 bg-white text-red-500 hover:bg-red-100 hover:text-red-500 focus:bg-red-100 focus:text-red-500 active:bg-red-100 active:text-red-500 dark:hover:bg-red-500/10 dark:focus:bg-red-500/10 dark:active:bg-red-500/10">
                                                                        Batal
                                                                    </button>
                                                                    <button type="submit"
                                                                        class="btn bg-custom-500 border-custom-500 hover:bg-custom-600 hover:border-custom-600 focus:bg-custom-600 focus:border-custom-600 focus:ring-custom-100 active:bg-custom-600 active:border-custom-600 active:ring-custom-100 dark:ring-custom-400/20 text-white hover:text-white focus:text-white focus:ring active:text-white active:ring">
                                                                        Update Pelanggaran
                                                                    </button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>


                                                <button data-modal-target="deleteViolationModal{{ $violation->id }}"
                                                    class="btn dark:bg-zink-700 flex size-[37.5px] items-center justify-center rounded-full border-red-500 bg-white p-0 text-red-500 hover:border-red-600 hover:bg-red-600 hover:text-white focus:border-red-600 focus:bg-red-600 focus:text-white focus:ring focus:ring-red-100 active:border-red-600 active:bg-red-600 active:text-white active:ring active:ring-red-100 dark:ring-red-400/20 dark:hover:bg-red-500 dark:focus:bg-red-500"
                                                    title="Hapus">
                                                    <i data-lucide="trash-2" class="size-4"></i>
                                                </button>

                                                <!-- Delete Confirmation Modal -->
                                                <div id="deleteViolationModal{{ $violation->id }}" modal-center
                                                    class="z-drawer show fixed left-2/4 flex hidden -translate-x-2/4 -translate-y-2/4 flex-col transition-all duration-300 ease-in-out">
                                                    <div
                                                        class="dark:bg-zink-600 w-screen rounded-md bg-white shadow md:w-[30rem]">
                                                        <div
                                                            class="dark:border-zink-300/20 flex items-center justify-between border-b p-4">
                                                            <h5 class="text-16">Konfirmasi Hapus</h5>
                                                            <button
                                                                data-modal-close="deleteViolationModal{{ $violation->id }}"
                                                                class="text-slate-400 transition-all duration-200 ease-linear hover:text-red-500">
                                                                <i data-lucide="x" class="size-5"></i>
                                                            </button>
                                                        </div>
                                                        <div class="p-4">
                                                            <div class="text-center">
                                                                <i data-lucide="alert-triangle"
                                                                    class="mx-auto mb-4 h-12 w-12 text-red-500"></i>
                                                                <h5 class="mb-2">Apakah Anda yakin?</h5>
                                                                <p class="dark:text-zink-200 mb-4 text-slate-500">
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
                                                                        class="btn dark:bg-zink-500 dark:border-zink-500 border-slate-300 bg-white text-slate-500 hover:text-slate-600">
                                                                        Batal
                                                                    </button>
                                                                    <button type="submit"
                                                                        class="btn border-red-500 bg-red-500 text-white hover:bg-red-600">
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
                        <div class="py-8 text-center">
                            <div class="dark:text-zink-400 flex flex-col items-center text-slate-500">
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
                        class="z-drawer show fixed left-2/4 flex hidden -translate-x-2/4 -translate-y-2/4 flex-col transition-all duration-300 ease-in-out">
                        <div class="dark:bg-zink-600 w-screen rounded-md bg-white shadow md:w-[30rem]">
                            <div class="dark:border-zink-500 flex items-center justify-between border-b p-4">
                                <h5 class="text-16">Tambah Pelanggaran</h5>
                                <button data-modal-close="addViolationModal"
                                    class="text-slate-400 transition-all duration-200 ease-linear hover:text-red-500">
                                    <i data-lucide="x" class="size-5"></i>
                                </button>
                            </div>
                            <div class="max-h-[calc(theme('height.screen')_-_180px)] overflow-y-auto p-4">
                                <form method="POST" action="{{ route('superadmin.violations.add') }}">
                                    @csrf
                                    <div id="alert-error-msg"
                                        class="hidden rounded-md border border-transparent bg-red-50 px-4 py-3 text-sm text-red-500 dark:bg-red-500/20">
                                    </div>
                                    <div class="grid grid-cols-1 gap-4">

                                        <div>
                                            <label for="categorySelect" class="mb-2 inline-block text-base font-medium">
                                                Kategori
                                            </label>
                                            <select name="category_id" id="categorySelect"
                                                class="form-input dark:border-zink-500 focus:border-custom-500 dark:disabled:bg-zink-600 dark:disabled:border-zink-500 dark:disabled:text-zink-200 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 dark:placeholder:text-zink-200 border-slate-200 placeholder:text-slate-400 focus:outline-none disabled:border-slate-300 disabled:bg-slate-100 disabled:text-slate-500"
                                                data-choices data-choices-search-false required>
                                                <option value="">Pilih Kategori</option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}">
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div>
                                            <label for="violationName" class="mb-2 inline-block text-base font-medium">
                                                Nama Pelanggaran
                                            </label>
                                            <input type="text" name="name" id="violationName"
                                                class="form-input dark:border-zink-500 focus:border-custom-500 dark:disabled:bg-zink-600 dark:disabled:border-zink-500 dark:disabled:text-zink-200 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 dark:placeholder:text-zink-200 border-slate-200 placeholder:text-slate-400 focus:outline-none disabled:border-slate-300 disabled:bg-slate-100 disabled:text-slate-500"
                                                placeholder="Masukkan nama pelanggaran" required>
                                        </div>

                                        <div>
                                            <label for="pointInput" class="mb-2 inline-block text-base font-medium">
                                                Poin Pelanggaran
                                            </label>
                                            <input type="number" name="point" id="pointInput" min="0"
                                                max="100"
                                                class="form-input dark:border-zink-500 focus:border-custom-500 dark:disabled:bg-zink-600 dark:disabled:border-zink-500 dark:disabled:text-zink-200 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 dark:placeholder:text-zink-200 border-slate-200 placeholder:text-slate-400 focus:outline-none disabled:border-slate-300 disabled:bg-slate-100 disabled:text-slate-500"
                                                placeholder="0" required>
                                        </div>
                                    </div>

                                    <div class="mt-4 flex justify-end gap-2">
                                        <button type="reset" data-modal-close="addViolationModal"
                                            class="btn dark:bg-zink-600 bg-white text-red-500 hover:bg-red-100 hover:text-red-500 focus:bg-red-100 focus:text-red-500 active:bg-red-100 active:text-red-500 dark:hover:bg-red-500/10 dark:focus:bg-red-500/10 dark:active:bg-red-500/10">
                                            Batal
                                        </button>
                                        <button type="submit"
                                            class="btn bg-custom-500 border-custom-500 hover:bg-custom-600 hover:border-custom-600 focus:bg-custom-600 focus:border-custom-600 focus:ring-custom-100 active:bg-custom-600 active:border-custom-600 active:ring-custom-100 dark:ring-custom-400/20 text-white hover:text-white focus:text-white focus:ring active:text-white active:ring">
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const categoryFilter = document.getElementById('categoryFilter');
            const minPointInput = document.getElementById('min_point');
            const maxPointInput = document.getElementById('max_point');
            const filterForm = document.getElementById('filterForm');

            let debounceTimer;
            let minChanged = false;
            let maxChanged = false;

            // Fungsi untuk cek apakah kedua input sudah diubah
            function checkAndSubmit() {
                if (minChanged && maxChanged) {
                    clearTimeout(debounceTimer);
                    filterForm.submit();
                    // Reset flag setelah submit
                    minChanged = false;
                    maxChanged = false;
                }
            }

            // Fungsi debounce untuk delay submit
            function debounceSubmit() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(function() {
                    checkAndSubmit();
                }, 500); // Delay 500ms setelah user berhenti mengetik
            }

            if (filterForm) {
                // Auto submit saat kategori berubah (langsung tanpa delay)
                if (categoryFilter) {
                    categoryFilter.addEventListener('change', function() {
                        filterForm.submit();
                    });
                }

                // Track perubahan poin minimum
                if (minPointInput) {
                    minPointInput.addEventListener('input', function() {
                        minChanged = true;
                        debounceSubmit();
                    });

                    minPointInput.addEventListener('blur', function() {
                        minChanged = true;
                        checkAndSubmit();
                    });
                }

                // Track perubahan poin maksimum
                if (maxPointInput) {
                    maxPointInput.addEventListener('input', function() {
                        maxChanged = true;
                        debounceSubmit();
                    });

                    maxPointInput.addEventListener('blur', function() {
                        maxChanged = true;
                        checkAndSubmit();
                    });
                }
            }
        });
    </script>
@endsection
