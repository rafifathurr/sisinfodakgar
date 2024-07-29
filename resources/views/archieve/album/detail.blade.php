@extends('layouts.main')
@section('content')
    <div class="section py-5 px-4 mb-5">
        <div class="col-md-12 mb-3">
            <div class="card">
                <div class="card-header p-3 bg-white">
                    <h4 class="card-title my-auto">Album {{ $album->name }}</h4>
                </div>
                <input type="hidden" id="album" value="{{ $album->id }}">
                <div class="card-body p-3">
                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label">Nama</label>
                        <div class="col-sm-9 col-form-label">
                            {{ $album->name }}
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label">Tanggal</label>
                        <div class="col-sm-9 col-form-label">
                            {{ date('d F Y', strtotime($album->date)) }}
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label">Deskripsi</label>
                        <div class="col-sm-9 col-form-label">
                            {!! $album->description ?? '-' !!}
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label">Diperbarui Oleh</label>
                        <div class="col-sm-9 col-form-label">
                            <b>{{ $album->updatedBy->name }}</b>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label">Diperbarui Pada</label>
                        <div class="col-sm-9 col-form-label">
                            <b>{{ date('d F Y H:i:s', strtotime($album->updated_at)) }}</b>
                        </div>
                    </div>
                    @if (!is_null($album->attachment))
                        <div class="col-md-12 text-center">
                            <div class="card">
                                <div class="card-header text-start">
                                    <h5>Daftar Foto</h5>
                                </div>
                                <div class="card-body p-3">
                                    <div class="d-flex">
                                        <div class="p-1">
                                            <a class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#addImage">
                                                <i class="bi bi-plus"></i>
                                                Tambah Foto
                                            </a>
                                        </div>
                                    </div>
                                    <div class="row justify-content-start mt-3">
                                        @foreach (json_decode($album->attachment) as $index => $attachment)
                                            <div class="col-md-3 mb-3">
                                                <div class="card mb-4 box-shadow">
                                                    <div style="width:100%;overflow:hidden">
                                                        <a href="{{ asset($attachment->attachment) }}" class="text-black">
                                                            <img src="{{ asset($attachment->attachment) }}"
                                                                onerror="this.onerror=null;this.src='{{ asset('img/image-not-found.jpg') }}'"
                                                                width="100%" style="height:350px;object-fit: cover;" />
                                                        </a>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="d-flex justify-content-between" class="text-start">
                                                            <div style="flex: 1; overflow: hidden;" class="text-start me-4"
                                                                id="file_name_view_{{ $index }}">
                                                                <p
                                                                    class="card-text fw-bold my-1 text-truncate d-inline-block">
                                                                    {{ $attachment->file_name }}
                                                                </p>
                                                            </div>
                                                            <input type="text" class="form-control me-4 d-none"
                                                                name="file_name_form[]" id="file_name_{{ $index }}"
                                                                value="{{ $attachment->file_name }}"
                                                                onkeydown="renameFile({{ $index }})">
                                                            <div class="dropdown text-black">
                                                                <a class="text-black" id="dropdownMenuButton"
                                                                    data-bs-toggle="dropdown"><i
                                                                        class="bi bi-three-dots"></i></a>
                                                                <div class="dropdown-menu"
                                                                    aria-labelledby="dropdownMenuLink">
                                                                    <a class="dropdown-item"
                                                                        onclick="openRename({{ $index }})">Ubah
                                                                        Nama</a>
                                                                    <a class="dropdown-item" onclick="destroyFile({{ $index }})">Hapus</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="text-end mt-4">
                                                            <small
                                                                class="text-muted">{{ date('d F Y H:i:s', strtotime($album->updated_at)) }}</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="row justify-content-center mt-5">
                            <div class="col-md-12 text-center">
                                <div class="card">
                                    <div class="card-header text-left">
                                        <h5>Daftar Foto</h5>
                                    </div>
                                    <div class="card-body p-5">
                                        <a data-bs-toggle="modal" data-bs-target="#addImage" class="text-black">
                                            <h1><i class="bi bi-image display-1"></i></h1>
                                            <h5>Tidak Terdapat Foto</h5>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="card-footer bg-white">
                    <div class="text-end my-1">
                        <a href="{{ route('archieve.album.index') }}" class="btn btn-sm btn-danger">
                            <i class="bi bi-arrow-left"></i>
                            Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addImage" tabindex="-1" style="z-index: 1051 !important;">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form method="post" action="{{ route('archieve.album.uploadImage', ['id' => $album->id]) }}"
                    class="forms-upload" enctype="multipart/form-data">
                    @csrf
                    @method('patch')
                    <div class="modal-header">
                        <h4 class="modal-title" id="exampleModalLongTitle">Tambah Foto</h4>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="date">Upload Foto <span class="text-danger">*</span></label>
                            <input type="file" class="form-control" name="attachment[]" id="documentInput"
                                accept="image/*" multiple="true" multiple="true" required>
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
    @push('js-bottom')
        @include('js.archieve.album.script')
    @endpush
@endsection
