@extends('layouts.app')

@section('headcontent')
<div class="mb-3">
    <a href="javascript:void(0)" class="btn btn-primary" onclick="addForm()"><i class="fa fa-plus-circle"></i> Tambah Data</a>
</div>
@endsection

@section('content')
<div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary">Tabel Pengguna IS4AC</h6>
</div>
<div class="card-body">
    <div class="table-responsive">
        <table class="table table-bordered" id="table" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Pengguna</th>
                    <th>Email Pengguna</th>
                    <th>Terdaftar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>
@include('pengguna.form')
@endsection

@section('script')
    <script type="text/javascript">
        var table, save_method;

        $(function(){
            table = $('#table').DataTable({
                "processing" : true,
                'responsive' : true,
                'scrollY'     : true,
                'autoWidth'   : false,
                "ajax" : {
                    "url" : "{{ route('pengguna.data') }}",
                    "type" : "GET"
                }
            });

            $('#modal-form form').on('submit', function(e){
                if(!e.isDefaultPrevented()){
                    $.ajax({
                        url : "{{ route('pengguna.store') }}",
                        type : "POST",
                        data : $('#modal-form form').serialize(),
                        success : function(data){
                            $('#modal-form').modal('hide');
                            table.ajax.reload();
                        },
                        error : function(){
                            alert("Tidak dapat menyimpan data!");
                        }
                    });
                    return false;
                }
            });
        });

        function addForm(){
            save_method = "add";
            $('input[name=_method]').val('POST');
            $('#modal-form').modal('show');
            $('#modal-form form')[0].reset();
            $('.modal-title').text('Tambah Pengguna');
        }

        function deleteData(id) {
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Data Pengguna akan dihapus!",
                icon: 'warning',
                showCancelButton: true,
                cancelButtonColor: '#d33',
                confirmButtonColor: "#4E73DF",
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
                }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url : "pengguna/"+id,
                        type : "POST",
                        data : {'_method' : 'DELETE', '_token' : $('meta[name=csrf-token]').attr('content')},
                        success : function(data) {
                            Swal.fire(
                                'Berhasil!',
                                'Data pengguna terhapus.',
                                'success'
                            )
                            table.ajax.reload();
                        },
                        error : function () {
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