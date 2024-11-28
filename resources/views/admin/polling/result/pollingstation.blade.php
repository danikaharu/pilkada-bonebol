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
                            @can('export polling')
                                <div class="card-title-elements ms-auto">
                                    <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                        data-bs-target="#reportPolling"><i class="bx bx-printer me-1"></i>Cetak </button>
                                </div>
                            @endcan
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
                                <div class="col-lg-12 col-sm-12">
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
                                    @can('verify polling')
                                        <form action="">
                                            <button type="submit" class="btn btn-success btn-accept"><i
                                                    class='bx bx-check-square'></i>
                                                Terima</button>
                                            <button type="submit" class="btn btn-danger btn-reject"> <i
                                                    class='bx bxs-x-square'></i>Tolak</button>
                                        </form>
                                    @endcan
                                </div>
                                <div class="col-lg-12 col-sm-12 mt-5">
                                    <div id="carouselExample" class="carousel slide mt-5">
                                        <h5>Form C1 :</h5>
                                        <div class="carousel-inner" id="carouselContent">

                                        </div>
                                        <button class="carousel-control-prev" type="button"
                                            data-bs-target="#carouselExample" data-bs-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Previous</span>
                                        </button>
                                        <button class="carousel-control-next" type="button"
                                            data-bs-target="#carouselExample" data-bs-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Next</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Report Polling Modal -->
    <div class="modal fade" id="reportPolling" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-simple modal-print">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel5">Cetak Laporan Pemungutan Suara</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.polling.exportExcel') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-6">
                            <div class="col mb-0">
                                <label for="startDate" class="form-label">Pemilihan</label>
                                <select id="type" name="type" class="form-select col-12">
                                    <option disabled selected>-- Pilih Pemilihan --</option>
                                    <option value="1">Gubernur</option>
                                    <option value="2">Kepala Daerah</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Cetak</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--/ Report Polling Modal -->
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

        $('#viewResult').on('click', function(event) {
            event.preventDefault();

            var selectedTps = $('#tps').val();
            var selectedSubdistrict = $('#kecamatan').val();
            var selectedType = $('#type').val();

            $.ajax({
                url: "{{ route('admin.polling.fetchPollingResult') }}",
                type: "POST",
                data: {
                    polling_station_id: selectedTps,
                    subdistrict_id: selectedSubdistrict,
                    type: selectedType,
                    _token: '{{ csrf_token() }}'
                },
                dataType: 'json',
                success: function(response) {
                    if ($.isEmptyObject(response.pollingResult)) {
                        alert('Data belum diinput.');
                        return;
                    }

                    var pollingResult = response.pollingResult;
                    var candidates = response.candidates;

                    var candidateVotes = JSON.parse(pollingResult.candidate_votes);

                    var tableBody = $('#resultTable tbody');
                    var c1Images = response.pollingResult.c1 ? JSON.parse(response.pollingResult.c1) :
                    [];
                    var carouselContent = $('#carouselContent'); // Elemen carousel-inner

                    // Kosongkan tabel hasil dan carousel sebelum mengisi ulang
                    tableBody.empty();
                    carouselContent.empty();

                    if (c1Images.length === 0) {
                        // Tampilkan pesan jika tidak ada gambar C1
                        carouselContent.append(`
            <div class="carousel-item active">
                <p class="text-center">Tidak ada gambar C1 yang tersedia.</p>
            </div>
        `);
                    } else {
                        c1Images.forEach(function(image, index) {
                            var isActive = index === 0 ? 'active' : ''; // Gambar pertama aktif
                            var imageUrl = "{{ asset('storage/upload/c1/') }}" + '/' + image;

                            carouselContent.append(`
                <div class="carousel-item ${isActive}">
                    <img src="${imageUrl}" class="d-block w-100" alt="Form C1">
                </div>
            `);
                        });
                    }

                    // Tampilkan data kandidat dan suara
                    Object.keys(candidateVotes).forEach(function(candidateNumber) {
                        var candidateNumberString = candidateNumber.toString();
                        var candidateData = candidates[candidateNumberString];

                        var candidateName = candidateData.regional_head + ' - ' + candidateData
                            .deputy_head;
                        var candidateVote = candidateVotes[candidateNumber];

                        var newRow = $('<tr>');
                        newRow.append('<td>' + candidateData.number +
                            '</td>'); // Nomor urut paslon
                        newRow.append('<td>' + candidateName + '</td>'); // Nama paslon
                        newRow.append('<td>' + candidateVote + '</td>'); // Jumlah suara paslon
                        tableBody.append(newRow);
                    });

                    // Tambahkan baris untuk suara tidak sah
                    var invalidRow = $('<tr>');
                    invalidRow.append('<td colspan="2">Suara Tidak Sah</td>');
                    invalidRow.append('<td>' + pollingResult.invalid_votes + '</td>');
                    tableBody.append(invalidRow);

                    // Tampilkan status verifikasi
                    var statusBadge;
                    var status = pollingResult.status;

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

                    @can('edit polling')
                        if (status == 2) {
                            let pollingResultId = pollingResult.id;
                            let editUrl = "{{ route('admin.polling.edit', ':id') }}".replace(':id',
                                pollingResultId);
                            let editButton = "<a href='" + editUrl +
                                "' class='btn btn-warning btn-edit'><i class='bx bx-edit-alt'></i> Edit</a>";
                            $('#status').append(' ' + editButton);
                        }
                    @endcan

                    // Tampilkan carousel
                    $('#form').show();
                },
                error: function(xhr, status, error) {
                    alert(xhr.responseText);
                }
            });
        });


        // Event listener untuk tombol Terima
        $('#form').on('click', '.btn-accept', function(event) {
            event.preventDefault();

            var selectedTps = $('#tps').val();
            var selectedType = $('#type').val();

            $.ajax({
                url: "{{ route('admin.polling.verify') }}",
                type: "POST",
                data: {
                    polling_station_id: selectedTps,
                    status: 1,
                    type: selectedType,
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
