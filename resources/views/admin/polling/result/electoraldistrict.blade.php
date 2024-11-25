@extends('layouts.admin.index')

@section('title', 'Hasil Perolehan Suara')

@push('style')
    <style>
        #form {
            display: none;
        }
    </style>
@endpush

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h5 class="fw-bold py-3 mb-2"><span class="text-muted fw-light">Beranda /</span> Hasil Perolehan Suara</h5>

        <div class="row">
            <div class="col-lg-12 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">Data Hasil Perolahan Suara</h5>
                        <h6 class="card-subtitle text-muted">Berikut data Hasil Perolehan Suara yang telah dimasukan</h6>
                        <div class="my-4">
                            <form action="{{ route('admin.polling.resultElectoraldistrict') }}">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-6 col-sm-12">
                                        <div class="mb-3">
                                            <label class="form-label">Dapil</label>
                                            <select name="electoraldistrict_id" class="form-select col-12">
                                                <option disabled selected>-- Pilih Dapil --</option>
                                                @foreach ($electoraldistricts as $electoraldistrict)
                                                    <option value="{{ $electoraldistrict->id }}">
                                                        {{ $electoraldistrict->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-sm-12">
                                        <div class="mb-3">
                                            <label class="form-label">Pemilihan</label>
                                            <select name="type" class="form-select col-12">
                                                <option disabled selected>-- Pilih Pemilihan --</option>
                                                <option value="1">Gubernur</option>
                                                <option value="2">Kepala Daerah</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <button type="submit" class="btn col-12 btn-primary">
                                            <i class="bx bx-search-alt"></i> Lihat Hasil Perolehan Suara
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <h6 class="card-subtitle text-muted">Berikut data Hasil Perolehan Suara yang telah dimasukan</h6>
                        <div class="my-4">
                            <div class="row">
                                <div class="col-lg-12 col-sm-12">
                                    <h5>Perolehan Suara :</h5>
                                    <table id="" class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th>No Urut Paslon</th>
                                                <th>Pasangan Calon</th>
                                                <th>Jumlah Suara</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (!empty($data['candidates']))
                                                @foreach ($data['candidates'] as $candidate)
                                                    <tr>
                                                        <td>{{ $candidate['candidate_no'] }}</td>
                                                        <td>{{ $candidate['candidate_name'] }}</td>
                                                        <td>{{ $candidate['votes'] }}</td>
                                                    </tr>
                                                @endforeach
                                                <tr>
                                                    <td colspan="2">Suara Tidak Sah</td>
                                                    <td>{{ $data['invalid_votes'] }}</td>
                                                </tr>
                                            @else
                                                <tr>
                                                    <td colspan="3" class="text-center">Tidak ada data untuk jenis
                                                        pemilihan ini.</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
