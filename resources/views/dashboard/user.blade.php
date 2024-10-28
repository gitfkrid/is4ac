@extends('layouts.app_user')

@section('headcontent')
    <div class="mb-3 d-flex justify-content-between align-items-center">
        <!-- Button Filter -->
        <div class="dropdown mr-2">
            <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="filterDropdown" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-filter"></i> Filter
            </button>
            <div class="dropdown-menu" aria-labelledby="filterDropdown">
                <h6 class="dropdown-header">Jenis Alat</h6>
                <a class="dropdown-item" href="javascript:void(0)" data-filter="all">Semua</a>
                @foreach ($filter as $id_jenis_alat => $filter)
                    <a class="dropdown-item" href="javascript:void(0)" data-filter="{{ $id_jenis_alat }}">
                        {{ $filter }}
                    </a>
                @endforeach
                <div class="dropdown-divider"></div>
                <h6 class="dropdown-header">Lokasi</h6>
                @foreach ($lokasi as $lokasiItem)
                    <a class="dropdown-item" href="javascript:void(0)" data-location="{{ $lokasiItem->id_lokasi }}">
                        {{ $lokasiItem->nama_lokasi }}
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Button Otomatisasi -->
        <button class="btn btn-outline-secondary mr-2" id="nilaiBatasButton">
            <i class="fa fa-sliders-h"></i> Otomatis
        </button>
    </div>
    @include('dashboard.nilaiForm')
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
        @include('dashboard.alatCards')
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        var save_method;

        // Filter
        $(function() {
            // Ambil pilihan jenis alat dan lokasi dari localStorage atau default ke 'all' dan lokasi pertama
            var selectedJenisAlat = localStorage.getItem('selectedJenisAlat') || 'all';
            var selectedLokasi = localStorage.getItem('selectedLokasi') || $('.dropdown-item[data-location]')
                .first().data('location');

            function applyFilters() {
                $('#alatCards .col-xl-3').each(function() {
                    var jenisAlat = $(this).data('jenis-alat');
                    var lokasiAlat = $(this).data('lokasi');

                    var matchJenisAlat = (selectedJenisAlat === 'all' || jenisAlat == selectedJenisAlat);
                    var matchLokasi = (selectedLokasi === 'all' || lokasiAlat == selectedLokasi);

                    if (matchJenisAlat && matchLokasi) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            }

            // Event listener untuk jenis alat
            $('.dropdown-item[data-filter]').on('click', function() {
                selectedJenisAlat = $(this).data('filter');

                // Simpan jenis alat yang dipilih ke localStorage
                localStorage.setItem('selectedJenisAlat', selectedJenisAlat);

                $('.dropdown-item[data-filter]').removeClass('active');
                $(this).addClass('active');

                applyFilters();
            });

            // Event listener untuk lokasi
            $('.dropdown-item[data-location]').on('click', function() {
                selectedLokasi = $(this).data('location');

                // Simpan lokasi yang dipilih ke localStorage
                localStorage.setItem('selectedLokasi', selectedLokasi);

                $('.dropdown-item[data-location]').removeClass('active');
                $(this).addClass('active');

                applyFilters();
            });

            // Aktifkan jenis alat berdasarkan pilihan dari localStorage
            $('.dropdown-item[data-filter]').each(function() {
                if ($(this).data('filter') == selectedJenisAlat) {
                    $(this).addClass('active');
                }
            });

            // Aktifkan lokasi berdasarkan pilihan dari localStorage atau default ke lokasi pertama
            $('.dropdown-item[data-location]').each(function() {
                if ($(this).data('location') == selectedLokasi) {
                    $(this).addClass('active');
                }
            });

            applyFilters();
        });

        // Show modal form
        function addForm() {
            var form = $('#modal-form form');
            if (form.length > 0) {
                form[0].reset();
                $('.modal-title').text('Tambah Device');
                $('#modal-form').modal('show');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Tidak dapat memuat Form!',
                });
            }
        }

        $(document).ready(function() {
            $('#nilaiBatasButton').on('click', function() {
                save_method = 'edit';
                $('input[name=_method]').val('PATCH');
                $.ajax({
                    url: "{{ route('nilaibatas.edit') }}",
                    type: "GET",
                    success: function(data) {
                        // Set nilai pada form modal sesuai dengan data yang diterima
                        $('#nb_suhu_atas').val(data.nb_suhu_atas);
                        $('#nb_suhu_bawah').val(data.nb_suhu_bawah);
                        $('#nb_rh_atas').val(data.nb_rh_atas);
                        $('#nb_rh_bawah').val(data.nb_rh_bawah);
                        $('#nb_ph3_atas').val(data.nb_ph3_atas);
                        $('#nb_ph3_bawah').val(data.nb_ph3_bawah);

                        if (parseInt(data.status) === 1) {
                            $('#statusOtomatis').prop('checked', true);
                        } else {
                            $('#statusManual').prop('checked', true);
                        }

                        // Tampilkan modal
                        $('#modal-nilai-batas').modal('show');
                        $('.modal-title').text('Edit Nilai Batas');
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Tidak dapat mengambil data!',
                        })
                    }
                });
            });

            $('#nilaiBatasForm').on('submit', function(e) {
                e.preventDefault();
                isi = $('#nilaiBatasForm').serialize();

                $.ajax({
                    url: "{{ route('nilaibatas.update') }}",
                    type: "POST",
                    data: isi,
                    dataType: 'json',
                    success: function(response) {
                        $('#modal-nilai-batas').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response
                                .success,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                location
                                    .reload();
                            }
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: 'Terjadi kesalahan saat mengupdate data!',
                        });
                    }
                });
            });
        });

        function initializeToggleListeners() {
            $('.custom-control-input').off('change').on('change', function() {
                let kodeBoard = $(this).attr('id').split('_')[1];
                let state = $(this).is(':checked') ? 1 : 0;

                $.ajax({
                    url: "dashboard/toggleRelay/" + kodeBoard,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        state: state
                    },
                    success: function(response) {
                        if (response.success) {
                            console.log('Relay state updated successfully');
                        } else {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Peringatan',
                                text: response.message,
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    location.reload();
                                }
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: 'Terjadi kesalahan saat memperbarui relay.',
                        });
                    }
                });
            });
        }

        function reloadAlatCards() {
            // Reload alatCards section
            $.ajax({
                url: "{{ route('dashboard.alatCards') }}",
                type: "GET",
                success: function(html) {
                    $('#alatCards').html(html);

                    $('#addDeviceButton').on('click', addForm);

                    var filter = $('.dropdown-item.active[data-filter]').data('filter');
                    var location = $('.dropdown-item.active[data-location]').data('location');

                    $('#alatCards .col-xl-3').each(function() {
                        var jenisAlat = $(this).data('jenis-alat');
                        var lokasiAlat = $(this).data('lokasi');
                        var show = true;

                        if (filter && filter !== 'all' && jenisAlat !== filter) {
                            show = false;
                        }

                        if (location && lokasiAlat !== location) {
                            show = false;
                        }

                        $(this).toggle(show);
                    });
                    initializeToggleListeners();
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Tidak dapat memuat data!',
                    });
                }
            });
        }

        $(function() {
            $('#addDeviceButton').on('click', addForm);

            $('#modal-form form').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('dashboard.store') }}",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(data) {
                        $('#modal-form').modal('hide');
                        Swal.fire(
                            'Berhasil!',
                            'Device berhasil ditambahkan!',
                            'success'
                        ).then((result) => {
                            if (result.isConfirmed) {
                                reloadAlatCards();
                                $('#device-container').load('/dashboard/alat/cards',
                                    function() {
                                        initializeToggleListeners();
                                    });
                            }
                        });
                    },
                    error: function(xhr) {
                        let errorMessage = 'Tidak dapat menyimpan data!';

                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            if (xhr.responseJSON.message.includes('validation.unique')) {
                                errorMessage = 'Nama Device atau Kode Board tidak boleh sama!';
                            } else {
                                errorMessage = xhr.responseJSON.message;
                            }
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: errorMessage,
                        });
                    }
                });
            });
            initializeToggleListeners();
        });
    </script>
@endsection
