@extends('layouts.app')
@section('content')
    <div
        class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu group-data-[sidebar-size=md]:ltr:ml-vertical-menu-md group-data-[sidebar-size=md]:rtl:mr-vertical-menu-md group-data-[sidebar-size=sm]:ltr:ml-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:mr-vertical-menu-sm px-4 pb-[calc(theme('spacing.header')_*_0.8)] pt-[calc(theme('spacing.header')_*_1)] group-data-[layout=horizontal]:mx-auto group-data-[layout=horizontal]:max-w-screen-2xl group-data-[layout=horizontal]:px-0 group-data-[layout=horizontal]:px-3 group-data-[layout=horizontal]:group-data-[navbar=hidden]:pt-[calc(theme('spacing.header')_*_0.9)] group-data-[navbar=bordered]:pt-[calc(theme('spacing.header')_*_1.3)] group-data-[navbar=hidden]:pt-0 group-data-[layout=horizontal]:md:pt-[calc(theme('spacing.header')_*_1.6)] group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:ltr:md:ml-auto group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:rtl:md:mr-auto">
        <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">

            <!-- Breadcrumb -->
            <div class="flex flex-col gap-2 py-4 md:flex-row md:items-center print:hidden">
                <div class="grow">
                    <h5 class="text-16">Input Laporan Pelanggaran Massal</h5>
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
                    <li
                        class="before:font-remix dark:text-zink-200 relative before:absolute before:-top-[3px] before:text-[18px] before:text-slate-400 before:content-['\ea54'] ltr:pr-4 ltr:before:-right-1 rtl:pl-4 rtl:before:-left-1">
                        <a href="{{ route('superadmin.violations') }}" class="dark:text-zink-200 text-slate-400">Pelanggaran</a>
                    </li>
                    <li class="dark:text-zink-100 text-slate-700">
                        Input Massal
                    </li>
                </ul>
            </div>

            {{-- Alert untuk error --}}
            @if ($errors->any())
                <div class="relative mb-4 flex gap-3 rounded-md border border-red-200 bg-red-50 p-4 pr-12 text-sm text-red-700 dark:border-red-900/30 dark:bg-red-500/10 dark:text-red-400 shadow-sm transition-all duration-300">
                    <div class="shrink-0 flex items-center justify-center size-8 rounded-full bg-red-100 dark:bg-red-900/50 text-red-600 dark:text-red-400">
                        <i data-lucide="alert-triangle" class="size-5"></i>
                    </div>
                    <div class="grow">
                        <h6 class="font-semibold text-15 mb-0.5">Peringatan!</h6>
                        <ul class="list-disc pl-4 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <button class="absolute top-4 right-4 text-red-400 hover:text-red-600 dark:hover:text-red-300 transition-colors duration-150"
                        onclick="this.parentElement.style.display='none'">
                        <i data-lucide="x" class="size-4"></i>
                    </button>
                </div>
            @endif

            {{-- Alert untuk success --}}
            @if (session('success'))
                <div class="relative mb-4 flex gap-3 rounded-md border border-green-200 bg-green-50 p-4 pr-12 text-sm text-green-700 dark:border-green-900/30 dark:bg-green-500/10 dark:text-green-400 shadow-sm transition-all duration-300">
                    <div class="shrink-0 flex items-center justify-center size-8 rounded-full bg-green-100 dark:bg-green-900/50 text-green-600 dark:text-green-400">
                        <i data-lucide="check-circle" class="size-5"></i>
                    </div>
                    <div class="grow">
                        <h6 class="font-semibold text-15 mb-0.5">Berhasil!</h6>
                        <p class="text-green-600 dark:text-green-400/90 leading-relaxed">{{ session('success') }}</p>
                    </div>
                    <button class="absolute top-4 right-4 text-green-400 hover:text-green-600 dark:hover:text-green-300 transition-colors duration-150"
                        onclick="this.parentElement.style.display='none'">
                        <i data-lucide="x" class="size-4"></i>
                    </button>
                </div>
            @endif

            {{-- Alert untuk warning (jika ada siswa dilewati) --}}
            @if (session('warning'))
                <div class="relative mb-4 flex gap-3 rounded-md border border-amber-200 bg-amber-50 p-4 pr-12 text-sm text-amber-700 dark:border-amber-900/30 dark:bg-amber-500/10 dark:text-amber-400 shadow-sm transition-all duration-300">
                    <div class="shrink-0 flex items-center justify-center size-8 rounded-full bg-amber-100 dark:bg-amber-900/50 text-amber-600 dark:text-amber-400">
                        <i data-lucide="alert-circle" class="size-5"></i>
                    </div>
                    <div class="grow">
                        <h6 class="font-semibold text-15 mb-0.5">Perhatian!</h6>
                        <p class="text-amber-600 dark:text-amber-400/90 leading-relaxed">{{ session('warning') }}</p>
                    </div>
                    <button class="absolute top-4 right-4 text-amber-400 hover:text-amber-600 dark:hover:text-amber-300 transition-colors duration-150"
                        onclick="this.parentElement.style.display='none'">
                        <i data-lucide="x" class="size-4"></i>
                    </button>
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <h6 class="text-15 mb-4">Form Input Pelanggaran Massal</h6>
                    <form action="{{ route('superadmin.violations.mass.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <!-- Opsi Tanggal Pelanggaran -->
                        <div>
                            <label class="dark:text-zink-300 mb-2 block text-sm font-medium text-slate-700">Tanggal Kejadian Pelanggaran</label>
                            <div class="flex flex-wrap gap-2 mb-3">
                                <button type="button" id="btn-today" class="btn bg-custom-500 border-custom-500 text-white hover:bg-custom-600 hover:border-custom-600 text-sm font-medium px-4 py-2">Hari Ini</button>
                                <button type="button" id="btn-custom" class="btn bg-white border-slate-200 text-slate-700 dark:bg-zink-600 dark:border-zink-500 dark:text-zink-100 hover:bg-slate-50 dark:hover:bg-zink-500 text-sm font-medium px-4 py-2">Pilih Tanggal Lain</button>
                            </div>
                            <div id="custom-date-container" class="hidden">
                                <input type="date" name="violation_date" id="violation_date" value="{{ date('Y-m-d') }}" class="form-input dark:border-zink-500 dark:bg-zink-700 dark:text-zink-100 focus:border-custom-500 dark:focus:border-custom-800 w-full md:w-64 border-slate-200">
                            </div>
                            <input type="hidden" name="date_mode" id="date_mode" value="today">
                        </div>

                        <!-- 1. Pilih Pelanggaran -->
                        <div>
                            <label for="violation_id" class="dark:text-zink-300 mb-2 block text-sm font-medium text-slate-700">1. Pilih Jenis Pelanggaran</label>
                            <select name="violation_id" id="violation_id" required class="form-input dark:border-zink-500 dark:bg-zink-700 dark:text-zink-100 focus:border-custom-500 dark:focus:border-custom-800 w-full border-slate-200">
                                <option value="" disabled selected>-- Pilih Pelanggaran --</option>
                                @foreach($violations as $violation)
                                    <option value="{{ $violation->id }}">
                                        [{{ $violation->category->name ?? '-' }}] {{ $violation->name }} ({{ $violation->point }} Poin)
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- 2. Pilih Siswa -->
                        <div>
                            <label class="dark:text-zink-300 mb-2 block text-sm font-medium text-slate-700">2. Pilih Siswa Pelanggar</label>

                            <!-- Search + Select All dalam satu baris -->
                            <div class="flex items-center gap-3 mb-3">
                                <div class="relative flex-1">
                                    <input type="text" id="search-student" placeholder="Cari Nama / Kelas / NIS..." class="form-input dark:border-zink-500 dark:bg-zink-700 dark:text-zink-100 focus:border-custom-500 dark:focus:border-custom-800 w-full border-slate-200 pl-9">
                                    <div class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                                        <i data-lucide="search" class="size-4"></i>
                                    </div>
                                </div>
                                <div class="flex shrink-0 items-center gap-2">
                                    <input type="checkbox" id="select-all" class="size-4 cursor-pointer rounded border-slate-300 text-custom-500 focus:ring-custom-500">
                                    <label for="select-all" class="cursor-pointer text-sm font-medium text-slate-600 dark:text-zink-200 whitespace-nowrap">Pilih Semua yang Tampil</label>
                                </div>
                            </div>

                            <!-- Scrollable Checkbox List -->
                            <div class="max-h-[380px] overflow-y-auto rounded-md border border-slate-200 bg-slate-50/50 p-4 dark:border-zink-500 dark:bg-zink-800/20">
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3" id="students-container">
                                    @forelse($studentAcademicYears as $say)
                                        <div class="student-row flex items-start gap-3 p-3 bg-white dark:bg-zink-700 rounded-lg border border-slate-100 dark:border-zink-600 hover:border-custom-300 hover:shadow-sm transition-all duration-150 cursor-pointer"
                                             data-name="{{ strtolower($say->student->full_name ?? '') }}"
                                             data-class="{{ strtolower($say->class ? $say->class->academic_level . ' ' . $say->class->name : '') }}"
                                             data-nis="{{ strtolower($say->student->student_number ?? '') }}">
                                            <div class="pt-0.5 shrink-0">
                                                <input type="checkbox" name="student_ids[]" value="{{ $say->id }}" id="student-{{ $say->id }}" class="student-checkbox size-4 cursor-pointer rounded border-slate-300 text-custom-500 focus:ring-custom-500">
                                            </div>
                                            <label for="student-{{ $say->id }}" class="flex flex-col gap-0.5 cursor-pointer min-w-0">
                                                <span class="font-semibold text-sm text-slate-800 dark:text-zink-50 leading-snug truncate">
                                                    {{ $say->student->full_name ?? 'Tanpa Nama' }}
                                                </span>
                                                <span class="text-xs text-slate-500 dark:text-zink-300">
                                                    Kelas: {{ $say->class ? $say->class->academic_level . ' ' . $say->class->name : '-' }}
                                                </span>
                                                <span class="text-[11px] text-slate-400 dark:text-zink-400">
                                                    NIS: {{ $say->student->student_number ?? '-' }}
                                                </span>
                                            </label>
                                        </div>
                                    @empty
                                        <div class="col-span-full text-center text-slate-500 dark:text-zink-400 py-8">
                                            <i data-lucide="users" class="size-8 mx-auto mb-2 opacity-40"></i>
                                            <p>Tidak ada data siswa aktif.</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <!-- 3. Submit -->
                        <div class="flex justify-end gap-2 border-t border-slate-200 pt-4 dark:border-zink-500">
                            <a href="{{ route('superadmin.violations') }}" class="btn bg-white text-red-500 border-slate-200 hover:bg-red-50 hover:text-red-500 dark:bg-zink-600 dark:border-zink-500 dark:text-zink-200 dark:hover:bg-zink-500">
                                Batal
                            </a>
                            <button type="submit" class="btn bg-custom-500 border-custom-500 text-white hover:bg-custom-600 hover:border-custom-600 focus:bg-custom-600 focus:border-custom-600">
                                Simpan Laporan Pelanggaran Massal
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const searchInput = document.getElementById('search-student');
            const selectAllCheckbox = document.getElementById('select-all');
            const studentRows = document.querySelectorAll('.student-row');

            // Date Picker Toggling
            const btnToday = document.getElementById('btn-today');
            const btnCustom = document.getElementById('btn-custom');
            const customDateContainer = document.getElementById('custom-date-container');
            const dateModeInput = document.getElementById('date_mode');

            const activeDateClasses = ['bg-custom-500', 'border-custom-500', 'text-white', 'hover:bg-custom-600', 'hover:border-custom-600'];
            const inactiveDateClasses = ['bg-white', 'border-slate-200', 'text-slate-700', 'dark:bg-zink-600', 'dark:border-zink-500', 'dark:text-zink-100', 'hover:bg-slate-50', 'dark:hover:bg-zink-500'];

            function setActiveDateButton(activeBtn, inactiveBtn) {
                activeBtn.classList.add(...activeDateClasses);
                activeBtn.classList.remove(...inactiveDateClasses);
                inactiveBtn.classList.remove(...activeDateClasses);
                inactiveBtn.classList.add(...inactiveDateClasses);
            }

            btnToday.addEventListener('click', function () {
                setActiveDateButton(btnToday, btnCustom);
                customDateContainer.classList.add('hidden');
                dateModeInput.value = 'today';
            });

            btnCustom.addEventListener('click', function () {
                setActiveDateButton(btnCustom, btnToday);
                customDateContainer.classList.remove('hidden');
                dateModeInput.value = 'custom';
            });

            // 1. Live Filter Pencarian
            searchInput.addEventListener('input', function (e) {
                const query = e.target.value.toLowerCase();
                studentRows.forEach(row => {
                    const name = row.getAttribute('data-name');
                    const nis = row.getAttribute('data-nis');
                    const classVal = row.getAttribute('data-class');

                    if (name.includes(query) || nis.includes(query) || classVal.includes(query)) {
                        row.style.setProperty('display', 'flex', 'important');
                    } else {
                        row.style.setProperty('display', 'none', 'important');
                    }
                });
            });

            // 2. Pilih Semua (hanya yang sedang tampil terfilter)
            selectAllCheckbox.addEventListener('change', function () {
                const isChecked = this.checked;
                studentRows.forEach(row => {
                    if (row.style.display !== 'none') {
                        const checkbox = row.querySelector('.student-checkbox');
                        if (checkbox) {
                            checkbox.checked = isChecked;
                        }
                    }
                });
            });
        });
    </script>
@endsection
