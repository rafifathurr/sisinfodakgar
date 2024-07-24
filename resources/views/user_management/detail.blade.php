@extends('layouts.main')
@section('content')
    <div class="section py-5 px-4 mb-5">
        <div class="col-md-12 mb-3">
            <div class="card">
                <div class="card-header p-3 bg-white">
                    <h4 class="card-title my-auto">Edit User</h4>
                </div>
                <div class="card-body p-3">
                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label">Nama Lengkap</label>
                        <div class="col-sm-9 col-form-label">
                            {{ $user->name }}
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label">Username</label>
                        <div class="col-sm-9 col-form-label">
                            {{ $user->username }}
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label">Email</label>
                        <div class="col-sm-9 col-form-label">
                            {{ $user->email }}
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label">Role</label>
                        <div class="col-sm-9 col-form-label">
                            {{ $user_role }}
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label">Diperbarui Pada</label>
                        <div class="col-sm-9 col-form-label">
                            {{ date('d F Y H:i:s', strtotime($user->updated_at)) }}
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white">
                    <div class="text-end my-1">
                        <a href="{{ route('user-management.index') }}" class="btn btn-sm btn-danger">
                            <i class="bi bi-arrow-left"></i>
                            Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
