@extends('layouts.admin.index')

@section('title', 'TPS')

@push('style')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/rowreorder/1.2.8/css/rowReorder.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.dataTables.min.css">
    <style>
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_info {
            margin-left: 1rem;
        }

        .dataTables_wrapper .dataTables_filter,
        .dataTables_wrapper .dataTables_paginate {
            margin-right: 1rem;
        }
    </style>
@endpush

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h5 class="fw-bold py-3 mb-2"><span class="text-muted fw-light">Beranda /</span> TPS</h5>
        <div class="row">
            <div class="col-lg-12 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">Data TPS</h5>
                        <h6 class="card-subtitle text-muted">Berikut data TPS yang telah dimasukan</h6>
                        @can('create polling station')
                            <a href="{{ route('admin.pollingstation.create') }}" class="btn btn-primary my-4 text-white"><i
                                    class='bx bx-plus-circle'></i>
                                Tambah TPS</a>
                        @endcan
                        <div class="table-responsive text-nowrap">
                            <table id="listData" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>TPS</th>
                                        <th>Desa/Kelurahan</th>
                                        <th>Kecamatan</th>
                                        <th>Jumlah Pemilih Tetap</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js" defer></script>
    <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js" defer></script>
    <script src="https://cdn.datatables.net/responsive/2.3.0/js/dataTables.responsive.min.js" defer></script>
    <script src="https://cdn.datatables.net/rowreorder/1.2.8/js/dataTables.rowReorder.min.js" defer></script>
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(document).ready(function() {
            $('#listData').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                rowReorder: {
                    selector: 'td:nth-child(2)'
                },
                ajax: '{{ url()->current() }}',
                columns: [{
                        data: 'DT_RowIndex'
                    }, {
                        data: 'name',
                    },
                    {
                        data: 'village',
                    },
                    {
                        data: 'subdistrict',
                    },
                    {
                        data: 'registered_voters',
                    },
                    {
                        data: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
            });

            // Sweet Alert Delete
            $("body").on('submit', `form[role='alert']`, function(event) {
                event.preventDefault();

                Swal.fire({
                    title: $(this).attr('alert-title'),
                    text: $(this).attr('alert-text'),
                    icon: "warning",
                    allowOutsideClick: false,
                    showCancelButton: true,
                    cancelButtonText: "Batal",
                    reverseButton: true,
                    confirmButtonText: "Hapus",
                }).then((result) => {
                    if (result.isConfirmed) {
                        event.target.submit();
                    }
                })
            });
        });
    </script>
@endpush
