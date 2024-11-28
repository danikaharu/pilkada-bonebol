@extends('layouts.admin.index')

@section('title', 'Detail Perolehan Suara')

@push('style')
    <style>
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
        <div class="row" id="form">
            <div class="col-lg-12 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <h6 class="card-subtitle text-muted">Berikut data Hasil Perolehan Suara yang telah dimasukan</h6>
                        <div class="my-4">
                            <div class="row">
                                <div class="col-lg-12 col-sm-12">
                                    <h5>Perolehan Suara {{ $polling->type() }} :</h5>
                                    <table id="resultTable" class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th>No Urut Paslon</th>
                                                <th>Pasangan Calon</th>
                                                <th>Jumlah Suara</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($polling)
                                                @php
                                                    $candidateVotes = json_decode($polling->candidate_votes, true);
                                                    $c1Images = $polling->c1 ? json_decode($polling->c1, true) : [];
                                                @endphp

                                                @foreach ($candidateVotes as $index => $voteCount)
                                                    @php
                                                        $candidate = $candidates->get($index);
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $candidate->number }}</td>
                                                        <td>{{ $candidate->regional_head }} - {{ $candidate->deputy_head }}
                                                        </td>
                                                        <td>{{ $voteCount }}</td>
                                                    </tr>
                                                @endforeach

                                                <!-- Suara tidak sah -->
                                                <tr>
                                                    <td colspan="2">Suara Tidak Sah</td>
                                                    <td>{{ $polling->invalid_votes }}</td>
                                                </tr>
                                            @else
                                                <tr>
                                                    <td colspan="3" class="text-center">Data belum diinput.</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                    <div class="my-2">
                                        @if ($polling->status == 0)
                                            <span class="badge bg-label-danger">Belum Diverifikasi</span>
                                        @elseif ($polling->status == 1)
                                            <span class="badge bg-label-success">Sudah Diverifikasi - Diterima</span>
                                        @elseif ($polling->status == 2)
                                            <span class="badge bg-label-danger">Sudah Diverifikasi - Ditolak</span>
                                        @else
                                            <span class="badge bg-label-info">Status Tidak Diketahui</span>
                                        @endif
                                    </div>
                                    @can('verify polling')
                                        @if ($polling->status == 0)
                                            <form action="">
                                                <button type="submit" class="btn btn-success btn-accept"><i
                                                        class='bx bx-check-square'></i>
                                                    Terima</button>
                                                <button type="submit" class="btn btn-danger btn-reject"> <i
                                                        class='bx bxs-x-square'></i>Tolak</button>
                                            </form>
                                        @endif
                                    @endcan
                                </div>
                                <div class="col-lg-12 col-sm-12 mt-5">
                                    <div id="carouselExample" class="carousel slide mt-5">
                                        <h5>Form C1 :</h5>
                                        <div class="carousel-inner">
                                            @if (!empty($c1Images))
                                                @foreach ($c1Images as $index => $image)
                                                    <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                                        <img src="{{ asset('storage/upload/c1/' . $image) }}"
                                                            class="d-block w-100" alt="Form C1">
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="carousel-item active">
                                                    <p class="text-center">Tidak ada gambar C1 yang tersedia.</p>
                                                </div>
                                            @endif
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

@endsection

@push('script')
    <script>
        // Event listener untuk tombol Terima
        $('#form').on('click', '.btn-accept', function(event) {
            event.preventDefault();

            var selectedTps = $('#tps').val();
            var selectedType = $('#type').val();

            $.ajax({
                url: "{{ route('admin.polling.verify', ['polling' => $polling->id]) }}",
                type: "POST",
                data: {
                    status: 1,
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

            $.ajax({
                url: "{{ route('admin.polling.verify', ['polling' => $polling->id]) }}",
                type: "POST",
                data: {
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
