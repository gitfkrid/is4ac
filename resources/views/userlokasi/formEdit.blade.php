<div class="modal fade" id="modal-edit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" class="form-horizontal" data-toggle="validator">
                {{ csrf_field() }} {{ method_field('POST') }}
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">Edit Lokasi Pengguna</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="user_id" name="user_id" />

                    <div class="form-group">
                        <label for="name">Pilih Pengguna</label>
                        <input type="text" id="nama" name="nama" class="form-control" disabled />
                    </div>

                    <div id="loc-container">
                        <!-- Lokasi pertama tanpa tombol hapus -->
                        <div class="row">
                            <div class="col mb-3">
                                <label for="lok_1">Pilih Lokasi</label>
                                <div class="input-group">
                                    <select id="lok_1" name="lok[]" class="form-control mr-2 loc-select" required>
                                        <option value="">Pilih Lokasi</option>
                                        @foreach ($lokasi as $loc)
                                            <option value="{{ $loc->id_lokasi }}">{{ $loc->nama_lokasi }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="button" id="add-loc" name="add-loc" class="btn btn-primary">Tambah Lokasi</button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
                <script>
                    $(document).ready(function() {
                        function updateLocationOptions() {
                            let selectedLocations = [];

                            $('.loc-select').each(function() {
                                if ($(this).val() !== "") {
                                    selectedLocations.push($(this).val());
                                }
                            });

                            let availableOptions = 0;

                            $('.loc-select').each(function() {
                                let currentSelect = $(this);
                                currentSelect.find('option').each(function() {
                                    if ($(this).val() !== "") {
                                        if (selectedLocations.includes($(this).val()) && $(this).val() !==
                                            currentSelect.val()) {
                                            $(this).hide();
                                        } else {
                                            $(this).show();
                                            if (!selectedLocations.includes($(this).val())) {
                                                availableOptions++;
                                            }
                                        }
                                    }
                                });
                            });

                            if (availableOptions === 0) {
                                $('#add-loc').hide();
                            } else {
                                $('#add-loc').show();
                            }
                        }

                        $('#add-loc').click(function() {
                            let locationIndex = $('#loc-container .row').length + 1;
                            let newLocation = `
                                <div class="row">
                                    <div class="col mb-3">
                                        <label for="lok_${locationIndex}">Pilih Lokasi</label>
                                        <div class="input-group">
                                            <select id="lok_${locationIndex}" name="lok[]" class="form-control mr-2 loc-select">
                                                <option value="">Pilih Lokasi</option>
                                                @foreach ($lokasi as $loc)
                                                    <option value="{{ $loc->id_lokasi }}">{{ $loc->nama_lokasi }}</option>
                                                @endforeach
                                            </select>
                                            <span class="btn btn-outline-danger remove-location" style="cursor: pointer;">
                                                <i class="fas fa-trash-alt"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            `;
                            $('#loc-container').append(newLocation);
                            updateLocationOptions();
                        });

                        // Remove location
                        $(document).on('click', '.remove-location', function() {
                            $(this).closest('.row').remove();
                            updateLocationOptions();
                        });

                        // Update options on change
                        $(document).on('change', '.location-select', function() {
                            updateLocationOptions();
                        });

                        // Reset form and load users when modal is hidden
                        $('#modal-edit').on('hidden.bs.modal', function() {
                            $(this).find('form')[0].reset();
                            $('#loc-container .row').not(':first').remove();
                            updateLocationOptions();
                        });
                    });
                </script>
            </form>
        </div>
    </div>
</div>
