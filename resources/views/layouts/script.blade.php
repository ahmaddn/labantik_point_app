<script src='{{ asset('assets/libs/choices.js/public/assets/scripts/choices.min.js') }}'></script>
<script src="{{ asset('assets/libs/%40popperjs/core/umd/popper.min.js') }}"></script>
<script src="{{ asset('assets/libs/tippy.js/tippy-bundle.umd.min.js') }}"></script>
<script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ asset('assets/libs/prismjs/prism.js') }}"></script>
<script src="{{ asset('assets/libs/lucide/umd/lucide.js') }}"></script>
<script src="{{ asset('assets/js/starcode.bundle.js') }}"></script>

<script src="{{ asset('assets/js/datatables/jquery-3.7.0.js') }}"></script>
<script src="{{ asset('assets/js/datatables/data-tables.min.js') }}"></script>
<script src="{{ asset('assets/js/datatables/data-tables.tailwindcss.min.js') }}"></script>
<!--buttons dataTables-->
<script src="{{ asset('assets/js/datatables/datatables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/js/datatables/buttons.html5.min.js') }}"></script>

<script src="{{ asset('assets/js/datatables/datatables.init.js') }}"></script>


<!-- list js-->
<script src="{{ asset('assets/libs/list.js/list.min.js') }}"></script>
<script src="{{ asset('assets/libs/list.pagination.js/list.pagination.min.js') }}"></script>

<script src="{{ asset('assets/js/pages/apps-ecommerce-product.init.js') }}"></script>

<!-- App js -->
<script src="{{ asset('assets/js/app.js') }}"></script>

<!-- SweetAlert2 -->
<script src="{{ asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
<script>
    function confirmSubmit(e, button, message) {
        e.preventDefault();
        Swal.fire({
            title: "Konfirmasi",
            text: message,
            icon: "warning",
            showCancelButton: true,
            customClass: {
                confirmButton: 'text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20',
                cancelButton: 'text-white btn bg-red-500 border-red-500 hover:text-white hover:bg-red-600 hover:border-red-600 focus:text-white focus:bg-red-600 focus:border-red-600 focus:ring focus:ring-red-100 active:text-white active:bg-red-600 active:border-red-600 active:ring active:ring-red-100 dark:ring-red-400/20 ml-2'
            },
            buttonsStyling: false,
            confirmButtonText: "Ya, Lanjutkan!",
            cancelButtonText: "Batal",
            showCloseButton: true
        }).then((result) => {
            if (result.isConfirmed) {
                const form = button.closest('form');
                if(button.name) {
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = button.name;
                    hiddenInput.value = button.value;
                    form.appendChild(hiddenInput);
                }
                form.submit();
            }
        });
    }
</script>
