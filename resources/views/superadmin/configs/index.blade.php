@extends('layouts.app')
@section('content')
    <div
        class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu group-data-[sidebar-size=md]:ltr:ml-vertical-menu-md group-data-[sidebar-size=md]:rtl:mr-vertical-menu-md group-data-[sidebar-size=sm]:ltr:ml-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:mr-vertical-menu-sm px-4 pb-[calc(theme('spacing.header')_*_0.8)] pt-[calc(theme('spacing.header')_*_1)] group-data-[layout=horizontal]:mx-auto group-data-[layout=horizontal]:max-w-screen-2xl group-data-[layout=horizontal]:px-0 group-data-[layout=horizontal]:px-3 group-data-[layout=horizontal]:group-data-[navbar=hidden]:pt-[calc(theme('spacing.header')_*_0.9)] group-data-[navbar=bordered]:pt-[calc(theme('spacing.header')_*_1.3)] group-data-[navbar=hidden]:pt-0 group-data-[layout=horizontal]:md:pt-[calc(theme('spacing.header')_*_1.6)] group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:ltr:md:ml-auto group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:rtl:md:mr-auto">
        <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">

            <div class="flex flex-col gap-2 py-4 md:flex-row md:items-center print:hidden">
                <div class="grow">
                    <h5 class="text-16">Konfigurasi Pelanggaran</h5>
                </div>
                <ul class="flex shrink-0 items-center gap-2 text-sm font-normal">
                    <li
                        class="before:font-remix dark:text-zink-200 relative before:absolute before:-top-[3px] before:text-[18px] before:text-slate-400 before:content-['\ea54'] ltr:pr-4 ltr:before:-right-1 rtl:pl-4 rtl:before:-left-1">
                        <a href="#!" class="dark:text-zink-200 text-slate-400">Konfigurasi</a>
                    </li>
                    <li class="dark:text-zink-100 text-slate-700">
                        List View
                    </li>
                </ul>
            </div>

            @if (session('success'))
                <div id="success-alert"
                    class="mb-4 flex items-center justify-between rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-500 dark:border-green-500/50 dark:bg-green-400/20">
                    <span>{{ session('success') }}</span>
                    <button type="button" onclick="document.getElementById('success-alert').remove()"
                        class="ml-4 text-green-500 hover:text-green-700 dark:hover:text-green-300">
                        <i data-lucide="x" class="h-4 w-4"></i>
                    </button>
                </div>
            @endif

            @if (session('error'))
                <div id="error-alert"
                    class="mb-4 flex items-center justify-between rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-500 dark:border-red-500/50 dark:bg-red-400/20">
                    <span>{{ session('error') }}</span>
                    <button type="button" onclick="document.getElementById('error-alert').remove()"
                        class="ml-4 text-red-500 hover:text-red-700 dark:hover:text-red-300">
                        <i data-lucide="x" class="h-4 w-4"></i>
                    </button>
                </div>
            @endif

            <div class="card" id="configListTable">
                <div class="card-body">
                    <div class="grid grid-cols-1 gap-4 lg:grid-cols-2 xl:grid-cols-12">
                        <div class="xl:col-span-3">
                            <div class="relative">
                                <input type="text" id="searchAcademic"
                                    class="search form-input dark:border-zink-500 focus:border-custom-500 dark:bg-zink-700 dark:placeholder:text-zink-200 border-slate-200 placeholder:text-slate-400 focus:outline-none ltr:pl-8 rtl:pr-8"
                                    placeholder="Cari tahun akademik..." autocomplete="off">

                                <i data-lucide="search"
                                    class="dark:text-zink-200 dark:fill-zink-600 absolute top-2.5 inline-block size-4 fill-slate-100 text-slate-500 ltr:left-2.5 rtl:right-2.5"></i>
                            </div>
                        </div>
                        <div class="lg:col-span-2 xl:col-span-2 xl:col-start-11 ltr:lg:text-right rtl:lg:text-left">
                            <button data-modal-target="addConfigModal" type="button"
                                class="btn bg-custom-500 border-custom-500 hover:bg-custom-600 hover:border-custom-600 focus:bg-custom-600 focus:border-custom-600 focus:ring-custom-100 active:bg-custom-600 active:border-custom-600 active:ring-custom-100 dark:ring-custom-400/20 text-white hover:text-white focus:text-white focus:ring active:text-white active:ring">
                                <i data-lucide="plus" class="inline-block size-4"></i>
                                <span class="align-middle">Tambah Konfigurasi</span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body !pt-1">
                    <div class="overflow-x-auto">
                        <table class="w-full whitespace-nowrap" id="configTable">
                            <thead class="dark:bg-zink-600 bg-slate-100 ltr:text-left rtl:text-right">
                                <tr>
                                    <th class="dark:border-zink-500 border-b border-slate-200 px-3.5 py-2.5 font-semibold">
                                        Tahun Akademik
                                    </th>
                                    <th class="dark:border-zink-500 border-b border-slate-200 px-3.5 py-2.5 font-semibold">
                                        Status
                                    </th>
                                    <th class="dark:border-zink-500 border-b border-slate-200 px-3.5 py-2.5 font-semibold">
                                        Dibuat Oleh
                                    </th>
                                    <th class="dark:border-zink-500 border-b border-slate-200 px-3.5 py-2.5 font-semibold">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="list">
                                @forelse($configs as $config)
                                    <tr>
                                        <td class="dark:border-zink-500 border-y border-slate-200 px-3.5 py-2.5">
                                            <span class="font-semibold">{{ $config->academic_year }}</span>
                                        </td>
                                        <td class="dark:border-zink-500 border-y border-slate-200 px-3.5 py-2.5">
                                            @if ($config->is_active)
                                                <span
                                                    class="inline-block rounded border border-green-200 bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-500 dark:border-green-500/20 dark:bg-green-500/20">
                                                    Aktif
                                                </span>
                                            @else
                                                <span
                                                    class="inline-block rounded border border-slate-200 bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-500 dark:border-slate-500/20 dark:bg-slate-500/20">
                                                    Tidak Aktif
                                                </span>
                                            @endif
                                        </td>
                                        <td class="dark:border-zink-500 border-y border-slate-200 px-3.5 py-2.5">
                                            {{ $config->createdBy->name ?? '-' }}
                                        </td>
                                        <td class="dark:border-zink-500 border-y border-slate-200 px-3.5 py-2.5">
                                            <div class="flex gap-2">
                                                {{-- Button View (Slate) - Dipindah ke depan --}}
                                                <button data-modal-target="viewHandlingsModal"
                                                    data-config-year="{{ $config->academic_year }}"
                                                    data-config-handlings='@json($config->handlings)'
                                                    class="btn-view btn flex size-[30px] items-center justify-center bg-slate-100 p-0 text-slate-500 hover:bg-slate-600 hover:text-white focus:bg-slate-600 focus:text-white dark:bg-slate-500/20 dark:text-slate-400 dark:hover:bg-slate-500 dark:hover:text-white"
                                                    title="Lihat Handling Points">
                                                    <i data-lucide="list" class="size-3"></i>
                                                </button>

                                                {{-- Button Edit (Blue) - Dipindah ke belakang --}}
                                                <button data-modal-target="editConfigModal"
                                                    data-config-id="{{ $config->id }}"
                                                    data-config-year="{{ $config->academic_year }}"
                                                    data-config-handlings='@json($config->handlings)'
                                                    class="btn-edit btn flex size-[30px] items-center justify-center bg-sky-100 p-0 text-sky-500 hover:bg-sky-600 hover:text-white focus:bg-sky-600 focus:text-white focus:ring focus:ring-sky-100 active:bg-sky-600 active:text-white active:ring active:ring-sky-100 dark:bg-sky-500/20 dark:text-sky-400 dark:ring-sky-400/20 dark:hover:bg-sky-500 dark:hover:text-white dark:focus:bg-sky-500 dark:focus:text-white dark:active:bg-sky-500 dark:active:text-white"
                                                    title="Edit">
                                                    <i data-lucide="pencil" class="size-3"></i>
                                                </button>

                                                @if ($config->is_active)
                                                    <button data-modal-target="deactivateConfigModal"
                                                        data-config-id="{{ $config->id }}"
                                                        class="btn-deactivate btn flex size-[30px] items-center justify-center bg-orange-100 p-0 text-orange-500 hover:bg-orange-600 hover:text-white focus:bg-orange-600 focus:text-white dark:bg-orange-500/20 dark:hover:bg-orange-500 dark:hover:text-white"
                                                        title="Nonaktifkan">
                                                        <i data-lucide="power" class="size-3"></i>
                                                    </button>
                                                @else
                                                    <button data-modal-target="activateConfigModal"
                                                        data-config-id="{{ $config->id }}"
                                                        class="btn-activate btn flex size-[30px] items-center justify-center bg-green-100 p-0 text-green-500 hover:bg-green-600 hover:text-white focus:bg-green-600 focus:text-white dark:bg-green-500/20 dark:hover:bg-green-500 dark:hover:text-white"
                                                        title="Aktifkan">
                                                        <i data-lucide="check-circle" class="size-3"></i>
                                                    </button>
                                                @endif

                                                <button data-modal-target="deleteConfigModal"
                                                    data-config-id="{{ $config->id }}"
                                                    data-config-year="{{ $config->academic_year }}"
                                                    class="btn-delete btn flex size-[30px] items-center justify-center bg-red-100 p-0 text-red-500 hover:bg-red-600 hover:text-white focus:bg-red-600 focus:text-white dark:bg-red-500/20 dark:hover:bg-red-500 dark:hover:text-white"
                                                    title="Hapus">
                                                    <i data-lucide="trash-2" class="size-3"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4"
                                            class="dark:border-zink-500 border-y border-slate-200 px-3.5 py-8 text-center">
                                            <div class="py-6">
                                                <i data-lucide="inbox" class="mx-auto mb-3 h-6 w-6 text-slate-500"></i>
                                                <h5 class="mb-1 mt-2">Tidak Ada Data</h5>
                                                <p class="dark:text-zink-200 mb-0 text-slate-500">
                                                    Belum ada konfigurasi yang ditambahkan.
                                                </p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($configs->count() > 0)
                        <div class="mt-4 flex flex-col items-center gap-4 px-4 md:flex-row">
                            <div class="grow">
                                <p class="dark:text-zink-200 text-slate-500">
                                    Showing <b>{{ $configs->firstItem() }}</b> to <b>{{ $configs->lastItem() }}</b> of
                                    <b>{{ $configs->total() }}</b> Results
                                </p>
                            </div>
                            <div class="col-sm-auto mt-sm-0">
                                {{ $configs->links() }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Add Config Modal -->
    <div id="addConfigModal" modal-center
        class="z-drawer show fixed left-2/4 flex hidden -translate-x-2/4 -translate-y-2/4 flex-col transition-all duration-300 ease-in-out">
        <div class="dark:bg-zink-600 w-screen rounded-md bg-white shadow md:w-[40rem]">
            <div class="dark:border-zink-300/20 flex items-center justify-between border-b p-4">
                <h5 class="text-16">Tambah Konfigurasi</h5>
                <button data-modal-close="addConfigModal"
                    class="text-slate-400 transition-all duration-200 ease-linear hover:text-red-500">
                    <i data-lucide="x" class="size-5"></i>
                </button>
            </div>
            <div class="max-h-[calc(theme('height.screen')_-_180px)] overflow-y-auto p-4">
                <form action="{{ route('superadmin.configs.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="academicYearInput" class="mb-2 inline-block text-base font-medium">
                            Tahun Akademik <span class="text-red-500">*</span>
                        </label>
                        <select id="academicYearInput" name="academic_year"
                            class="form-input dark:border-zink-500 focus:border-custom-500 border-slate-200 focus:outline-none"
                            required>
                            <option value="">Pilih Tahun Akademik</option>
                            @foreach ($academicYears as $year)
                                <option value="{{ $year->academic_year }}">{{ $year->academic_year }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="is_active" value="1"
                                class="form-checkbox dark:border-zink-500 focus:ring-custom-500 border-slate-200">
                            <span class="ml-2 text-base">Set sebagai konfigurasi aktif</span>
                        </label>
                        <p class="mt-1 text-sm text-slate-500">
                            Jika dicentang, konfigurasi lain akan otomatis menjadi tidak aktif
                        </p>
                    </div>

                    <div class="mb-4">
                        <label class="mb-2 inline-block text-base font-medium">
                            Handling Points (Opsional)
                        </label>
                        <div id="handlingPointsContainer" class="space-y-2">
                            <div class="handling-point-row mb-2 flex items-center gap-3">
                                <input type="number" name="handling_points[]"
                                    class="form-input dark:border-zink-500 focus:border-custom-500 w-24 shrink-0 border-slate-200 focus:outline-none"
                                    placeholder="Point (contoh: 25)">

                                <input type="text" name="handling_actions[]"
                                    class="form-input dark:border-zink-500 focus:border-custom-500 flex-1 border-slate-200 focus:outline-none"
                                    placeholder="Aksi (contoh: Peringatan lisan)">

                                <button type="button"
                                    class="btn-remove-handling btn shrink-0 rounded-lg bg-red-100 !px-3 !py-2 text-red-500 hover:bg-red-600 hover:text-white">
                                    <i data-lucide="trash-2" class="size-4"></i>
                                </button>
                            </div>
                        </div>

                        <button type="button" id="btnAddHandling"
                            class="text-custom-500 btn bg-custom-100 hover:bg-custom-200 mt-2">
                            <i data-lucide="plus" class="inline-block size-4"></i> Tambah Handling Point
                        </button>
                    </div>

                    <div class="mt-4 flex justify-end gap-2">
                        <button type="button" data-modal-close="addConfigModal"
                            class="btn dark:bg-zink-500 dark:border-zink-500 border-white bg-white text-red-500 hover:text-red-600">
                            Batal
                        </button>
                        <button type="submit" class="btn bg-custom-500 border-custom-500 hover:bg-custom-600 text-white">
                            Simpan Konfigurasi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Config Modal -->
    <div id="editConfigModal" modal-center
        class="z-drawer show fixed left-2/4 flex hidden -translate-x-2/4 -translate-y-2/4 flex-col transition-all duration-300 ease-in-out">
        <div class="dark:bg-zink-600 w-screen rounded-md bg-white shadow md:w-[40rem]">
            <div class="dark:border-zink-300/20 flex items-center justify-between border-b p-4">
                <h5 class="text-16">Edit Konfigurasi</h5>
                <button data-modal-close="editConfigModal"
                    class="text-slate-400 transition-all duration-200 ease-linear hover:text-red-500">
                    <i data-lucide="x" class="size-5"></i>
                </button>
            </div>
            <div class="max-h-[calc(theme('height.screen')_-_180px)] overflow-y-auto p-4">
                <form id="editConfigForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="editAcademicYear" class="mb-2 inline-block text-base font-medium">
                            Tahun Akademik <span class="text-red-500">*</span>
                        </label>
                        <select id="editAcademicYear" name="academic_year"
                            class="form-input dark:border-zink-500 focus:border-custom-500 border-slate-200 focus:outline-none"
                            required>
                            <option value="">Pilih Tahun Akademik</option>
                            @foreach ($academicYears as $year)
                                <option value="{{ $year->academic_year }}">{{ $year->academic_year }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="mb-2 inline-block text-base font-medium">
                            Handling Points
                        </label>
                        <div id="editHandlingPointsContainer" class="space-y-2">
                            <!-- Will be populated by JavaScript -->
                        </div>
                        <button type="button" id="btnAddEditHandling"
                            class="text-custom-500 btn bg-custom-100 hover:bg-custom-200 mt-2">
                            <i data-lucide="plus" class="inline-block size-4"></i> Tambah Handling Point
                        </button>
                    </div>

                    <div class="mt-4 flex justify-end gap-2">
                        <button type="button" data-modal-close="editConfigModal"
                            class="btn dark:bg-zink-500 dark:border-zink-500 border-white bg-white text-red-500 hover:text-red-600">
                            Batal
                        </button>
                        <button type="submit" class="btn bg-custom-500 border-custom-500 hover:bg-custom-600 text-white">
                            Update Konfigurasi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Handlings Modal -->
    <div id="viewHandlingsModal" modal-center
        class="z-drawer show fixed left-2/4 flex hidden -translate-x-2/4 -translate-y-2/4 flex-col transition-all duration-300 ease-in-out">
        <div class="dark:bg-zink-600 w-screen rounded-md bg-white shadow md:w-[40rem]">
            <div class="dark:border-zink-300/20 flex items-center justify-between border-b p-4">
                <h5 class="text-16">Handling Points - <span id="handlingAcademicYear"></span></h5>
                <button data-modal-close="viewHandlingsModal"
                    class="text-slate-400 transition-all duration-200 ease-linear hover:text-red-500">
                    <i data-lucide="x" class="size-5"></i>
                </button>
            </div>
            <div class="max-h-[calc(theme('height.screen')_-_180px)] overflow-y-auto p-4">
                <div id="handlingsList" class="space-y-3">
                    <!-- Will be populated by JavaScript -->
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteConfigModal" modal-center
        class="z-drawer show fixed left-2/4 flex hidden -translate-x-2/4 -translate-y-2/4 flex-col transition-all duration-300 ease-in-out">
        <div class="dark:bg-zink-600 w-screen rounded-md bg-white shadow md:w-[30rem]">
            <div class="dark:border-zink-300/20 flex items-center justify-between border-b p-4">
                <h5 class="text-16">Konfirmasi Hapus</h5>
                <button data-modal-close="deleteConfigModal"
                    class="text-slate-400 transition-all duration-200 ease-linear hover:text-red-500">
                    <i data-lucide="x" class="size-5"></i>
                </button>
            </div>
            <div class="p-4">
                <div class="text-center">
                    <i data-lucide="alert-triangle" class="mx-auto mb-4 h-12 w-12 text-red-500"></i>
                    <h5 class="mb-2">Apakah Anda yakin?</h5>
                    <p class="dark:text-zink-200 mb-4 text-slate-500">
                        Anda akan menghapus konfigurasi <strong id="deleteConfigName"></strong>.
                        Tindakan ini tidak dapat dibatalkan.
                    </p>
                </div>
                <form id="deleteConfigForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="flex justify-center gap-2">
                        <button type="button" data-modal-close="deleteConfigModal"
                            class="btn dark:bg-zink-500 dark:border-zink-500 border-slate-300 bg-white text-slate-500 hover:text-slate-600">
                            Batal
                        </button>
                        <button type="submit" class="btn border-red-500 bg-red-500 text-white hover:bg-red-600">
                            Ya, Hapus
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Activate Confirmation Modal -->
    <div id="activateConfigModal" modal-center
        class="z-drawer show fixed left-2/4 flex hidden -translate-x-2/4 -translate-y-2/4 flex-col transition-all duration-300 ease-in-out">
        <div class="dark:bg-zink-600 w-screen rounded-md bg-white shadow md:w-[30rem]">
            <div class="dark:border-zink-300/20 flex items-center justify-between border-b p-4">
                <h5 class="text-16">Konfirmasi Aktivasi</h5>
                <button data-modal-close="activateConfigModal"
                    class="text-slate-400 transition-all duration-200 ease-linear hover:text-red-500">
                    <i data-lucide="x" class="size-5"></i>
                </button>
            </div>
            <div class="p-4">
                <div class="text-center">
                    <i data-lucide="check-circle" class="mx-auto mb-4 h-12 w-12 text-green-500"></i>
                    <h5 class="mb-2">Aktifkan Konfigurasi?</h5>
                    <p class="dark:text-zink-200 mb-4 text-slate-500">
                        Konfigurasi lain akan dinonaktifkan secara otomatis.
                    </p>
                </div>
                <form id="activateConfigForm" method="POST">
                    @csrf
                    <div class="flex justify-center gap-2">
                        <button type="button" data-modal-close="activateConfigModal"
                            class="btn dark:bg-zink-500 dark:border-zink-500 border-slate-300 bg-white text-slate-500 hover:text-slate-600">
                            Batal
                        </button>
                        <button type="submit" class="btn border-green-500 bg-green-500 text-white hover:bg-green-600">
                            Ya, Aktifkan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Deactivate Confirmation Modal -->
    <div id="deactivateConfigModal" modal-center
        class="z-drawer show fixed left-2/4 flex hidden -translate-x-2/4 -translate-y-2/4 flex-col transition-all duration-300 ease-in-out">
        <div class="dark:bg-zink-600 w-screen rounded-md bg-white shadow md:w-[30rem]">
            <div class="dark:border-zink-300/20 flex items-center justify-between border-b p-4">
                <h5 class="text-16">Konfirmasi Nonaktifkan</h5>
                <button data-modal-close="deactivateConfigModal"
                    class="text-slate-400 transition-all duration-200 ease-linear hover:text-red-500">
                    <i data-lucide="x" class="size-5"></i>
                </button>
            </div>
            <div class="p-4">
                <div class="text-center">
                    <i data-lucide="power" class="mx-auto mb-4 h-12 w-12 text-orange-500"></i>
                    <h5 class="mb-2">Nonaktifkan Konfigurasi?</h5>
                    <p class="dark:text-zink-200 mb-4 text-slate-500">
                        Konfigurasi ini akan dinonaktifkan.
                    </p>
                </div>
                <form id="deactivateConfigForm" method="POST">
                    @csrf
                    <div class="flex justify-center gap-2">
                        <button type="button" data-modal-close="deactivateConfigModal"
                            class="btn dark:bg-zink-500 dark:border-zink-500 border-slate-300 bg-white text-slate-500 hover:text-slate-600">
                            Batal
                        </button>
                        <button type="submit" class="btn border-orange-500 bg-orange-500 text-white hover:bg-orange-600">
                            Ya, Nonaktifkan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        // Laravel routes helper
        const routes = {
            update: "{{ route('superadmin.configs.update', ':id') }}",
            destroy: "{{ route('superadmin.configs.destroy', ':id') }}",
            activate: "{{ route('superadmin.configs.activate', ':id') }}",
            deactivate: "{{ route('superadmin.configs.deactivate', ':id') }}"
        };
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Modal System
            document.addEventListener('click', function(e) {
                // Open modal
                if (e.target.closest('[data-modal-target]')) {
                    const btn = e.target.closest('[data-modal-target]');
                    const modalId = btn.getAttribute('data-modal-target');
                    const modal = document.getElementById(modalId);

                    if (modal) {
                        // Handle different modals
                        if (modalId === 'editConfigModal') {
                            handleEditModal(btn);
                        } else if (modalId === 'viewHandlingsModal') {
                            handleViewModal(btn);
                        } else if (modalId === 'deleteConfigModal') {
                            handleDeleteModal(btn);
                        } else if (modalId === 'activateConfigModal') {
                            handleActivateModal(btn);
                        } else if (modalId === 'deactivateConfigModal') {
                            handleDeactivateModal(btn);
                        }

                        modal.classList.remove('hidden');
                        if (typeof lucide !== 'undefined') {
                            lucide.createIcons();
                        }
                    }
                }

                // Close modal
                if (e.target.closest('[data-modal-close]')) {
                    const btn = e.target.closest('[data-modal-close]');
                    const modalId = btn.getAttribute('data-modal-close');
                    const modal = document.getElementById(modalId);
                    if (modal) {
                        modal.classList.add('hidden');
                    }
                }
            });

            // Handle Edit Modal
            function handleEditModal(btn) {
                const configId = btn.getAttribute('data-config-id');
                const configYear = btn.getAttribute('data-config-year');
                const handlings = JSON.parse(btn.getAttribute('data-config-handlings') || '[]');

                const form = document.getElementById('editConfigForm');
                form.action = routes.update.replace(':id', configId);

                document.getElementById('editAcademicYear').value = configYear;

                const container = document.getElementById('editHandlingPointsContainer');
                container.innerHTML = '';

                if (handlings && handlings.length > 0) {
                    handlings.forEach(handling => {
                        addEditHandlingRow(handling);
                    });
                } else {
                    addEditHandlingRow();
                }
            }

            // Handle View Modal
            function handleViewModal(btn) {
                const configYear = btn.getAttribute('data-config-year');
                const handlings = JSON.parse(btn.getAttribute('data-config-handlings') || '[]');

                document.getElementById('handlingAcademicYear').textContent = configYear;

                const container = document.getElementById('handlingsList');
                container.innerHTML = '';

                if (handlings && handlings.length > 0) {
                    handlings.forEach((handling, index) => {
                        const item = document.createElement('div');
                        item.className =
                            'p-4 border border-slate-200 dark:border-zink-500 rounded-md bg-slate-50 dark:bg-zink-700';
                        item.innerHTML = `
                    <div class="flex items-start gap-3">
                        <div class="flex items-center justify-center w-8 h-8 rounded-full bg-custom-500 text-white font-semibold text-sm">
                            ${index + 1}
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="px-2 py-1 text-xs font-semibold rounded bg-custom-100 text-custom-500 dark:bg-custom-500/20">
                                    ${handling.handling_point} Point
                                </span>
                            </div>
                            <p class="text-slate-700 dark:text-zink-200">
                                ${handling.handling_action}
                            </p>
                        </div>
                    </div>
                `;
                        container.appendChild(item);
                    });
                } else {
                    container.innerHTML = `
                <div class="text-center py-8">
                    <i data-lucide="inbox" class="w-12 h-12 mx-auto mb-3 text-slate-400"></i>
                    <p class="text-slate-500 dark:text-zink-200">
                        Belum ada handling points yang ditambahkan
                    </p>
                </div>
            `;
                }

                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            }

            // Handle Delete Modal
            function handleDeleteModal(btn) {
                const configId = btn.getAttribute('data-config-id');
                const configYear = btn.getAttribute('data-config-year');

                const form = document.getElementById('deleteConfigForm');
                form.action = routes.destroy.replace(':id', configId);

                document.getElementById('deleteConfigName').textContent = configYear;

            }

            // Handle Activate Modal
            function handleActivateModal(btn) {
                const configId = btn.getAttribute('data-config-id');

                const form = document.getElementById('activateConfigForm');
                form.action = routes.activate.replace(':id', configId);

                // Pastikan ada input hidden untuk method PUT jika belum ada
                let methodInput = form.querySelector('input[name="_method"]');
                if (!methodInput) {
                    methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'PUT';
                    form.appendChild(methodInput);
                }
            }

            // Handle Deactivate Modal
            function handleDeactivateModal(btn) {
                const configId = btn.getAttribute('data-config-id');

                const form = document.getElementById('deactivateConfigForm');
                form.action = routes.deactivate.replace(':id', configId);

                // Pastikan ada input hidden untuk method PUT jika belum ada
                let methodInput = form.querySelector('input[name="_method"]');
                if (!methodInput) {
                    methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'PUT';
                    form.appendChild(methodInput);
                }
            }

            // Add handling row for create form
            document.getElementById('btnAddHandling').addEventListener('click', function() {
                addHandlingRow();
            });

            function addHandlingRow(handling = null) {
                const container = document.getElementById('handlingPointsContainer');

                const newRow = document.createElement('div');
                newRow.className = 'flex items-center gap-3 mb-2 handling-point-row';

                newRow.innerHTML = `
            <input type="number" name="handling_points[]" value="${handling ? handling.handling_point : ''}"
                class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 w-24 shrink-0"
                placeholder="Point">

            <input type="text" name="handling_actions[]" value="${handling ? handling.handling_action : ''}"
                class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 flex-1"
                placeholder="Aksi (contoh: Peringatan lisan)">

            <button type="button"
                class="btn-remove-handling text-red-500 btn bg-red-100 hover:text-white hover:bg-red-600 !px-3 !py-2 rounded-lg shrink-0">
                <i data-lucide="trash-2" class="size-4"></i>
            </button>
        `;

                container.appendChild(newRow);

                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            }

            // Add handling row for edit form
            document.getElementById('btnAddEditHandling').addEventListener('click', function() {
                addEditHandlingRow();
            });

            function addEditHandlingRow(handling = null) {
                const container = document.getElementById('editHandlingPointsContainer');
                const newRow = document.createElement('div');

                newRow.className = 'flex items-center gap-3 mb-2 handling-point-row';

                newRow.innerHTML = `
            <input type="number" name="handling_points[]" value="${handling ? handling.handling_point : ''}"
                class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 w-24 shrink-0"
                placeholder="Point (contoh: 25)" required>

            <input type="text" name="handling_actions[]" value="${handling ? handling.handling_action : ''}"
                class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 flex-1"
                placeholder="Aksi (contoh: Peringatan lisan)" required>

            <button type="button"
                class="btn-remove-handling text-red-500 btn bg-red-100 hover:text-white hover:bg-red-600 !px-3 !py-2 rounded-lg shrink-0">
                <i data-lucide="trash-2" class="size-4"></i>
            </button>
         `;

                container.appendChild(newRow);

                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            }

            // Remove handling row (event delegation)
            document.addEventListener('click', function(e) {
                if (e.target.closest('.btn-remove-handling')) {
                    const btn = e.target.closest('.btn-remove-handling');
                    const row = btn.closest('.handling-point-row');
                    const container = row.parentElement;
                    const rows = container.querySelectorAll('.handling-point-row');

                    if (rows.length > 1) {
                        row.remove();
                    }
                }
            });

            // Search functionality
            document.getElementById("searchAcademic").addEventListener("keyup", function() {
                const keyword = this.value.toLowerCase();
                const rows = document.querySelectorAll("#configTable tbody tr");

                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(keyword) ? "" : "none";
                });
            });
        });
    </script>
@endsection
