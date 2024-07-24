<!-- Vendor JS Files -->
<script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('vendor/php-email-form/validate.js') }}"></script>
<script src="{{ asset('vendor/aos/aos.js') }}"></script>
<script src="{{ asset('vendor/glightbox/js/glightbox.min.js') }}"></script>
<script src="{{ asset('vendor/purecounter/purecounter_vanilla.js') }}"></script>
<script src="{{ asset('vendor/imagesloaded/imagesloaded.pkgd.min.js') }}"></script>
<script src="{{ asset('vendor/isotope-layout/isotope.pkgd.min.js') }}"></script>
<script src="{{ asset('vendor/swiper/swiper-bundle.min.js') }}"></script>
<script src="{{ asset('lib/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ asset('lib/datatables.net/jquery.dataTables.js') }}"></script>
<script src="{{ asset('lib/datatables.net-bs4/dataTables.bootstrap4.js') }}"></script>

<!-- Main JS File -->
<script src="{{ asset('js/main.js') }}"></script>

@include('js.alert.script')
@stack('js-bottom')
<script>
    function resetLevel() {
        $('#level').val('').trigger('change');
    }
</script>
