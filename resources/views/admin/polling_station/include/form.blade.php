<div class="mb-3">
    <label class="form-label">Nama TPS</label>
    <input type="text" name="name" class="form-control @error('name')
        invalid
    @enderror"
        placeholder="Masukan Nama TPS" value="{{ isset($pollingstation) ? $pollingstation->name : old('name') }}" />
    @error('name')
        <div class="small text-danger">
            {{ $message }}
        </div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Kelurahan</label>
    <select name="village_id" id="village_id" class="form-select @error('village_id') is-invalid @enderror village">
        <option selected>-- {{ __('Pilih Kelurahan') }} --</option>
        @foreach ($villages as $village)
            <option value="{{ $village->id }}"
                {{ isset($pollingstation) && $pollingstation->village_id == $village->id ? 'selected' : (old('village_id') == $village->id ? 'selected' : '') }}>
                {{ $village->name }}
            </option>
        @endforeach
    </select>
    @error('village_id')
        <div class="small text-danger">
            {{ $message }}
        </div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Jumlah Pemilih Tetap</label>
    <input type="text" name="registered_voters"
        class="form-control @error('registered_voters')
        invalid
    @enderror"
        placeholder="Jumlah Pemilih Tetap"
        value="{{ isset($pollingstation) ? $pollingstation->registered_voters : old('registered_voters') }}" />
    @error('registered_voters')
        <div class="small text-danger">
            {{ $message }}
        </div>
    @enderror
</div>
