@foreach ($alat as $index => $device)
    @if ($device->id_jenis_alat == 1 || $device->id_jenis_alat == 2)
        <div class="col-xl-3 col-md-6 mb-4" data-jenis-alat="{{ $device->id_jenis_alat }}"
            data-lokasi="{{ $device->id_lokasi }}">
            <div class="card shadow h-100 py-2" style="border-radius: 16px; cursor: pointer; position: relative;"
                onclick="window.location='{{ url('/dashboard/' . $device->uuid) }}'">
                <div class="card-body">
                    <!-- Checkbox (Hidden by default) -->
                    <!-- Informasi Device -->
                    <div class="row no-gutters align-items-center mb-3">
                        <div class="col-auto">
                            @if ($device->id_jenis_alat == 1)
                                <i class="fas fa-wind fa-2x text-primary"></i>
                            @else
                                <i class="fas fa-thermometer-half fa-2x text-primary"></i>
                            @endif
                        </div>
                        <div class="col ml-3">
                            <div class="h5 mb-0 font-weight-bold text-primary">{{ $device->nama_device }}</div>
                            <div class="text-muted">{{ $device->kode_board }}</div>
                        </div>
                    </div>
                    <hr>
                    <!-- Informasi Sensor -->
                    <div class="row">
                        @if ($device->id_jenis_alat == 1)
                            {{-- PH3 --}}
                            <div class="col-6 mb-3 d-flex align-items-center">
                                <i class="fas fa-flask text-muted mr-2"></i>
                                <div class="d-flex flex-column">
                                    <div class="small text-muted">Gas PH3</div>
                                    <div class="nfosfin font-weight-bold" data-uuid="{{ $device->uuid }}"
                                        style="line-height: 1;">
                                        {{ $device->fosfin }} ppm
                                    </div>
                                </div>
                            </div>
                        @elseif ($device->id_jenis_alat == 2)
                            {{-- DHT --}}
                            <div class="col-6 d-flex align-items-center">
                                <i class="fas fa-thermometer-half text-muted mr-2"></i>
                                <div class="d-flex flex-column">
                                    <div class="small text-muted">Suhu</div>
                                    <div class="nsuhu font-weight-bold" data-uuid="{{ $device->uuid }}" style="line-height: 1;">{{ $device->suhu }}°C
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 d-flex align-items-center">
                                <i class="fas fa-tint text-muted mr-2"></i>
                                <div class="d-flex flex-column">
                                    <div class="small text-muted">Kelembaban</div>
                                    <div class="nkelembaban font-weight-bold" data-uuid="{{ $device->uuid }}" style="line-height: 1;">
                                        {{ $device->kelembaban }}%</div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Lokasi (Hidden) -->
                    <div style="display: none;">
                        <span>{{ $device->nama_lokasi }}</span>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="col-xl-3 col-md-6 mb-4" data-jenis-alat="{{ $device->id_jenis_alat }}"
            data-lokasi="{{ $device->id_lokasi }}">
            <div class="card shadow h-100 py-2" style="border-radius: 16px;">
                <div class="card-body">
                    <!-- Device Number di pojok kanan atas -->
                    <div style="position: absolute; top: 10px; right: 10px;">
                        <span class="font-weight-bold text-muted"></span>
                    </div>
                    <!-- Informasi Device -->
                    <div class="row no-gutters align-items-center mb-3">
                        <div class="col-auto">
                            <i class="fas fa-fan fa-2x text-primary"></i>
                        </div>
                        <div class="col ml-3">
                            <div class="h5 mb-0 font-weight-bold text-primary">{{ $device->nama_device }}</div>
                            <div class="text-muted">{{ $device->kode_board }}</div>
                        </div>
                    </div>
                    <hr>
                    <!-- Toggle On Off Button -->
                    <div class="d-flex align-items-center mb-3">
                        <div class="d-flex flex-column">
                            <div class="small text-muted">Kondisi Relay</div>
                            <div class="font-weight-bold" style="line-height: 1;">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input"
                                        id="relay_{{ $device->kode_board }}"
                                        @if ($device->state) checked @endif>
                                    <label class="custom-control-label" for="relay_{{ $device->kode_board }}"></label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Lokasi (Hidden) -->
                    <div style="display: none;">
                        <span>{{ $device->nama_lokasi }}</span>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endforeach
{{-- Update Data --}}
<script>
    function fetchSensorData() {
        $.ajax({
            url: "{{ url('/dashboard/alat/sensor') }}",
            method: 'GET',
            success: function(data) {
                data.forEach(function(device) {
                    // Update nilai fosfin hanya untuk alat dengan jenis PH3
                    if (device.jenis_alat === 'PH3') {
                        var $fosfinElement = $('.nfosfin[data-uuid="' + device.uuid + '"]');
                        $fosfinElement.text(device.fosfin ? device.fosfin + ' ppm' : 'ppm');
                    }

                    // Update suhu dan kelembaban hanya untuk alat dengan jenis DHT
                    if (device.jenis_alat === 'DHT') {
                        var $suhuElement = $('.nsuhu[data-uuid="' + device.uuid + '"]');
                        var $kelembabanElement = $('.nkelembaban[data-uuid="' + device.uuid + '"]');
                        $suhuElement.text(device.suhu ? device.suhu + '°C' : '°C');
                        $kelembabanElement.text(device.kelembaban ? device.kelembaban + '%' :
                        '%');
                    }
                });
            },
            error: function() {
                console.error('Tidak dapat mengambil data sensor.');
            }
        });
    }

    setInterval(fetchSensorData, 3000);
</script>
