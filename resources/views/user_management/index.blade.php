@extends('layouts.main')
@section('content')
    <div class="section py-5 px-4 mb-5">
        <div class="col-md-12 mb-3">
            <div class="card">
                <div class="card-header p-3 bg-white d-flex justify-content-between">
                    <h4 class="card-title my-auto">Manajemen User</h4>
                    <div class="p-2">
                        <a href="{{ route('user-management.create') }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-plus me-1"></i>
                            Tambah User
                        </a>
                    </div>
                </div>
                <div class="card-body p-3">
                    <div class="table-responsive">
                        <input type="hidden" id="datatable-url" value="{{ $dt_route }}">
                        <table class="table table-bordered table-striped dataTable" id="datatable-user">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Lengkap</th>
                                    <th>Role</th>
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
        @include('js.user_management.script')
        <script>
            dataTable();
        </script>
    @endpush
@endsection
