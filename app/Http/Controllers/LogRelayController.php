<?php

namespace App\Http\Controllers;

use App\Models\log_relay;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;

class LogRelayController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->id_level == 1) {
            return view('LogR.index');
        } elseif ($user->id_level == 2) {
            return view('LogR.index_user');
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function dataLogRelay(Request $request)
    {
        $query = log_relay::query();

        // Cek apakah ada parameter start_date dan end_date
        if ($request->has('start_date') && $request->has('end_date')) {
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');

            // Filter berdasarkan rentang tanggal
            $query->whereBetween('waktu', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        } else {
            // Default: Tampilkan data untuk hari ini
            $today = now()->format('Y-m-d');
            $query->whereDate('waktu', $today);
        }

        $logrelay = $query->orderBy('id_log', 'desc')->get();
        $no = 0;
        $data = array();

        foreach ($logrelay as $list) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $list->suhu;
            $row[] = $list->kelembaban;
            $row[] = $list->mode == 1 ? 'Otomatis' : 'Manual';
            $row[] = $list->waktu->format('Y-m-d H:i:s');
            $row[] = $list->keterangan;
            $data[] = $row;
        }

        return DataTables::of($data)->escapeColumns([])->make(true);
    }
}
