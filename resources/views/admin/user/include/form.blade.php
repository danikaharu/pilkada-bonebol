@push('style')
    <link rel="stylesheet" href="{{ asset('template/vendor/libs/select2/select2.css') }}">
@endpush

<div class="row">
    <div class="col-lg-6 col-md-12 mb-3">
        <label class="form-label">Nama</label>
        <div class="input-group input-group-merge">
            <span class="input-group-text @error('name')
                invalid
            @enderror"><i
                    class="bx bx-user"></i></span>
            <input type="text" class="form-control @error('name')
                invalid
            @enderror"
                placeholder="Nama" value="{{ isset($user) ? $user->name : old('name') }}" name="name">
        </div>
        @error('name')
            <div class="small text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-lg-6 col-md-12 mb-3">
        <label class="form-label">Username</label>
        <div class="input-group input-group-merge">
            <span class="input-group-text @error('username')
                invalid
            @enderror"><i
                    class="bx bx-user"></i></span>
            <input type="text" class="form-control @error('username')
                invalid
            @enderror"
                placeholder="Username" value="{{ isset($user) ? $user->username : old('username') }}" name="username">
        </div>
        @error('username')
            <div class="small text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-lg-6 col-md-12 mb-3">
        <label class="form-label">Password</label>
        <div class="input-group input-group-merge">
            <span class="input-group-text @error('password')
                invalid
            @enderror"><i
                    class="bx bx-key"></i></span>
            <input type="password" class="form-control @error('password')
                invalid
            @enderror"
                placeholder="Password" name="password">
        </div>
        @error('password')
            <div class="small text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-lg-6 col-md-12 mb-3">
        <label class="form-label">{{ __('Konfirmasi Password') }}</label>
        <div class="form-group">
            <input type="password" name="password_confirmation" id="password-confirmation" class="form-control"
                placeholder="{{ __('Password Confirmation') }}" autocomplete="new-password">
        </div>
    </div>

    <div class="col-lg-6 col-md-12 mb-3">
        <label class="form-label">Email</label>
        <div class="input-group input-group-merge">
            <span class="input-group-text @error('email')
                invalid
            @enderror"><i
                    class="bx bx-envelope"></i></span>
            <input type="text" class="form-control @error('email')
                invalid
            @enderror"
                placeholder="Email" value="{{ isset($user) ? $user->email : old('email') }}" name="email">
        </div>
        @error('email')
            <div class="small text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-lg-6 col-md-12 mb-3">
        <label class="form-label">No. Telpon</label>
        <div class="input-group input-group-merge">
            <span class="input-group-text @error('phone_number')
                invalid
            @enderror"><i
                    class="bx bx-envelope"></i></span>
            <input type="text"
                class="form-control @error('phone_number')
                invalid
            @enderror"
                placeholder="Nomor Telepon" value="{{ isset($user) ? $user->phone_number : old('phone_number') }}"
                name="phone_number">
        </div>
        @error('phone_number')
            <div class="small text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-lg-6 col-md-12 mb-3">
        <div class="form-group">
            <label class="form-label">{{ __('Kecamatan') }}</label>
            <select class="form-select @error('subdistrict_id')
            invalid
        @enderror select2"
                name="subdistrict_id" data-allow-clear="true">
                <option selected disabled>-- Pilih Kecamatan --</option>
                @foreach ($subdistricts as $subdistrict)
                    <option value="{{ $subdistrict->id }}"
                        {{ isset($user) && $user->subdistrict_id == $subdistrict->id ? 'selected' : (old('subdistrict_id') == $subdistrict->id ? 'selected' : '') }}>
                        {{ $subdistrict->name }}</option>
                @endforeach
            </select>
            @error('subdistrict_id')
                <div class="small text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
    @empty($user)
        <div class="col-lg-6 col-md-12 mb-3">
            <div class="form-group">
                <label class="form-label" for="role">{{ __('Role') }}</label>
                <select class="form-select @error('role')
                invalid
            @enderror" name="role"
                    id="role">
                    <option selected disabled>-- Pilih role --</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->name }}">{{ $role->name }}</option>
                    @endforeach
                </select>
                @error('role')
                    <div class="small text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
    @endempty

    @isset($user)
        <div class="col-lg-6 col-md-12 mb-3">
            <div class="form-group">
                <label class="form-label" for="role">{{ __('Role') }}</label>
                <select class="form-select @error('role')
                invalid
            @enderror" name="role"
                    id="role">
                    <option disabled>{{ __('-- Pilih role --') }}</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->name }}"
                            {{ $user->getRoleNames()->toArray() !== [] && $user->getRoleNames()[0] == $role->name ? 'selected' : '-' }}>
                            {{ $role->name }}</option>
                    @endforeach
                </select>
                @error('role')
                    <div class="invalid-feedback">
                        <i class="bx bx-radio-circle"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
    @endisset
</div>


@push('script')
    <script src="{{ asset('template/vendor/libs/select2/select2.js') }}"></script>
    <script>
        $(document).ready(function() {
            $(".select2").select2({
                placeholder: "-- Pilih TPS --",
                allowClear: true
            });
        });
    </script>
@endpush
