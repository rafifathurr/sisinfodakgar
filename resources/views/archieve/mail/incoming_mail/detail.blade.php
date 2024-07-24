@extends('layouts.main')
@section('content')
    <div class="section py-5 px-4 mb-5">
        <div class="col-md-12 mb-3">
            <div class="card">
                <div class="card-header p-3 bg-white">
                    <h4 class="card-title my-auto">Detail Surat Masuk</h4>
                </div>
                <div class="card-body p-3">
                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label">Nomor</label>
                        <div class="col-sm-9 col-form-label">
                            {{ $incoming_mail->number }}
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label">Judul Surat</label>
                        <div class="col-sm-9 col-form-label">
                            {{ $incoming_mail->name }}
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label">Tanggal</label>
                        <div class="col-sm-9 col-form-label">
                            {{ date('d F Y', strtotime($incoming_mail->date)) }}
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label">Klasifikasi</label>
                        <div class="col-sm-9 col-form-label">
                            {{ $incoming_mail->classification->name }}
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label">Jenis Isi Surat</label>
                        <div class="col-sm-9 col-form-label">
                            {{ $incoming_mail->typeMailContent->name }}
                        </div>
                    </div>
                    @if (is_null($incoming_mail->institution_id))
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label">Instansi</label>
                            <div class="col-sm-9 col-form-label">
                                Eksternal
                            </div>
                        </div>
                    @else
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label">Instansi Polri Utama</label>
                            <div class="col-sm-9 col-form-label">
                                {{ $incoming_mail->institution->parent->name }}
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label">Instansi Polri Wilayah</label>
                            <div class="col-sm-9 col-form-label">
                                {{ $incoming_mail->institution->name }}
                            </div>
                        </div>
                    @endif
                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label">Deskripsi</label>
                        <div class="col-sm-9 col-form-label">
                            {!! $incoming_mail->description ?? '-' !!}
                        </div>
                    </div>
                    {{-- <div class="mb-3">
                        <label>Lampiran</label>
                        <div class="p-1">
                            <div class="row">
                                @foreach (json_decode($incoming_mail->attachment) as $attachment)
                                    <div class="col-sm-3 col-form-label">
                                        <a target="_blank" href="{{ asset($attachment) }}">Lampiran Surat Masuk<i
                                                class="fas fa-download ml-1"></i></a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div> --}}
                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label">Diperbarui Oleh</label>
                        <div class="col-sm-9 col-form-label">
                            <b>{{ $incoming_mail->updatedBy->name }}</b>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label">Diperbarui Pada</label>
                        <div class="col-sm-9 col-form-label">
                            <b>{{ date('d F Y H:i:s', strtotime($incoming_mail->updated_at)) }}</b>
                        </div>
                    </div>
                    @if (explode('.', $incoming_mail->attachment)[count(explode('.', $incoming_mail->attachment)) - 1] == 'pdf')
                        <div class="mb-3">
                            <label class="col-sm-12 col-form-label">Lampiran</label>
                            <div class="col-sm-12 col-form-label">
                                <iframe class="w-100 mt-3" style="height: 600px;"
                                    src="{{ asset($incoming_mail->attachment) }}" width="1000" height="1000"
                                    frameborder="0"></iframe>
                            </div>
                        </div>
                    @else
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label">Lampiran</label>
                            <div class="col-sm-9 col-form-label">
                                <a href="{{ asset($incoming_mail->attachment) }}" class="text-primary" target="_blank"><i class="bi bi-download me-1"></i> Lampiran Surat Masuk</a>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="card-footer bg-white">
                    <div class="text-end my-1">
                        <a href="{{ route('archieve.mail.incoming-mail.index') }}" class="btn btn-sm btn-danger">
                            <i class="bi bi-arrow-left"></i>
                            Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
