@extends('layouts.main')
@section('content')
    <div class="section py-5 px-4 mb-5">
        <div class="col-md-12 mb-3">
            <div class="card">
                <div class="card-header p-3 bg-white d-flex justify-content-between">
                    <h4 class="card-title my-auto">Daftar Surat Keluar</h4>
                    <div class="p-2">
                        <a href="{{ route('archieve.mail.outgoing-mail.create') }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-plus me-1"></i>
                            Tambah Surat Masuk
                        </a>
                    </div>
                </div>
                <div class="card-body p-3">
                    <div class="table-responsive">
                        <input type="hidden" id="datatable-url" value="{{ $dt_route }}">
                        <table class="table table-bordered mg-b-0" id="datatable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nomor</th>
                                    <th>Tanggal</th>
                                    <th>Klasifikasi</th>
                                    <th>Jenis Isi Surat</th>
                                    <th>Instansi</th>
                                    <th>Nama</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('js-bottom')
        @include('js.archieve.mail.outgoing_mail.script')
        <script>
            dataTable();
        </script>
    @endpush
@endsection
