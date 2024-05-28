<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="mb-3">
            <label class="form-label">Nomor Urut</label>
            <input type="text" name="number"
                class="form-control @error('number')
            invalid
        @enderror"
                placeholder="Masukan Nomor Paslon" value="{{ isset($candidate) ? $candidate->number : old('number') }}">
        </div>
        @error('number')
            <div class="small text-danger">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="col-md-6 col-sm-12">
        <div class="mb-3">
            <label class="form-label">Nama Calon Kepala Daerah</label>
            <input type="text" name="regional_head"
                class="form-control @error('regional_head')
            invalid
        @enderror"
                placeholder="Masukan Nama Calon Kepala Daerah"
                value="{{ isset($candidate) ? $candidate->regional_head : old('regional_head') }}">
            @error('regional_head')
                <div class="small text-danger">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>
    <div class="col-md-6 col-sm-12">
        <div class="mb-3">
            <label class="form-label">Nama Calon Wakil Kepala Daerah</label>
            <input type="text" name="deputy_head"
                class="form-control @error('deputy_head')
            invalid
        @enderror"
                placeholder="Masukan Nama Calon Wakil Kepala Daerah"
                value="{{ isset($candidate) ? $candidate->deputy_head : old('deputy_head') }}">
            @error('deputy_head')
                <div class="small text-danger">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>
</div>
