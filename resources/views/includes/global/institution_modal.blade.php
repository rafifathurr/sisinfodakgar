<div class="modal fade" id="addInstitution">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form method="post" action="{{ route('master.institution.store') }}">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLongTitle">Tambah Instansi Polri</h4>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <input type="hidden" name="redirect" value="true">
                        <label for="level">Tingkat Instansi Polri <span class="text-danger">*</span></label>
                        <select class="form-control" id="level_select" name="level" required>
                            <option disabled hidden selected>Pilih Tingkatan</option>
                            @foreach ($levels as $level)
                                <option value="{{ $level['level'] }}" @if (!is_null(old('level')) && old('level') == $level['level']) selected @endif>
                                    {{ $level['name'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="institution_form_modal"></div>
                    <div class="mb-3">
                        <label for="name">Nama Instansi <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name"
                            placeholder="Nama Instansi Polri" value="{{ old('name') }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-primary mx-2">
                        Simpan
                        <i class="bi bi-check"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $('#level_select').on('change', function() {
        $('.institution_form_modal').html('');

        let level = $(this).find(":selected").val() - 1;
        if ($(this).find(":selected").val() == 1 || $(this).find(":selected").val() == 2) {
            level = 0;
        }
        $.ajax({
            url: '{{ url('institution/get-institution') }}/' + level + '/0',
            type: 'GET',
            cache: false,
            success: function(data) {
                $('.institution_form_modal').html(data);
            },
            error: function(xhr, error, code) {
                alertError(error);
            }
        });
    });
</script>
