@extends('layouts.dashboard')

@section('headcontent')
    <div class="mb-3 d-flex justify-content-between align-items-center">
        <!-- Button Filter -->
        <div class="dropdown">
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

        <!-- Tombol Tambah Device dan Delete Device -->
        <div class="d-flex align-items-center">
            <a href="javascript:void(0)" class="btn btn-primary mr-2" id="addDeviceButton">
                <i class="fa fa-plus-circle"></i> Tambah Device
            </a>
        </div>
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
        @include('dashboard.alatCards')
    </div>
    @include('dashboard.form')
@endsection

@section('script')
    <script type="text/javascript">
        var save_method;

        // Filter
        $(function() {
            var selectedJenisAlat = 'all';
            var selectedLokasi = $('.dropdown-item[data-location]').first().data('location');

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

            $('.dropdown-item[data-filter]').on('click', function() {
                selectedJenisAlat = $(this).data('filter');

                $('.dropdown-item[data-filter]').removeClass('active');
                $(this).addClass('active');

                applyFilters();
            });

            $('.dropdown-item[data-location]').on('click', function() {
                selectedLokasi = $(this).data('location');

                $('.dropdown-item[data-location]').removeClass('active');
                $(this).addClass('active');

                applyFilters();
            });

            $('.dropdown-item[data-filter="all"]').addClass('active');
            $('.dropdown-item[data-location]').first().addClass('active');

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
                            console.error('Failed to update relay state');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
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
