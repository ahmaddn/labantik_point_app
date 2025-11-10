@extends('layouts.app')
@section('content')
    <div
        class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu group-data-[sidebar-size=md]:ltr:ml-vertical-menu-md group-data-[sidebar-size=md]:rtl:mr-vertical-menu-md group-data-[sidebar-size=sm]:ltr:ml-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:mr-vertical-menu-sm pt-[calc(theme('spacing.header')_*_1)] pb-[calc(theme('spacing.header')_*_0.8)] px-4 group-data-[navbar=bordered]:pt-[calc(theme('spacing.header')_*_1.3)] group-data-[navbar=hidden]:pt-0 group-data-[layout=horizontal]:mx-auto group-data-[layout=horizontal]:max-w-screen-2xl group-data-[layout=horizontal]:px-0 group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:ltr:md:ml-auto group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:rtl:md:mr-auto group-data-[layout=horizontal]:md:pt-[calc(theme('spacing.header')_*_1.6)] group-data-[layout=horizontal]:px-3 group-data-[layout=horizontal]:group-data-[navbar=hidden]:pt-[calc(theme('spacing.header')_*_0.9)]">
        <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">

            <div class="flex flex-col gap-2 py-4 md:flex-row md:items-center print:hidden">
                <div class="grow">
                    <h5 class="text-16">Konfigurasi Pelanggaran</h5>
                </div>
                <ul class="flex items-center gap-2 text-sm font-normal shrink-0">
                    <li
                        class="relative before:content-['\ea54'] before:font-remix ltr:before:-right-1 rtl:before:-left-1  before:absolute before:text-[18px] before:-top-[3px] ltr:pr-4 rtl:pl-4 before:text-slate-400 dark:text-zink-200">
                        <a href="#!" class="text-slate-400 dark:text-zink-200">Konfigurasi</a>
                    </li>
                    <li class="text-slate-700 dark:text-zink-100">
                        List View
                    </li>
                </ul>
            </div>

            @if (session('success'))
                <div id="success-alert"
                    class="mb-4 px-4 py-3 text-sm text-green-500 border border-green-200 rounded-md bg-green-50 dark:bg-green-400/20 dark:border-green-500/50 flex items-center justify-between">
                    <span>{{ session('success') }}</span>
                    <button type="button" onclick="document.getElementById('success-alert').remove()"
                        class="ml-4 text-green-500 hover:text-green-700 dark:hover:text-green-300">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
            @endif

            @if (session('error'))
                <div id="error-alert"
                    class="mb-4 px-4 py-3 text-sm text-red-500 border border-red-200 rounded-md bg-red-50 dark:bg-red-400/20 dark:border-red-500/50 flex items-center justify-between">
                    <span>{{ session('error') }}</span>
                    <button type="button" onclick="document.getElementById('error-alert').remove()"
                        class="ml-4 text-red-500 hover:text-red-700 dark:hover:text-red-300">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
            @endif

            <div class="card" id="configListTable">
                <div class="card-body">
                    <div class="grid grid-cols-1 gap-4 lg:grid-cols-2 xl:grid-cols-12">
                        <div class="xl:col-span-3">
                            <div class="relative">
                                <input type="text" id="searchAcademic"
                                    class="ltr:pl-8 rtl:pr-8 search form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:bg-zink-700 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                    placeholder="Cari tahun akademik..." autocomplete="off">

                                <i data-lucide="search"
                                    class="inline-block size-4 absolute ltr:left-2.5 rtl:right-2.5 top-2.5 text-slate-500 dark:text-zink-200 fill-slate-100 dark:fill-zink-600"></i>
                            </div>
                        </div>
                        <div class="lg:col-span-2 ltr:lg:text-right rtl:lg:text-left xl:col-span-2 xl:col-start-11">
                            <button data-modal-target="addConfigModal" type="button"
                                class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20">
                                <i data-lucide="plus" class="inline-block size-4"></i>
                                <span class="align-middle">Tambah Konfigurasi</span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="!pt-1 card-body">
                    <div class="overflow-x-auto">
                        <table class="w-full whitespace-nowrap" id="configTable">
                            <thead class="ltr:text-left rtl:text-right bg-slate-100 dark:bg-zink-600">
                                <tr>
                                    <th class="px-3.5 py-2.5 font-semibold border-b border-slate-200 dark:border-zink-500">
                                        Tahun Akademik
                                    </th>
                                    <th class="px-3.5 py-2.5 font-semibold border-b border-slate-200 dark:border-zink-500">
                                        Status
                                    </th>
                                    <th class="px-3.5 py-2.5 font-semibold border-b border-slate-200 dark:border-zink-500">
                                        Dibuat Oleh
                                    </th>
                                    <th class="px-3.5 py-2.5 font-semibold border-b border-slate-200 dark:border-zink-500">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="list">
                                @forelse($configs as $config)
                                    <tr>
                                        <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">
                                            <span class="font-semibold">{{ $config->academic_year }}</span>
                                        </td>
                                        <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">
                                            @if ($config->is_active)
                                                <span
                                                    class="px-2.5 py-0.5 inline-block text-xs font-medium rounded border bg-green-100 border-green-200 text-green-500 dark:bg-green-500/20 dark:border-green-500/20">
                                                    Aktif
                                                </span>
                                            @else
                                                <span
                                                    class="px-2.5 py-0.5 inline-block text-xs font-medium rounded border bg-slate-100 border-slate-200 text-slate-500 dark:bg-slate-500/20 dark:border-slate-500/20">
                                                    Tidak Aktif
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">
                                            {{ $config->createdBy->name ?? '-' }}
                                        </td>
                                        <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">
                                            <div class="flex gap-2">
                                                {{-- Button View (Slate) - Dipindah ke depan --}}
                                                <button data-modal-target="viewHandlingsModal"
                                                    data-config-year="{{ $config->academic_year }}"
                                                    data-config-handlings='@json($config->handlings)'
                                                    class="btn-view flex items-center justify-center size-[30px] p-0 text-slate-500 btn bg-slate-100 hover:text-white hover:bg-slate-600 focus:text-white focus:bg-slate-600 dark:bg-slate-500/20 dark:text-slate-400 dark:hover:bg-slate-500 dark:hover:text-white"
                                                    title="Lihat Handling Points">
                                                    <i data-lucide="list" class="size-3"></i>
                                                </button>

                                                {{-- Button Edit (Blue) - Dipindah ke belakang --}}
                                                <button data-modal-target="editConfigModal"
                                                    data-config-id="{{ $config->id }}"
                                                    data-config-year="{{ $config->academic_year }}"
                                                    data-config-handlings='@json($config->handlings)'
                                                    class="btn-edit flex items-center justify-center size-[30px] p-0 text-blue-500 btn bg-blue-100 hover:text-white hover:bg-blue-600 focus:text-white focus:bg-blue-600 dark:bg-blue-500/20 dark:text-blue-400 dark:hover:bg-blue-500 dark:hover:text-white"
                                                    title="Edit">
                                                    <i data-lucide="pencil" class="size-3"></i>
                                                </button>

                                                @if ($config->is_active)
                                                    <button data-modal-target="deactivateConfigModal"
                                                        data-config-id="{{ $config->id }}"
                                                        class="btn-deactivate flex items-center justify-center size-[30px] p-0 text-orange-500 btn bg-orange-100 hover:text-white hover:bg-orange-600 focus:text-white focus:bg-orange-600 dark:bg-orange-500/20 dark:hover:bg-orange-500 dark:hover:text-white"
                                                        title="Nonaktifkan">
                                                        <i data-lucide="power" class="size-3"></i>
                                                    </button>
                                                @else
                                                    <button data-modal-target="activateConfigModal"
                                                        data-config-id="{{ $config->id }}"
                                                        class="btn-activate flex items-center justify-center size-[30px] p-0 text-green-500 btn bg-green-100 hover:text-white hover:bg-green-600 focus:text-white focus:bg-green-600 dark:bg-green-500/20 dark:hover:bg-green-500 dark:hover:text-white"
                                                        title="Aktifkan">
                                                        <i data-lucide="check-circle" class="size-3"></i>
                                                    </button>
                                                @endif

                                                <button data-modal-target="deleteConfigModal"
                                                    data-config-id="{{ $config->id }}"
                                                    data-config-year="{{ $config->academic_year }}"
                                                    class="btn-delete flex items-center justify-center size-[30px] p-0 text-red-500 btn bg-red-100 hover:text-white hover:bg-red-600 focus:text-white focus:bg-red-600 dark:bg-red-500/20 dark:hover:bg-red-500 dark:hover:text-white"
                                                    title="Hapus">
                                                    <i data-lucide="trash-2" class="size-3"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4"
                                            class="px-3.5 py-8 text-center border-y border-slate-200 dark:border-zink-500">
                                            <div class="py-6">
                                                <i data-lucide="inbox" class="w-6 h-6 mx-auto mb-3 text-slate-500"></i>
                                                <h5 class="mt-2 mb-1">Tidak Ada Data</h5>
                                                <p class="mb-0 text-slate-500 dark:text-zink-200">
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
                        <div class="flex flex-col items-center gap-4 px-4 mt-4 md:flex-row">
                            <div class="grow">
                                <p class="text-slate-500 dark:text-zink-200">
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
        class="fixed flex flex-col hidden transition-all duration-300 ease-in-out left-2/4 z-drawer -translate-x-2/4 -translate-y-2/4 show">
        <div class="w-screen md:w-[40rem] bg-white shadow rounded-md dark:bg-zink-600">
            <div class="flex items-center justify-between p-4 border-b dark:border-zink-300/20">
                <h5 class="text-16">Tambah Konfigurasi</h5>
                <button data-modal-close="addConfigModal"
                    class="transition-all duration-200 ease-linear text-slate-400 hover:text-red-500">
                    <i data-lucide="x" class="size-5"></i>
                </button>
            </div>
            <div class="max-h-[calc(theme('height.screen')_-_180px)] p-4 overflow-y-auto">
                <form action="{{ route('superadmin.configs.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="academicYearInput" class="inline-block mb-2 text-base font-medium">
                            Tahun Akademik <span class="text-red-500">*</span>
                        </label>
                        <select id="academicYearInput" name="academic_year"
                            class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500"
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
                                class="form-checkbox border-slate-200 dark:border-zink-500 focus:ring-custom-500">
                            <span class="ml-2 text-base">Set sebagai konfigurasi aktif</span>
                        </label>
                        <p class="mt-1 text-sm text-slate-500">
                            Jika dicentang, konfigurasi lain akan otomatis menjadi tidak aktif
                        </p>
                    </div>

                    <div class="mb-4">
                        <label class="inline-block mb-2 text-base font-medium">
                            Handling Points (Opsional)
                        </label>
                        <div id="handlingPointsContainer" class="space-y-2">
                            <div class="flex items-center gap-3 mb-2 handling-point-row">
                                <input type="number" name="handling_points[]"
                                    class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 w-24 shrink-0"
                                    placeholder="Point (contoh: 25)">

                                <input type="text" name="handling_actions[]"
                                    class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 flex-1"
                                    placeholder="Aksi (contoh: Peringatan lisan)">

                                <button type="button"
                                    class="btn-remove-handling text-red-500 btn bg-red-100 hover:text-white hover:bg-red-600 !px-3 !py-2 rounded-lg shrink-0">
                                    <i data-lucide="trash-2" class="size-4"></i>
                                </button>
                            </div>
                        </div>

                        <button type="button" id="btnAddHandling"
                            class="mt-2 text-custom-500 btn bg-custom-100 hover:bg-custom-200">
                            <i data-lucide="plus" class="inline-block size-4"></i> Tambah Handling Point
                        </button>
                    </div>

                    <div class="flex justify-end gap-2 mt-4">
                        <button type="button" data-modal-close="addConfigModal"
                            class="text-red-500 bg-white border-white btn hover:text-red-600 dark:bg-zink-500 dark:border-zink-500">
                            Batal
                        </button>
                        <button type="submit" class="text-white btn bg-custom-500 border-custom-500 hover:bg-custom-600">
                            Simpan Konfigurasi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Config Modal -->
    <div id="editConfigModal" modal-center
        class="fixed flex flex-col hidden transition-all duration-300 ease-in-out left-2/4 z-drawer -translate-x-2/4 -translate-y-2/4 show">
        <div class="w-screen md:w-[40rem] bg-white shadow rounded-md dark:bg-zink-600">
            <div class="flex items-center justify-between p-4 border-b dark:border-zink-300/20">
                <h5 class="text-16">Edit Konfigurasi</h5>
                <button data-modal-close="editConfigModal"
                    class="transition-all duration-200 ease-linear text-slate-400 hover:text-red-500">
                    <i data-lucide="x" class="size-5"></i>
                </button>
            </div>
            <div class="max-h-[calc(theme('height.screen')_-_180px)] p-4 overflow-y-auto">
                <form id="editConfigForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="editAcademicYear" class="inline-block mb-2 text-base font-medium">
                            Tahun Akademik <span class="text-red-500">*</span>
                        </label>
                        <select id="editAcademicYear" name="academic_year"
                            class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500"
                            required>
                            <option value="">Pilih Tahun Akademik</option>
                            @foreach ($academicYears as $year)
                                <option value="{{ $year->academic_year }}">{{ $year->academic_year }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="inline-block mb-2 text-base font-medium">
                            Handling Points
                        </label>
                        <div id="editHandlingPointsContainer" class="space-y-2">
                            <!-- Will be populated by JavaScript -->
                        </div>
                        <button type="button" id="btnAddEditHandling"
                            class="mt-2 text-custom-500 btn bg-custom-100 hover:bg-custom-200">
                            <i data-lucide="plus" class="inline-block size-4"></i> Tambah Handling Point
                        </button>
                    </div>

                    <div class="flex justify-end gap-2 mt-4">
                        <button type="button" data-modal-close="editConfigModal"
                            class="text-red-500 bg-white border-white btn hover:text-red-600 dark:bg-zink-500 dark:border-zink-500">
                            Batal
                        </button>
                        <button type="submit" class="text-white btn bg-custom-500 border-custom-500 hover:bg-custom-600">
                            Update Konfigurasi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Handlings Modal -->
    <div id="viewHandlingsModal" modal-center
        class="fixed flex flex-col hidden transition-all duration-300 ease-in-out left-2/4 z-drawer -translate-x-2/4 -translate-y-2/4 show">
        <div class="w-screen md:w-[40rem] bg-white shadow rounded-md dark:bg-zink-600">
            <div class="flex items-center justify-between p-4 border-b dark:border-zink-300/20">
                <h5 class="text-16">Handling Points - <span id="handlingAcademicYear"></span></h5>
                <button data-modal-close="viewHandlingsModal"
                    class="transition-all duration-200 ease-linear text-slate-400 hover:text-red-500">
                    <i data-lucide="x" class="size-5"></i>
                </button>
            </div>
            <div class="max-h-[calc(theme('height.screen')_-_180px)] p-4 overflow-y-auto">
                <div id="handlingsList" class="space-y-3">
                    <!-- Will be populated by JavaScript -->
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteConfigModal" modal-center
        class="fixed flex flex-col hidden transition-all duration-300 ease-in-out left-2/4 z-drawer -translate-x-2/4 -translate-y-2/4 show">
        <div class="w-screen md:w-[30rem] bg-white shadow rounded-md dark:bg-zink-600">
            <div class="flex items-center justify-between p-4 border-b dark:border-zink-300/20">
                <h5 class="text-16">Konfirmasi Hapus</h5>
                <button data-modal-close="deleteConfigModal"
                    class="transition-all duration-200 ease-linear text-slate-400 hover:text-red-500">
                    <i data-lucide="x" class="size-5"></i>
                </button>
            </div>
            <div class="p-4">
                <div class="text-center">
                    <i data-lucide="alert-triangle" class="w-12 h-12 mx-auto mb-4 text-red-500"></i>
                    <h5 class="mb-2">Apakah Anda yakin?</h5>
                    <p class="text-slate-500 dark:text-zink-200 mb-4">
                        Anda akan menghapus konfigurasi <strong id="deleteConfigName"></strong>.
                        Tindakan ini tidak dapat dibatalkan.
                    </p>
                </div>
                <form id="deleteConfigForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="flex justify-center gap-2">
                        <button type="button" data-modal-close="deleteConfigModal"
                            class="text-slate-500 bg-white border-slate-300 btn hover:text-slate-600 dark:bg-zink-500 dark:border-zink-500">
                            Batal
                        </button>
                        <button type="submit" class="text-white btn bg-red-500 border-red-500 hover:bg-red-600">
                            Ya, Hapus
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Activate Confirmation Modal -->
    <div id="activateConfigModal" modal-center
        class="fixed flex flex-col hidden transition-all duration-300 ease-in-out left-2/4 z-drawer -translate-x-2/4 -translate-y-2/4 show">
        <div class="w-screen md:w-[30rem] bg-white shadow rounded-md dark:bg-zink-600">
            <div class="flex items-center justify-between p-4 border-b dark:border-zink-300/20">
                <h5 class="text-16">Konfirmasi Aktivasi</h5>
                <button data-modal-close="activateConfigModal"
                    class="transition-all duration-200 ease-linear text-slate-400 hover:text-red-500">
                    <i data-lucide="x" class="size-5"></i>
                </button>
            </div>
            <div class="p-4">
                <div class="text-center">
                    <i data-lucide="check-circle" class="w-12 h-12 mx-auto mb-4 text-green-500"></i>
                    <h5 class="mb-2">Aktifkan Konfigurasi?</h5>
                    <p class="text-slate-500 dark:text-zink-200 mb-4">
                        Konfigurasi lain akan dinonaktifkan secara otomatis.
                    </p>
                </div>
                <form id="activateConfigForm" method="POST">
                    @csrf
                    <div class="flex justify-center gap-2">
                        <button type="button" data-modal-close="activateConfigModal"
                            class="text-slate-500 bg-white border-slate-300 btn hover:text-slate-600 dark:bg-zink-500 dark:border-zink-500">
                            Batal
                        </button>
                        <button type="submit" class="text-white btn bg-green-500 border-green-500 hover:bg-green-600">
                            Ya, Aktifkan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Deactivate Confirmation Modal -->
    <div id="deactivateConfigModal" modal-center
        class="fixed flex flex-col hidden transition-all duration-300 ease-in-out left-2/4 z-drawer -translate-x-2/4 -translate-y-2/4 show">
        <div class="w-screen md:w-[30rem] bg-white shadow rounded-md dark:bg-zink-600">
            <div class="flex items-center justify-between p-4 border-b dark:border-zink-300/20">
                <h5 class="text-16">Konfirmasi Nonaktifkan</h5>
                <button data-modal-close="deactivateConfigModal"
                    class="transition-all duration-200 ease-linear text-slate-400 hover:text-red-500">
                    <i data-lucide="x" class="size-5"></i>
                </button>
            </div>
            <div class="p-4">
                <div class="text-center">
                    <i data-lucide="power" class="w-12 h-12 mx-auto mb-4 text-orange-500"></i>
                    <h5 class="mb-2">Nonaktifkan Konfigurasi?</h5>
                    <p class="text-slate-500 dark:text-zink-200 mb-4">
                        Konfigurasi ini akan dinonaktifkan.
                    </p>
                </div>
                <form id="deactivateConfigForm" method="POST">
                    @csrf
                    <div class="flex justify-center gap-2">
                        <button type="button" data-modal-close="deactivateConfigModal"
                            class="text-slate-500 bg-white border-slate-300 btn hover:text-slate-600 dark:bg-zink-500 dark:border-zink-500">
                            Batal
                        </button>
                        <button type="submit" class="text-white btn bg-orange-500 border-orange-500 hover:bg-orange-600">
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
