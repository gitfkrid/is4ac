@extends('layouts.dashboard')

@section('headcontent')
    <div class="mb-3 d-flex justify-content-between align-items-center">
        <!-- Button Filter -->
        <style>
            .btn-group .btn.active {
                background-color: #212429;
                color: white;
            }
        </style>
        <div class="btn-group" role="group" aria-label="Jenis Alat">
            <button type="button" class="btn btn-outline-secondary active" data-filter="all">
                Semua
            </button>
            @foreach ($filter as $id_jenis_alat => $filter)
                <button type="button" class="btn btn-outline-secondary" data-filter="{{ $id_jenis_alat }}">
                    {{ $filter }}
                </button>
            @endforeach
        </div>

        <!-- Button Tambah Device -->
        <a href="javascript:void(0)" class="btn btn-primary" onclick="addForm()">
            <i class="fa fa-plus-circle"></i> Tambah Device
        </a>
    </div>
@endsection

@section('content')
    @if (Session::has('alert'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            {{ Session::get('alert') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    <div id="alatCards" class="row">
        <!-- Card -->
        @include('dashboard.alatCards')
        @include('dashboard.form')
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        var save_method;

        // Waktu
        function updateTime() {
            var now = new Date();
            var hours = String(now.getHours()).padStart(2, '0');
            var minutes = String(now.getMinutes()).padStart(2, '0');
            var seconds = String(now.getSeconds()).padStart(2, '0');
            var currentTime = hours + ':' + minutes + ':' + seconds;
            document.getElementById('time').innerText = currentTime;
        }
        setInterval(updateTime, 1000);
        updateTime();

        // Filter
        $(function() {
            $('.btn-group button').on('click', function() {
                var filter = $(this).data('filter');

                $('.btn-group button').removeClass('active');

                $(this).addClass('active');

                if (filter === 'all') {
                    $('#alatCards .col-xl-3').show();
                } else {
                    $('#alatCards .col-xl-3').each(function() {
                        var jenisAlat = $(this).data('jenis-alat');
                        if (jenisAlat == filter) {
                            $(this).show();
                        } else {
                            $(this).hide();
                        }
                    });
                }
            });
        });

        // Add Device
        $(function() {
            $('#modal-form form').on('submit', function(e) {
                if (!e.isDefaultPrevented()) {
                    $.ajax({
                        url: "{{ route('dashboard.store') }}",
                        type: "POST",
                        data: $('#modal-form form').serialize(),
                        success: function(data) {
                        $('#modal-form').modal('hide');
                        Swal.fire(
                            'Berhasil!',
                            'Device berhasil ditambahkan!',
                            'success'
                        );

                        // Reload alatCards section
                        $.ajax({
                        url: "{{ route('dashboard.alatCards') }}",
                        type: "GET",
                        success: function(html) {
                            $('#alatCards').html(html);
                            var filter = $('.btn-group .btn.active').data('filter');
                            if (filter !== 'all') {
                                $('#alatCards .col-xl-3').each(function() {
                                    var jenisAlat = $(this).data('jenis-alat');
                                    if (jenisAlat != filter) {
                                        $(this).hide();
                                    }
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Tidak dapat memuat data!',
                            });
                        }
                    });
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Tidak dapat menyimpan data!',
                        });
                    }
                    });
                    return false;
                }
            });
        });

        function addForm() {
            save_method = "add";
            $('input[name=_method]').val('POST');
            $('#modal-form').modal('show');
            $('#modal-form form')[0].reset();
            $('.modal-title').text('Tambah Device');
        }

        function deleteData(id) {
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Data Pengguna akan dihapus!",
                icon: 'warning',
                showCancelButton: true,
                cancelButtonColor: '#d33',
                confirmButtonColor: "#4E73DF",
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "dashboard/" + id,
                        type: "POST",
                        data: {
                            '_method': 'DELETE',
                            '_token': $('meta[name=csrf-token]').attr('content')
                        },
                        success: function(data) {
                            Swal.fire(
                                'Berhasil!',
                                'Device terhapus.',
                                'success'
                            )
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Tidak dapat menghapus data!',
                            })
                        }
                    });
                }
            })
        }
    </script>
@endsection
