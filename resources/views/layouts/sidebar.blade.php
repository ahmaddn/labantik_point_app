<?php
// Di Controller atau Blade, set variable $routePrefix:
$currentPath = request()->path();
$segments = explode('/', $currentPath);
$routePrefix = '';

if (count($segments) > 0 && in_array($segments[0], ['guru', 'kesiswaan-bk', 'superadmin'])) {
    $routePrefix = $segments[0] . '.';
}
?>
<div
    class="app-menu w-vertical-menu bg-vertical-menu border-vertical-menu-border group-data-[sidebar-size=md]:w-vertical-menu-md group-data-[sidebar-size=sm]:w-vertical-menu-sm group-data-[sidebar-size=sm]:pt-header group-data-[sidebar=dark]:bg-vertical-menu-dark group-data-[sidebar=dark]:border-vertical-menu-dark group-data-[sidebar=brand]:bg-vertical-menu-brand group-data-[sidebar=brand]:border-vertical-menu-brand group-data-[sidebar=modern]:to-vertical-menu-to-modern group-data-[sidebar=modern]:from-vertical-menu-form-modern group-data-[layout=horizontal]:top-header group-data-[sidebar=modern]:border-vertical-menu-border-modern group-data-[layout=horizontal]:dark:bg-zink-700 group-data-[layout=horizontal]:dark:border-zink-500 group-data-[sidebar=dark]:dark:bg-zink-700 group-data-[sidebar=dark]:dark:border-zink-600 group-data-[layout=horizontal]:group-data-[navbar=bordered]:[&.sticky]:top-header group-data-[layout=horizontal]:dark:shadow-zink-500/10 fixed bottom-0 top-0 z-[1003] hidden transition-all duration-75 ease-linear group-data-[layout=horizontal]:group-data-[navbar=scroll]:absolute group-data-[sidebar-size=sm]:absolute group-data-[layout=horizontal]:group-data-[navbar=bordered]:inset-x-4 group-data-[layout=horizontal]:bottom-auto group-data-[layout=horizontal]:group-data-[navbar=bordered]:top-[calc(theme('spacing.header')_+_theme('spacing.4'))] group-data-[layout=horizontal]:group-data-[navbar=hidden]:top-0 group-data-[layout=horizontal]:group-data-[navbar=hidden]:h-16 group-data-[layout=horizontal]:group-data-[navbar=bordered]:w-[calc(100%_-_2rem)] group-data-[layout=horizontal]:w-full group-data-[layout=horizontal]:group-data-[navbar=bordered]:rounded-b-md group-data-[layout=horizontal]:border-r-0 group-data-[layout=horizontal]:border-t group-data-[sidebar=modern]:bg-gradient-to-tr group-data-[layout=horizontal]:opacity-0 group-data-[layout=horizontal]:shadow-md group-data-[layout=horizontal]:shadow-slate-500/10 md:block ltr:border-r rtl:border-l print:hidden">
    <div
        class="h-header group-data-[sidebar-size=sm]:bg-vertical-menu group-data-[sidebar-size=sm]:group-data-[sidebar=dark]:bg-vertical-menu-dark group-data-[sidebar-size=sm]:group-data-[sidebar=brand]:bg-vertical-menu-brand group-data-[sidebar-size=sm]:group-data-[sidebar=modern]:to-vertical-menu-to-modern group-data-[sidebar-size=sm]:group-data-[sidebar=modern]:from-vertical-menu-form-modern group-data-[sidebar-size=sm]:group-data-[sidebar=modern]:bg-vertical-menu-modern group-data-[sidebar-size=sm]:group-data-[sidebar=dark]:dark:bg-zink-700 flex items-center justify-center px-5 text-center group-data-[sidebar-size=sm]:fixed group-data-[sidebar-size=sm]:top-0 group-data-[sidebar-size=sm]:z-10 group-data-[layout=horizontal]:hidden group-data-[sidebar-size=sm]:w-[calc(theme('spacing.vertical-menu-sm')_-_1px)] group-data-[sidebar-size=sm]:group-data-[sidebar=modern]:bg-gradient-to-br">
        <a href="index.html"
            class="group-data-[sidebar=brand]:hidden group-data-[sidebar=dark]:hidden group-data-[sidebar=modern]:hidden">
            <span class="hidden group-data-[sidebar-size=sm]:block">
                <img src="{{ asset('assets/images/logo_smk.png') }}" alt="" class="mx-auto h-10">
            </span>
            <span class="group-data-[sidebar-size=sm]:hidden">
                <img src="{{ asset('assets/images/logo_smk.png') }}" alt="" class="mx-auto h-10">
            </span>
        </a>
        <a href="index.html"
            class="hidden group-data-[sidebar=brand]:block group-data-[sidebar=dark]:block group-data-[sidebar=modern]:block">
            <span class="hidden group-data-[sidebar-size=sm]:block">
                <img src="{{ asset('assets/images/logo_smk.png') }}" alt="" class="mx-auto h-10">
            </span>
            <span class="group-data-[sidebar-size=sm]:hidden">
                <img src="{{ asset('assets/images/logo_smk.png') }}" alt="" class="mx-auto h-10">
            </span>
        </a>
        <button type="button" class="float-end hidden p-0" id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar"
        class="group-data-[layout=horizontal]:mx-auto group-data-[layout=horizontal]:h-56 group-data-[sidebar-size=lg]:max-h-[calc(100vh_-_theme('spacing.header')_*_1.2)] group-data-[sidebar-size=md]:max-h-[calc(100vh_-_theme('spacing.header')_*_1.2)] group-data-[layout=horizontal]:max-w-screen-2xl group-data-[layout=horizontal]:overflow-auto group-data-[layout=horizontal]:md:h-auto group-data-[layout=horizontal]:md:overflow-visible">
        <div>
            <ul class="group-data-[layout=horizontal]:flex group-data-[layout=horizontal]:flex-col group-data-[layout=horizontal]:md:flex-row"
                id="navbar-nav">
                <li
                    class="text-vertical-menu-item group-data-[sidebar=dark]:text-vertical-menu-item-dark group-data-[sidebar=brand]:text-vertical-menu-item-brand group-data-[sidebar=modern]:text-vertical-menu-item-modern group-data-[sidebar=dark]:dark:text-zink-200 inline-block cursor-default px-4 py-1 text-[11px] font-medium uppercase tracking-wider group-data-[sidebar-size=md]:block group-data-[layout=horizontal]:hidden group-data-[sidebar-size=sm]:hidden group-data-[sidebar-size=md]:text-center group-data-[sidebar-size=md]:underline">
                    <span data-key="t-menu">Menu</span>
                </li>
                <li class="group/sm relative group-data-[layout=horizontal]:shrink-0">
                    <a href="{{ route($routePrefix . 'dashboard') }}"
                        class="group/menu-link text-vertical-menu-item-font-size text-vertical-menu-item hover:text-vertical-menu-item-hover hover:bg-vertical-menu-item-bg-hover [&.active]:text-vertical-menu-item-active [&.active]:bg-vertical-menu-item-bg-active group-data-[sidebar=dark]:text-vertical-menu-item-dark group-data-[sidebar=dark]:hover:text-vertical-menu-item-hover-dark group-data-[sidebar=dark]:dark:hover:text-custom-500 group-data-[layout=horizontal]:dark:hover:text-custom-500 group-data-[sidebar=dark]:hover:bg-vertical-menu-item-bg-hover-dark group-data-[sidebar=dark]:dark:hover:bg-zink-600 group-data-[sidebar=dark]:[&.active]:text-vertical-menu-item-active-dark group-data-[sidebar=dark]:[&.active]:bg-vertical-menu-item-bg-active-dark group-data-[sidebar=brand]:text-vertical-menu-item-brand group-data-[sidebar=brand]:hover:text-vertical-menu-item-hover-brand group-data-[sidebar=brand]:hover:bg-vertical-menu-item-bg-hover-brand group-data-[sidebar=brand]:[&.active]:bg-vertical-menu-item-bg-active-brand group-data-[sidebar=brand]:[&.active]:text-vertical-menu-item-active-brand group-data-[sidebar=modern]:text-vertical-menu-item-modern group-data-[sidebar=modern]:hover:bg-vertical-menu-item-bg-hover-modern group-data-[sidebar=modern]:hover:text-vertical-menu-item-hover-modern group-data-[sidebar=modern]:[&.active]:bg-vertical-menu-item-bg-active-modern group-data-[sidebar=modern]:[&.active]:text-vertical-menu-item-active-modern group-data-[sidebar-size=sm]:group-hover/sm:bg-vertical-menu group-data-[sidebar-size=sm]:group-data-[sidebar=dark]:group-hover/sm:bg-vertical-menu-dark group-data-[sidebar-size=sm]:group-data-[sidebar=modern]:group-hover/sm:bg-vertical-menu-border-modern group-data-[sidebar-size=sm]:group-data-[sidebar=brand]:group-hover/sm:bg-vertical-menu-brand [&.dropdown-button]:before:font-remix [&.dropdown-button]:before:text-16 group-data-[sidebar=dark]:dark:text-zink-200 group-data-[layout=horizontal]:dark:text-zink-200 group-data-[sidebar=dark]:[&.active]:dark:bg-zink-600 group-data-[layout=horizontal]:dark:[&.active]:text-custom-500 {{ request()->routeIs($routePrefix . 'dashboard') ? 'active' : '' }} relative mx-3 my-1 flex items-center rounded-md py-2.5 font-normal transition-all duration-75 ease-linear group-data-[layout=horizontal]:m-0 group-data-[sidebar-size=sm]:my-0 group-data-[sidebar-size=md]:block group-data-[sidebar-size=sm]:rounded-b-none group-data-[sidebar-size=md]:text-center group-data-[layout=horizontal]:hover:bg-transparent group-data-[sidebar-size=sm]:group-hover/sm:w-[calc(theme('spacing.vertical-menu-sm')_*_3.63)] ltr:pl-3 ltr:pr-5 group-data-[layout=horizontal]:ltr:pr-8 rtl:pl-5 rtl:pr-3 group-data-[layout=horizontal]:rtl:pl-8 group-data-[layout=horizontal]:[&.active]:bg-transparent [&.dropdown-button]:before:absolute [&.dropdown-button]:before:content-['\ea6e'] group-data-[sidebar-size=md]:[&.dropdown-button]:before:hidden group-data-[sidebar-size=sm]:[&.dropdown-button]:before:hidden group-data-[layout=horizontal]:[&.dropdown-button]:before:rotate-90 ltr:[&.dropdown-button]:before:right-2 rtl:[&.dropdown-button]:before:left-2 rtl:[&.dropdown-button]:before:rotate-180 [&.dropdown-button]:[&.show]:before:content-['\ea4e'] group-data-[layout=horizontal]:[&.dropdown-button]:[&.show]:before:rotate-0 rtl:[&.dropdown-button]:[&.show]:before:rotate-0">

                        <span
                            class="inline-block min-w-[1.75rem] text-start text-[16px] group-data-[sidebar-size=md]:block group-data-[sidebar-size=sm]:flex group-data-[sidebar-size=sm]:h-[1.75rem] group-data-[sidebar-size=sm]:items-center">
                            <i data-lucide="monitor-dot"
                                class="group-hover/menu-link:animate-icons group-data-[sidebar=dark]:fill-vertical-menu-item-bg-active-dark group-data-[sidebar=dark]:dark:fill-zink-600 group-data-[layout=horizontal]:dark:fill-zink-600 group-data-[sidebar=brand]:fill-vertical-menu-item-bg-active-brand group-data-[sidebar=modern]:fill-vertical-menu-item-bg-active-modern group-data-[sidebar=dark]:group-hover/menu-link:fill-vertical-menu-item-bg-active-dark group-data-[sidebar=dark]:group-hover/menu-link:dark:fill-custom-500/20 group-data-[layout=horizontal]:dark:group-hover/menu-link:fill-custom-500/20 group-data-[sidebar=brand]:group-hover/menu-link:fill-vertical-menu-item-bg-active-brand group-data-[sidebar=modern]:group-hover/menu-link:fill-vertical-menu-item-bg-active-modern h-4 fill-slate-100 transition group-hover/menu-link:fill-blue-200 group-data-[sidebar-size=md]:mx-auto group-data-[sidebar-size=md]:mb-2 group-data-[sidebar-size=md]:block group-data-[sidebar-size=sm]:h-5 group-data-[sidebar-size=sm]:w-5"></i>
                        </span>

                        <span
                            class="align-middle group-data-[sidebar-size=sm]:hidden group-data-[sidebar-size=sm]:group-hover/sm:block group-data-[sidebar-size=sm]:ltr:pl-10 group-data-[sidebar-size=sm]:rtl:pr-10"
                            data-key="t-dashboards">Dashboard</span>
                    </a>
                </li>

                @if ($routePrefix === 'guru.')
                    <li class="group/sm relative group-data-[layout=horizontal]:shrink-0">
                        <a href="{{ route($routePrefix . 'student-data') }}"
                            class="group/menu-link text-vertical-menu-item-font-size text-vertical-menu-item hover:text-vertical-menu-item-hover hover:bg-vertical-menu-item-bg-hover [&.active]:text-vertical-menu-item-active [&.active]:bg-vertical-menu-item-bg-active group-data-[sidebar=dark]:text-vertical-menu-item-dark group-data-[sidebar=dark]:hover:text-vertical-menu-item-hover-dark group-data-[sidebar=dark]:dark:hover:text-custom-500 group-data-[layout=horizontal]:dark:hover:text-custom-500 group-data-[sidebar=dark]:hover:bg-vertical-menu-item-bg-hover-dark group-data-[sidebar=dark]:dark:hover:bg-zink-600 group-data-[sidebar=dark]:[&.active]:text-vertical-menu-item-active-dark group-data-[sidebar=dark]:[&.active]:bg-vertical-menu-item-bg-active-dark {{ request()->routeIs($routePrefix . 'student-data') ? 'active' : '' }} relative mx-3 my-1 flex items-center rounded-md py-2.5 font-normal transition-all duration-75 ease-linear ltr:pl-3 ltr:pr-5 rtl:pl-5 rtl:pr-3">

                            <span
                                class="inline-block min-w-[1.75rem] text-start text-[16px] group-data-[sidebar-size=md]:block group-data-[sidebar-size=sm]:flex group-data-[sidebar-size=sm]:h-[1.75rem] group-data-[sidebar-size=sm]:items-center">
                                <i data-lucide="users-round"
                                    class="group-hover/menu-link:animate-icons group-data-[sidebar=dark]:fill-vertical-menu-item-bg-active-dark group-data-[sidebar=dark]:dark:fill-zink-600 group-data-[layout=horizontal]:dark:fill-zink-600 group-data-[sidebar=brand]:fill-vertical-menu-item-bg-active-brand group-data-[sidebar=modern]:fill-vertical-menu-item-bg-active-modern group-data-[sidebar=dark]:group-hover/menu-link:fill-vertical-menu-item-bg-active-dark group-data-[sidebar=dark]:group-hover/menu-link:dark:fill-custom-500/20 group-data-[layout=horizontal]:dark:group-hover/menu-link:fill-custom-500/20 group-data-[sidebar=brand]:group-hover/menu-link:fill-vertical-menu-item-bg-active-brand group-data-[sidebar=modern]:group-hover/menu-link:fill-vertical-menu-item-bg-active-modern h-4 fill-slate-100 transition group-hover/menu-link:fill-blue-200 group-data-[sidebar-size=md]:mx-auto group-data-[sidebar-size=md]:mb-2 group-data-[sidebar-size=md]:block group-data-[sidebar-size=sm]:h-5 group-data-[sidebar-size=sm]:w-5"></i>
                            </span>

                            <span
                                class="align-middle group-data-[sidebar-size=sm]:hidden group-data-[sidebar-size=sm]:group-hover/sm:block group-data-[sidebar-size=sm]:ltr:pl-10 group-data-[sidebar-size=sm]:rtl:pr-10"
                                data-key="t-student-data">Data Siswa</span>
                        </a>
                    </li>
                    <li class="group/sm relative group-data-[layout=horizontal]:shrink-0">
                        <a href="{{ route($routePrefix . 'recaps') }}"
                            class="group/menu-link text-vertical-menu-item-font-size text-vertical-menu-item hover:text-vertical-menu-item-hover hover:bg-vertical-menu-item-bg-hover [&.active]:text-vertical-menu-item-active [&.active]:bg-vertical-menu-item-bg-active group-data-[sidebar=dark]:text-vertical-menu-item-dark group-data-[sidebar=dark]:hover:text-vertical-menu-item-hover-dark group-data-[sidebar=dark]:dark:hover:text-custom-500 group-data-[layout=horizontal]:dark:hover:text-custom-500 group-data-[sidebar=dark]:hover:bg-vertical-menu-item-bg-hover-dark group-data-[sidebar=dark]:dark:hover:bg-zink-600 group-data-[sidebar=dark]:[&.active]:text-vertical-menu-item-active-dark group-data-[sidebar=dark]:[&.active]:bg-vertical-menu-item-bg-active-dark {{ request()->routeIs($routePrefix . 'recaps') ? 'active' : '' }} relative mx-3 my-1 flex items-center rounded-md py-2.5 font-normal transition-all duration-75 ease-linear ltr:pl-3 ltr:pr-5 rtl:pl-5 rtl:pr-3">

                            <span
                                class="inline-block min-w-[1.75rem] text-start text-[16px] group-data-[sidebar-size=md]:block group-data-[sidebar-size=sm]:flex group-data-[sidebar-size=sm]:h-[1.75rem] group-data-[sidebar-size=sm]:items-center">
                                <i data-lucide="file"
                                    class="group-hover/menu-link:animate-icons group-data-[sidebar=dark]:fill-vertical-menu-item-bg-active-dark group-data-[sidebar=dark]:dark:fill-zink-600 group-data-[layout=horizontal]:dark:fill-zink-600 group-data-[sidebar=brand]:fill-vertical-menu-item-bg-active-brand group-data-[sidebar=modern]:fill-vertical-menu-item-bg-active-modern group-data-[sidebar=dark]:group-hover/menu-link:fill-vertical-menu-item-bg-active-dark group-data-[sidebar=dark]:group-hover/menu-link:dark:fill-custom-500/20 group-data-[layout=horizontal]:dark:group-hover/menu-link:fill-custom-500/20 group-data-[sidebar=brand]:group-hover/menu-link:fill-vertical-menu-item-bg-active-brand group-data-[sidebar=modern]:group-hover/menu-link:fill-vertical-menu-item-bg-active-modern h-4 fill-slate-100 transition group-hover/menu-link:fill-blue-200 group-data-[sidebar-size=md]:mx-auto group-data-[sidebar-size=md]:mb-2 group-data-[sidebar-size=md]:block group-data-[sidebar-size=sm]:h-5 group-data-[sidebar-size=sm]:w-5"></i>
                            </span>

                            <span
                                class="align-middle group-data-[sidebar-size=sm]:hidden group-data-[sidebar-size=sm]:group-hover/sm:block group-data-[sidebar-size=sm]:ltr:pl-10 group-data-[sidebar-size=sm]:rtl:pr-10"
                                data-key="t-recaps">Recaps</span>
                        </a>
                    </li>
                @endif
                @if ($routePrefix === 'kesiswaan-bk.')
                    <li class="group/sm relative group-data-[layout=horizontal]:shrink-0">
                        <a href="{{ route($routePrefix . 'student-data') }}"
                            class="group/menu-link text-vertical-menu-item-font-size text-vertical-menu-item hover:text-vertical-menu-item-hover hover:bg-vertical-menu-item-bg-hover [&.active]:text-vertical-menu-item-active [&.active]:bg-vertical-menu-item-bg-active group-data-[sidebar=dark]:text-vertical-menu-item-dark group-data-[sidebar=dark]:hover:text-vertical-menu-item-hover-dark group-data-[sidebar=dark]:dark:hover:text-custom-500 group-data-[layout=horizontal]:dark:hover:text-custom-500 group-data-[sidebar=dark]:hover:bg-vertical-menu-item-bg-hover-dark group-data-[sidebar=dark]:dark:hover:bg-zink-600 group-data-[sidebar=dark]:[&.active]:text-vertical-menu-item-active-dark group-data-[sidebar=dark]:[&.active]:bg-vertical-menu-item-bg-active-dark {{ request()->routeIs($routePrefix . 'student-data') ? 'active' : '' }} relative mx-3 my-1 flex items-center rounded-md py-2.5 font-normal transition-all duration-75 ease-linear ltr:pl-3 ltr:pr-5 rtl:pl-5 rtl:pr-3">

                            <span
                                class="inline-block min-w-[1.75rem] text-start text-[16px] group-data-[sidebar-size=md]:block group-data-[sidebar-size=sm]:flex group-data-[sidebar-size=sm]:h-[1.75rem] group-data-[sidebar-size=sm]:items-center">
                                <i data-lucide="users-round"
                                    class="group-hover/menu-link:animate-icons group-data-[sidebar=dark]:fill-vertical-menu-item-bg-active-dark group-data-[sidebar=dark]:dark:fill-zink-600 group-data-[layout=horizontal]:dark:fill-zink-600 group-data-[sidebar=brand]:fill-vertical-menu-item-bg-active-brand group-data-[sidebar=modern]:fill-vertical-menu-item-bg-active-modern group-data-[sidebar=dark]:group-hover/menu-link:fill-vertical-menu-item-bg-active-dark group-data-[sidebar=dark]:group-hover/menu-link:dark:fill-custom-500/20 group-data-[layout=horizontal]:dark:group-hover/menu-link:fill-custom-500/20 group-data-[sidebar=brand]:group-hover/menu-link:fill-vertical-menu-item-bg-active-brand group-data-[sidebar=modern]:group-hover/menu-link:fill-vertical-menu-item-bg-active-modern h-4 fill-slate-100 transition group-hover/menu-link:fill-blue-200 group-data-[sidebar-size=md]:mx-auto group-data-[sidebar-size=md]:mb-2 group-data-[sidebar-size=md]:block group-data-[sidebar-size=sm]:h-5 group-data-[sidebar-size=sm]:w-5"></i>
                            </span>

                            <span
                                class="align-middle group-data-[sidebar-size=sm]:hidden group-data-[sidebar-size=sm]:group-hover/sm:block group-data-[sidebar-size=sm]:ltr:pl-10 group-data-[sidebar-size=sm]:rtl:pr-10"
                                data-key="t-student-data">Data Siswa</span>
                        </a>
                    </li>
                    <li class="group/sm relative group-data-[layout=horizontal]:shrink-0">
                        <a href="{{ route($routePrefix . 'recaps') }}"
                            class="group/menu-link text-vertical-menu-item-font-size text-vertical-menu-item hover:text-vertical-menu-item-hover hover:bg-vertical-menu-item-bg-hover [&.active]:text-vertical-menu-item-active [&.active]:bg-vertical-menu-item-bg-active group-data-[sidebar=dark]:text-vertical-menu-item-dark group-data-[sidebar=dark]:hover:text-vertical-menu-item-hover-dark group-data-[sidebar=dark]:dark:hover:text-custom-500 group-data-[layout=horizontal]:dark:hover:text-custom-500 group-data-[sidebar=dark]:hover:bg-vertical-menu-item-bg-hover-dark group-data-[sidebar=dark]:dark:hover:bg-zink-600 group-data-[sidebar=dark]:[&.active]:text-vertical-menu-item-active-dark group-data-[sidebar=dark]:[&.active]:bg-vertical-menu-item-bg-active-dark {{ request()->routeIs($routePrefix . 'recaps') ? 'active' : '' }} relative mx-3 my-1 flex items-center rounded-md py-2.5 font-normal transition-all duration-75 ease-linear ltr:pl-3 ltr:pr-5 rtl:pl-5 rtl:pr-3">

                            <span
                                class="inline-block min-w-[1.75rem] text-start text-[16px] group-data-[sidebar-size=md]:block group-data-[sidebar-size=sm]:flex group-data-[sidebar-size=sm]:h-[1.75rem] group-data-[sidebar-size=sm]:items-center">
                                <i data-lucide="file"
                                    class="group-hover/menu-link:animate-icons group-data-[sidebar=dark]:fill-vertical-menu-item-bg-active-dark group-data-[sidebar=dark]:dark:fill-zink-600 group-data-[layout=horizontal]:dark:fill-zink-600 group-data-[sidebar=brand]:fill-vertical-menu-item-bg-active-brand group-data-[sidebar=modern]:fill-vertical-menu-item-bg-active-modern group-data-[sidebar=dark]:group-hover/menu-link:fill-vertical-menu-item-bg-active-dark group-data-[sidebar=dark]:group-hover/menu-link:dark:fill-custom-500/20 group-data-[layout=horizontal]:dark:group-hover/menu-link:fill-custom-500/20 group-data-[sidebar=brand]:group-hover/menu-link:fill-vertical-menu-item-bg-active-brand group-data-[sidebar=modern]:group-hover/menu-link:fill-vertical-menu-item-bg-active-modern h-4 fill-slate-100 transition group-hover/menu-link:fill-blue-200 group-data-[sidebar-size=md]:mx-auto group-data-[sidebar-size=md]:mb-2 group-data-[sidebar-size=md]:block group-data-[sidebar-size=sm]:h-5 group-data-[sidebar-size=sm]:w-5"></i>
                            </span>

                            <span
                                class="align-middle group-data-[sidebar-size=sm]:hidden group-data-[sidebar-size=sm]:group-hover/sm:block group-data-[sidebar-size=sm]:ltr:pl-10 group-data-[sidebar-size=sm]:rtl:pr-10"
                                data-key="t-recaps">Recaps</span>
                        </a>
                    </li>
                @endif
                @if ($routePrefix === 'superadmin.')
                    <li class="group/sm relative group-data-[layout=horizontal]:shrink-0">
                        <a href="{{ route($routePrefix . 'student-data') }}"
                            class="group/menu-link text-vertical-menu-item-font-size text-vertical-menu-item hover:text-vertical-menu-item-hover hover:bg-vertical-menu-item-bg-hover [&.active]:text-vertical-menu-item-active [&.active]:bg-vertical-menu-item-bg-active group-data-[sidebar=dark]:text-vertical-menu-item-dark group-data-[sidebar=dark]:hover:text-vertical-menu-item-hover-dark group-data-[sidebar=dark]:dark:hover:text-custom-500 group-data-[layout=horizontal]:dark:hover:text-custom-500 group-data-[sidebar=dark]:hover:bg-vertical-menu-item-bg-hover-dark group-data-[sidebar=dark]:dark:hover:bg-zink-600 group-data-[sidebar=dark]:[&.active]:text-vertical-menu-item-active-dark group-data-[sidebar=dark]:[&.active]:bg-vertical-menu-item-bg-active-dark {{ request()->routeIs($routePrefix . 'student-data') ? 'active' : '' }} relative mx-3 my-1 flex items-center rounded-md py-2.5 font-normal transition-all duration-75 ease-linear ltr:pl-3 ltr:pr-5 rtl:pl-5 rtl:pr-3">

                            <span
                                class="inline-block min-w-[1.75rem] text-start text-[16px] group-data-[sidebar-size=md]:block group-data-[sidebar-size=sm]:flex group-data-[sidebar-size=sm]:h-[1.75rem] group-data-[sidebar-size=sm]:items-center">
                                <i data-lucide="users-round"
                                    class="group-hover/menu-link:animate-icons group-data-[sidebar=dark]:fill-vertical-menu-item-bg-active-dark group-data-[sidebar=dark]:dark:fill-zink-600 group-data-[layout=horizontal]:dark:fill-zink-600 group-data-[sidebar=brand]:fill-vertical-menu-item-bg-active-brand group-data-[sidebar=modern]:fill-vertical-menu-item-bg-active-modern group-data-[sidebar=dark]:group-hover/menu-link:fill-vertical-menu-item-bg-active-dark group-data-[sidebar=dark]:group-hover/menu-link:dark:fill-custom-500/20 group-data-[layout=horizontal]:dark:group-hover/menu-link:fill-custom-500/20 group-data-[sidebar=brand]:group-hover/menu-link:fill-vertical-menu-item-bg-active-brand group-data-[sidebar=modern]:group-hover/menu-link:fill-vertical-menu-item-bg-active-modern h-4 fill-slate-100 transition group-hover/menu-link:fill-blue-200 group-data-[sidebar-size=md]:mx-auto group-data-[sidebar-size=md]:mb-2 group-data-[sidebar-size=md]:block group-data-[sidebar-size=sm]:h-5 group-data-[sidebar-size=sm]:w-5"></i>
                            </span>

                            <span
                                class="align-middle group-data-[sidebar-size=sm]:hidden group-data-[sidebar-size=sm]:group-hover/sm:block group-data-[sidebar-size=sm]:ltr:pl-10 group-data-[sidebar-size=sm]:rtl:pr-10"
                                data-key="t-student-data">Data Siswa</span>
                        </a>
                    </li>
                    <li class="group/sm relative group-data-[layout=horizontal]:shrink-0">
                        <a href="{{ route($routePrefix . 'confirm-recaps') }}"
                            class="group/menu-link text-vertical-menu-item-font-size text-vertical-menu-item hover:text-vertical-menu-item-hover hover:bg-vertical-menu-item-bg-hover [&.active]:text-vertical-menu-item-active [&.active]:bg-vertical-menu-item-bg-active group-data-[sidebar=dark]:text-vertical-menu-item-dark group-data-[sidebar=dark]:hover:text-vertical-menu-item-hover-dark group-data-[sidebar=dark]:dark:hover:text-custom-500 group-data-[layout=horizontal]:dark:hover:text-custom-500 group-data-[sidebar=dark]:hover:bg-vertical-menu-item-bg-hover-dark group-data-[sidebar=dark]:dark:hover:bg-zink-600 group-data-[sidebar=dark]:[&.active]:text-vertical-menu-item-active-dark group-data-[sidebar=dark]:[&.active]:bg-vertical-menu-item-bg-active-dark {{ request()->routeIs($routePrefix . 'confirm-recaps') ? 'active' : '' }} relative mx-3 my-1 flex items-center rounded-md py-2.5 font-normal transition-all duration-75 ease-linear ltr:pl-3 ltr:pr-5 rtl:pl-5 rtl:pr-3">

                            <span
                                class="inline-block min-w-[1.75rem] text-start text-[16px] group-data-[sidebar-size=md]:block group-data-[sidebar-size=sm]:flex group-data-[sidebar-size=sm]:h-[1.75rem] group-data-[sidebar-size=sm]:items-center">
                                <i data-lucide="file-check-2"
                                    class="group-hover/menu-link:animate-icons group-data-[sidebar=dark]:fill-vertical-menu-item-bg-active-dark group-data-[sidebar=dark]:dark:fill-zink-600 group-data-[layout=horizontal]:dark:fill-zink-600 group-data-[sidebar=brand]:fill-vertical-menu-item-bg-active-brand group-data-[sidebar=modern]:fill-vertical-menu-item-bg-active-modern group-data-[sidebar=dark]:group-hover/menu-link:fill-vertical-menu-item-bg-active-dark group-data-[sidebar=dark]:group-hover/menu-link:dark:fill-custom-500/20 group-data-[layout=horizontal]:dark:group-hover/menu-link:fill-custom-500/20 group-data-[sidebar=brand]:group-hover/menu-link:fill-vertical-menu-item-bg-active-brand group-data-[sidebar=modern]:group-hover/menu-link:fill-vertical-menu-item-bg-active-modern h-4 fill-slate-100 transition group-hover/menu-link:fill-blue-200 group-data-[sidebar-size=md]:mx-auto group-data-[sidebar-size=md]:mb-2 group-data-[sidebar-size=md]:block group-data-[sidebar-size=sm]:h-5 group-data-[sidebar-size=sm]:w-5"></i>
                            </span>

                            <span
                                class="align-middle group-data-[sidebar-size=sm]:hidden group-data-[sidebar-size=sm]:group-hover/sm:block group-data-[sidebar-size=sm]:ltr:pl-10 group-data-[sidebar-size=sm]:rtl:pr-10"
                                data-key="t-recaps">Rekap & Verifikasi</span>
                        </a>
                    </li>
                    <li class="group/sm relative group-data-[layout=horizontal]:shrink-0">
                        <a href="{{ route($routePrefix . 'configs') }}"
                            class="group/menu-link text-vertical-menu-item-font-size text-vertical-menu-item hover:text-vertical-menu-item-hover hover:bg-vertical-menu-item-bg-hover [&.active]:text-vertical-menu-item-active [&.active]:bg-vertical-menu-item-bg-active group-data-[sidebar=dark]:text-vertical-menu-item-dark group-data-[sidebar=dark]:hover:text-vertical-menu-item-hover-dark group-data-[sidebar=dark]:dark:hover:text-custom-500 group-data-[layout=horizontal]:dark:hover:text-custom-500 group-data-[sidebar=dark]:hover:bg-vertical-menu-item-bg-hover-dark group-data-[sidebar=dark]:dark:hover:bg-zink-600 group-data-[sidebar=dark]:[&.active]:text-vertical-menu-item-active-dark group-data-[sidebar=dark]:[&.active]:bg-vertical-menu-item-bg-active-dark {{ request()->routeIs($routePrefix . 'configs') ? 'active' : '' }} relative mx-3 my-1 flex items-center rounded-md py-2.5 font-normal transition-all duration-75 ease-linear ltr:pl-3 ltr:pr-5 rtl:pl-5 rtl:pr-3">

                            <span
                                class="inline-block min-w-[1.75rem] text-start text-[16px] group-data-[sidebar-size=md]:block group-data-[sidebar-size=sm]:flex group-data-[sidebar-size=sm]:h-[1.75rem] group-data-[sidebar-size=sm]:items-center">
                                <i data-lucide="settings-2"
                                    class="group-hover/menu-link:animate-icons group-data-[sidebar=dark]:fill-vertical-menu-item-bg-active-dark group-data-[sidebar=dark]:dark:fill-zink-600 group-data-[layout=horizontal]:dark:fill-zink-600 group-data-[sidebar=brand]:fill-vertical-menu-item-bg-active-brand group-data-[sidebar=modern]:fill-vertical-menu-item-bg-active-modern group-data-[sidebar=dark]:group-hover/menu-link:fill-vertical-menu-item-bg-active-dark group-data-[sidebar=dark]:group-hover/menu-link:dark:fill-custom-500/20 group-data-[layout=horizontal]:dark:group-hover/menu-link:fill-custom-500/20 group-data-[sidebar=brand]:group-hover/menu-link:fill-vertical-menu-item-bg-active-brand group-data-[sidebar=modern]:group-hover/menu-link:fill-vertical-menu-item-bg-active-modern h-4 fill-slate-100 transition group-hover/menu-link:fill-blue-200 group-data-[sidebar-size=md]:mx-auto group-data-[sidebar-size=md]:mb-2 group-data-[sidebar-size=md]:block group-data-[sidebar-size=sm]:h-5 group-data-[sidebar-size=sm]:w-5"></i>
                            </span>

                            <span
                                class="align-middle group-data-[sidebar-size=sm]:hidden group-data-[sidebar-size=sm]:group-hover/sm:block group-data-[sidebar-size=sm]:ltr:pl-10 group-data-[sidebar-size=sm]:rtl:pr-10"
                                data-key="t-configs">Konfigurasi</span>
                        </a>
                    </li>
                @endif
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->
