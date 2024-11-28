@extends('layouts.app_user')

@section('headcontent')
    <div class="d-flex justify-content-start mb-3">
        <button id="daterange-start" class="btn btn-primary btn-sm mr-2">
            <i class="fas fa-calendar-alt"></i> Start Date
        </button>
        <button id="daterange-end" class="btn btn-secondary btn-sm">
            <i class="fas fa-calendar-alt"></i> End Date
        </button>
    </div>
@endsection

@section('content')
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Tabel Log Exhaust IS4AC</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="table" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Rata-rata Suhu</th>
                        <th>Rata-rata Kelembaban</th>
                        <th>Mode</th>
                        <th>Waktu</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        var table;

        $(function() {
            table = $('#table').DataTable({
                "processing": true,
                "responsive": true,
                "scrollY": true,
                "autoWidth": false,
                "ajax": {
                    "url": "{{ route('log.data') }}",
                    "type": "GET",
                    "data": function(d) {
                        d.start_date = $('#daterange-start').text() || moment().format(
                        'YYYY-MM-DD'); // Default hari ini
                        d.end_date = $('#daterange-end').text() || moment().format(
                        'YYYY-MM-DD'); // Default hari ini
                    }
                }
            });

            // Date Range Picker
            $('#daterange-start, #daterange-end').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                autoUpdateInput: false,
                locale: {
                    format: 'YYYY-MM-DD'
                }
            }, function(selectedDate) {
                $(this.element).text(selectedDate.format('YYYY-MM-DD'));
                table.ajax.reload(); // Reload DataTable dengan filter
            });
        });
    </script>
@endsection
