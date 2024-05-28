<div class="mb-3">
    <label class="form-label">Nama Dapil</label>
    <input type="text" name="name" class="form-control @error('name')
        invalid
    @enderror"
        placeholder="Masukan Nama Dapil"value="{{ isset($electoraldistrict) ? $electoraldistrict->name : old('name') }}" />
    @error('name')
        <div class="small text-danger">
            {{ $message }}
        </div>
    @enderror
</div>
