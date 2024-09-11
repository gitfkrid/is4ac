@extends('layouts.app')

@section('headcontent')
    <div class="mb-3">
        <a href="javascript:void(0)" class="btn btn-primary" onclick="addForm()"><i class="fa fa-plus-circle"></i> Tambah
            Data</a>
    </div>
@endsection

@section('content')
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Tabel Manajemen Lokasi Pengguna</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="table" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Pengguna</th>
                        <th>Email Pengguna</th>
                        <th>Lokasi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
    @include('userlokasi.form')
    @include('userlokasi.formEdit')
@endsection

@section('script')
    <script type="text/javascript">
        var table, save_method

        $(function() {
            table = $('#table').DataTable({
                "processing": true,
                'responsive': true,
                'scrollY': true,
                'autoWidth': false,
                "ajax": {
                    "url": "{{ route('userLokasi.data') }}",
                    "type": "GET"
                }
            });

            $('#modal-form form').on('submit', function(e) {
                if (!e.isDefaultPrevented()) {
                    $.ajax({
                        url: "{{ route('userLokasi.store') }}",
                        type: "POST",
                        data: $('#modal-form form').serialize(),
                        success: function(data) {
                            $('#modal-form').modal('hide');
                            Swal.fire(
                                'Berhasil!',
                                'Lokasi pengguna berhasil ditambahkan!',
                                'success'
                            )
                            table.ajax.reload();
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Tidak dapat menyimpan data!',
                            })
                        }
                    });
                    return false;
                }
            });

            $('#modal-edit form').on('submit', function(e) {
                if (!e.isDefaultPrevented()) {
                    $.ajax({
                        url: "{{ route('userLokasi.update') }}",
                        type: "POST",
                        data: $('#modal-edit form').serialize(),
                        success: function(data) {
                            $('#modal-edit').modal('hide');
                            Swal.fire(
                                'Berhasil!',
                                'Lokasi pengguna berhasil ditambahkan!',
                                'success'
                            )
                            table.ajax.reload();
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Tidak dapat menyimpan data!',
                            })
                        }
                    });
                    return false;
                }
            });
        });

        function addForm() {
            save_method = "add";
            $('input[name=_method]').val('POST');
            $('#modal-form').modal('show');
            $('#modal-form form')[0].reset();
            $('.modal-title').text('Tambah Lokasi Pengguna');
        }

        function editData(id) {
            save_method = "edit";
            $('input[name=_method]').val('POST');
            $('#modal-edit form')[0].reset();

            $.ajax({
                url: "lokasi/" + id + "/edit",
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $('#modal-edit').modal('show');

                    $('#user_id').val(data.id_user);
                    $('#nama').val(data.name);
                },
                error: function() {
                    alert("Tidak dapat menampilkan data!");
                }
            });
        }

        function deleteData(id) {
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Data Lokasi pengguna akan dihapus!",
                icon: 'warning',
                showCancelButton: true,
                cancelButtonColor: '#d33',
                confirmButtonColor: "#4E73DF",
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "./lokasi/" + id,
                        type: "POST",
                        data: {
                            '_method': 'DELETE',
                            '_token': $('meta[name=csrf-token]').attr('content')
                        },
                        success: function() {
                            Swal.fire(
                                'Berhasil!',
                                'Data lokasi pengguna terhapus.',
                                'success'
                            )
                            $.ajax({
                                url: "{{ route('userLokasi.getUsers') }}",
                                type: 'GET',
                                success: function(response) {
                                    const event = new CustomEvent('userListUpdated', {
                                        detail: {
                                            users: response.users
                                        }
                                    });
                                    window.dispatchEvent(event);
                                },
                                error: function() {
                                    console.error('Failed to load users');
                                }
                            });
                            table.ajax.reload();
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
            })
        }
    </script>
@endsection
