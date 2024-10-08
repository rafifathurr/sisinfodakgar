<div class="mb-3">
    <label for="institution">Instansi Polri <span class="text-danger">*</span></label>
    <select class="form-control select2" id="institution_form" name="institution" required>
        <option value="" hidden></option>
        @foreach ($institutions as $institution)
            <option value="{{ $institution->id }}" @if (!is_null(old('institution')) && old('institution') == $institution->id) selected @endif>
                {{ $institution->name }}
            </option>
        @endforeach
    </select>
</div>
<script>
    $('#institution_form').select2({
        placeholder: "Pilih Instansi Polri",
    });
</script>
