@extends('layouts.main')
@section('content')
    <section class="px-4">
        <div class="col-md-12 mb-5">
            <form class="forms-sample" method="post" action="{{ route('archieve.mail.incoming-mail.store') }}"
                enctype="multipart/form-data">
                @csrf
                <div class="card">
                    <div class="card-header p-3 bg-white">
                        <h4 class="card-title my-auto">Tambah Surat Masuk</h4>
                    </div>
                    <div class="card-body p-3">
                        <div class="mb-3">
                            <label for="number">Nomor <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="number" name="number"
                                placeholder="Nomor Surat" value="{{ old('number') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="name">Judul Surat <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="Judul Surat" value="{{ old('name') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="date">Tanggal <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="date" name="date" placeholder="Tanggal"
                                value="{{ old('date') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="classification">Klasifikasi <span class="text-danger">*</span></label>
                            <select class="form-control" id="classification" name="classification">
                                <option disabled hidden selected>Pilih Klasifikasi</option>
                                @foreach ($classifications as $classification)
                                    <option value="{{ $classification->id }}"
                                        @if (!is_null(old('classification')) && old('classification') == $classification->id) selected @endif>
                                        {{ $classification->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="type_mail_content">Jenis Isi Surat <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <select class="form-control" id="type_mail_content" name="type_mail_content">
                                    <option disabled hidden selected>Pilih Jenis Isi Surat</option>
                                    @foreach ($type_mail_contents as $type_mail_content)
                                        <option value="{{ $type_mail_content->id }}"
                                            @if (!is_null(old('type_mail_content')) && old('type_mail_content') == $type_mail_content->id) selected @endif>
                                            {{ $type_mail_content->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <a class="btn btn-primary" title="Tambah Jenis" data-bs-toggle="modal"
                                    data-bs-target="#addTypeMailContent">
                                    <i class="fas fa-plus mr-1"></i> Tambah Jenis
                                </a>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="level">Tingkat Instansi Polri</label>
                            <div class="input-group">
                                <select class="form-control" id="level" name="level">
                                    <option disabled hidden selected>Pilih Tingkatan</option>
                                    @foreach ($levels as $level)
                                        <option value="{{ $level['level'] }}"
                                            @if (!is_null(old('level')) && old('level') == $level['level']) selected @endif>
                                            {{ $level['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                                <a class="btn btn-warning" onclick="resetLevel()" title="Reset">
                                    <i class="fas fa-undo me-1"></i> Reset
                                </a>
                            </div>
                            <p class="text-danger py-1">* Diisi Jika Bersumber Dari Instansi Polri</p>
                        </div>
                        <div class="institution_form">
                        </div>
                        <div class="mb-3">
                            <label for="attachment">Lampiran <span class="text-danger">*</span></label>
                            <input type="file" class="form-control" name="attachment" id="documentInput"
                                accept=".pdf,.doc,.docx,.txt,.xls,.xlsx" multiple="true" required>
                            <p class="text-danger py-1">* .pdf .docx .xlsx .pptx (Max 10 MB)</p>
                            <iframe id="documentPreview" class="w-100 mt-3 d-none" style="height: 600px;"></iframe>
                        </div>
                        <div class="mb-3">
                            <label for="description">Deskripsi</label>
                            <textarea class="form-control" name="description" id="description" cols="10" rows="3"
                                placeholder="Deskripsi">{{ old('description') }}</textarea>
                        </div>
                    </div>
                    <div class="card-footer bg-white">
                        <div class="text-end my-1">
                            <a href="{{ route('archieve.mail.incoming-mail.index') }}" class="btn btn-sm btn-danger">
                                <i class="bi bi-arrow-left"></i>
                                Kembali
                            </a>
                            <button type="submit" class="btn btn-sm btn-primary ms-2">
                                Simpan
                                <i class="bi bi-check"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
    @push('js-bottom')
        @include('includes.global.type_mail_content_modal')
        @include('includes.global.institution_modal')
        @include('js.archieve.mail.incoming_mail.script')
        <script>

            $('#level').on('change', function() {
                $('.institution_form').html('');

                if ($(this).find(":selected").val() != undefined) {
                    $.ajax({
                        url: '{{ url('institution/get-institution') }}/' + $(this).find(":selected").val() +
                            '/' +
                            1,
                        type: 'GET',
                        cache: false,
                        success: function(data) {
                            $('.institution_form').html(data);
                        },
                        error: function(xhr, error, code) {
                            alertError(error);
                        }
                    });
                }
            });
        </script>
    @endpush
@endsection
