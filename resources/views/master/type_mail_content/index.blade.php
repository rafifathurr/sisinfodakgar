@extends('layouts.main')
@section('content')
    <section class="px-4">
        <div class="col-md-12 mb-5">
            <div class="card">
                <div class="card-header p-3 bg-white d-flex justify-content-between">
                    <h4 class="card-title my-auto">Jenis Isi Surat</h4>
                    <div class="p-2">
                        <a href="{{ route('master.type-mail-content.create') }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-plus me-1"></i>
                            Tambah Jenis Isi Surat
                        </a>
                    </div>
                </div>
                <div class="card-body p-3">
                    <div class="table-responsive">
                        <input type="hidden" id="datatable-url" value="{{ $dt_route }}">
                        <table class="table table-bordered table-striped dataTable" id="datatable">
                            <thead>
                                <tr>
                                    <th>No</th>
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
        @include('js.master.type_mail_content.script')
        <script>
            dataTable();
        </script>
    @endpush
@endsection
