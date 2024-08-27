@extends('layouts.admin.index')

@section('title', 'Input Suara')

@push('style')
    <style>
        #form {
            display: none;
        }
    </style>
@endpush

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h5 class="fw-bold py-3 mb-2"><span class="text-muted fw-light">Beranda / </span> Input Suara</h5>
        <div class="row">
            <div class="col-lg-12 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">Input Suara</h5>
                        <h6 class="card-subtitle text-muted">Halaman Input Suara</h6>
                        <div class="my-4">
                            <form action="">
                                <div class="row">
                                    <div class="col-lg-3 col-sm-12">
                                        <div class="mb-3">
                                            <label class="form-label">Dapil</label>
                                            <select id="dapil" name="" class="form-select col-12 dapil">
                                                <option selected>---Pilih Dapil---</option>
                                                @foreach ($electoralDistricts as $electoralDistrict)
                                                    <option value="{{ $electoralDistrict->id }}">
                                                        {{ $electoralDistrict->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-12">
                                        <div class="mb-3">
                                            <label class="form-label">Kecamatan</label>
                                            <select id="kecamatan" name="" class="form-select col-12 kecamatan">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-12">
                                        <div class="mb-3">
                                            <label class="form-label">Kelurahan</label>
                                            <select id="kelurahan" name="" class="form-select col-12 kelurahan">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-12">
                                        <div class="mb-3">
                                            <label class="form-label">TPS</label>
                                            <select id="tps" name="" class="form-select col-12 tps">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-12">
                                        <div class="mb-3">
                                            <label class="form-label">Pemilihan</label>
                                            <select id="type" name="type" class="form-select col-12">
                                                <option value="">-- Pilih Pemilihan --</option>
                                                <option value="1">Gubernur</option>
                                                <option value="2">Kepala Daerah</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <button id="choosePollingStation" class="btn col-12 btn-primary">
                                            <i class='bx bx-search-alt'></i> Pilih Tempat Pemilihan
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" id="form">
            <div class="col-lg-12 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <h6 class="card-subtitle text-muted">Berikut form Tambah Perolehan Suara</h6>
                        <div class="my-4">
                            <div class="row">
                                <div class="col-lg-12 col-sm-12">
                                    <h5>Tambah Perolehan Suara :</h5>
                                    <form class="my-4" id="pollingForm">
                                        <input type="hidden" id="selectedTps" name="polling_station_id">
                                        <input type="hidden" id="selectedPemilihan" name="type">
                                        <div id="candidateInputs"></div>
                                        <div class="mb-3">
                                            <label class="form-label">Suara Tidak Sah / Suara Rusak</label>
                                            <input type="number" name="invalid_votes"
                                                class="form-control @error('invalid_votes')
                                            is-invalid
                                        @enderror"
                                                placeholder="Masukan Jumlah Suara">
                                            @error('invalid_votes')
                                                <div class="small text-danger">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Form C1</label>
                                            <input type="file" name="c1"
                                                class="form-control @error('c1')
                                                is-invalid
                                            @enderror">
                                            @error('c1')
                                                <div class="small text-danger">
                                                    {{ $message }}
                                                </div>
                                            @enderror
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

@push('script')
    <script>
        $(document).ready(function() {
            /*------------------------------------------
            --------------------------------------------
            Dapil Change Event
            --------------------------------------------
            --------------------------------------------*/
            $('#dapil').on('change', function() {
                var idDapil = this.value;
                $("#kecamatan").html('');
                $.ajax({
                    url: "{{ route('admin.polling.fetchSubdistrict') }}",
                    type: "POST",
                    data: {
                        electoral_district_id: idDapil,
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function(result) {
                        $('#kecamatan').html(
                            '<option value="">-- Pilih Kecamatan --</option>');
                        $.each(result.subdistricts, function(key, value) {
                            $("#kecamatan").append('<option value="' + value
                                .id + '">' + value.name + '</option>');
                        });
                        $('#kelurahan').html('<option value="">-- Pilih Kelurahan --</option>');
                        $('#tps').html('<option value="">-- Pilih TPS --</option>');
                    }
                });
            });

            /*------------------------------------------
            --------------------------------------------
            Kecamatan Change Event
            --------------------------------------------
            --------------------------------------------*/
            $('#kecamatan').on('change', function() {
                var idSubdistrict = this.value;
                $("#kelurahan").html('');
                $.ajax({
                    url: "{{ route('admin.polling.fetchVillage') }}",
                    type: "POST",
                    data: {
                        subdistrict_id: idSubdistrict,
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function(res) {
                        $('#kelurahan').html('<option value="">-- Pilih Kelurahan --</option>');
                        $.each(res.villages, function(key, value) {
                            $("#kelurahan").append('<option value="' + value
                                .id + '">' + value.name + '</option>');
                        });
                        $('#tps').html('<option value="">-- Pilih TPS --</option>');
                    }
                });
            });

            /*------------------------------------------
            --------------------------------------------
            Kelurahan Change Event
            --------------------------------------------
            --------------------------------------------*/
            $('#kelurahan').on('change', function() {
                var idVillage = this.value;
                $("#tps").html('');
                $.ajax({
                    url: "{{ route('admin.polling.fetchPollingStation') }}",
                    type: "POST",
                    data: {
                        village_id: idVillage,
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function(res) {
                        $('#tps').html('<option value="">-- Pilih TPS --</option>');
                        $.each(res.pollingstations, function(key, value) {
                            $("#tps").append('<option value="' + value
                                .id + '">' + value.name + '</option>');
                        });
                    }
                });
            });

            $('#type').on('change', function() {
                var type = this.value;

                $.ajax({
                    url: "{{ route('admin.polling.fetchCandidate') }}",
                    type: "POST",
                    data: {
                        type: type,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(result) {
                        $('#candidateInputs').html(''); // Kosongkan form
                        for (let i = 1; i <= result.candidates; i++) {
                            $('#candidateInputs').append(`
                        <div class="mb-3">
                            <label class="form-label">Suara Paslon ` + i + `</label>
                            <input type="number" name="candidate_votes[]" class="form-control" placeholder="Masukan jumlah Suara" />
                        </div>
                    `);
                        }
                    }
                });
            });

            //  Nilai TPS
            $('#tps').on('change', function() {
                var selectedTps = $(this).val();

                $('#selectedTps').val(selectedTps);
            });

            //  Nilai Pemilihan
            $('#type').on('change', function() {
                var selectedPemilihan = $(this).val();

                $('#selectedPemilihan').val(selectedPemilihan);
            });
        });

        $('#choosePollingStation').on('click', function() {
            event.preventDefault();
            var form = document.getElementById('form');
            form.style.display = 'block';
        });

        $('#pollingForm').on('submit', function() {
            event.preventDefault();

            var formData = new FormData(this);
            formData.append('_token', '{{ csrf_token() }}');

            $.ajax({
                url: '{{ route('admin.polling.store') }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    location.reload()
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });
    </script>
@endpush
