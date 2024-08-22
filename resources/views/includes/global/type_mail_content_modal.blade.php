<div class="modal fade" id="addTypeMailContent">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form method="post" id="form_modal_type_mail_content">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLongTitle">Tambah Jenis Isi Surat</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="hidden" name="redirect" value="true">
                        <label for="name">Nama <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name"
                            placeholder="Nama Jenis Isi Surat" value="{{ old('name') }}" required>
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
