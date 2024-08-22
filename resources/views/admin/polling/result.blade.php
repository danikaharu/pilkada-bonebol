@extends('layouts.admin.index')

@section('title', 'Hasil Perolehan Suara')

@push('style')
    <style>
        #form {
            display: none;
        }

        .click-zoom input[type="checkbox"] {
            display: none;
        }

        .click-zoom img {
            transition: transform 0.25s ease;
            cursor: zoom-in;
        }

        .click-zoom input[type="checkbox"]:checked~img {
            transform: scale(2);
            cursor: zoom-out;
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
                        <h5 class="card-title header-elements">Data Hasil Perolehan Suara
                            <div class="card-title-elements ms-auto">
                                <a href="{{ route('admin.polling.exportExcel') }}" class="btn btn-success"><i
                                        class="bx bx-printer"></i> Cetak</a>
                            </div>
                        </h5>
                        <h6 class="card-subtitle text-muted">Berikut data Hasil Perolehan Suara yang telah dimasukan</h6>
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
                                        <button id="viewResult" class="btn col-12 btn-primary">
                                            <i class='bx bx-search-alt'></i> Lihat Hasil Perolehan Suara
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
                        <h6 class="card-subtitle text-muted">Berikut data Hasil Perolehan Suara yang telah dimasukan</h6>
                        <div class="my-4">
                            <div class="row">
                                <div class="col-lg-6 col-sm-12">
                                    <h5>Perolehan Suara :</h5>
                                    <table id="resultTable" class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th>No Urut Paslon</th>
                                                <th>Pasangan Calon</th>
                                                <th>Jumlah Suara</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                    <div id="status" class="mt-2"></div>
                                    <form action="">
                                        <button type="submit" class="btn btn-success btn-accept"><i
                                                class='bx bx-check-square'></i>
                                            Terima</button>
                                        <button type="submit" class="btn btn-danger btn-reject"> <i
                                                class='bx bxs-x-square'></i>Tolak</button>
                                    </form>
                                </div>
                                <div class="col-lg-6 col-sm-12">
                                    <h5>Form C1 :</h5>
                                    <div class="click-zoom">
                                        <label>
                                            <input type="checkbox" />
                                            <img id="c1" src="" alt="Form C1"
                                                style="display:block; margin:0 auto;width:100%">
                                        </label>
                                    </div>
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
        });

        $('#viewResult').on('click', function() {
            event.preventDefault();

            var selectedTps = $('#tps').val();
            var selectedType = $('#type').val();

            $.ajax({
                url: "{{ route('admin.polling.fetchPollingResult') }}",
                type: "POST",
                data: {
                    polling_station_id: selectedTps,
                    type: selectedType,
                    _token: '{{ csrf_token() }}'
                },
                dataType: 'json',
                success: function(response) {

                    if ($.isEmptyObject(response.pollingResult)) {
                        // Tampilkan pesan alert jika tidak ada data
                        alert('Data belum diinput.');
                        return;
                    }

                    var pollingResult = response.pollingResult;
                    var candidates = response.candidates;

                    var candidateVotes = JSON.parse(pollingResult.candidate_votes);

                    var tableBody = $('#resultTable tbody');

                    var c1ImageUrl =
                        "{{ asset('storage/upload/c1/') }}" + '/' + response.pollingResult.c1
                    $('#c1').attr('src', c1ImageUrl);

                    tableBody.empty();

                    Object.keys(candidateVotes).forEach(function(candidateNumber) {
                        // Konversi nomor urut menjadi string
                        var candidateNumberString = candidateNumber.toString();

                        // Mendapatkan data kandidat untuk nomor urut
                        var candidateData = candidates[candidateNumberString];

                        var candidateName = candidateData.regional_head + ' - ' +
                            candidateData.deputy_head;
                        var candidateVote = candidateVotes[candidateNumber];

                        var newRow = $('<tr>');
                        newRow.append('<td>' + candidateData.number +
                            '</td>'); // Nomor urut paslon
                        newRow.append('<td>' + candidateName + '</td>'); // Nama paslon
                        newRow.append('<td>' + candidateVote +
                            '</td>'); // Jumlah suara paslon
                        tableBody.append(newRow);
                    });


                    var invalidRow = $('<tr>');
                    invalidRow.append('<td colspan="2">Suara Tidak Sah</td>');
                    invalidRow.append('<td>' + response.pollingResult.invalid_votes + '</td>');
                    tableBody.append(invalidRow);

                    // Menampilkan status verifikasi
                    var statusBadge;
                    var status = response.pollingResult.status;

                    if (status == 0) {
                        statusBadge = '<span class="badge bg-label-danger">Belum Diverifikasi</span>';
                    } else if (status == 1) {
                        statusBadge =
                            '<span class="badge bg-label-success">Sudah Diverifikasi - Diterima</span>';
                        $('#form form').hide();
                    } else if (status == 2) {
                        statusBadge =
                            '<span class="badge bg-label-danger">Sudah Diverifikasi - Ditolak</span>';
                        $('#form form').hide();
                    } else {
                        statusBadge = '<span class="badge bg-label-info">Status Tidak Diketahui</span>';
                    }
                    $('#status').html('Status: ' + statusBadge);

                    // Jika status adalah 2, tambahkan tombol edit
                    if (status == 2) {
                        let pollingResultId = response.pollingResult.id;
                        let editUrl = "{{ route('admin.polling.edit', ':id') }}".replace(':id',
                            pollingResultId);

                        let editButton = "<a href='" + editUrl +
                            "' class='btn btn-warning btn-edit'><i class='bx bx-edit-alt'></i> Edit</a>";
                        $('#status').append(' ' +
                            editButton); // Append tombol edit setelah status badge
                    }

                    // Menampilkan hasil suara
                    $('#form').show();
                },
                error: function(xhr, status, error) {
                    // Menangani kesalahan jika terjadi
                    console.error(xhr.responseText);
                }
            });
        });

        // Event listener untuk tombol Terima
        $('#form').on('click', '.btn-accept', function(event) {
            event.preventDefault();

            var selectedTps = $('#tps').val();

            $.ajax({
                url: "{{ route('admin.polling.verify') }}",
                type: "POST",
                data: {
                    polling_station_id: selectedTps,
                    status: 1, // Status 1 untuk diterima
                    _token: '{{ csrf_token() }}'
                },
                dataType: 'json',
                success: function(response) {
                    alert('Hasil perolehan suara diterima.');
                    location.reload();
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);

                }
            });
        });

        // Event listener untuk tombol Tolak
        $('#form').on('click', '.btn-reject', function(event) {
            event.preventDefault();

            var selectedTps = $('#tps').val();

            $.ajax({
                url: "{{ route('admin.polling.verify') }}",
                type: "POST",
                data: {
                    polling_station_id: selectedTps,
                    status: 2,
                    _token: '{{ csrf_token() }}'
                },
                dataType: 'json',
                success: function(response) {
                    alert('Hasil perolehan suara ditolak.');
                    location.reload();
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });
    </script>
@endpush
