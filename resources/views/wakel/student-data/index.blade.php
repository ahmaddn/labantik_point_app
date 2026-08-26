@extends('layouts.app')
@section('content')
    <div
        class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu group-data-[sidebar-size=md]:ltr:ml-vertical-menu-md group-data-[sidebar-size=md]:rtl:mr-vertical-menu-md group-data-[sidebar-size=sm]:ltr:ml-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:mr-vertical-menu-sm pt-[calc(theme('spacing.header')_*_1)] pb-[calc(theme('spacing.header')_*_0.8)] px-4 group-data-[navbar=bordered]:pt-[calc(theme('spacing.header')_*_1.3)] group-data-[navbar=hidden]:pt-0 group-data-[layout=horizontal]:mx-auto group-data-[layout=horizontal]:max-w-screen-2xl group-data-[layout=horizontal]:px-0 group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:ltr:md:ml-auto group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:rtl:md:mr-auto group-data-[layout=horizontal]:md:pt-[calc(theme('spacing.header')_*_1.6)] group-data-[layout=horizontal]:px-3 group-data-[layout=horizontal]:group-data-[navbar=hidden]:pt-[calc(theme('spacing.header')_*_0.9)]">
        <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">

            <div class="flex flex-col gap-2 py-4 md:flex-row md:items-center print:hidden">
                <div class="grow">
                    <h5 class="text-16 font-semibold">Data Siswa Kelas {{ $class->academic_level }} {{ $class->name }}</h5>
                </div>
                <ul class="flex items-center gap-2 text-sm font-normal shrink-0">
                    <li class="dark:text-zink-200 text-slate-400">Portal Wali Kelas</li>
                    <li class="text-slate-400">/</li>
                    <li class="dark:text-zink-100 text-slate-700 font-medium">Data Siswa</li>
                </ul>
            </div>

            {{-- Alert untuk error --}}
            @if ($errors->has('error'))
                <div class="relative mb-4 flex gap-3 rounded-md border border-red-200 bg-red-50 p-4 pr-12 text-sm text-red-700 dark:border-red-900/30 dark:bg-red-500/10 dark:text-red-400 shadow-sm transition-all duration-300">
                    <div class="shrink-0 flex items-center justify-center size-8 rounded-full bg-red-100 dark:bg-red-900/50 text-red-600 dark:text-red-400">
                        <i data-lucide="alert-triangle" class="size-5"></i>
                    </div>
                    <div class="grow">
                        <h6 class="font-semibold text-15 mb-0.5">Peringatan!</h6>
                        <p class="text-red-600 dark:text-red-400/90 leading-relaxed">{{ $errors->first('error') }}</p>
                    </div>
                    <button class="absolute top-4 right-4 text-red-400 hover:text-red-600 dark:hover:text-red-300 transition-colors duration-150"
                        onclick="this.parentElement.style.display='none'">
                        <i data-lucide="x" class="size-4"></i>
                    </button>
                </div>
            @endif

            {{-- Alert untuk success --}}
            @if (session('success') && !$errors->has('error'))
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

            <div class="card">
                <div class="card-body">
                    <h6 class="mb-4 text-15 font-semibold text-slate-800 dark:text-zink-50">Daftar Siswa Bimbingan</h6>

                    @if ($studentAcademicYears->count() > 0)
                        <table id="hoverableTable" style="width: 100%" class="hover group">
                            <thead>
                                <tr>
                                    <th>Aksi</th>
                                    <th>No</th>
                                    <th>Nama Lengkap</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Poin Akumulasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($studentAcademicYears as $murid)
                                    @php
                                        $verifiedPoints = $murid->recaps->where('status', 'verified')->sum(fn($r) => $r->violation->point ?? 0);
                                        $pendingPoints = $murid->recaps->where('status', 'pending')->sum(fn($r) => $r->violation->point ?? 0);
                                    @endphp
                                    <tr>
                                        <td>
                                            <!-- Tombol buka modal -->
                                            <button data-modal-target="modal-{{ $murid->id }}" type="button"
                                                class="flex rounded-full items-center justify-center size-[37.5px] p-0 text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20">
                                                <i data-lucide="plus" class="w-4 h-4"></i>
                                            </button>
                                        </td>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $murid->student->full_name }}</td>
                                        <td>{{ $murid->student->gender }}</td>
                                        <td>
                                            <span class="font-bold text-slate-800 dark:text-zink-50">{{ $verifiedPoints }}</span>
                                            @if ($pendingPoints > 0)
                                                <span class="text-11 text-amber-500 ml-1">({{ $pendingPoints }} pending)</span>
                                            @endif
                                        </td>
                                    </tr>

                                    <!-- Modal untuk siswa ini -->
                                    <div id="modal-{{ $murid->id }}" modal-center=""
                                        class="fixed flex flex-col hidden transition-all duration-300 ease-in-out left-2/4 z-drawer -translate-x-2/4 -translate-y-2/4 show">
                                        <div
                                            class="w-11/12 md:w-full md:max-w-lg lg:max-w-xl bg-white shadow rounded-md dark:bg-zink-600 flex flex-col max-h-[90vh]">
                                            <div
                                                class="flex items-center justify-between p-4 border-b border-slate-200 dark:border-zink-500 bg-slate-50 dark:bg-zink-700 rounded-t-md">
                                                <h5 class="text-16 font-bold text-slate-700 dark:text-zink-100">
                                                    Laporkan Pelanggaran
                                                    <span class="block text-sm font-normal text-slate-500 mt-1">{{ $murid->student->full_name }}</span>
                                                </h5>
                                                <button data-modal-close="modal-{{ $murid->id }}"
                                                    class="transition-all duration-200 ease-linear text-slate-400 hover:text-red-500 dark:text-zink-200 dark:hover:text-red-500">
                                                    <i data-lucide="x" class="size-5"></i>
                                                </button>
                                            </div>

                                            <form method="POST"
                                                action="{{ route('wakel.violations.store', $murid->id) }}" class="flex flex-col overflow-hidden h-full">
                                                @csrf

                                                <div class="p-4 border-b border-slate-100 dark:border-zink-500 shadow-sm z-10">
                                                    <div class="flex flex-col gap-2">
                                                        <div class="relative w-full">
                                                            <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 size-4 text-slate-400"></i>
                                                            <input type="text" id="searchViolation-{{ $murid->id }}"
                                                                placeholder="Cari jenis pelanggaran..." style="padding-left: 2.25rem;"
                                                                class="w-full pr-3 py-2 text-sm border rounded-lg border-slate-200 dark:border-zink-500 dark:bg-zink-700 dark:text-zink-100 focus:outline-none focus:border-custom-500 focus:ring-1 focus:ring-custom-500 transition-all">
                                                        </div>
                                                        <select id="categoryFilter-{{ $murid->id }}"
                                                            class="w-full py-2 px-3 text-sm border rounded-lg border-slate-200 dark:border-zink-500 dark:bg-zink-700 dark:text-zink-100 focus:outline-none focus:border-custom-500 focus:ring-1 focus:ring-custom-500 transition-all">
                                                            <option value="">Semua Kategori</option>
                                                            <option value="ringan">Ringan</option>
                                                            <option value="sedang">Sedang</option>
                                                            <option value="berat">Berat</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="p-4 overflow-y-auto flex-1 bg-slate-50/50 dark:bg-zink-600/50" style="min-height: 300px; max-height: 50vh;">
                                                    <div class="space-y-2" id="violationList-{{ $murid->id }}">
                                                        @foreach ($vals as $violation)
                                                            <label class="violation-item flex items-start gap-3 p-3 rounded-lg border border-slate-200 bg-white dark:bg-zink-700 dark:border-zink-500 cursor-pointer hover:border-custom-500 hover:shadow-sm transition-all has-[:checked]:border-custom-500 has-[:checked]:bg-custom-50/50 dark:has-[:checked]:bg-custom-900/20"
                                                                data-violation-name="{{ strtolower($violation->name) }}"
                                                                data-violation-category="{{ strtolower($violation->category->name ?? '') }}">
                                                                
                                                                <div class="mt-0.5">
                                                                    <input type="checkbox" name="violations[]"
                                                                        value="{{ $violation->id }}"
                                                                        id="violation_{{ $violation->id }}_{{ $murid->id }}"
                                                                        class="border rounded-sm appearance-none cursor-pointer size-4 bg-slate-100 border-slate-300 dark:bg-zink-600 dark:border-zink-500 checked:bg-custom-500 checked:border-custom-500 dark:checked:bg-custom-500 dark:checked:border-custom-500 transition-all">
                                                                </div>
                                                                <div class="grow">
                                                                    <div class="flex items-center justify-between gap-2">
                                                                        <span class="text-sm font-semibold text-slate-800 dark:text-zink-50">{{ $violation->name }}</span>
                                                                        <span class="text-xs font-bold text-red-500 shrink-0">{{ $violation->point }} Poin</span>
                                                                    </div>
                                                                    <div class="flex items-center gap-1.5 mt-1">
                                                                        @php
                                                                            $violationCategory = $violation->category->name ?? 'Lainnya';
                                                                            $violationCatColor = 'bg-slate-100 text-slate-600 dark:bg-zink-500 dark:text-zink-200';
                                                                            if ($violationCategory === 'Ringan') {
                                                                                $violationCatColor = 'bg-blue-50 text-blue-600 dark:bg-blue-900/20 dark:text-blue-200';
                                                                            } elseif ($violationCategory === 'Sedang') {
                                                                                $violationCatColor = 'bg-amber-50 text-amber-600 dark:bg-amber-900/20 dark:text-amber-200';
                                                                            } elseif ($violationCategory === 'Berat') {
                                                                                $violationCatColor = 'bg-red-50 text-red-600 dark:bg-red-900/20 dark:text-red-200';
                                                                            }
                                                                        @endphp
                                                                        <span class="px-2 py-0.5 text-[10px] font-semibold rounded {{ $violationCatColor }}">{{ $violationCategory }}</span>
                                                                    </div>
                                                                </div>
                                                            </label>
                                                        @endforeach
                                                    </div>
                                                </div>

                                                <div class="p-4 border-t border-slate-200 dark:border-zink-500 flex justify-end gap-2 bg-slate-50 dark:bg-zink-700 rounded-b-md">
                                                    <button type="button" data-modal-close="modal-{{ $murid->id }}"
                                                        class="btn bg-white dark:bg-zink-600 border-slate-200 dark:border-zink-500 text-slate-700 dark:text-zink-100 hover:bg-slate-50 dark:hover:bg-zink-500 font-semibold px-4 py-2 rounded-lg text-13">
                                                        Batal
                                                    </button>
                                                    <button type="submit"
                                                        class="btn bg-custom-500 border-custom-500 text-white hover:bg-custom-600 font-semibold px-4 py-2 rounded-lg text-13">
                                                        Simpan Laporan
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="text-center text-slate-500 py-6">Tidak ada siswa terdaftar untuk kelas ini pada tahun akademik aktif.</div>
                    @endif
                </div>
            </div>

        </div>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Logika Live Search di modal pelanggaran
        @foreach ($studentAcademicYears as $murid)
            (function() {
                const searchInput = document.getElementById("searchViolation-{{ $murid->id }}");
                const categoryFilter = document.getElementById("categoryFilter-{{ $murid->id }}");
                const listItems = document.querySelectorAll("#violationList-{{ $murid->id }} .violation-item");

                function filterViolations() {
                    const query = searchInput.value.toLowerCase().trim();
                    const selectedCat = categoryFilter.value.toLowerCase();

                    listItems.forEach(item => {
                        const name = item.getAttribute("data-violation-name");
                        const category = item.getAttribute("data-violation-category");

                        const matchesSearch = name.includes(query);
                        const matchesCategory = selectedCat === "" || category === selectedCat;

                        if (matchesSearch && matchesCategory) {
                            item.style.display = "flex";
                        } else {
                            item.style.display = "none";
                        }
                    });
                }

                if (searchInput) searchInput.addEventListener("input", filterViolations);
                if (categoryFilter) categoryFilter.addEventListener("change", filterViolations);
            })();
        @endforeach
    });
</script>
@endsection
