@extends('layouts.app')
@section('content')
    <div
        class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu group-data-[sidebar-size=md]:ltr:ml-vertical-menu-md group-data-[sidebar-size=md]:rtl:mr-vertical-menu-md group-data-[sidebar-size=sm]:ltr:ml-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:mr-vertical-menu-sm px-4 pb-[calc(theme('spacing.header')_*_0.8)] pt-[calc(theme('spacing.header')_*_1)] group-data-[layout=horizontal]:mx-auto group-data-[layout=horizontal]:max-w-screen-2xl group-data-[layout=horizontal]:px-0 group-data-[layout=horizontal]:px-3 group-data-[layout=horizontal]:group-data-[navbar=hidden]:pt-[calc(theme('spacing.header')_*_0.9)] group-data-[navbar=bordered]:pt-[calc(theme('spacing.header')_*_1.3)] group-data-[navbar=hidden]:pt-0 group-data-[layout=horizontal]:md:pt-[calc(theme('spacing.header')_*_1.6)] group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:ltr:md:ml-auto group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:rtl:md:mr-auto">
        <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">

            <div class="flex flex-col gap-2 py-4 md:flex-row md:items-center print:hidden">
                <div class="grow">
                    <h5 class="text-16 font-semibold">Rekap Pelanggaran Kelas {{ $class->academic_level }} {{ $class->name }}</h5>
                </div>
                <ul class="flex shrink-0 items-center gap-2 text-sm font-normal">
                    <li class="dark:text-zink-200 text-slate-400">Portal Wali Kelas</li>
                    <li class="text-slate-400">/</li>
                    <li class="dark:text-zink-100 text-slate-700 font-medium">Rekap Pelanggaran</li>
                </ul>
            </div>

            <div class="card mb-4">
                <div class="card-body">
                    <h6 class="text-15 mb-4 font-semibold text-slate-800 dark:text-zink-50">Filter Data</h6>
                    <div class="flex flex-col gap-4 sm:flex-row">
                        <div class="flex-1">
                            <label for="genderFilter" class="dark:text-zink-300 mb-2 block text-sm font-medium text-slate-700">Filter Jenis Kelamin</label>
                            <select id="genderFilter" class="dark:bg-zink-600 dark:border-zink-500 dark:text-zink-100 w-full rounded-md border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                                <option value="">Semua Jenis Kelamin</option>
                                <option value="Laki - Laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>
                        <div class="flex-1">
                            <label for="pointRangeFilter" class="dark:text-zink-300 mb-2 block text-sm font-medium text-slate-700">Filter Range Poin</label>
                            <select id="pointRangeFilter" class="dark:bg-zink-600 dark:border-zink-500 dark:text-zink-100 w-full rounded-md border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                                <option value="">Semua Range</option>
                                <option value="0">0 Poin</option>
                                <option value="1-10">1-10 Poin</option>
                                <option value="11-25">11-25 Poin</option>
                                <option value="26-50">26-50 Poin</option>
                                <option value="51+">51+ Poin</option>
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button type="button" id="resetMainFilter" class="dark:bg-zink-600 dark:border-zink-500 dark:text-zink-300 dark:hover:bg-zink-700 rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-600 transition-colors duration-200 hover:bg-slate-50 focus:ring-2 focus:ring-blue-500">
                                Reset Filter
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <!-- Navigation Tabs -->
                    <div class="mb-5 flex border-b border-slate-200 dark:border-zink-500 print:hidden">
                        <button type="button" id="tabActiveBtn" onclick="switchTab('active')"
                            class="tab-btn px-5 py-3 text-sm font-semibold border-b-2 border-custom-500 text-custom-500 dark:text-custom-400 dark:border-custom-400">
                            Perlu Tindakan / Aktif ({{ $activeStudents->count() }})
                        </button>
                        <button type="button" id="tabHistoryBtn" onclick="switchTab('history')"
                            class="tab-btn px-5 py-3 text-sm font-semibold border-b-2 border-transparent text-slate-500 hover:text-slate-700 dark:text-zink-200 dark:hover:text-zink-50">
                            Riwayat Tindakan ({{ $historyStudents->count() }})
                        </button>
                    </div>

                    <div id="tabActiveContent" class="tab-pane">
                        <h6 class="text-15 mb-4 font-semibold text-slate-800 dark:text-zink-50">Daftar Pelanggaran Aktif</h6>

                        <div id="filterInfo" class="dark:text-zink-300 mb-3 hidden text-sm text-slate-600">
                            <span id="showingCount">0</span> dari <span id="totalCount">0</span> data ditampilkan
                        </div>

                        <table id="hoverableTable" style="width: 100%" class="hover group">
                            <thead>
                                <tr>
                                    <th>Total Poin Terverifikasi</th>
                                    <th>Nama Lengkap</th>
                                    <th>NIS</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($activeStudents as $rec)
                                <tr class="student-row" data-gender="{{ $rec->student->gender }}" data-points="{{ $rec->violations_sum_point ?? 0 }}">
                                    <td>
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 font-semibold text-red-600 bg-red-50 border border-red-200 rounded-lg dark:bg-red-900/20 dark:text-red-400 dark:border-red-800">
                                            {{ $rec->violations_sum_point ?? 0 }} Poin
                                        </span>
                                    </td>
                                    <td>{{ $rec->student->full_name }}</td>
                                    <td>{{ $rec->student->student_number }}</td>
                                    <td>{{ $rec->student->gender }}</td>
                                    <td>
                                        <a href="{{ route('wakel.recaps.detail', $rec->id) }}" class="btn bg-blue-100 hover:bg-blue-200 text-blue-800 dark:bg-zink-600 dark:hover:bg-zink-500 dark:text-zink-50 text-12 font-medium px-3 py-1.5 rounded">
                                            Detail & Tindakan
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    </div> <!-- End tabActiveContent -->

                    <div id="tabHistoryContent" class="tab-pane hidden">
                        <h6 class="text-15 mb-4 font-semibold text-slate-800 dark:text-zink-50">Daftar Riwayat Tindakan</h6>
                        <div class="table-wrapper">
                            <table id="historyTable" style="width: 100%" class="hover group">
                                <thead>
                                    <tr>
                                        <th>Nama Lengkap</th>
                                        <th>NIS</th>
                                        <th>Total Poin Terverifikasi</th>
                                        <th>Tindakan Terakhir</th>
                                        <th>Diberikan Oleh</th>
                                        <th>Tanggal Penindakan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($historyStudents as $student)
                                        <tr class="student-row"
                                            data-gender="{{ $student->student->gender }}">
                                            <td>{{ $student->student->full_name ?? '-' }}</td>
                                            <td>{{ $student->student->student_number ?? '-' }}</td>
                                            <td>
                                                <span class="whitespace-nowrap font-semibold text-red-600 dark:text-red-400">
                                                    {{ $student->violations_sum_point ?? 0 }} Poin
                                                </span>
                                            </td>
                                            <td>
                                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                                                    {{ $student->action_detail->handling->handling_action ?? $student->action_detail->handling->handling_name ?? '-' }}
                                                </span>
                                            </td>
                                            <td>{{ $student->action_detail->handle->name ?? '-' }}</td>
                                            <td>{{ $student->action_detail->created_at->format('d M Y') }}</td>
                                            <td>
                                                <a href="{{ route('wakel.recaps.detail', $student->id) }}" class="btn bg-blue-100 hover:bg-blue-200 text-blue-800 dark:bg-zink-600 dark:hover:bg-zink-500 dark:text-zink-50 text-12 font-medium px-3 py-1.5 rounded">
                                                    Detail
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div> <!-- End tabHistoryContent -->

                    <div id="noMainData" class="hidden py-8 text-center">
                        <div class="dark:text-zink-400 flex flex-col items-center text-slate-500">
                            <i data-lucide="search" class="mb-2 w-8 h-8"></i>
                            <p class="text-sm">Tidak ada data yang sesuai dengan filter</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        if (typeof DataTable !== 'undefined') {
            new DataTable('#historyTable');
        }

        const genderFilter = document.getElementById("genderFilter");
        const pointRangeFilter = document.getElementById("pointRangeFilter");
        const resetButton = document.getElementById("resetMainFilter");
        const rows = document.querySelectorAll("#hoverableTable .student-row");
        const noData = document.getElementById("noMainData");
        const filterInfo = document.getElementById("filterInfo");
        const showingCountSpan = document.getElementById("showingCount");
        const totalCountSpan = document.getElementById("totalCount");

        if (totalCountSpan) {
            totalCountSpan.textContent = rows.length;
        }

        function filterData() {
            const selectedGender = genderFilter.value;
            const selectedRange = pointRangeFilter.value;
            let activeRowsCount = 0;

            rows.forEach((row, index) => {
                const gender = row.getAttribute("data-gender");
                const points = parseInt(row.getAttribute("data-points")) || 0;

                const matchesGender = selectedGender === "" || gender === selectedGender;
                let matchesRange = true;

                if (selectedRange !== "") {
                    if (selectedRange === "0") {
                        matchesRange = points === 0;
                    } else if (selectedRange === "1-10") {
                        matchesRange = points >= 1;
                    } else if (selectedRange === "11-25") {
                        matchesRange = points >= 11 && points <= 25;
                    } else if (selectedRange === "26-50") {
                        matchesRange = points >= 26 && points <= 50;
                    } else if (selectedRange === "51+") {
                        matchesRange = points >= 51;
                    }
                }

                if (matchesGender && matchesRange) {
                    row.style.display = "";
                    activeRowsCount++;
                    const numberCell = row.querySelector(".row-number");
                    if (numberCell) numberCell.textContent = activeRowsCount;
                } else {
                    row.style.display = "none";
                }
            });

            if (showingCountSpan) showingCountSpan.textContent = activeRowsCount;

            const isFiltered = selectedGender !== "" || selectedRange !== "";
            if (isFiltered) {
                filterInfo.classList.remove("hidden");
            } else {
                filterInfo.classList.add("hidden");
            }

            if (activeRowsCount === 0) {
                noData.classList.remove("hidden");
                document.getElementById("hoverableTable").classList.add("hidden");
            } else {
                noData.classList.add("hidden");
                document.getElementById("hoverableTable").classList.remove("hidden");
            }
        }

        if (genderFilter) genderFilter.addEventListener("change", filterData);
        if (pointRangeFilter) pointRangeFilter.addEventListener("change", filterData);

        if (resetButton) {
            resetButton.addEventListener("click", function () {
                genderFilter.value = "";
                pointRangeFilter.value = "";
                filterData();
            });
        }
    });

    function switchTab(tab) {
        const activeBtn = document.getElementById('tabActiveBtn');
        const historyBtn = document.getElementById('tabHistoryBtn');
        const activeContent = document.getElementById('tabActiveContent');
        const historyContent = document.getElementById('tabHistoryContent');

        if (tab === 'active') {
            activeBtn.classList.add('border-custom-500', 'text-custom-500', 'dark:text-custom-400', 'dark:border-custom-400');
            activeBtn.classList.remove('border-transparent', 'text-slate-500');
            
            historyBtn.classList.remove('border-custom-500', 'text-custom-500', 'dark:text-custom-400', 'dark:border-custom-400');
            historyBtn.classList.add('border-transparent', 'text-slate-500');

            activeContent.classList.remove('hidden');
            historyContent.classList.add('hidden');
        } else {
            historyBtn.classList.add('border-custom-500', 'text-custom-500', 'dark:text-custom-400', 'dark:border-custom-400');
            historyBtn.classList.remove('border-transparent', 'text-slate-500');
            
            activeBtn.classList.remove('border-custom-500', 'text-custom-500', 'dark:text-custom-400', 'dark:border-custom-400');
            activeBtn.classList.add('border-transparent', 'text-slate-500');

            historyContent.classList.remove('hidden');
            activeContent.classList.add('hidden');
        }
    }
</script>
@endsection
