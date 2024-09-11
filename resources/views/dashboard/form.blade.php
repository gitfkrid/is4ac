<div class="modal fade" id="modal-form" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="device-form" method="POST" class="form-horizontal" data-toggle="validator">
                {{ csrf_field() }} {{ method_field('POST') }}
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="id" name="id"/>
                    <div class="row">
                        <div class="col mb-3">
                            <label for="kode_board" class="form-label">Kode Board</label>
                            <input type="text" id="kode_board" name="kode_board" class="form-control" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-3">
                            <label for="nama_device" class="form-label">Nama Device</label>
                            <input type="text" id="nama_device" name="nama_device" class="form-control" autofocus/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-3">
                            <label for="id_jenis_alat" class="form-label">Jenis Alat</label>
                            <select id="id_jenis_alat" name="id_jenis_alat" class="form-control">
                                @foreach($jenis_alat as $id_jenis_alat => $jenis_alat)
                                    <option value="{{ $id_jenis_alat }}">{{ $jenis_alat }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-3">
                            <label for="id_lokasi" class="form-label">Lokasi</label>
                            <select id="id_lokasi" name="id_lokasi" class="form-control">
                                @foreach($lokasi as $data)
                                    <option value="{{ $data->id_lokasi }}">{{ $data->nama_lokasi }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <input type="text" id="keterangan" name="keterangan" class="form-control" autofocus/>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
