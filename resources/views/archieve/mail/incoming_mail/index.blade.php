@extends('layouts.main')
@section('content')
    <section class="px-4">
        <div class="col-md-12 mb-5">
            <div class="card">
                <div class="card-header p-3 bg-white d-flex justify-content-between">
                    <h4 class="card-title my-auto">Daftar Surat Masuk</h4>
                    @if (!Illuminate\Support\Facades\Auth::user()->hasRole('kasubdit'))
                        <div class="p-2">
                            <a href="{{ route('archieve.mail.incoming-mail.create') }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-plus me-1"></i>
                                Tambah Surat Masuk
                            </a>
                        </div>
                    @endif
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
    </section>
    @push('js-bottom')
        @include('js.archieve.mail.incoming_mail.script')
        <script>
            dataTable();
        </script>
    @endpush
@endsection
