@extends('layouts.main')
@section('content')
    <div class="section py-5 px-4 mb-5">
        <div class="col-md-12 mb-3">
            <form class="forms-sample" method="post" action="{{ route('master.institution.store') }}">
                @csrf
                <div class="card">
                    <div class="card-header p-3 bg-white">
                        <h4 class="card-title my-auto">Tambah Instansi Polri</h4>
                    </div>
                    <div class="card-body p-3">
                        <div class="mb-3">
                            <label for="level">Tingkat Instansi Polri <span class="text-danger">*</span></label>
                            <select class="form-control" id="level" name="level" required>
                                <option disabled hidden selected>Pilih Tingkatan</option>
                                @foreach ($levels as $level)
                                    <option value="{{ $level['level'] }}" @if (!is_null(old('level')) && old('level') == $level['level']) selected @endif>
                                        {{ $level['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="institution_form"></div>
                        <div class="mb-3">
                            <label for="name">Nama Instansi <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="Nama Instansi Polri" value="{{ old('name') }}" required>
                        </div>
                    </div>
                    <div class="card-footer bg-white">
                        <div class="text-end my-1">
                            <a href="{{ route('master.institution.index') }}" class="btn btn-sm btn-danger">
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
        @include('js.master.institution.script')
        <script>
            $('#level').on('change', function() {
                $('.institution_form').html('');

                let level = $(this).find(":selected").val() - 1;
                if ($(this).find(":selected").val() == 1 || $(this).find(":selected").val() == 2) {
                    level = 0;
                }
                $.ajax({
                    url: '{{ url('institution/get-institution') }}/' + level + '/0',
                    type: 'GET',
                    cache: false,
                    success: function(data) {
                        $('.institution_form').html(data);
                        $('.select2').select2();
                    },
                    error: function(xhr, error, code) {
                        alertError(error);
                    }
                });
            });
        </script>
    @endpush
@endsection
