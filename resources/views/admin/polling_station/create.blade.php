@extends('layouts.admin.index')

@section('title', 'Tambah TPS')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h5 class="fw-bold py-3 mb-2"><span class="text-muted fw-light">Beranda / TPS / </span> Tambah TPS</h5>
        <div class="row">
            <div class="col-lg-12 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">Tambah Data TPS</h5>
                        <h6 class="card-subtitle text-muted">Halaman Menambah Data TPS</h6>
                        <form class="my-4" action="{{ route('admin.pollingstation.store') }}" method="POST">
                            @csrf

                            @include('admin.polling_station.include.form')
                            <a href="{{ route('admin.pollingstation.index') }}" class="btn btn-secondary"><i
                                    class='bx bx-arrow-back'></i> Kembali</a>
                            <button type="submit" class="btn btn-primary"><i class='bx bxs-save'></i> Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection