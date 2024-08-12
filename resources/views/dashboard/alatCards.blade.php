@foreach ($alat as $index => $device)
    <div class="col-xl-3 col-md-6 mb-4" data-jenis-alat="{{ $device->id_jenis_alat }}">
        <div class="card shadow h-100 py-2" style="border-radius: 16px; cursor: pointer;"
            onclick="window.location='{{ url('/dashboard/' . $device->uuid) }}'">
            <div class="card-body">
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
                        <div class="text-muted">{{ $device->jenis_alat }}</div>
                    </div>
                </div>
                <hr>
                <!-- Informasi Sensor -->
                <div class="row">
                    <div class="col-6 mb-3 d-flex align-items-center">
                        <i class="fas fa-hashtag text-muted mr-2"></i>
                        <div class="d-flex flex-column">
                            <div class="small text-muted">Device Number</div>
                            <div class="font-weight-bold" style="line-height: 1;">{{ $index + 1 }}</div>
                        </div>
                    </div>
                    <div class="col-6 mb-3 d-flex align-items-center">
                        <i class="fas fa-flask text-muted mr-2"></i>
                        <div class="d-flex flex-column">
                            <div class="small text-muted">Gas PH3</div>
                            <div class="font-weight-bold" style="line-height: 1;">0.03 ppm</div>
                        </div>
                    </div>
                    <div class="col-6 d-flex align-items-center">
                        <i class="fas fa-thermometer-half text-muted mr-2"></i>
                        <div class="d-flex flex-column">
                            <div class="small text-muted">Temperature</div>
                            <div class="font-weight-bold" style="line-height: 1;">28°C</div>
                        </div>
                    </div>
                    <div class="col-6 d-flex align-items-center">
                        <i class="fas fa-tint text-muted mr-2"></i>
                        <div class="d-flex flex-column">
                            <div class="small text-muted">Humidity</div>
                            <div class="font-weight-bold" style="line-height: 1;">70%</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach
