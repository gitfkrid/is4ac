@extends('layouts.app_user')

@section('headcontent')
    <div class="d-flex justify-content-start mb-3">
        <button id="daterange-start" class="btn btn-primary btn-sm mr-2">
            <i class="fas fa-calendar-alt"></i> Start Date
        </button>
        <button id="daterange-end" class="btn btn-secondary btn-sm">
            <i class="fas fa-calendar-alt"></i> End Date
        </button>
        <!-- Hidden inputs to store selected dates -->
        <input type="hidden" id="start_date" value="{{ date('Y-m-d') }}">
        <input type="hidden" id="end_date" value="{{ date('Y-m-d') }}">
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
                        // Ambil tanggal dari input hidden, default hari ini jika tidak dipilih
                        d.start_date = $('#start_date').val() || moment().format('YYYY-MM-DD'); // Default hari ini
                        d.end_date = $('#end_date').val() || moment().format('YYYY-MM-DD'); // Default hari ini
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
                // Set value dari input hidden ketika memilih tanggal
                if ($(this.element).attr('id') === 'daterange-start') {
                    $('#start_date').val(selectedDate.format('YYYY-MM-DD'));
                    $('#daterange-start').text(selectedDate.format('YYYY-MM-DD')); // Update button text
                } else if ($(this.element).attr('id') === 'daterange-end') {
                    $('#end_date').val(selectedDate.format('YYYY-MM-DD'));
                    $('#daterange-end').text(selectedDate.format('YYYY-MM-DD')); // Update button text
                }

                // Reload DataTable dengan filter berdasarkan tanggal
                table.ajax.reload();
            });

            // Set default value for start and end date inputs if no selection made
            $('#start_date').val(moment().format('YYYY-MM-DD'));
            $('#end_date').val(moment().format('YYYY-MM-DD'));
            $('#daterange-start').text(moment().format('YYYY-MM-DD')); // Set default text on the button
            $('#daterange-end').text(moment().format('YYYY-MM-DD')); // Set default text on the button
        });
    </script>
@endsection
