@extends('layouts.main')
@section('content')
    <div class="section py-5 px-4 mb-5">
        <div class="col-md-12 mb-3">
            <div class="card">
                <div class="card-header p-3 bg-white d-flex justify-content-between">
                    <h4 class="card-title my-auto">Daftar Album Dokumentasi</h4>
                    @if (!Illuminate\Support\Facades\Auth::user()->hasRole('kasubdit'))
                        <div class="p-2">
                            <a data-bs-toggle="modal" data-bs-target="#addAlbum" data class="btn btn-sm btn-primary">
                                <i class="bi bi-plus me-1"></i>
                                Tambah Album
                            </a>
                        </div>
                    @endif
                </div>
                <div class="card-body p-3">
                    <div class="row g-4 my-5" id="waiting-container">
                        <div class="col-md-12">
                            <h5 class="text-center">
                                Harap Tunggu...
                            </h5>
                        </div>
                    </div>
                    <div id="all_album">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addAlbum" tabindex="-1" style="z-index: 1051 !important;">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form method="post" action="{{ route('archieve.album.store') }}" class="forms-sample">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title" id="exampleModalLongTitle">Tambah Album</h4>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name">Nama <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="Nama Album" value="{{ old('name') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="date">Tanggal <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="date" name="date" placeholder="Tanggal"
                                value="{{ old('date') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="description">Deskripsi</label>
                            <textarea class="form-control" name="description" id="description" cols="10" rows="3"
                                placeholder="Deskripsi">{{ old('description') }}</textarea>
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
    <div class="modal fade" id="editAlbum" tabindex="-1" style="z-index: 1051 !important;">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form method="post" action="" class="form-edit">
                    @csrf
                    @method('patch')
                    <input type="hidden" class="form-control" id="url_edit" value="{{ url('archieve/album/') }}">
                    <div class="modal-header">
                        <h4 class="modal-title" id="exampleModalLongTitle">Ubah Album</h4>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name">Nama <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name_edit" name="name"
                                placeholder="Nama Album" required>
                        </div>
                        <div class="mb-3">
                            <label for="date">Tanggal <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="date_edit" name="date"
                                placeholder="Tanggal"required>
                        </div>
                        <div class="mb-3">
                            <label for="description">Deskripsi</label>
                            <textarea class="form-control" name="description" id="description_edit" cols="10" rows="3"
                                placeholder="Deskripsi"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-bs-dismiss="modal" class="btn btn-sm btn-danger">
                            <i class="bi bi-arrow-left"></i>
                            Tutup
                        </button>
                        <button type="submit" class="btn btn-sm btn-primary mx-2">
                            Simpan
                            <i class="bi bi-check"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="infoAlbum" tabindex="-1" style="z-index: 1051 !important;">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLongTitle">Informasi Album</h4>
                </div>
                <div class="modal-body">
                    <div class="mb-3 row">
                        <label class="col-sm-12 col-form-label">Nama Album</label>
                        <div class="col-sm-12 col-form-label">
                            <span id="name_info"></span>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-12 col-form-label">Tanggal</label>
                        <div class="col-sm-12 col-form-label">
                            <span id="date_format_info"></span>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-12 col-form-label">Deskripsi</label>
                        <div class="col-sm-12 col-form-label">
                            <span id="description_info"></span>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-12 col-form-label">Diperbarui Oleh</label>
                        <div class="col-sm-12 col-form-label">
                            <span id="updated_by_info"></span>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-12 col-form-label">Diperbarui Pada</label>
                        <div class="col-sm-12 col-form-label">
                            <span id="updated_at_info"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-bs-dismiss="modal" class="btn btn-sm btn-danger mx-2">
                        <i class="bi bi-arrow-left"></i>
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
    @push('js-bottom')
        @include('js.archieve.album.script')
        <script>
            getAlbum();
        </script>
    @endpush
@endsection
