<div class="modal fade" id="modal-form" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" class="form-horizontal" data-toggle="validator">
                {{ csrf_field() }} {{ method_field('POST') }}
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">Tambah Pengguna dan Lokasi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="id" name="id" />

                    <div class="form-group">
                        <label for="id_user">Pilih Pengguna</label>
                        <select id="id_user" name="id_user" class="form-control select2" required autofocus>
                            <option value="">Cari dan Pilih Pengguna</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div id="location-container">
                        <!-- Lokasi pertama tanpa tombol hapus -->
                        <div class="row">
                            <div class="col mb-3">
                                <label for="lokasi_1">Pilih Lokasi</label>
                                <div class="input-group">
                                    <select id="lokasi_1" name="lokasi[]" class="form-control mr-2 location-select"
                                        required>
                                        <option value="">Pilih Lokasi</option>
                                        @foreach ($lokasi as $loc)
                                            <option value="{{ $loc->id_lokasi }}">{{ $loc->nama_lokasi }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="button" id="add-location" class="btn btn-primary">Tambah Lokasi</button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
                <script>
                    $(document).ready(function() {
                        // Function to load users into the select dropdown
                        function loadUsers() {
                            $.ajax({
                                url: '{{ route('userLokasi.getUsers') }}', // Endpoint to get users
                                type: 'GET',
                                success: function(data) {
                                    let userSelect = $('#id_user');
                                    userSelect.empty();
                                    userSelect.append('<option value="">Cari dan Pilih Pengguna</option>');
                                    data.users.forEach(function(user) {
                                        userSelect.append('<option value="' + user.id + '">' + user.name +
                                            '</option>');
                                    });
                                },
                                error: function(xhr) {
                                    console.error('Error loading users:', xhr.responseText);
                                }
                            });
                        }

                        // Load users on page load
                        loadUsers();

                        function updateUserSelectOptions(users) {
                            let $select = $('#id_user');
                            $select.empty();
                            $select.append('<option value="">Cari dan Pilih Pengguna</option>');
                            users.forEach(user => {
                                $select.append(`<option value="${user.id}">${user.name}</option>`);
                            });
                        }

                        window.addEventListener('userListUpdated', function(e) {
                            updateUserSelectOptions(e.detail.users);
                        });

                        // Function to update location options
                        function updateLocationOptions() {
                            let selectedLocations = [];

                            $('.location-select').each(function() {
                                if ($(this).val() !== "") {
                                    selectedLocations.push($(this).val());
                                }
                            });

                            let availableOptions = 0;

                            $('.location-select').each(function() {
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
                                $('#add-location').hide();
                            } else {
                                $('#add-location').show();
                            }
                        }

                        // Add location
                        $('#add-location').click(function() {
                            let locationIndex = $('#location-container .row').length + 1;
                            let newLocation = `
                                <div class="row">
                                    <div class="col mb-3">
                                        <label for="lokasi_${locationIndex}">Pilih Lokasi</label>
                                        <div class="input-group">
                                            <select id="lokasi_${locationIndex}" name="lokasi[]" class="form-control mr-2 location-select">
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
                            $('#location-container').append(newLocation);
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
                        $('#modal-form').on('hidden.bs.modal', function() {
                            $(this).find('form')[0].reset();
                            $('#location-container .row').not(':first').remove();
                            updateLocationOptions();
                            loadUsers(); // Reload users when modal is closed
                        });
                    });
                </script>
            </form>
        </div>
    </div>
</div>
