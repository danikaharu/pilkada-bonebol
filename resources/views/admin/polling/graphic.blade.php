@extends('layouts.admin.index')

@section('title', 'Grafik Perolehan Suara')

@push('style')
    <link rel="stylesheet" href="{{ asset('template/vendor/libs/apex-charts/apex-charts.css') }}" />
    <style>
        #form {
            display: none;
        }
    </style>
@endpush

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h5 class="fw-bold py-3 mb-2"><span class="text-muted fw-light">Beranda /</span> Grafik Perolehan Suara</h5>
        <div class="row">
            <div class="col-lg-12 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">Data Hasil Perolehan Suara</h5>
                        <h6 class="card-subtitle text-muted">Berikut data Hasil Perolehan Suara yang telah dimasukan</h6>
                        <div class="my-4">
                            <form action="">
                                <div class="row">
                                    <div class="col-lg-3 col-sm-12">
                                        <div class="mb-3">
                                            <label class="form-label">Dapil</label>
                                            <select name="" id="dapil" class="form-select col-12 dapil">
                                                <option selected>---Pilih Dapil---</option>
                                                @foreach ($electoralDistricts as $electoralDistrict)
                                                    <option value="{{ $electoralDistrict->id }}">
                                                        {{ $electoralDistrict->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-12">
                                        <div class="mb-3">
                                            <label class="form-label">Kecamatan</label>
                                            <select name="" id="kecamatan" class="form-select col-12 kecamatan">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-12">
                                        <div class="mb-3">
                                            <label class="form-label">Kelurahan</label>
                                            <select name="" id="kelurahan" class="form-select col-12 kelurahan">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-12">
                                        <div class="mb-3">
                                            <label class="form-label">TPS</label>
                                            <select name="" id="tps" class="form-select col-12 tps">
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <button id="viewGraphic" class="btn col-12 btn-primary">
                                            <i class='bx bx-search-alt'></i> Lihat Grafik Perolehan Suara
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
                        <h5 class="card-title">Grafik Hasil Perolahan Suara</h5>
                        <h6 class="card-subtitle text-muted">Berikut data Hasil Perolehan Suara yang telah dimasukan</h6>
                        <div class="my-4">
                            <div class="text-center">
                                <center>
                                    <div id="chart"></div>
                                </center>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ asset('template/vendor/libs/apex-charts/apexcharts.js') }}"></script>
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

            $('#tps').on('change', function() {
                var selectedTps = $(this).val();

                // Menyalin nilai TPS ke input hidden
                $('#selectedTps').val(selectedTps);
            });
        });

        $('#viewGraphic').on('click', function() {
            event.preventDefault();

            var selectedTps = $('#tps').val();

            $.ajax({
                url: "{{ route('admin.polling.fetchPollingGraphic') }}",
                type: "POST",
                data: {
                    polling_station_id: selectedTps,
                    _token: '{{ csrf_token() }}'
                },
                dataType: 'json',
                success: function(response) {

                    if ($.isEmptyObject(response.pollingResult)) {
                        alert('Data belum diinput.');
                        return;
                    }

                    var candidateVotes = JSON.parse(response.pollingResult.candidate_votes).map(
                        function(vote) {
                            return parseInt(vote,
                                10);
                        });

                    var invalidVotes = parseInt(response.pollingResult.invalid_votes,
                        10);

                    var series = candidateVotes.concat(invalidVotes);

                    console.log(series);

                    var labels = [];
                    response.candidates.forEach(function(candidate) {
                        labels.push(candidate.regional_head + '-' + candidate.deputy_head);
                    });
                    labels.push('Suara Tidak Sah');

                    var options = {
                        series: series,
                        chart: {
                            width: 800,
                            type: 'pie',
                        },
                        labels: labels,
                        responsive: [{
                            breakpoint: 480,
                            options: {
                                legend: {
                                    position: 'bottom'
                                }
                            }
                        }]
                    };

                    var chart = new ApexCharts(document.querySelector("#chart"), options);
                    chart.render();

                    // Menampilkan form yang berisi hasil perolehan suara
                    $('#form').show();
                },
                error: function(xhr, status, error) {
                    // Menangani kesalahan jika terjadi
                    console.error(xhr.responseText);
                }
            });
        });
    </script>
@endpush
