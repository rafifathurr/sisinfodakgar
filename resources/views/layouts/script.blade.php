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

    $("#form_modal_institution").submit(function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Apakah Anda Yakin Tambah Instansi Data?',
            icon: 'question',
            showCancelButton: true,
            allowOutsideClick: false,
            customClass: {
                confirmButton: 'btn btn-primary me-2 mb-3',
                cancelButton: 'btn btn-danger mb-3',
            },
            buttonsStyling: false,
            confirmButtonText: 'Ya',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ url('master/institution') }}",
                    type: 'POST',
                    cache: false,
                    data: $("#form_modal_institution").serialize(),
                    success: function(data) {
                        $('#level').val($('#level').val()).trigger('change');
                        $('#form_modal_institution #level_select').val('').trigger(
                        'change');
                        $('#form_modal_institution #name').val('');

                        $('#addInstitution').modal('hide');
                        alertSuccess("Berhasil Menambahkan Instansi");
                    },
                    error: function(xhr, error, code) {
                        alertError(error);
                    }
                });
            }
        })
    });

    $("#form_modal_type_mail_content").submit(function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Apakah Anda Yakin Tambah Jenis Isi Surat?',
            icon: 'question',
            showCancelButton: true,
            allowOutsideClick: false,
            customClass: {
                confirmButton: 'btn btn-primary me-2 mb-3',
                cancelButton: 'btn btn-danger mb-3',
            },
            buttonsStyling: false,
            confirmButtonText: 'Ya',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ url('master/type-mail-content') }}",
                    type: 'POST',
                    cache: false,
                    data: $("#form_modal_type_mail_content").serialize(),
                    success: function(data) {
                        $('#addTypeMailContent').modal('hide');
                        $('#type_mail_content').empty();

                        $('#type_mail_content').append($('<option>', {
                            value: '',
                            text: 'Pilih Jenis Isi Surat'
                        }).attr('hidden', true));

                        data.data.forEach(function(item, index) {
                            $('#type_mail_content').append($('<option>', {
                                value: item.id,
                                text: item.name
                            }));
                        });

                        $('#type_mail_content').val('').trigger('change');
                        $('#form_modal_type_mail_content #name').val('');

                        alertSuccess("Berhasil Menambahkan Jenis Isi Surat");
                    },
                    error: function(xhr, error, code) {
                        console.log(xhr, error, code);

                        alertError(error);
                    }
                });
            }
        })
    });

    $('#documentInput').on('change', function(event) {
        var file = event.target.files[0];
        if (file.size <= 10000000) {
            if (file.type === "application/pdf") {
                var fileURL = URL.createObjectURL(file);
                $('#documentPreview').attr('src', fileURL);
                $('#documentPreview').removeClass('d-none');
            } else {
                $('#documentPreview').addClass('d-none');
                $('#documentPreview').attr('src', '');
            }
        } else {
            $('#documentPreview').addClass('d-none');
            $('#documentPreview').attr('src', '');
            $('#documentInput').val('');
            alertError('Ukuran File Lebih Dari 10MB');
        }
    });
</script>
