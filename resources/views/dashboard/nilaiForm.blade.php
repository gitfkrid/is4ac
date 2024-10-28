<div class="modal fade" id="modal-nilai-batas" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="nilaiBatasForm" method="POST" class="form-horizontal" data-toggle="validator">
                {{ csrf_field() }} {{ method_field('POST') }}
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">Edit Nilai Batas</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nb_suhu_atas">Suhu Atas</label>
                        <input type="text" class="form-control" id="nb_suhu_atas" name="nb_suhu_atas">
                    </div>
                    <div class="form-group">
                        <label for="nb_suhu_bawah">Suhu Bawah</label>
                        <input type="text" class="form-control" id="nb_suhu_bawah" name="nb_suhu_bawah">
                    </div>
                    <div class="form-group">
                        <label for="nb_rh_atas">RH Atas</label>
                        <input type="text" class="form-control" id="nb_rh_atas" name="nb_rh_atas">
                    </div>
                    <div class="form-group">
                        <label for="nb_rh_bawah">RH Bawah</label>
                        <input type="text" class="form-control" id="nb_rh_bawah" name="nb_rh_bawah">
                    </div>
                    <div class="form-group">
                        <label for="nb_ph3_atas">PH3 Atas</label>
                        <input type="text" class="form-control" id="nb_ph3_atas" name="nb_ph3_atas">
                    </div>
                    <div class="form-group">
                        <label for="nb_ph3_bawah">PH3 Bawah</label>
                        <input type="text" class="form-control" id="nb_ph3_bawah" name="nb_ph3_bawah">
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="status" id="statusOtomatis" value="1">
                            <label class="form-check-label" for="statusOtomatis">Otomatis</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="status" id="statusManual" value="0">
                            <label class="form-check-label" for="statusManual">Manual</label>
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
