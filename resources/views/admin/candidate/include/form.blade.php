<div class="row">
    <div class="col-md-6 col-sm-12">
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
            <label class="form-label">Pemilihan</label>
            <select name="type" class="form-select @error('type')
            invalid
        @enderror">
                <option disabled selected>--Pilih Pemilihan--</option>
                <option value="1"
                    {{ isset($candidate) && $candidate->type == 1 ? 'selected' : (old('type') == '1' ? 'selected' : '') }}>
                    Gubernur</option>
                <option value="2"
                    {{ isset($candidate) && $candidate->type == 2 ? 'selected' : (old('type') == '2' ? 'selected' : '') }}>
                    Kepala Daerah</option>
            </select>
        </div>
        @error('type')
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
    <div class="col-md-6 col-sm-12">
        <div class="mb-3">
            <label class="form-label">Partai Pengusung</label>
            <input type="text" name="candidate_pair"
                class="form-control @error('candidate_pair')
            invalid
        @enderror"
                placeholder="Masukan Partai Pengusung"
                value="{{ isset($candidate) ? $candidate->candidate_pair : old('candidate_pair') }}">
            @error('candidate_pair')
                <div class="small text-danger">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>
    @isset($candidate)
        <div class="mb-3 col-md-12">
            <div class="row">
                <div class="col-md-3">
                    @if ($candidate->photo == null)
                        <label for="thumbnail" class="form-label">Gambar Lama</label>
                        <img src="https://via.placeholder.com/350?text=No+Image+Avaiable" alt="Thumbnail"
                            class="rounded mb-2 mt-2" alt="Thumbnail" width="200" height="150"
                            style="object-fit: cover">
                    @else
                        <label for="thumbnail" class="form-label">Gambar Lama</label>
                        <img src="{{ asset('storage/upload/paslon/' . $candidate->photo) }}" alt="Thumbnail"
                            class="rounded mb-2 mt-2" width="200" height="150" style="object-fit: cover">
                    @endif
                </div>
                <div class="col-md-9">
                    <div class="form-group ms-3">
                        <label for="photo" class="form-label">Foto Paslon</label>
                        <input class="form-control  @error('photo') is-invalid @enderror" type="file" name="photo" />
                        @error('photo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="col-md-6 col-sm-12">
            <div class="mb-3">
                <label class="form-label">Foto Paslon</label>
                <input type="file" name="photo"
                    class="form-control @error('photo')
            invalid
        @enderror">
                @error('photo')
                    <div class="small text-danger">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
    @endisset

</div>
