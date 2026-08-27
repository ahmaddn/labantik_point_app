@extends('layouts.app')
@section('content')
    <div
        class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu group-data-[sidebar-size=md]:ltr:ml-vertical-menu-md group-data-[sidebar-size=md]:rtl:mr-vertical-menu-md group-data-[sidebar-size=sm]:ltr:ml-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:mr-vertical-menu-sm px-4 pb-[calc(theme('spacing.header')_*_0.8)] pt-[calc(theme('spacing.header')_*_1)] group-data-[layout=horizontal]:mx-auto group-data-[layout=horizontal]:max-w-screen-2xl group-data-[layout=horizontal]:px-0 group-data-[layout=horizontal]:px-3 group-data-[layout=horizontal]:group-data-[navbar=hidden]:pt-[calc(theme('spacing.header')_*_0.9)] group-data-[navbar=bordered]:pt-[calc(theme('spacing.header')_*_1.3)] group-data-[navbar=hidden]:pt-0 group-data-[layout=horizontal]:md:pt-[calc(theme('spacing.header')_*_1.6)] group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:ltr:md:ml-auto group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:rtl:md:mr-auto">
        <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">

            <div class="flex flex-col gap-2 py-4 md:flex-row md:items-center print:hidden">
                <div class="grow">
                    <h5 class="text-16 font-semibold">Input Laporan Pelanggaran Massal (Guru)</h5>
                </div>
                <ul class="flex shrink-0 items-center gap-2 text-sm font-normal">
                    <li class="before:font-remix dark:text-zink-200 relative before:absolute before:-top-[3px] before:text-[18px] before:text-slate-400 before:content-['\ea54'] ltr:pr-4 ltr:before:-right-1 rtl:pl-4 rtl:before:-left-1">
                        <a href="#!" class="dark:text-zink-200 text-slate-400">Pelanggaran</a>
                    </li>
                    <li class="dark:text-zink-100 text-slate-700">
                        Input Massal
                    </li>
                </ul>
            </div>

            {{-- Alert untuk error --}}
            @if ($errors->any())
                <div class="relative mb-4 flex gap-3 rounded-md border border-red-200 bg-red-50 p-4 pr-12 text-sm text-red-700 dark:border-red-900/30 dark:bg-red-500/10 dark:text-red-400 shadow-sm transition-all duration-300">
                    <div class="shrink-0 flex items-center justify-center size-8 rounded-full bg-red-100 dark:bg-red-950/50 text-red-600 dark:text-red-400">
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

            <div class="card border-none shadow-md rounded-xl bg-white dark:bg-zink-700">
                <div class="card-body p-6">
                    <form action="{{ route('guru.violations.mass.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <!-- Opsi Tanggal Pelanggaran -->
                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-slate-700 dark:text-zink-100 mb-2">Tanggal Kejadian Pelanggaran</label>
                            <div class="flex gap-2 mb-3">
                                <button type="button" id="btn-today" class="btn bg-blue-600 text-white text-sm font-medium px-4 py-2 rounded-lg transition-all duration-150">Hari Ini</button>
                                <button type="button" id="btn-custom" class="btn border border-slate-200 dark:border-zink-600 text-slate-700 dark:text-zink-100 text-sm font-medium px-4 py-2 rounded-lg transition-all duration-150">Pilih Tanggal Lain</button>
                            </div>
                            <div id="custom-date-container" class="hidden">
                                <input type="date" name="violation_date" id="violation_date" value="{{ date('Y-m-d') }}" class="dark:bg-zink-600 dark:border-zink-500 dark:text-zink-100 w-full md:w-64 rounded-md border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                            </div>
                            <input type="hidden" name="date_mode" id="date_mode" value="today">
                        </div>

                        <!-- 1. Pilih Pelanggaran -->
                        <div>
                            <label for="violation_id" class="block text-sm font-semibold text-slate-700 dark:text-zink-100 mb-2">1. Pilih Jenis Pelanggaran</label>
                            <select name="violation_id" id="violation_id" required class="dark:bg-zink-600 dark:border-zink-500 dark:text-zink-100 w-full rounded-md border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
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
                            <label class="block text-sm font-semibold text-slate-700 dark:text-zink-100 mb-2">2. Pilih Siswa Pelanggar</label>
                            
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 mb-4">
                                <!-- Kolom Pencarian -->
                                <div class="relative w-full md:w-72">
                                    <input type="text" id="search-student" placeholder="Cari Nama / Kelas / NISN..." class="dark:bg-zink-600 dark:border-zink-500 dark:text-zink-100 w-full rounded-md border border-slate-200 pl-9 pr-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                                    <div class="absolute left-3 top-2.5 text-slate-400">
                                        <i data-lucide="search" class="size-4"></i>
                                    </div>
                                </div>

                                <!-- Checkbox Pilih Semua -->
                                <div class="flex items-center gap-2">
                                    <input type="checkbox" id="select-all" class="size-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500">
                                    <label for="select-all" class="text-sm font-medium text-slate-600 dark:text-zink-200 cursor-pointer">Pilih Semua yang Tampil</label>
                                </div>
                            </div>

                            <!-- Scrollable Checkbox List -->
                            <div class="border border-slate-200 dark:border-zink-600 rounded-lg p-4 max-h-[350px] overflow-y-auto bg-slate-50/50 dark:bg-zink-800/20">
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3" id="students-container">
                                    @forelse($studentAcademicYears as $say)
                                        <div class="student-row flex items-start p-3 bg-white dark:bg-zink-700 rounded-lg border border-slate-100 dark:border-zink-600 hover:shadow-sm transition-all duration-150"
                                             data-name="{{ $say->student->full_name ?? '' }}"
                                             data-class="{{ $say->class ? $say->class->academic_level . ' ' . $say->class->name : '' }}"
                                             data-nisn="{{ $say->student->national_student_number ?? '' }}">
                                            <div class="flex items-center h-5">
                                                <input type="checkbox" name="student_ids[]" value="{{ $say->id }}" id="student-{{ $say->id }}" class="student-checkbox size-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500">
                                            </div>
                                            <div class="ml-3 text-sm">
                                                <label for="student-{{ $say->id }}" class="font-semibold text-slate-800 dark:text-zink-50 cursor-pointer block leading-tight">
                                                    {{ $say->student->full_name ?? 'Tanpa Nama' }}
                                                </label>
                                                <span class="text-xs text-slate-500 dark:text-zink-300 block mt-1">
                                                    Kelas: {{ $say->class ? $say->class->academic_level . ' ' . $say->class->name : '-' }}
                                                </span>
                                                <span class="text-[11px] text-slate-400 block">
                                                    NISN: {{ $say->student->national_student_number ?? '-' }}
                                                </span>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-span-full text-center text-slate-500 py-6">Tidak ada data siswa aktif.</div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <!-- 3. Submit -->
                        <div class="pt-4 border-t border-slate-100 dark:border-zink-600 flex justify-end gap-3">
                            <a href="{{ route('guru.dashboard') }}" class="btn border border-slate-200 hover:bg-slate-50 dark:border-zink-600 dark:hover:bg-zink-600 text-slate-700 dark:text-zink-100 text-sm font-medium px-5 py-2.5 rounded-lg">
                                Batal
                            </a>
                            <button type="submit" class="btn bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg">
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

            btnToday.addEventListener('click', function () {
                btnToday.classList.add('bg-blue-600', 'text-white');
                btnToday.classList.remove('border', 'border-slate-200', 'text-slate-700', 'dark:border-zink-600', 'dark:text-zink-100');
                
                btnCustom.classList.remove('bg-blue-600', 'text-white');
                btnCustom.classList.add('border', 'border-slate-200', 'text-slate-700', 'dark:border-zink-600', 'dark:text-zink-100');
                
                customDateContainer.classList.add('hidden');
                dateModeInput.value = 'today';
            });

            btnCustom.addEventListener('click', function () {
                btnCustom.classList.add('bg-blue-600', 'text-white');
                btnCustom.classList.remove('border', 'border-slate-200', 'text-slate-700', 'dark:border-zink-600', 'dark:text-zink-100');
                
                btnToday.classList.remove('bg-blue-600', 'text-white');
                btnToday.classList.add('border', 'border-slate-200', 'text-slate-700', 'dark:border-zink-600', 'dark:text-zink-100');
                
                customDateContainer.classList.remove('hidden');
                dateModeInput.value = 'custom';
            });

            // 1. Live Filter Pencarian
            searchInput.addEventListener('input', function (e) {
                const query = e.target.value.toLowerCase();
                studentRows.forEach(row => {
                    const name = row.getAttribute('data-name').toLowerCase();
                    const nisn = row.getAttribute('data-nisn').toLowerCase();
                    const classVal = row.getAttribute('data-class').toLowerCase();

                    if (name.includes(query) || nisn.includes(query) || classVal.includes(query)) {
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
