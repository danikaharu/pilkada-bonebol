<div class="mb-3">
    <label class="form-label">Nama Kecamatan</label>
    <input type="text" name="name" class="form-control @error('name')
        invalid
    @enderror"
        placeholder="Masukan Nama Kecamatan" value="{{ isset($subdistrict) ? $subdistrict->name : old('name') }}" />
    @error('name')
        <div class="small text-danger">
            {{ $message }}
        </div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Dapil</label>
    <select name="electoral_district_id" id="defaultSelect"
        class="form-select @error('electoral_district_id') is-invalid @enderror">
        <option value="" selected>-- {{ __('Pilih Dapil') }} --</option>
        @foreach ($electoral_districts as $electoral_district)
            <option value="{{ $electoral_district->id }}"
                {{ isset($subdistrict) && $subdistrict->electoral_district_id == $electoral_district->id ? 'selected' : (old('electoral_district_id') == $electoral_district->id ? 'selected' : '') }}>
                {{ $electoral_district->name }}
            </option>
        @endforeach
    </select>
    @error('electoral_district_id')
        <div class="small text-danger">
            {{ $message }}
        </div>
    @enderror
</div>
