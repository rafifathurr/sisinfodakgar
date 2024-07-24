@extends('layouts.main')
@section('content')
    <div class="section py-5 px-4 mb-5">
        <div class="col-md-12 mb-3">
            <form class="forms-sample" method="post"
                action="{{ route('master.classification.update', ['id' => $classification->id]) }}">
                @csrf
                @method('patch')
                <div class="card">
                    <div class="card-header p-3 bg-white">
                        <h4 class="card-title my-auto">Edit Klasifikasi Surat</h4>
                    </div>
                    <div class="card-body p-3">
                        <div class="mb-3">
                            <label for="name">Nama <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="Nama Klasifikasi" value="{{ $classification->name }}" required>
                        </div>
                    </div>
                    <div class="card-footer bg-white">
                        <div class="text-end my-1">
                            <a href="{{ route('master.classification.index') }}" class="btn btn-sm btn-danger">
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
        @include('js.master.classification.script')
    @endpush
@endsection
