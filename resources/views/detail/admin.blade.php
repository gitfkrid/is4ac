@extends('layouts.dashboard')

@section('headcontent')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard {{ $alat->nama_device }}</h1>
        <div class="d-flex">
            <a href="javascript:void(0)" class="btn btn-light mr-2" id="exportData">
                <i class="fa fa-download"></i> Export Data
            </a>
            <a href="javascript:void(0)" class="btn btn-warning mr-2" id="editDevice">
                <i class="fa fa-edit"></i> Edit Device
            </a>
            <a href="javascript:void(0)" class="btn btn-danger" id="deleteDevice">
                <i class="fa fa-trash"></i>
            </a>
        </div>
    </div>

    @include('detail.exportModal')
    @include('detail.editForm')

    <!-- Content Row -->
    <div class="row">
        @if ($alat->id_jenis_alat == 1)
            <!-- 1 Fosfin -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Fosfin (PH3)</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><span
                                        id="fosfin">{{ $nilaisensor->fosfin }}</span> ppm</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-tint fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2 Lokasi -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Lokasi</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><span
                                        id="lokasi">{{ $nilaisensor->nama_lokasi }}</span></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-warehouse fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- 1 Suhu -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Suhu</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><span
                                        id="suhu">{{ $nilaisensor->suhu }}</span> °C</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-thermometer-half fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2 Kelembaban -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Kelembaban</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><span
                                        id="kelembaban">{{ $nilaisensor->kelembaban }}</span> %
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-water fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3 Lokasi -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Lokasi</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><span
                                        id="lokasi">{{ $nilaisensor->nama_lokasi }}</span></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-warehouse fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@section('content')
    @if ($alat->id_jenis_alat == 1)
        <!-- Area Chart -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Chart PH3</h6>
            </div>
            <div class="card-body">
                <div class="chart-area" style="height: 410px;">
                    <canvas id="areaChart"></canvas>
                </div>
                <hr>
                Terakhir di update pada <span id="updated_at_c">{{ $nilaisensor->updated_at }}</span>
            </div>
        </div>
    @else
        <div class="row">
            <!-- Area Chart 1 -->
            <div class="col-lg-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        @if ($alat->id_jenis_alat == 1)
                            <h6 class="m-0 font-weight-bold text-primary">Chart Fosfin (PH3)</h6>
                        @else
                            <h6 class="m-0 font-weight-bold text-primary">Chart Suhu</h6>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="chart-area" style="height: 400px;">
                            <canvas id="areaChart"></canvas>
                        </div>
                        <hr>
                        Terakhir di update pada <span id="updated_at_a">{{ $nilaisensor->updated_at }}</span>
                    </div>
                </div>
            </div>

            <!-- Area Chart 2 -->
            <div class="col-lg-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-success">Chart Kelembaban</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-area" style="height: 400px;">
                            <canvas id="areaChartHumi"></canvas>
                        </div>
                        <hr>
                        Terakhir di update pada <span id="updated_at_b">{{ $nilaisensor->updated_at }}</span>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@section('script')
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '{{ session('success') }}',
            });
        </script>
    @endif
    <script>
        $(document).ready(function() {
            $('#editDevice').on('click', function() {
                $('#modal-form').modal('show');
            });
            var uuid = "{{ $alat->uuid }}";
            save_method = 'edit';
            $('input[name=_method]').val('PATCH');
            $.ajax({
                url: uuid + "/edit",
                type: "GET",
                success: function(data) {
                    $('#modal-edit').modal('show');
                    $('.modal-title').text('Edit Device');

                    $('#id').val(data.id_alat);
                    $('#kode_board').val(data.kode_board);
                    $('#nama_device').val(data.nama_device);
                    $('#id_jenis_alat').val(data.id_jenis_alat);
                    $('#id_lokasi').val(data.id_lokasi);
                    $('#keterangan').val(data.keterangan);
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Tidak dapat mengambil data!',
                    })
                }
            });

            $('#form-edit').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    url: "{{ route('detail_dashboard.update', $alat->uuid) }}",
                    type: "POST",
                    data: $('#form-edit').serialize(),
                    dataType: "json",
                    headers: {
                        'Accept': 'application/json',
                    },
                    success: function(response) {
                        $('#modal-edit').modal('hide');
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

        $(document).ready(function() {
            $('#deleteDevice').on('click', function() {
                var uuid = "{{ $alat->uuid }}";
                Swal.fire({
                    title: 'Apakah anda yakin?',
                    text: "Data Device akan dihapus!",
                    icon: 'warning',
                    showCancelButton: true,
                    cancelButtonColor: '#d33',
                    confirmButtonColor: "#4E73DF",
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: uuid,
                            type: "POST",
                            data: {
                                '_method': 'DELETE',
                                '_token': $('meta[name=csrf-token]').attr('content')
                            },
                            success: function() {
                                Swal.fire(
                                    'Berhasil!',
                                    'Data Device terhapus.',
                                    'success'
                                ).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.href =
                                            "{{ route('dashboard') }}";
                                    }
                                });
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
                });
            });
        });

        $(document).ready(function() {
            $('#exportData').on('click', function() {
                $('#exportModal').modal('show');
                $('.modal-titles').text('Export Data');
                $('#exportModal form')[0].reset();
            });

            $('#submitExport').on('click', function() {
                var startDate = $('#startDate').val();
                var endDate = $('#endDate').val();

                if (startDate === '' || endDate === '') {
                    alert('Please select both start and end dates.');
                    return;
                }

                var uuid = "{{ $alat->uuid }}";

                $.ajax({
                    url: uuid + "/export-data",
                    type: 'POST',
                    data: {
                        startDate: startDate,
                        endDate: endDate,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        var blob = new Blob([response], {
                            type: 'text/csv'
                        });
                        var link = document.createElement('a');
                        link.href = window.URL.createObjectURL(blob);
                        link.download = "sensor_data.csv";
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                        $('#exportModal').modal('hide');
                    },
                    error: function(xhr, status, error) {
                        alert('Error: ' + error);
                    }
                });
            });
        });
    </script>

    {{-- <script src="{{ asset('public/assets/vendor/chart.js/Chart.min.js') }}"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        let chartAInstance;
        let chartBInstance;
    
        function fetchDataAndUpdateChartA() {
            var uuid = "{{ $alat->uuid }}";
            fetch(uuid + "/chart")
                .then(response => response.json())
                .then(data => {
                    console.log(data); // Debugging untuk melihat data yang diterima
    
                    const ctx = document.getElementById('areaChart');
                    ctx.style.height = '100%';
                    ctx.style.width = '100%';
                    if (chartAInstance) {
                        // Update chart jika sudah ada
                        chartAInstance.data.labels = data.labels;
                        chartAInstance.data.datasets = data.datasets.map(dataset => ({
                            label: dataset.label,
                            data: dataset.data,
                            borderColor: dataset.borderColor,
                            borderWidth: dataset.borderWidth,
                            fill: dataset.fill,
                        }));
                        chartAInstance.update(); // Perbarui chart
                    } else {
                        // Buat instance baru
                        chartAInstance = new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: data.labels,
                                datasets: data.datasets.map(dataset => ({
                                    label: dataset.label,
                                    data: dataset.data,
                                    borderColor: dataset.borderColor,
                                    borderWidth: dataset.borderWidth,
                                    fill: dataset.fill,
                                }))
                            },
                            options: {
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        suggestedMin: Math.min(...data.datasets.flatMap(dataset => dataset.data)) - 1,
                                        suggestedMax: Math.max(...data.datasets.flatMap(dataset => dataset.data)) + 1
                                    }
                                }
                            }
                        });
                    }
                    // Panggil kembali fungsi ini setiap menit
                    setTimeout(fetchDataAndUpdateChartA, 60000); // 60000 ms = 1 menit
                })
                .catch(error => {
                    console.error('Error fetching chart data:', error);
                    // Tetap coba lagi setelah 1 menit meskipun terjadi error
                    setTimeout(fetchDataAndUpdateChartA, 60000); 
                });
        }
    
        function fetchDataAndUpdateChartB() {
            var uuid = "{{ $alat->uuid }}";
            fetch(uuid + "/chart/kelembaban")
                .then(response => response.json())
                .then(data => {
                    console.log(data); // Debugging untuk melihat data yang diterima
    
                    const ctx = document.getElementById('areaChartHumi');
                    ctx.style.height = '100%';
                    ctx.style.width = '100%';
                    if (chartBInstance) {
                        // Update chart jika sudah ada
                        chartBInstance.data.labels = data.labels;
                        chartBInstance.data.datasets = data.datasets.map(dataset => ({
                            label: dataset.label,
                            data: dataset.data,
                            borderColor: dataset.borderColor,
                            borderWidth: dataset.borderWidth,
                            fill: dataset.fill,
                        }));
                        chartBInstance.update(); // Perbarui chart
                    } else {
                        // Buat instance baru
                        chartBInstance = new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: data.labels,
                                datasets: data.datasets.map(dataset => ({
                                    label: dataset.label,
                                    data: dataset.data,
                                    borderColor: dataset.borderColor,
                                    borderWidth: dataset.borderWidth,
                                    fill: dataset.fill,
                                }))
                            },
                            options: {
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        suggestedMin: Math.min(...data.datasets.flatMap(dataset => dataset.data)) - 1,
                                        suggestedMax: Math.max(...data.datasets.flatMap(dataset => dataset.data)) + 1
                                    }
                                }
                            }
                        });
                    }
                    // Panggil kembali fungsi ini setiap menit
                    setTimeout(fetchDataAndUpdateChartB, 30000); // 60000 ms = 1 menit
                })
                .catch(error => {
                    console.error('Error fetching chart data:', error);
                    // Tetap coba lagi setelah 1 menit meskipun terjadi error
                    setTimeout(fetchDataAndUpdateChartB, 30000);
                });
        }
    
        // Panggil fungsi untuk pertama kali
        fetchDataAndUpdateChartA();
        fetchDataAndUpdateChartB();
    </script>    

    {{-- Update Data --}}
    <script>
        function fetchSensorData() {
            $.ajax({
                url: "{{ url('/dashboard/' . $alat->uuid . '/sensor-data') }}",
                method: 'GET',
                success: function(data) {
                    if (data.fosfin !== undefined) {
                        $('#fosfin').text(data.fosfin);
                    } else {
                        $('#suhu').text(data.suhu);
                        $('#kelembaban').text(data.kelembaban);
                    }
                    $('#lokasi').text(data.nama_lokasi);
                    $('#updated_at_a').text(data.updated_at);
                    $('#updated_at_b').text(data.updated_at);
                    $('#updated_at_c').text(data.updated_at);
                },
                error: function() {
                    console.error('Tidak dapat mengambil data sensor.');
                }
            });
        }

        setInterval(fetchSensorData, 3000);
    </script>
@endsection
