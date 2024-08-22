@extends('layouts.admin.index')

@section('title', 'Dashboard')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12 mb-4 order-0">
                <div class="card">
                    <div class="d-flex align-items-end row">
                        <div class="col-sm-7">
                            <div class="card-body">
                                <h5 class="card-title text-primary">Hai {{ auth()->user()->name }}! 💕</h5>

                                @hasrole('Super Admin')
                                    <p class="mb-4">
                                        Di halaman Dashboard Admin Pilkada ini, Anda dapat dengan
                                        mudah mengelola data penting seperti kelurahan, kecamatan, daerah pemilihan (dapil),
                                        TPS, dan pasangan calon. Semuanya dirancang untuk mempermudah tugas Anda dalam menambah,
                                        mengedit, dan menghapus data sesuai kebutuhan.
                                    </p>
                                @endhasrole
                                @hasrole('Operator')
                                    <p class="mb-4">
                                        Di halaman Dashboard Operator Pilkada ini, Anda dapat dengan mudah mengakses dan
                                        memperbarui data terkait pelaksanaan Pilkada, seperti kelurahan, kecamatan, dapil,
                                        TPS, dan pasangan calon. Dashboard ini dirancang untuk mendukung tugas Anda dalam
                                        memastikan kelancaran proses pengolahan data yang akurat dan up-to-date.
                                    </p>
                                @endhasrole

                            </div>
                        </div>
                        <div class="col-sm-5 text-center text-sm-left">
                            <div class="card-body pb-0 px-0 px-md-4">
                                <img src="{{ asset('template/img/illustrations/man-with-laptop-light.png') }}"
                                    height="140" alt="View Badge User"
                                    data-app-dark-img="illustrations/man-with-laptop-dark.png"
                                    data-app-light-img="illustrations/man-with-laptop-light.png" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 mt-2 col-sm-12 order-1">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between flex-sm-row flex-column gap-3">
                            <div class="d-flex flex-sm-column flex-row align-items-start justify-content-between">
                                <div class="card-title">
                                    <h5 class="text-nowrap mb-2">Jumlah Dapil</h5>
                                </div>
                                <div class="mt-sm-auto">
                                    <h3 class="mb-0">{{ $totalElectoralDistrict }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 mt-2 col-sm-12 order-1">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between flex-sm-row flex-column gap-3">
                            <div class="d-flex flex-sm-column flex-row align-items-start justify-content-between">
                                <div class="card-title">
                                    <h5 class="text-nowrap mb-2">Jumlah Kecamatan</h5>
                                </div>
                                <div class="mt-sm-auto">
                                    <h3 class="mb-0">{{ $totalSubdistrict }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 mt-2 col-sm-12 order-1">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between flex-sm-row flex-column gap-3">
                            <div class="d-flex flex-sm-column flex-row align-items-start justify-content-between">
                                <div class="card-title">
                                    <h5 class="text-nowrap mb-2">Jumlah Kelurahan/Desa</h5>
                                </div>
                                <div class="mt-sm-auto">
                                    <h3 class="mb-0">{{ $totalVillage }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 mt-2 col-sm-12 order-1">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between flex-sm-row flex-column gap-3">
                            <div class="d-flex flex-sm-column flex-row align-items-start justify-content-between">
                                <div class="card-title">
                                    <h5 class="text-nowrap mb-2">Jumlah TPS</h5>
                                </div>
                                <div class="mt-sm-auto">
                                    <h3 class="mb-0">{{ $totalPollingStation }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mt-4 col-sm-12 order-1">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between flex-sm-row flex-column gap-3">
                            <div class="d-flex flex-sm-column flex-row align-items-start justify-content-between">
                                <div class="card-title">
                                    <h5 class="text-nowrap mb-2">Jumlah Suara yang Belum Diverifikasi
                                        (TPS) </h5>
                                </div>
                                <div class="mt-sm-auto">
                                    <h3 class="mb-0">{{ $totalPollingUnverified }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mt-4 col-sm-12 order-1">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between flex-sm-row flex-column gap-3">
                            <div class="d-flex flex-sm-column flex-row align-items-start justify-content-between">
                                <div class="card-title">
                                    <h5 class="text-nowrap mb-2">Jumlah Suara yang sudah Terverifikasi
                                        (TPS) </h5>
                                </div>
                                <div class="mt-sm-auto">
                                    <h3 class="mb-0">{{ $totalPollingVerified }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
