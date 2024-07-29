<script>
    $(".forms-sample").submit(function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Apakah Anda Yakin Simpan Data?',
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
                alertProcess();
                $('.forms-sample').unbind('submit').submit();
            }
        })
    });

    $(".forms-edit").submit(function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Apakah Anda Yakin Simpan Data?',
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
                alertProcess();
                $('.forms-edit').unbind('submit').submit();
            }
        })
    });

    $(".forms-upload").submit(function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Apakah Anda Yakin Upload Foto?',
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
                alertProcess();
                $('.forms-upload').unbind('submit').submit();
            }
        })
    });

    function getRecord(id, category) {
        alertProcess();
        $.get('{{ url('archieve/album/show-json') }}/' + id, {}).done(function(data) {
            Swal.close();
            let url_update = $('#url_edit').val() + '/' + id;
            $('.form-edit').attr('action', url_update);
            Object.entries(data).forEach(([key, value], index) => {
                if (category == 'edit') {
                    $('#' + key + '_' + category).val(value);
                } else {
                    $('#' + key + '_' + category).html(value);
                }
            });
        }).fail(function(xhr, status, error) {
            alertError(error);
        });
    }

    function getAlbum(page) {
        // let query = $('#search_keyword').val();
        if (page != undefined) {
            $.get("{{ route('archieve.album.getAlbum') }}", {
                page: page,
                query: null
            }).done(function(data) {
                $('#waiting-container').removeClass('d-block');
                $('#waiting-container').addClass('d-none');
                $('#all_album').html(data);
            }).fail(function(xhr, status, error) {
                alertError(error);
            });
        } else {
            $.get("{{ route('archieve.album.getAlbum') }}", {
                query: null
            }).done(function(data) {
                $('#waiting-container').removeClass('d-block');
                $('#waiting-container').addClass('d-none');
                $('#all_album').html(data);
            }).fail(function(xhr, status, error) {
                alertError(error);
            });
        }
    }

    function openRename(index) {
        if ($('#file_name_' + index)[0].className == 'form-control me-4 d-none') {
            $('#file_name_' + index).removeClass('d-none');
            $('#file_name_' + index).addClass('d-block');
            $('#file_name_view_' + index).addClass('d-none');
        }
    }

    function renameFile(index) {
        if (event.key === 'Enter' && $('#file_name_' + index)[0].className == 'form-control me-4 d-block') {

            let token = $('meta[name="csrf-token"]').attr('content');

            let file_name_form = $("input[name='file_name_form[]']")
                .map(function() {
                    return $(this).val();
                }).get();

            $.ajax({
                url: '{{ url('archieve/album/change-image') }}/' + $('#album').val(),
                type: 'PUT',
                data: {
                    _token: token,
                    file_name_form: file_name_form
                },
                success: function(data) {
                    location.reload();
                },
                error: function(xhr, error, code) {
                    alertError(error);
                }
            });

        }
    }

    function destroyFile(index) {
        let token = $('meta[name="csrf-token"]').attr('content');

        Swal.fire({
            title: 'Apakah Anda Yakin Hapus Data?',
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
                    url: '{{ url('archieve/album/destroy-image') }}/' + $('#album').val(),
                    type: 'PUT',
                    data: {
                        _token: token,
                        file_name: $('#file_name_'+index).val()
                    },
                    success: function(data) {
                        location.reload();
                    },
                    error: function(xhr, error, code) {
                        alertError(error);
                    }
                });
            }
        })

    }

    function destroyRecord(id) {
        let token = $('meta[name="csrf-token"]').attr('content');

        Swal.fire({
            title: 'Apakah Anda Yakin Hapus Data?',
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
                alertProcess();
                $.ajax({
                    url: '{{ url('archieve/album') }}/' + id,
                    type: 'DELETE',
                    cache: false,
                    data: {
                        _token: token
                    },
                    success: function(data) {
                        location.reload();
                    },
                    error: function(xhr, error, code) {
                        alertError(error);
                    }
                });
            }
        })
    }
</script>
