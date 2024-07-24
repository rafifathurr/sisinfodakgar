@extends('layouts.main')
@section('content')
    <div class="section py-5 px-4 mb-5">
        <div class="col-md-12 mb-3">
            <form class="forms-sample" method="post" action="{{ route('user-management.store') }}">
                @csrf
                <div class="card">
                    <div class="card-header p-3 bg-white">
                        <h4 class="card-title my-auto">Tambah User</h4>
                    </div>
                    <div class="card-body p-3">
                        <div class="mb-3">
                            <label for="name">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="Nama Lengkap" value="{{ old('name') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="username">Username <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="Username"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="email">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Email"
                                value="{{ old('email') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="roles">Role <span class="text-danger">*</span></label>
                            <select class="form-control" id="roles" name="roles" required>
                                <option disabled hidden selected>Pilih Role</option>
                                @foreach ($roles as $role)
                                    @if (!is_null(old('roles')) && old('roles') == $role->name)
                                        <option value="{{ $role->name }}" selected>{{ $role->name }}</option>
                                    @else
                                        <option value="{{ $role->name }}">{{ $role->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="password">Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="Password" required>
                        </div>
                        <div class="mb-3">
                            <label for="re_password">Ulangi Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="re_password" name="re_password"
                                placeholder="Ulangi Password" required>
                        </div>
                    </div>
                    <div class="card-footer bg-white">
                        <div class="text-end my-1">
                            <a href="{{ route('user-management.index') }}" class="btn btn-sm btn-danger">
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
    </div>
    @push('js-bottom')
        @include('js.user_management.script')
    @endpush
@endsection
