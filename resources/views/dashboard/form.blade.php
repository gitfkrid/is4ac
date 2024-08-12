<div class="modal fade" id="modal-form" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" class="form-horizontal" data-toggle="validator">
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
                            <label for="nama_device" class="form-label">Nama Device</label>
                            <input type="text" id="nama_device" name="nama_device" class="form-control" autofocus/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-3">
                            <label for="topic_mqtt" class="form-label">Topic MQTT</label>
                            <input type="text" id="topic_mqtt" name="topic_mqtt" class="form-control" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-3">
                            <label for="jenis_alat" class="form-label">Jenis Alat</label>
                            <select id="jenis_alat" name="jenis_alat" class="form-control">
                                @foreach($jenis_alat as $id_jenis_alat => $jenis_alat)
                                    <option value="{{ $id_jenis_alat }}">{{ $jenis_alat }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select id="status" name="status" class="form-control">
                                <option value="1">Enable</option>
                                <option value="0">Disable</option>
                            </select>
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