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

                            <!-- Search + Select All -->
                            <div class="flex flex-col sm:flex-row sm:items-center gap-2 mb-3">
                                <div class="relative flex-1">
                                    <input type="text" id="search-student" placeholder="Cari Nama / Kelas / NIS..." class="dark:bg-zink-600 dark:border-zink-500 dark:text-zink-100 w-full rounded-md border border-slate-200 pl-9 pr-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>
                                        </svg>
                                    </span>
                                </div>
                                <div class="flex items-center gap-2 shrink-0">
                                    <input type="checkbox" id="select-all" class="size-4 cursor-pointer text-blue-600 border-slate-300 rounded focus:ring-blue-500">
                                    <label for="select-all" class="text-sm font-medium text-slate-600 dark:text-zink-200 cursor-pointer whitespace-nowrap">Pilih Semua yang Tampil</label>
                                </div>
                            </div>

                            <!-- Student Grid dengan Pagination -->
                            <div class="border border-slate-200 dark:border-zink-600 rounded-lg p-4 bg-slate-50/50 dark:bg-zink-800/20">
                                <!-- Hidden data store -->
                                <div id="students-data" class="hidden">
                                    @forelse($studentAcademicYears as $say)
                                        <div class="student-row"
                                             data-name="{{ strtolower($say->student->full_name ?? '') }}"
                                             data-class="{{ strtolower($say->class ? $say->class->academic_level . ' ' . $say->class->name : '') }}"
                                             data-nis="{{ strtolower($say->student->student_number ?? '') }}"
                                             data-id="{{ $say->id }}"
                                             data-fullname="{{ $say->student->full_name ?? 'Tanpa Nama' }}"
                                             data-classname="{{ $say->class ? $say->class->academic_level . ' ' . $say->class->name : '-' }}"
                                             data-nisval="{{ $say->student->student_number ?? '-' }}">
                                        </div>
                                    @empty
                                    @endforelse
                                </div>

                                <!-- Grid tampilan -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 min-h-[200px]" id="students-container"></div>

                                <!-- Empty state -->
                                <div id="empty-state" class="hidden text-center text-slate-500 dark:text-zink-400 py-8">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-8 mx-auto mb-2 opacity-40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                                    </svg>
                                    <p class="text-sm">Tidak ada siswa ditemukan.</p>
                                </div>

                                <!-- Pagination -->
                                <div id="pagination-container" class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mt-4 pt-3 border-t border-slate-200 dark:border-zink-600">
                                    <p id="pagination-info" class="text-xs text-slate-500 dark:text-zink-400"></p>
                                    <div id="pagination-buttons" class="flex items-center gap-1 flex-wrap"></div>
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
            // ─── Choices.js untuk dropdown pelanggaran ────────────────
            new Choices('#violation_id', {
                searchEnabled: true,
                searchPlaceholderValue: 'Ketik untuk mencari...',
                itemSelectText: '',
                noResultsText: 'Tidak ada hasil',
                noChoicesText: 'Tidak ada pilihan',
                placeholder: true,
                placeholderValue: '-- Pilih Pelanggaran --',
            });

            // ─── State ───────────────────────────────────────────────
            const PER_PAGE   = 6;
            let currentPage  = 1;
            let filteredRows = [];
            const checkedIds = new Set();

            // ─── Elemen ──────────────────────────────────────────────
            const searchInput       = document.getElementById('search-student');
            const selectAllCb       = document.getElementById('select-all');
            const container         = document.getElementById('students-container');
            const emptyState        = document.getElementById('empty-state');
            const paginationInfo    = document.getElementById('pagination-info');
            const paginationButtons = document.getElementById('pagination-buttons');
            const allRows           = Array.from(document.querySelectorAll('#students-data .student-row'));

            // ─── Date Picker ─────────────────────────────────────────
            const btnToday            = document.getElementById('btn-today');
            const btnCustom           = document.getElementById('btn-custom');
            const customDateContainer = document.getElementById('custom-date-container');
            const dateModeInput       = document.getElementById('date_mode');

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

            // ─── Render halaman ──────────────────────────────────────
            function renderPage(page) {
                currentPage = page;
                const start = (page - 1) * PER_PAGE;
                const end   = start + PER_PAGE;
                const slice = filteredRows.slice(start, end);

                container.innerHTML = '';

                if (filteredRows.length === 0) {
                    emptyState.classList.remove('hidden');
                    paginationInfo.textContent = '';
                    paginationButtons.innerHTML = '';
                    return;
                }
                emptyState.classList.add('hidden');

                slice.forEach(function (row) {
                    const id        = row.dataset.id;
                    const fullname  = row.dataset.fullname;
                    const className = row.dataset.classname;
                    const nis       = row.dataset.nisval;
                    const checked   = checkedIds.has(id) ? 'checked' : '';

                    const card = document.createElement('div');
                    card.className = 'flex items-start gap-3 p-3 bg-white dark:bg-zink-700 rounded-lg border border-slate-100 dark:border-zink-600 hover:border-blue-300 hover:shadow-sm transition-all duration-150 cursor-pointer';
                    card.innerHTML = `
                        <div class="pt-0.5 shrink-0">
                            <input type="checkbox" name="student_ids[]" value="${id}" id="student-${id}"
                                class="student-checkbox size-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500 cursor-pointer"
                                ${checked}>
                        </div>
                        <label for="student-${id}" class="flex flex-col gap-0.5 cursor-pointer min-w-0">
                            <span class="font-semibold text-sm text-slate-800 dark:text-zink-50 leading-snug truncate">${fullname}</span>
                            <span class="text-xs text-slate-500 dark:text-zink-300">Kelas: ${className}</span>
                            <span class="text-[11px] text-slate-400 dark:text-zink-400">NIS: ${nis}</span>
                        </label>`;
                    container.appendChild(card);

                    card.querySelector('.student-checkbox').addEventListener('change', function () {
                        if (this.checked) checkedIds.add(id);
                        else checkedIds.delete(id);
                        syncSelectAll();
                    });
                });

                renderPagination();
                syncSelectAll();
            }

            // ─── Render tombol pagination ─────────────────────────────
            function renderPagination() {
                const total      = filteredRows.length;
                const totalPages = Math.ceil(total / PER_PAGE);
                const start      = Math.min((currentPage - 1) * PER_PAGE + 1, total);
                const end        = Math.min(currentPage * PER_PAGE, total);

                paginationInfo.textContent = total > 0
                    ? `Menampilkan ${start}–${end} dari ${total} siswa`
                    : '';

                paginationButtons.innerHTML = '';
                if (totalPages <= 1) return;

                paginationButtons.appendChild(makeBtn('&laquo;', currentPage === 1, () => renderPage(currentPage - 1)));

                for (let i = 1; i <= totalPages; i++) {
                    const btn = makeBtn(i, false, () => renderPage(i));
                    if (i === currentPage) {
                        btn.classList.add('bg-blue-600', 'text-white', 'border-blue-600');
                        btn.classList.remove('text-slate-600', 'hover:bg-slate-100');
                    }
                    paginationButtons.appendChild(btn);
                }

                paginationButtons.appendChild(makeBtn('&raquo;', currentPage === totalPages, () => renderPage(currentPage + 1)));
            }

            function makeBtn(label, disabled, onClick) {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.innerHTML = label;
                btn.className = 'min-w-[32px] h-8 px-2 rounded border border-slate-200 dark:border-zink-600 text-sm text-slate-600 dark:text-zink-200 hover:bg-slate-100 dark:hover:bg-zink-600 transition-colors duration-150';
                if (disabled) {
                    btn.disabled = true;
                    btn.classList.add('opacity-40', 'cursor-not-allowed');
                } else {
                    btn.addEventListener('click', onClick);
                }
                return btn;
            }

            function syncSelectAll() {
                const cbs = container.querySelectorAll('.student-checkbox');
                selectAllCb.checked = cbs.length > 0 && Array.from(cbs).every(cb => cb.checked);
            }

            function applyFilter() {
                const query = searchInput.value.toLowerCase().trim();
                filteredRows = allRows.filter(function (row) {
                    return row.dataset.name.includes(query)
                        || row.dataset.nis.includes(query)
                        || row.dataset.class.includes(query);
                });
                renderPage(1);
            }

            searchInput.addEventListener('input', applyFilter);

            selectAllCb.addEventListener('change', function () {
                const isChecked = this.checked;
                container.querySelectorAll('.student-checkbox').forEach(function (cb) {
                    cb.checked = isChecked;
                    if (isChecked) checkedIds.add(cb.value);
                    else checkedIds.delete(cb.value);
                });
            });

            // Init
            filteredRows = allRows;
            renderPage(1);
        });
    </script>
@endsection
