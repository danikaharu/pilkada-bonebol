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
                        <h5 class="card-title">Data Grafik Perolehan Suara</h5>
                        <form id="pollingForm">
                            <div class="mb-3">
                                <label class="form-label">Pemilihan</label>
                                <select id="type" name="type" class="form-select">
                                    <option disabled selected>-- Pilih Pemilihan --</option>
                                    <option value="1">Gubernur</option>
                                    <option value="2">Kepala Daerah</option>
                                </select>
                            </div>
                            <button id="viewGraphic" class="btn btn-primary col-12">
                                <i class='bx bx-search-alt'></i> Lihat Grafik Perolehan Suara
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" id="form">
            <div class="col-lg-12 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">Grafik Hasil Perolehan Suara</h5>
                        <div id="chart"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ asset('template/vendor/libs/apex-charts/apexcharts.js') }}"></script>
    <script>
        $('#viewGraphic').on('click', function(event) {
            event.preventDefault();
            var selectedType = $('#type').val();

            if (!selectedType) {
                alert('Silakan pilih jenis pemilihan.');
                return;
            }

            $.ajax({
                url: "{{ route('admin.polling.graphicAll') }}",
                type: "GET",
                data: {
                    type: selectedType,
                },
                success: function(response) {
                    if (!response.totalVotes.length) {
                        alert('Data belum diinput.');
                        return;
                    }

                    var options = {
                        series: response.totalVotes,
                        chart: {
                            type: 'pie',
                        },
                        labels: response.candidateNames,
                        dataLabels: {
                            formatter: function(val, opts) {
                                return opts.w.globals.labels[opts.seriesIndex] + ': ' + val
                                    .toFixed(1) + '%';
                            },
                        },
                    };

                    $('#form').show();
                    var chart = new ApexCharts(document.querySelector("#chart"), options);
                    chart.render();
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    alert('Terjadi kesalahan.');
                }
            });
        });
    </script>
@endpush
