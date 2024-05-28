<div class="mb-3">
    <label class="form-label">Nama Kelurahan</label>
    <input type="text" name="name" class="form-control @error('name')
        invalid
    @enderror"
        placeholder="Masukan Nama Kelurahan" value="{{ isset($village) ? $village->name : old('name') }}" />
    @error('name')
        <div class="small text-danger">
            {{ $message }}
        </div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Kecamatan</label>
    <select name="subdistrict_id" id="defaultSelect" class="form-select @error('subdistrict_id') is-invalid @enderror">
        <option value="" selected>-- {{ __('Pilih Kecamatan') }} --</option>
        @foreach ($subdistricts as $subdistrict)
            <option value="{{ $subdistrict->id }}"
                {{ isset($village) && $village->subdistrict_id == $subdistrict->id ? 'selected' : (old('subdistrict_id') == $subdistrict->id ? 'selected' : '') }}>
                {{ $subdistrict->name }}
            </option>
        @endforeach
    </select>
    @error('subdistrict_id')
        <div class="small text-danger">
            {{ $message }}
        </div>
    @enderror
</div>
