@include('layouts.head')
<!-- Tambahkan CSS ini di bagian head atau file CSS Anda -->
<style>
    #pageLoader {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: #ffffff;
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 99999;
        transition: opacity 0.3s ease-in-out;
    }

    /* Deteksi dark mode dari awal menggunakan CSS */
    html.dark #pageLoader,
    body.dark #pageLoader,
    html[data-mode="dark"] #pageLoader,
    body[data-mode="dark"] #pageLoader {
        background-color: #18181b;
    }

    #pageLoader.dark-mode {
        background-color: #18181b;
    }

    #pageLoader.hide {
        opacity: 0;
        pointer-events: none;
    }

    .loader-spinner {
        border: 4px solid #f3f4f6;
        border-top: 4px solid #3b82f6;
        border-radius: 50%;
        width: 60px;
        height: 60px;
        animation: spin 1s linear infinite;
    }

    html.dark .loader-spinner,
    body.dark .loader-spinner,
    html[data-mode="dark"] .loader-spinner,
    body[data-mode="dark"] .loader-spinner,
    #pageLoader.dark-mode .loader-spinner {
        border: 4px solid #3f3f46;
        border-top: 4px solid #3b82f6;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }
</style>

<body
    class="text-base bg-body-bg text-body font-public dark:text-zink-100 dark:bg-zink-800 group-data-[skin=bordered]:bg-body-bordered group-data-[skin=bordered]:dark:bg-zink-700">

    <div id="pageLoader">
        <div class="loader-spinner"></div>
    </div>

    <div class="group-data-[sidebar-size=sm]:min-h-sm group-data-[sidebar-size=sm]:relative">
        @include('layouts.sidebar')
        @include('layouts.topbar')
        <div id="cartSidePenal" drawer-end=""
            class="fixed inset-y-0 flex flex-col w-full transition-transform duration-300 ease-in-out transform bg-white shadow dark:bg-zink-600 ltr:right-0 rtl:left-0 md:w-96 z-drawer show">
            <div class="flex items-center justify-between p-4 border-b border-slate-200 dark:border-zink-500">
                <div class="grow">
                    <h5 class="mb-0 text-16">Shopping Cart <span
                            class="inline-flex items-center justify-center w-5 h-5 ml-1 text-[11px] font-medium border rounded-full text-white bg-custom-500 border-custom-500">3</span>
                    </h5>
                </div>
                <div class="shrink-0">
                    <button data-drawer-close="cartSidePenal"
                        class="transition-all duration-150 ease-linear text-slate-500 hover:text-slate-800"><i
                            data-lucide="x" class="size-4"></i></button>
                </div>
            </div>
            <div class="px-4 py-3 text-sm text-green-500 border border-transparent bg-green-50 dark:bg-green-400/20">
                <span class="font-bold underline">starcode50</span> Coupon code applied successfully.
            </div>
            <div>
                <div class="h-[calc(100vh_-_370px)] p-4 overflow-y-auto product-list">
                    <div class="flex flex-col gap-4">
                        <div class="flex gap-2 product">
                            <div
                                class="flex items-center justify-center w-12 h-12 rounded-md bg-slate-100 shrink-0 dark:bg-zink-500">
                                <img src="assets/images/img-012.png" alt="" class="h-8">
                            </div>
                            <div class="overflow-hidden grow">
                                <div class="ltr:float-right rtl:float-left">
                                    <button
                                        class="transition-all duration-150 ease-linear text-slate-500 dark:text-zink-200 hover:text-red-500 dark:hover:text-red-500"><i
                                            data-lucide="x" class="size-4"></i></button>
                                </div>
                                <a href="#!" class="transition-all duration-200 ease-linear hover:text-custom-500">
                                    <h6 class="mb-1 text-15">Cotton collar t-shirts for men</h6>
                                </a>
                                <div class="flex items-center mb-3">
                                    <h5 class="text-base product-price"> $<span>155.32</span></h5>
                                    <div class="font-normal rtl:mr-1 ltr:ml-1 text-slate-500 dark:text-zink-200">
                                        (Fashion)</div>
                                </div>
                                <div class="flex items-center justify-between gap-3">
                                    <div class="inline-flex text-center input-step">
                                        <button type="button"
                                            class="border w-9 h-9 leading-[15px] minus bg-white dark:bg-zink-700 dark:border-zink-500 ltr:rounded-l rtl:rounded-r transition-all duration-200 ease-linear border-slate-200 text-slate-500 dark:text-zink-200 hover:bg-custom-500 dark:hover:bg-custom-500 hover:text-custom-50 dark:hover:text-custom-50 hover:border-custom-500 dark:hover:border-custom-500 focus:bg-custom-500 dark:focus:bg-custom-500 focus:border-custom-500 dark:focus:border-custom-500 focus:text-custom-50 dark:focus:text-custom-50"><i
                                                data-lucide="minus" class="inline-block size-4"></i></button>
                                        <input type="number"
                                            class="w-12 text-center h-9 border-y product-quantity dark:bg-zink-700 focus:shadow-none dark:border-zink-500"
                                            value="2" min="0" max="100" readonly="">
                                        <button type="button"
                                            class="transition-all duration-200 ease-linear bg-white border dark:bg-zink-700 dark:border-zink-500 ltr:rounded-r rtl:rounded-l w-9 h-9 border-slate-200 plus text-slate-500 dark:text-zink-200 hover:bg-custom-500 dark:hover:bg-custom-500 hover:text-custom-50 dark:hover:text-custom-50 hover:border-custom-500 dark:hover:border-custom-500 focus:bg-custom-500 dark:focus:bg-custom-500 focus:border-custom-500 dark:focus:border-custom-500 focus:text-custom-50 dark:focus:text-custom-50"><i
                                                data-lucide="plus" class="inline-block size-4"></i></button>
                                    </div>
                                    <h6 class="product-line-price">310.64</h6>
                                </div>
                            </div>
                        </div>
                        <div class="flex gap-2 product">
                            <div
                                class="flex items-center justify-center w-12 h-12 rounded-md bg-slate-100 shrink-0 dark:bg-zink-500">
                                <img src="assets/images/img-03.png" alt="" class="h-8">
                            </div>
                            <div class="overflow-hidden grow">
                                <div class="ltr:float-right rtl:float-left">
                                    <button
                                        class="transition-all duration-150 ease-linear text-slate-500 dark:text-zink-200 hover:text-red-500 dark:hover:text-red-500"><i
                                            data-lucide="x" class="size-4"></i></button>
                                </div>
                                <a href="#!" class="transition-all duration-200 ease-linear hover:text-custom-500">
                                    <h6 class="mb-1 text-15">Like style travel black handbag</h6>
                                </a>
                                <div class="flex items-center mb-3">
                                    <h5 class="text-base product-price"> $<span>349.95</span></h5>
                                    <div class="font-normal rtl:mr-1 ltr:ml-1 text-slate-400 dark:text-zink-200">
                                        (Luggage)</div>
                                </div>
                                <div class="flex items-center justify-between gap-3">
                                    <div class="inline-flex text-center input-step">
                                        <button type="button"
                                            class="border w-9 h-9 leading-[15px] minus bg-white dark:bg-zink-700 dark:border-zink-500 ltr:rounded-l rtl:rounded-r transition-all duration-200 ease-linear border-slate-200 text-slate-500 dark:text-zink-200 hover:bg-custom-500 dark:hover:bg-custom-500 hover:text-custom-50 dark:hover:text-custom-50 hover:border-custom-500 dark:hover:border-custom-500 focus:bg-custom-500 dark:focus:bg-custom-500 focus:border-custom-500 dark:focus:border-custom-500 focus:text-custom-50 dark:focus:text-custom-50"><i
                                                data-lucide="minus" class="inline-block size-4"></i></button>
                                        <input type="number"
                                            class="w-12 text-center h-9 border-y product-quantity dark:bg-zink-700 focus:shadow-none dark:border-zink-500"
                                            value="1" min="0" max="100" readonly="">
                                        <button type="button"
                                            class="transition-all duration-200 ease-linear bg-white border dark:bg-zink-700 dark:border-zink-500 ltr:rounded-r rtl:rounded-l w-9 h-9 border-slate-200 plus text-slate-500 dark:text-zink-200 hover:bg-custom-500 dark:hover:bg-custom-500 hover:text-custom-50 dark:hover:text-custom-50 hover:border-custom-500 dark:hover:border-custom-500 focus:bg-custom-500 dark:focus:bg-custom-500 focus:border-custom-500 dark:focus:border-custom-500 focus:text-custom-50 dark:focus:text-custom-50"><i
                                                data-lucide="plus" class="inline-block size-4"></i></button>
                                    </div>
                                    <h6 class="product-line-price">349.95</h6>
                                </div>
                            </div>
                        </div>
                        <div class="flex gap-2 product">
                            <div
                                class="flex items-center justify-center w-12 h-12 rounded-md bg-slate-100 shrink-0 dark:bg-zink-500">
                                <img src="assets/images/img-09.png" alt="" class="h-8">
                            </div>
                            <div class="overflow-hidden grow">
                                <div class="ltr:float-right rtl:float-left">
                                    <button
                                        class="transition-all duration-150 ease-linear text-slate-500 dark:text-zink-200 hover:text-red-500 dark:hover:text-red-500"><i
                                            data-lucide="x" class="size-4"></i></button>
                                </div>
                                <a href="#!"
                                    class="transition-all duration-200 ease-linear hover:text-custom-500">
                                    <h6 class="mb-1 text-15">Blive Printed Men Round Neck</h6>
                                </a>
                                <div class="flex items-center mb-3">
                                    <h5 class="text-base product-price">$<span>546.74</span></h5>
                                    <div class="font-normal rtl:mr-1 ltr:ml-1 text-slate-400 dark:text-zink-200">
                                        (Fashion)</div>
                                </div>
                                <div class="flex items-center justify-between gap-3">
                                    <div class="inline-flex text-center input-step">
                                        <button type="button"
                                            class="border w-9 h-9 leading-[15px] minus bg-white dark:bg-zink-700 dark:border-zink-500 ltr:rounded-l rtl:rounded-r transition-all duration-200 ease-linear border-slate-200 text-slate-500 dark:text-zink-200 hover:bg-custom-500 dark:hover:bg-custom-500 hover:text-custom-50 dark:hover:text-custom-50 hover:border-custom-500 dark:hover:border-custom-500 focus:bg-custom-500 dark:focus:bg-custom-500 focus:border-custom-500 dark:focus:border-custom-500 focus:text-custom-50 dark:focus:text-custom-50"><i
                                                data-lucide="minus" class="inline-block size-4"></i></button>
                                        <input type="number"
                                            class="w-12 text-center h-9 border-y product-quantity dark:bg-zink-700 focus:shadow-none dark:border-zink-500"
                                            value="4" min="0" max="100" readonly="">
                                        <button type="button"
                                            class="transition-all duration-200 ease-linear bg-white border dark:bg-zink-700 dark:border-zink-500 ltr:rounded-r rtl:rounded-l w-9 h-9 border-slate-200 plus text-slate-500 dark:text-zink-200 hover:bg-custom-500 dark:hover:bg-custom-500 hover:text-custom-50 dark:hover:text-custom-50 hover:border-custom-500 dark:hover:border-custom-500 focus:bg-custom-500 dark:focus:bg-custom-500 focus:border-custom-500 dark:focus:border-custom-500 focus:text-custom-50 dark:focus:text-custom-50"><i
                                                data-lucide="plus" class="inline-block size-4"></i></button>
                                    </div>
                                    <h6 class="product-line-price end">2,186.96</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="p-4 border-t border-slate-200 dark:border-zink-500">

                    <table class="w-full mb-3 ">
                        <tbody class="table-total">
                            <tr>
                                <td class="py-2">Sub Total :</td>
                                <td class="text-right cart-subtotal">$2,847.55</td>
                            </tr>
                            <tr>
                                <td class="py-2">Discount <span class="text-muted">(starcode50)</span>:</td>
                                <td class="text-right cart-discount">-$476.00</td>
                            </tr>
                            <tr>
                                <td class="py-2">Shipping Charge :</td>
                                <td class="text-right cart-shipping">$89.00</td>
                            </tr>
                            <tr>
                                <td class="py-2">Estimated Tax (12.5%) : </td>
                                <td class="text-right cart-tax">$70.62</td>
                            </tr>
                            <tr class="font-semibold">
                                <td class="py-2">Total : </td>
                                <td class="text-right cart-total">$2,531.17</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="flex items-center justify-between gap-3">
                        <a href="apps-ecommerce-product-grid.html"
                            class="w-full text-white btn bg-slate-500 border-slate-500 hover:text-white hover:bg-slate-600 hover:border-slate-600 focus:text-white focus:bg-slate-600 focus:border-slate-600 focus:ring focus:ring-slate-100 active:text-white active:bg-slate-600 active:border-slate-600 active:ring active:ring-slate-100 dark:ring-slate-400/10">Continue
                            Shopping</a>
                        <a href="apps-ecommerce-checkout.html"
                            class="w-full text-white bg-red-500 border-red-500 btn hover:text-white hover:bg-red-600 hover:border-red-600 focus:text-white focus:bg-red-600 focus:border-red-600 focus:ring focus:ring-red-100 active:text-white active:bg-red-600 active:border-red-600 active:ring active:ring-red-100 dark:ring-custom-400/20">Checkout</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="relative min-h-screen group-data-[sidebar-size=sm]:min-h-sm">
            @yield('content')
            @include('layouts.footer')
        </div>
    </div>
    <!-- end main content -->

    <div class="fixed items-center hidden bottom-6 right-12 h-header group-data-[navbar=hidden]:flex">
        <button data-drawer-target="customizerButton" type="button"
            class="inline-flex items-center justify-center w-12 h-12 p-0 transition-all duration-200 ease-linear rounded-md shadow-lg text-sky-50 bg-sky-500">
            <i data-lucide="settings" class="inline-block w-5 h-5"></i>
        </button>
    </div>

    <div id="customizerButton" drawer-end=""
        class="fixed inset-y-0 flex flex-col w-full transition-transform duration-300 ease-in-out transform bg-white shadow ltr:right-0 rtl:left-0 md:w-96 z-drawer show dark:bg-zink-600">
        <div class="flex justify-between p-4 border-b border-slate-200 dark:border-zink-500">
            <div class="grow">
                <h5 class="mb-1 text-16">starcode Theme Customizer</h5>
                <p class="font-normal text-slate-500 dark:text-zink-200">Choose your themes & layouts etc.</p>
            </div>
            <div class="shrink-0">
                <button data-drawer-close="customizerButton"
                    class="transition-all duration-150 ease-linear text-slate-500 hover:text-slate-800 dark:text-zink-200 dark:hover:text-zink-50"><i
                        data-lucide="x" class="w-4 h-4"></i></button>
            </div>
        </div>
        <div class="h-full p-6 overflow-y-auto">
            <div>
                <h5 class="mb-3 underline capitalize text-15">Choose Layouts</h5>
                <div class="grid grid-cols-1 mb-5 gap-7 sm:grid-cols-2">
                    <div class="relative">
                        <input id="layout-one" name="dataLayout"
                            class="absolute w-4 h-4 border rounded-full appearance-none cursor-pointer ltr:right-2 rtl:left-2 top-2 vertical-menu-btn bg-slate-100 border-slate-300 checked:bg-custom-500 checked:border-custom-500 dark:bg-zink-400 dark:border-zink-500"
                            type="radio" value="vertical" checked="">
                        <label
                            class="block w-full h-24 p-0 overflow-hidden border rounded-lg cursor-pointer border-slate-200 dark:border-zink-500"
                            for="layout-one">
                            <span class="flex h-full gap-0">
                                <span class="shrink-0">
                                    <span
                                        class="flex flex-col h-full gap-1 p-1 ltr:border-r rtl:border-l border-slate-200 dark:border-zink-500">
                                        <span class="block p-1 px-2 mb-2 rounded bg-slate-100 dark:bg-zink-400"></span>
                                        <span class="block p-1 px-2 pb-0 bg-slate-100 dark:bg-zink-500"></span>
                                        <span class="block p-1 px-2 pb-0 bg-slate-100 dark:bg-zink-500"></span>
                                        <span class="block p-1 px-2 pb-0 bg-slate-100 dark:bg-zink-500"></span>
                                    </span>
                                </span>
                                <span class="grow">
                                    <span class="flex flex-col h-full">
                                        <span class="block h-3 bg-slate-100 dark:bg-zink-500"></span>
                                        <span class="block h-3 mt-auto bg-slate-100 dark:bg-zink-500"></span>
                                    </span>
                                </span>
                            </span>
                        </label>
                        <h5 class="mt-2 text-center text-15">Vertical</h5>
                    </div>

                    <div class="relative">
                        <input id="layout-two" name="dataLayout"
                            class="absolute w-4 h-4 border rounded-full appearance-none cursor-pointer ltr:right-2 rtl:left-2 top-2 vertical-menu-btn bg-slate-100 border-slate-300 checked:bg-custom-500 checked:border-custom-500 dark:bg-zink-400 dark:border-zink-500"
                            type="radio" value="horizontal">
                        <label
                            class="block w-full h-24 p-0 overflow-hidden border rounded-lg cursor-pointer border-slate-200 dark:border-zink-500"
                            for="layout-two">
                            <span class="flex flex-col h-full gap-1">
                                <span class="flex items-center gap-1 p-1 bg-slate-100 dark:bg-zink-500">
                                    <span class="block p-1 ml-1 bg-white rounded dark:bg-zink-500"></span>
                                    <span class="block p-1 px-2 pb-0 bg-white dark:bg-zink-500 ms-auto"></span>
                                    <span class="block p-1 px-2 pb-0 bg-white dark:bg-zink-500"></span>
                                </span>
                                <span class="block p-1 bg-slate-100 dark:bg-zink-500"></span>
                                <span class="block p-1 mt-auto bg-slate-100 dark:bg-zink-500"></span>
                            </span>
                        </label>
                        <h5 class="mt-2 text-center text-15">Horizontal</h5>
                    </div>
                </div>

                <div id="semi-dark">
                    <div class="flex items-center">
                        <div class="relative inline-block w-10 mr-2 align-middle transition duration-200 ease-in">
                            <input type="checkbox" name="customDefaultSwitch" value="dark"
                                id="customDefaultSwitch"
                                class="absolute block w-5 h-5 transition duration-300 ease-linear border-2 rounded-full appearance-none cursor-pointer border-slate-200 bg-white/80 peer/published checked:bg-white checked:right-0 checked:border-custom-500 arrow-none dark:border-zink-500 dark:bg-zink-500 dark:checked:bg-zink-400 checked:bg-none">
                            <label for="customDefaultSwitch"
                                class="block h-5 overflow-hidden transition duration-300 ease-linear border rounded-full cursor-pointer border-slate-200 bg-slate-200 peer-checked/published:bg-custom-500 peer-checked/published:border-custom-500 dark:border-zink-500 dark:bg-zink-600"></label>
                        </div>
                        <label for="customDefaultSwitch" class="inline-block text-base font-medium">Semi Dark
                            (Sidebar
                            & Header)</label>
                    </div>
                </div>
            </div>

            <div class="mt-6">
                <!-- data-skin="" -->
                <h5 class="mb-3 underline capitalize text-15">Skin Layouts</h5>
                <div class="grid grid-cols-1 mb-5 gap-7 sm:grid-cols-2">
                    <div class="relative">
                        <input id="layoutSkitOne" name="dataLayoutSkin"
                            class="absolute w-4 h-4 border rounded-full appearance-none cursor-pointer ltr:right-2 rtl:left-2 top-2 vertical-menu-btn bg-slate-100 border-slate-300 checked:bg-custom-500 checked:border-custom-500 dark:bg-zink-400 dark:border-zink-500"
                            type="radio" value="default">
                        <label
                            class="block w-full h-24 p-0 overflow-hidden border rounded-lg cursor-pointer border-slate-200 dark:border-zink-500 bg-slate-50 dark:bg-zink-600"
                            for="layoutSkitOne">
                            <span class="flex h-full gap-0">
                                <span class="shrink-0">
                                    <span
                                        class="flex flex-col h-full gap-1 p-1 ltr:border-r rtl:border-l border-slate-200 dark:border-zink-500">
                                        <span class="block p-1 px-2 mb-2 rounded bg-slate-100 dark:bg-zink-400"></span>
                                        <span class="block p-1 px-2 pb-0 bg-slate-100 dark:bg-zink-500"></span>
                                        <span class="block p-1 px-2 pb-0 bg-slate-100 dark:bg-zink-500"></span>
                                        <span class="block p-1 px-2 pb-0 bg-slate-100 dark:bg-zink-500"></span>
                                    </span>
                                </span>
                                <span class="grow">
                                    <span class="flex flex-col h-full">
                                        <span class="block h-3 bg-slate-100 dark:bg-zink-500"></span>
                                        <span class="block h-3 mt-auto bg-slate-100 dark:bg-zink-500"></span>
                                    </span>
                                </span>
                            </span>
                        </label>
                        <h5 class="mt-2 text-center text-15">Default</h5>
                    </div>

                    <div class="relative">
                        <input id="layoutSkitTwo" name="dataLayoutSkin"
                            class="absolute w-4 h-4 border rounded-full appearance-none cursor-pointer ltr:right-2 rtl:left-2 top-2 vertical-menu-btn bg-slate-100 border-slate-300 checked:bg-custom-500 checked:border-custom-500 dark:bg-zink-400 dark:border-zink-500"
                            type="radio" value="bordered" checked="">
                        <label
                            class="block w-full h-24 p-0 overflow-hidden border rounded-lg cursor-pointer border-slate-200 dark:border-zink-500"
                            for="layoutSkitTwo">
                            <span class="flex h-full gap-0">
                                <span class="shrink-0">
                                    <span
                                        class="flex flex-col h-full gap-1 p-1 ltr:border-r rtl:border-l border-slate-200 dark:border-zink-500">
                                        <span class="block p-1 px-2 mb-2 rounded bg-slate-100 dark:bg-zink-400"></span>
                                        <span class="block p-1 px-2 pb-0 bg-slate-100 dark:bg-zink-500"></span>
                                        <span class="block p-1 px-2 pb-0 bg-slate-100 dark:bg-zink-500"></span>
                                        <span class="block p-1 px-2 pb-0 bg-slate-100 dark:bg-zink-500"></span>
                                    </span>
                                </span>
                                <span class="grow">
                                    <span class="flex flex-col h-full">
                                        <span class="block h-3 border-b border-slate-200 dark:border-zink-500"></span>
                                        <span
                                            class="block h-3 mt-auto border-t border-slate-200 dark:border-zink-500"></span>
                                    </span>
                                </span>
                            </span>
                        </label>
                        <h5 class="mt-2 text-center text-15">Bordered</h5>
                    </div>
                </div>
            </div>

            <div class="mt-6">
                <!-- data-mode="" -->
                <h5 class="mb-3 underline capitalize text-15">Light & Dark</h5>
                <div class="flex gap-3">
                    <button type="button" id="dataModeOne" name="dataMode" value="light"
                        class="transition-all duration-200 ease-linear bg-white border-dashed text-slate-500 btn border-slate-200 hover:text-slate-500 hover:bg-slate-50 hover:border-slate-200 [&.active]:text-custom-500 [&.active]:bg-custom-50 [&.active]:border-custom-200 dark:bg-zink-600 dark:text-zink-200 dark:border-zink-400 dark:hover:bg-zink-600 dark:hover:text-zink-100 dark:hover:border-zink-400 dark:[&.active]:bg-custom-500/10 dark:[&.active]:border-custom-500/30 dark:[&.active]:text-custom-500 active">Light
                        Mode</button>
                    <button type="button" id="dataModeTwo" name="dataMode" value="dark"
                        class="transition-all duration-200 ease-linear bg-white border-dashed text-slate-500 btn border-slate-200 hover:text-slate-500 hover:bg-slate-50 hover:border-slate-200 [&.active]:text-custom-500 [&.active]:bg-custom-50 [&.active]:border-custom-200 dark:bg-zink-600 dark:text-zink-200 dark:border-zink-400 dark:hover:bg-zink-600 dark:hover:text-zink-100 dark:hover:border-zink-400 dark:[&.active]:bg-custom-500/10 dark:[&.active]:border-custom-500/30 dark:[&.active]:text-custom-500">Dark
                        Mode</button>
                </div>
            </div>

            <div class="mt-6">
                <!-- dir="ltr" -->
                <h5 class="mb-3 underline capitalize text-15">LTR & RTL</h5>
                <div class="flex flex-wrap gap-3">
                    <button type="button" id="diractionOne" name="dir" value="ltr"
                        class="transition-all duration-200 ease-linear bg-white border-dashed text-slate-500 btn border-slate-200 hover:text-slate-500 hover:bg-slate-50 hover:border-slate-200 [&.active]:text-custom-500 [&.active]:bg-custom-50 [&.active]:border-custom-200 dark:bg-zink-600 dark:text-zink-200 dark:border-zink-400 dark:hover:bg-zink-600 dark:hover:text-zink-100 dark:hover:border-zink-400 dark:[&.active]:bg-custom-500/10 dark:[&.active]:border-custom-500/30 dark:[&.active]:text-custom-500 active">LTR
                        Mode</button>
                    <button type="button" id="diractionTwo" name="dir" value="rtl"
                        class="transition-all duration-200 ease-linear bg-white border-dashed text-slate-500 btn border-slate-200 hover:text-slate-500 hover:bg-slate-50 hover:border-slate-200 [&.active]:text-custom-500 [&.active]:bg-custom-50 [&.active]:border-custom-200 dark:bg-zink-600 dark:text-zink-200 dark:border-zink-400 dark:hover:bg-zink-600 dark:hover:text-zink-100 dark:hover:border-zink-400 dark:[&.active]:bg-custom-500/10 dark:[&.active]:border-custom-500/30 dark:[&.active]:text-custom-500">RTL
                        Mode</button>
                </div>
            </div>

            <div class="mt-6">
                <!-- data-content -->
                <h5 class="mb-3 underline capitalize text-15">Content Width</h5>
                <div class="flex gap-3">
                    <button type="button" id="datawidthOne" name="datawidth" value="fluid"
                        class="transition-all duration-200 ease-linear bg-white border-dashed text-slate-500 btn border-slate-200 hover:text-slate-500 hover:bg-slate-50 hover:border-slate-200 [&.active]:text-custom-500 [&.active]:bg-custom-50 [&.active]:border-custom-200 dark:bg-zink-600 dark:text-zink-200 dark:border-zink-400 dark:hover:bg-zink-600 dark:hover:text-zink-100 dark:hover:border-zink-400 dark:[&.active]:bg-custom-500/10 dark:[&.active]:border-custom-500/30 dark:[&.active]:text-custom-500 active">Fluid</button>
                    <button type="button" id="datawidthTwo" name="datawidth" value="boxed"
                        class="transition-all duration-200 ease-linear bg-white border-dashed text-slate-500 btn border-slate-200 hover:text-slate-500 hover:bg-slate-50 hover:border-slate-200 [&.active]:text-custom-500 [&.active]:bg-custom-50 [&.active]:border-custom-200 dark:bg-zink-600 dark:text-zink-200 dark:border-zink-400 dark:hover:bg-zink-600 dark:hover:text-zink-100 dark:hover:border-zink-400 dark:[&.active]:bg-custom-500/10 dark:[&.active]:border-custom-500/30 dark:[&.active]:text-custom-500">Boxed</button>
                </div>
            </div>

            <div class="mt-6" id="sidebar-size">
                <!-- data-sidebar-size="" -->
                <h5 class="mb-3 underline capitalize text-15">Sidebar Size</h5>
                <div class="flex flex-wrap gap-3">
                    <button type="button" id="sidebarSizeOne" name="sidebarSize" value="lg"
                        class="transition-all duration-200 ease-linear bg-white border-dashed text-slate-500 btn border-slate-200 hover:text-slate-500 hover:bg-slate-50 hover:border-slate-200 [&.active]:text-custom-500 [&.active]:bg-custom-50 [&.active]:border-custom-200 dark:bg-zink-600 dark:text-zink-200 dark:border-zink-400 dark:hover:bg-zink-600 dark:hover:text-zink-100 dark:hover:border-zink-400 dark:[&.active]:bg-custom-500/10 dark:[&.active]:border-custom-500/30 dark:[&.active]:text-custom-500 active">Default</button>
                    <button type="button" id="sidebarSizeTwo" name="sidebarSize" value="md"
                        class="transition-all duration-200 ease-linear bg-white border-dashed text-slate-500 btn border-slate-200 hover:text-slate-500 hover:bg-slate-50 hover:border-slate-200 [&.active]:text-custom-500 [&.active]:bg-custom-50 [&.active]:border-custom-200 dark:bg-zink-600 dark:text-zink-200 dark:border-zink-400 dark:hover:bg-zink-600 dark:hover:text-zink-100 dark:hover:border-zink-400 dark:[&.active]:bg-custom-500/10 dark:[&.active]:border-custom-500/30 dark:[&.active]:text-custom-500">Compact</button>
                    <button type="button" id="sidebarSizeThree" name="sidebarSize" value="sm"
                        class="transition-all duration-200 ease-linear bg-white border-dashed text-slate-500 btn border-slate-200 hover:text-slate-500 hover:bg-slate-50 hover:border-slate-200 [&.active]:text-custom-500 [&.active]:bg-custom-50 [&.active]:border-custom-200 dark:bg-zink-600 dark:text-zink-200 dark:border-zink-400 dark:hover:bg-zink-600 dark:hover:text-zink-100 dark:hover:border-zink-400 dark:[&.active]:bg-custom-500/10 dark:[&.active]:border-custom-500/30 dark:[&.active]:text-custom-500">Small
                        (Icon)</button>
                </div>
            </div>

            <div class="mt-6" id="navigation-type">
                <!-- data-navbar="" -->
                <h5 class="mb-3 underline capitalize text-15">Navigation Type</h5>
                <div class="flex flex-wrap gap-3">
                    <button type="button" id="navbarTwo" name="navbar" value="sticky"
                        class="transition-all duration-200 ease-linear bg-white border-dashed text-slate-500 btn border-slate-200 hover:text-slate-500 hover:bg-slate-50 hover:border-slate-200 [&.active]:text-custom-500 [&.active]:bg-custom-50 [&.active]:border-custom-200 dark:bg-zink-600 dark:text-zink-200 dark:border-zink-400 dark:hover:bg-zink-600 dark:hover:text-zink-100 dark:hover:border-zink-400 dark:[&.active]:bg-custom-500/10 dark:[&.active]:border-custom-500/30 dark:[&.active]:text-custom-500 active">Sticky</button>
                    <button type="button" id="navbarOne" name="navbar" value="scroll"
                        class="transition-all duration-200 ease-linear bg-white border-dashed text-slate-500 btn border-slate-200 hover:text-slate-500 hover:bg-slate-50 hover:border-slate-200 [&.active]:text-custom-500 [&.active]:bg-custom-50 [&.active]:border-custom-200 dark:bg-zink-600 dark:text-zink-200 dark:border-zink-400 dark:hover:bg-zink-600 dark:hover:text-zink-100 dark:hover:border-zink-400 dark:[&.active]:bg-custom-500/10 dark:[&.active]:border-custom-500/30 dark:[&.active]:text-custom-500">Scroll</button>
                    <button type="button" id="navbarThree" name="navbar" value="bordered"
                        class="transition-all duration-200 ease-linear bg-white border-dashed text-slate-500 btn border-slate-200 hover:text-slate-500 hover:bg-slate-50 hover:border-slate-200 [&.active]:text-custom-500 [&.active]:bg-custom-50 [&.active]:border-custom-200 dark:bg-zink-600 dark:text-zink-200 dark:border-zink-400 dark:hover:bg-zink-600 dark:hover:text-zink-100 dark:hover:border-zink-400 dark:[&.active]:bg-custom-500/10 dark:[&.active]:border-custom-500/30 dark:[&.active]:text-custom-500">Bordered</button>
                    <button type="button" id="navbarFour" name="navbar" value="hidden"
                        class="transition-all duration-200 ease-linear bg-white border-dashed text-slate-500 btn border-slate-200 hover:text-slate-500 hover:bg-slate-50 hover:border-slate-200 [&.active]:text-custom-500 [&.active]:bg-custom-50 [&.active]:border-custom-200 dark:bg-zink-600 dark:text-zink-200 dark:border-zink-400 dark:hover:bg-zink-600 dark:hover:text-zink-100 dark:hover:border-zink-400 dark:[&.active]:bg-custom-500/10 dark:[&.active]:border-custom-500/30 dark:[&.active]:text-custom-500">Hidden</button>
                </div>
            </div>

            <div class="mt-6" id="sidebar-color">
                <!-- data-sidebar="" light, dark, brand, modern-->
                <h5 class="mb-3 underline capitalize text-15">Sizebar Colors</h5>
                <div class="flex flex-wrap gap-3">
                    <button type="button" id="sidebarColorOne" name="sidebarColor" value="light"
                        class="flex items-center justify-center w-10 h-10 bg-white border rounded-md border-slate-200 group active"><i
                            data-lucide="check"
                            class="w-5 h-5 hidden group-[.active]:inline-block text-slate-600"></i></button>
                    <button type="button" id="sidebarColorTwo" name="sidebarColor" value="dark"
                        class="flex items-center justify-center w-10 h-10 border rounded-md border-zink-900 bg-zink-900 group"><i
                            data-lucide="check"
                            class="w-5 h-5 hidden group-[.active]:inline-block text-white"></i></button>
                    <button type="button" id="sidebarColorThree" name="sidebarColor" value="brand"
                        class="flex items-center justify-center w-10 h-10 border rounded-md border-custom-800 bg-custom-800 group"><i
                            data-lucide="check"
                            class="w-5 h-5 hidden group-[.active]:inline-block text-white"></i></button>
                    <button type="button" id="sidebarColorFour" name="sidebarColor" value="modern"
                        class="flex items-center justify-center w-10 h-10 border rounded-md border-purple-950 bg-gradient-to-t from-red-400 to-purple-500 group"><i
                            data-lucide="check"
                            class="w-5 h-5 hidden group-[.active]:inline-block text-white"></i></button>
                </div>
            </div>

            <div class="mt-6">
                <!-- data-topbar="" light, dark, brand, modern-->
                <h5 class="mb-3 underline capitalize text-15">Topbar Colors</h5>
                <div class="flex flex-wrap gap-3">
                    <button type="button" id="topbarColorOne" name="topbarColor" value="light"
                        class="flex items-center justify-center w-10 h-10 bg-white border rounded-md border-slate-200 group active"><i
                            data-lucide="check"
                            class="w-5 h-5 hidden group-[.active]:inline-block text-slate-600"></i></button>
                    <button type="button" id="topbarColorTwo" name="topbarColor" value="dark"
                        class="flex items-center justify-center w-10 h-10 border rounded-md border-zink-900 bg-zink-900 group"><i
                            data-lucide="check"
                            class="w-5 h-5 hidden group-[.active]:inline-block text-white"></i></button>
                    <button type="button" id="topbarColorThree" name="topbarColor" value="brand"
                        class="flex items-center justify-center w-10 h-10 border rounded-md border-custom-800 bg-custom-800 group"><i
                            data-lucide="check"
                            class="w-5 h-5 hidden group-[.active]:inline-block text-white"></i></button>
                </div>
            </div>

        </div>
        <div class="flex items-center justify-between gap-3 p-4 border-t border-slate-200 dark:border-zink-500">
            <button type="button" id="reset-layout"
                class="w-full transition-all duration-200 ease-linear text-slate-500 btn bg-slate-200 border-slate-200 hover:text-slate-600 hover:bg-slate-300 hover:border-slate-300 focus:text-slate-600 focus:bg-slate-300 focus:border-slate-300 focus:ring focus:ring-slate-100">Reset</button>
        </div>
    </div>

    <script>
        // Fungsi untuk menyembunyikan loader
        function hideLoader() {
            const loader = document.getElementById('pageLoader');
            if (loader && !loader.classList.contains('hide')) {
                loader.classList.add('hide');
                // Hapus elemen setelah animasi selesai
                setTimeout(() => {
                    loader.style.display = 'none';
                }, 300);
            }
        }

        // Cek apakah semua resource sudah dimuat
        function checkIfAllLoaded() {
            // Cek document ready state
            if (document.readyState === 'complete') {
                // Tunggu sebentar untuk memastikan rendering selesai
                requestAnimationFrame(() => {
                    requestAnimationFrame(() => {
                        hideLoader();
                    });
                });
            }
        }

        // Event listener untuk berbagai tahap loading
        if (document.readyState === 'complete') {
            // Jika sudah complete saat script dijalankan
            checkIfAllLoaded();
        } else {
            // Event ketika DOM selesai di-parse
            document.addEventListener('DOMContentLoaded', function() {
                // Cek apakah ada gambar yang perlu dimuat
                const images = document.querySelectorAll('img');
                let imagesLoaded = 0;
                const totalImages = images.length;

                if (totalImages === 0) {
                    // Tidak ada gambar, langsung hide setelah DOM ready
                    checkIfAllLoaded();
                } else {
                    // Tunggu semua gambar selesai dimuat
                    images.forEach(img => {
                        if (img.complete) {
                            imagesLoaded++;
                        } else {
                            img.addEventListener('load', () => {
                                imagesLoaded++;
                                if (imagesLoaded === totalImages) {
                                    checkIfAllLoaded();
                                }
                            });
                            img.addEventListener('error', () => {
                                imagesLoaded++;
                                if (imagesLoaded === totalImages) {
                                    checkIfAllLoaded();
                                }
                            });
                        }
                    });

                    // Jika semua gambar sudah loaded dari cache
                    if (imagesLoaded === totalImages) {
                        checkIfAllLoaded();
                    }
                }
            });

            // Event ketika semua resource (termasuk CSS, font, dll) selesai dimuat
            window.addEventListener('load', function() {
                checkIfAllLoaded();
            });
        }

        // Fallback: sembunyikan loader setelah 5 detik untuk mencegah stuck
        setTimeout(function() {
            hideLoader();
        }, 5000);
    </script>

    @include('layouts.script')
</body>

</html>
