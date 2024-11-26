@extends('layouts.admin.index')

@section('title', 'Edit Suara')

@push('style')
    <style>
        #form {
            display: none;
        }
    </style>
@endpush

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h5 class="fw-bold py-3 mb-2"><span class="text-muted fw-light">Beranda / </span> Edit Suara</h5>
        <div class="row">
            <div class="col-lg-12 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <h6 class="card-subtitle text-muted">Berikut form Edit Perolehan Suara</h6>
                        @if ($errors->any())
                            {!! implode('', $errors->all('<div>:message</div>')) !!}
                        @endif
                        <div class="my-4">
                            <div class="row">
                                <div class="col-lg-12 col-sm-12">
                                    <form class="my-4" action="{{ route('admin.polling.update', $polling->id) }}"
                                        method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" id="selectedTps" name="polling_station_id"
                                            value="{{ $polling->polling_station_id }}">
                                        <input type="hidden" id="selectedPemilihan" name="type"
                                            value="{{ $polling->type }}">

                                        @php
                                            // Decode the candidate_votes JSON to an array if it exists
                                            $votes = isset($polling)
                                                ? json_decode($polling->candidate_votes, true)
                                                : old('candidate_votes', []);
                                        @endphp

                                        @foreach ($votes as $index => $vote)
                                            <div class="mb-3">
                                                <label class="form-label">Suara Paslon {{ $index + 1 }}</label>
                                                <input type="number" name="candidate_votes[]" class="form-control"
                                                    value="{{ $vote }}" placeholder="Masukan jumlah Suara" />
                                                @error('candidate_votes')
                                                    <div class="small text-danger">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        @endforeach

                                        <div class="mb-3">
                                            <label class="form-label">Suara Tidak Sah / Suara Rusak</label>
                                            <input type="number" name="invalid_votes"
                                                class="form-control @error('invalid_votes')
                                            is-invalid
                                        @enderror"
                                                placeholder="Masukan Jumlah Suara"
                                                value="{{ isset($polling) ? $polling->invalid_votes : old('invalid_votes') }}">
                                            @error('invalid_votes')
                                                <div class="small text-danger">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="c1">{{ __('C1') }}</label>
                                                <input type="file" name="c1[]"
                                                    class="form-control @error('c1') is-invalid @enderror" multiple>

                                                @error('c1')
                                                    <div class="invalid-feedback">
                                                        <i class="bx bx-radio-circle"></i>
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <button class="btn btn-secondary"><i class='bx bx-reset'></i> Reset</button>
                                        <button id="storeData" type="submit" class="btn btn-primary"><i
                                                class='bx bxs-save'></i>
                                            Simpan</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
