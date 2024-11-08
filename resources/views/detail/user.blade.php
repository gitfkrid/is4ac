@extends('layouts.app_user')

@section('headcontent')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard {{ $alat->nama_device }}</h1>
        <div class="d-flex">
            <a href="javascript:void(0)" class="btn btn-light mr-2" id="exportData">
                <i class="fa fa-download"></i> Export Data
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
                <h6 class="m-0 font-weight-bold text-primary">Chart {{ $alat->nama_device }}</h6>
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
            $('#exportData').on('click', function() {
                $('#exportModal').modal('show');
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

    <script src="{{ asset('public/assets/vendor/chart.js/Chart.min.js') }}"></script>


    <script>
        var myLineChart, myHumiChart;

        function showChart() {
            Chart.defaults.global.defaultFontFamily =
                "Nunito, '-apple-system', system-ui, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif";
            Chart.defaults.global.defaultFontColor = "#858796";

            var jenis_alat = "{{ $alat->id_jenis_alat }}";
            var desimal = jenis_alat == 1 ? 3 : 2;

            function number_format(number, decimals = desimal, dec_point = ".", thousands_sep = ",") {
                number = (number + "").replace(",", "").replace(" ", "");
                var n = !isFinite(+number) ? 0 : +number,
                    prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
                    sep = thousands_sep,
                    dec = dec_point,
                    s = "",
                    toFixedFix = function(n, prec) {
                        var k = Math.pow(10, prec);
                        return "" + Math.round(n * k) / k;
                    };
                s = (prec ? toFixedFix(n, prec) : "" + Math.round(n)).split(".");
                if (s[0].length > 3) {
                    s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
                }
                if ((s[1] || "").length < prec) {
                    s[1] = s[1] || "";
                    s[1] += new Array(prec - s[1].length + 1).join("0");
                }
                return s.join(dec);
            }

            // Function to fetch and update the Suhu/Fosfin chart
            function fetchAndUpdateChart() {
                var uuid = "{{ $alat->uuid }}";
                fetch(uuid + "/chart")
                    .then(response => response.json())
                    .then(data => {
                        var ctx1 = document.getElementById("areaChart").getContext("2d");
                        if (myLineChart) {
                            myLineChart.data.labels = data.labels;
                            myLineChart.data.datasets[0].data = data.values;
                            myLineChart.update();
                        } else {
                            myLineChart = new Chart(ctx1, {
                                type: "line",
                                data: {
                                    labels: data.labels,
                                    datasets: [{
                                        label: "Sensor Value",
                                        lineTension: 0.3,
                                        backgroundColor: "rgba(78, 115, 223, 0.05)",
                                        borderColor: "rgba(78, 115, 223, 1)",
                                        pointRadius: 3,
                                        pointBackgroundColor: "rgba(78, 115, 223, 1)",
                                        pointBorderColor: "rgba(78, 115, 223, 1)",
                                        pointHoverRadius: 3,
                                        pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
                                        pointHoverBorderColor: "rgba(78, 115, 223, 1)",
                                        pointHitRadius: 10,
                                        pointBorderWidth: 2,
                                        data: data.values,
                                    }],
                                },
                                options: {
                                    maintainAspectRatio: false,
                                    layout: {
                                        padding: {
                                            left: 10,
                                            right: 25,
                                            top: 25,
                                            bottom: 0,
                                        },
                                    },
                                    scales: {
                                        xAxes: [{
                                            time: {
                                                unit: "time",
                                            },
                                            gridLines: {
                                                display: false,
                                                drawBorder: false,
                                            },
                                            ticks: {
                                                maxTicksLimit: 7,
                                            },
                                        }],
                                        yAxes: [{
                                            ticks: {
                                                maxTicksLimit: 5,
                                                padding: 10,
                                                callback: function(value, index, values) {
                                                    return number_format(value);
                                                },
                                            },
                                            gridLines: {
                                                color: "rgb(234, 236, 244)",
                                                zeroLineColor: "rgb(234, 236, 244)",
                                                drawBorder: false,
                                                borderDash: [2],
                                                zeroLineBorderDash: [2],
                                            },
                                        }],
                                    },
                                    legend: {
                                        display: false,
                                    },
                                    tooltips: {
                                        backgroundColor: "rgb(255,255,255)",
                                        bodyFontColor: "#858796",
                                        titleMarginBottom: 10,
                                        titleFontColor: "#6e707e",
                                        titleFontSize: 14,
                                        borderColor: "#dddfeb",
                                        borderWidth: 1,
                                        xPadding: 15,
                                        yPadding: 15,
                                        displayColors: false,
                                        intersect: false,
                                        mode: "index",
                                        caretPadding: 10,
                                        callbacks: {
                                            title: function(tooltipItems, data) {
                                                // Show the sensor value as the title (bold text at the top)
                                                var sensorValue = number_format(tooltipItems[0].yLabel);
                                                return 'Value: ' + sensorValue;
                                            },
                                            label: function(tooltipItem) {
                                                // Display the time in regular font below the sensor value
                                                return tooltipItem.label;
                                            },
                                        },
                                    },
                                },
                            });
                        }
                    })
                    .catch(error => console.error("Error fetching sensor data:", error));
            }

            // Function to fetch and update the Kelembaban chart
            function fetchAndUpdateHumiChart() {
                var uuid = "{{ $alat->uuid }}";
                fetch(uuid + "/chart/kelembaban")
                    .then(response => response.json())
                    .then(data => {
                        var ctx2 = document.getElementById("areaChartHumi").getContext("2d");
                        if (myHumiChart) {
                            myHumiChart.data.labels = data.labels;
                            myHumiChart.data.datasets[0].data = data.values;
                            myHumiChart.update();
                        } else {
                            myHumiChart = new Chart(ctx2, {
                                type: "line",
                                data: {
                                    labels: data.labels,
                                    datasets: [{
                                        label: "Humidity",
                                        lineTension: 0.3,
                                        backgroundColor: "rgba(28, 200, 138, 0.05)",
                                        borderColor: "rgba(28, 200, 138, 1)",
                                        pointRadius: 3,
                                        pointBackgroundColor: "rgba(28, 200, 138, 1)",
                                        pointBorderColor: "rgba(28, 200, 138, 1)",
                                        pointHoverRadius: 3,
                                        pointHoverBackgroundColor: "rgba(28, 200, 138, 1)",
                                        pointHoverBorderColor: "rgba(28, 200, 138, 1)",
                                        pointHitRadius: 10,
                                        pointBorderWidth: 2,
                                        data: data.values,
                                    }],
                                },
                                options: {
                                    maintainAspectRatio: false,
                                    layout: {
                                        padding: {
                                            left: 10,
                                            right: 25,
                                            top: 25,
                                            bottom: 0,
                                        },
                                    },
                                    scales: {
                                        xAxes: [{
                                            time: {
                                                unit: "time",
                                            },
                                            gridLines: {
                                                display: false,
                                                drawBorder: false,
                                            },
                                            ticks: {
                                                maxTicksLimit: 7,
                                            },
                                        }],
                                        yAxes: [{
                                            ticks: {
                                                maxTicksLimit: 5,
                                                padding: 10,
                                                callback: function(value, index, values) {
                                                    return number_format(value);
                                                },
                                            },
                                            gridLines: {
                                                color: "rgb(234, 236, 244)",
                                                zeroLineColor: "rgb(234, 236, 244)",
                                                drawBorder: false,
                                                borderDash: [2],
                                                zeroLineBorderDash: [2],
                                            },
                                        }],
                                    },
                                    legend: {
                                        display: false,
                                    },
                                    tooltips: {
                                        backgroundColor: "rgb(255,255,255)",
                                        bodyFontColor: "#858796",
                                        titleMarginBottom: 10,
                                        titleFontColor: "#6e707e",
                                        titleFontSize: 14,
                                        borderColor: "#dddfeb",
                                        borderWidth: 1,
                                        xPadding: 15,
                                        yPadding: 15,
                                        displayColors: false,
                                        intersect: false,
                                        mode: "index",
                                        caretPadding: 10,
                                        callbacks: {
                                            title: function(tooltipItems, data) {
                                                // Show the humidity value as the title (bold text at the top)
                                                var humidityValue = number_format(tooltipItems[0].yLabel);
                                                return 'Humidity: ' + humidityValue;
                                            },
                                            label: function(tooltipItem) {
                                                // Display the time in regular font below the humidity value
                                                return tooltipItem.label;
                                            },
                                        },
                                    },

                                },
                            });
                        }
                    })
                    .catch(error => console.error("Error fetching humidity data:", error));
            }

            // Fetch data and initialize charts
            fetchAndUpdateChart();
            fetchAndUpdateHumiChart();
            setInterval(fetchAndUpdateChart, 5000);
            setInterval(fetchAndUpdateHumiChart, 5000);
        }
        showChart();
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
