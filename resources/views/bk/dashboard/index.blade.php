@extends('layouts.app')

@section('content')
    <div
        class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu group-data-[sidebar-size=md]:ltr:ml-vertical-menu-md group-data-[sidebar-size=md]:rtl:mr-vertical-menu-md group-data-[sidebar-size=sm]:ltr:ml-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:mr-vertical-menu-sm pt-[calc(theme('spacing.header')_*_1)] pb-[calc(theme('spacing.header')_*_0.8)] px-4 group-data-[navbar=bordered]:pt-[calc(theme('spacing.header')_*_1.3)] group-data-[navbar=hidden]:pt-0 group-data-[layout=horizontal]:mx-auto group-data-[layout=horizontal]:max-w-screen-2xl group-data-[layout=horizontal]:px-0 group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:ltr:md:ml-auto group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:rtl:md:mr-auto group-data-[layout=horizontal]:md:pt-[calc(theme('spacing.header')_*_1.6)] group-data-[layout=horizontal]:px-3 group-data-[layout=horizontal]:group-data-[navbar=hidden]:pt-[calc(theme('spacing.header')_*_0.9)]">
        <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">

            <!-- Breadcrumb -->
            <div class="flex flex-col gap-2 py-4 md:flex-row md:items-center print:hidden">
                <div class="grow">
                    <h5 class="text-16">Dashboard BK</h5>
                </div>
                <ul class="flex items-center gap-2 text-sm font-normal shrink-0">
                    <li
                        class="relative before:content-['\ea54'] before:font-remix ltr:before:-right-1 rtl:before:-left-1 before:absolute before:text-[18px] before:-top-[3px] ltr:pr-4 rtl:pl-4 before:text-slate-400 dark:text-zink-200">
                        <a href="#!" class="text-slate-400 dark:text-zink-200">Dashboards</a>
                    </li>
                    <li class="text-slate-700 dark:text-zink-100">
                        Kesiswaan
                    </li>
                </ul>
            </div>

            {{-- Alert untuk error --}}
            @if ($errors->has('error'))
                <div
                    class="relative p-3 pr-12 text-sm text-red-500 border border-transparent rounded-md bg-red-50 dark:bg-red-400/20 mb-4">
                    <button
                        class="absolute top-0 bottom-0 right-0 p-3 text-red-200 transition hover:text-red-500 dark:text-red-400/50 dark:hover:text-red-500"
                        onclick="this.parentElement.style.display='none'">
                        <i data-lucide="x" class="h-5"></i>
                    </button>
                    <div>
                        <span class="font-bold">⚠️ Peringatan!</span>
                        {{ $errors->first('error') }}
                    </div>
                </div>
            @endif

            {{-- Alert untuk success --}}
            @if (session('success') && !$errors->has('error'))
                <div
                    class="relative p-3 pr-12 text-sm text-green-500 border border-transparent rounded-md bg-green-50 dark:bg-green-400/20 mb-4">
                    <button
                        class="absolute top-0 bottom-0 right-0 p-3 text-green-200 transition hover:text-green-500 dark:text-green-400/50 dark:hover:text-green-500"
                        onclick="this.parentElement.style.display='none'">
                        <i data-lucide="x" class="h-5"></i>
                    </button>
                    <div>
                        <span class="font-bold">✅ Berhasil!</span> {{ session('success') }}
                    </div>
                </div>
            @endif

            <!-- Main Card -->
            <div class="card">
                <div class="card-body">
                    <h6 class="mb-4 text-15">Daftar Siswa dan Pelanggaran</h6>

                    <!-- Students Table -->
                    <div class="overflow-x-auto">
                        <table id="studentsTable" class="w-full whitespace-nowrap">
                            <thead>
                                <tr class="text-left bg-slate-100 dark:bg-zink-600">
                                    <th class="px-3.5 py-2.5 font-semibold border-b border-slate-200 dark:border-zink-500">
                                        Aksi</th>
                                    <th class="px-3.5 py-2.5 font-semibold border-b border-slate-200 dark:border-zink-500">#
                                    </th>
                                    <th class="px-3.5 py-2.5 font-semibold border-b border-slate-200 dark:border-zink-500">
                                        Nama Siswa</th>
                                    <th class="px-3.5 py-2.5 font-semibold border-b border-slate-200 dark:border-zink-500">
                                        Kelas</th>
                                    <th class="px-3.5 py-2.5 font-semibold border-b border-slate-200 dark:border-zink-500">
                                        Total Pelanggaran</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($studentAcademicYears as $index => $student)
                                    <tr class="hover:bg-slate-50 dark:hover:bg-zink-600">
                                        <td class="px-3.5 py-2.5 border-b border-slate-200 dark:border-zink-500">
                                            <button data-modal-target="violationsModal-{{ $student->id }}" type="button"
                                                class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                    </path>
                                                </svg>

                                            </button>
                                        </td>
                                        <td class="px-3.5 py-2.5 border-b border-slate-200 dark:border-zink-500">
                                            {{ $index + 1 }}
                                        </td>
                                        <td class="px-3.5 py-2.5 border-b border-slate-200 dark:border-zink-500">
                                            {{ $student->student->full_name ?? 'Nama tidak tersedia' }}
                                        </td>
                                        <td class="px-3.5 py-2.5 border-b border-slate-200 dark:border-zink-500">
                                            {{ $student->class->academic_level }} {{ $student->class->name ?? '-' }}
                                        </td>
                                        <td class="px-3.5 py-2.5 border-b border-slate-200 dark:border-zink-500">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                {{ $student->recaps_count }} Pelanggaran
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5"
                                            class="px-3.5 py-2.5 text-center border-b border-slate-200 dark:border-zink-500">
                                            Tidak ada siswa dengan pelanggaran
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals for each student -->
    @foreach ($studentAcademicYears as $student)
        <div id="violationsModal-{{ $student->id }}" modal-center=""
            class="fixed flex flex-col hidden transition-all duration-300 ease-in-out left-2/4 z-drawer -translate-x-2/4 -translate-y-2/4 show">
            <div
                class="w-screen md:w-[50rem] bg-white shadow rounded-md dark:bg-zink-600 flex flex-col h-full max-h-[90vh]">
                <div class="flex items-center justify-between p-4 border-b border-slate-200 dark:border-zink-500">
                    <h5 class="text-16">Daftar Pelanggaran - {{ $student->student->full_name }}</h5>
                    <button data-modal-close="violationsModal-{{ $student->id }}"
                        class="transition-all duration-200 ease-linear text-slate-500 hover:text-red-500 dark:text-zink-200 dark:hover:text-red-500">
                        <i data-lucide="x" class="size-5"></i>
                    </button>
                </div>
                <div class="max-h-[calc(theme('height.screen')_-_180px)] p-4 overflow-y-auto">
                    <div class="overflow-x-auto">




                        <table class="w-full whitespace-nowrap">
                            <thead>
                                <tr class="text-left bg-slate-100 dark:bg-zink-600">
                                    <th class="px-3.5 py-2.5 font-semibold border-b border-slate-200 dark:border-zink-500">
                                        No</th>
                                    <th class="px-3.5 py-2.5 font-semibold border-b border-slate-200 dark:border-zink-500">
                                        Nama Pelanggaran</th>
                                    <th class="px-3.5 py-2.5 font-semibold border-b border-slate-200 dark:border-zink-500">
                                        Jenis Pelanggaran</th>
                                    <th class="px-3.5 py-2.5 font-semibold border-b border-slate-200 dark:border-zink-500">
                                        Dibuat Oleh</th>

                                    <th class="px-3.5 py-2.5 font-semibold border-b border-slate-200 dark:border-zink-500">
                                        Tanggal</th>
                                    <th class="px-3.5 py-2.5 font-semibold border-b border-slate-200 dark:border-zink-500">
                                        Status</th>
                                    <th class="px-3.5 py-2.5 font-semibold border-b border-slate-200 dark:border-zink-500">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($student->recaps as $index => $violation)
                                    <tr class="hover:bg-slate-50 dark:hover:bg-zink-600">
                                        <td class="px-3.5 py-2.5 border-b border-slate-200 dark:border-zink-500">
                                            {{ $index + 1 }}
                                        </td>
                                        <td class="px-3.5 py-2.5 border-b border-slate-200 dark:border-zink-500 text-xs">
                                            {{ $violation->violation->name ?? 'Tidak diketahui' }}

                                        </td>
                                        <td class="px-3.5 py-2.5 border-b border-slate-200 dark:border-zink-500">
                                            <div> {{ $violation->violation->category->name ?? 'N/A' }}</div>

                                        </td>
                                        <td class="px-3.5 py-2.5 border-b border-slate-200 dark:border-zink-500">
                                            <div> {{ $violation->createdBy->name ?? 'N/A' }}</div>

                                        </td>
                                        <td class="px-3.5 py-2.5 border-b border-slate-200 dark:border-zink-500">
                                            {{ $violation->created_at->format('d F Y') }}
                                        </td>
                                        <td class="px-3.5 py-2.5 border-b border-slate-200 dark:border-zink-500">
                                            @if ($violation->status == 'pending')
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Pending</span>
                                            @elseif($violation->status == 'verified')
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Verified</span>
                                            @else
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Not
                                                    Verified</span>
                                            @endif
                                        </td>
                                        <td class="px-3.5 py-2.5 border-b border-slate-200 dark:border-zink-500">
                                            <div class="flex space-x-1">
                                                @if ($violation->status == 'pending')
                                                    {{-- Form untuk Verify --}}
                                                    <form
                                                        action="{{ route('bk.violation-status.update', $violation->getKey()) }}"
                                                        method="POST" class="inline">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="status" value="verified">
                                                        <button type="submit" style="background-color: #74bcb4;"
                                                            class="inline-flex items-center px-2 py-1 bg-green-600 text-white rounded hover:bg-green-700 focus:outline-none text-xs"
                                                            title="Verifikasi ID: {{ $violation->getKey() }}"
                                                            onclick="console.log('Verifying ID: {{ $violation->getKey() }}')">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-check-icon lucide-check">
                                                                <path d="M20 6 9 17l-5-5" />
                                                            </svg>

                                                        </button>
                                                    </form>

                                                    {{-- Form untuk Reject --}}
                                                    <form
                                                        action="{{ route('bk.violation-status.update', $violation->getKey()) }}"
                                                        method="POST" class="inline">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="status" value="not_verified">
                                                        <button type="submit" style="background-color:#f48484;"
                                                            class="inline-flex items-center px-2 py-1 bg-red-600 text-white rounded hover:bg-red-700 focus:outline-none text-xs"
                                                            title="Tolak ID: {{ $violation->getKey() }}"
                                                            onclick="console.log('Rejecting ID: {{ $violation->getKey() }}')">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-x-icon lucide-x">
                                                                <path d="M18 6 6 18" />
                                                                <path d="m6 6 12 12" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                @else
                                                    {{-- Form untuk Reset --}}
                                                    <form
                                                        action="{{ route('bk.violation-status.update', $violation->getKey()) }}"
                                                        method="POST" class="inline">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="status" value="pending">
                                                        <button type="submit" style="background-color: #ec4646;"
                                                            class="inline-flex items-center px-2 py-1 bg-gray-600 text-white rounded hover:bg-gray-700 focus:outline-none text-xs"
                                                            title="Reset ID: {{ $violation->getKey() }}"
                                                            onclick="console.log('Resetting ID: {{ $violation->getKey() }}')">
                                                            Reset
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6"
                                            class="px-3.5 py-2.5 text-center border-b border-slate-200 dark:border-zink-500">
                                            Tidak ada pelanggaran ditemukan
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>


                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection
