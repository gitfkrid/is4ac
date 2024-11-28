<?php

namespace App\Http\Controllers;

use App\Models\nilaibatas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NilaiBatasController extends Controller
{
    public function edit()
    {
        $nilaibatas = nilaibatas::find(1);
        return response()->json($nilaibatas);
    }

    public function update(Request $request)
    {
        $nilaiBatas = NilaiBatas::first();

        if (!$nilaiBatas) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }

        $nilaiBatas->update([
            'nb_suhu_atas' => $request->nb_suhu_atas,
            'nb_suhu_bawah' => $request->nb_suhu_bawah,
            'nb_rh_atas' => $request->nb_rh_atas,
            'nb_rh_bawah' => $request->nb_rh_bawah,
            'nb_ph3_atas' => $request->nb_ph3_atas,
            'nb_ph3_bawah' => $request->nb_ph3_bawah,
            'status' => $request->status,
        ]);

        if ($request->status == 0) {
            DB::table('relay')->update(['state' => 0]);

            // Cek data terakhir pada tabel log_relay untuk hari ini
            $lastLog = DB::table('log_relay')
                // ->whereDate('waktu', now()->toDateString())
                ->orderBy('waktu', 'desc')
                ->first();
            $latestData = DB::table('dht as t1')
                ->join(DB::raw('(SELECT id_alat, MAX(created_at) as latest FROM dht WHERE created_at >= NOW() - INTERVAL 5 MINUTE GROUP BY id_alat) as t2'), function ($join) {
                    $join->on('t1.id_alat', '=', 't2.id_alat')
                        ->on('t1.created_at', '=', 't2.latest');
                })
                ->select('t1.id_alat', 't1.suhu', 't1.kelembaban', 't1.created_at')
                ->get();

            // Menghitung rata-rata suhu dan kelembaban dari data yang diambil
            $avgSuhu = $latestData->avg('suhu');
            $avgKelembaban = $latestData->avg('kelembaban');

            if ($lastLog && $lastLog->keterangan == 'Exhaust Hidup') {
                // Insert log baru untuk Exhaust Mati
                DB::table('log_relay')->insert([
                    'waktu' => now(),
                    'suhu' => $avgSuhu,
                    'kelembaban' => $avgKelembaban,
                    'mode' => $request->status,
                    'keterangan' => 'Exhaust Mati',
                ]);
            }
        }

        return response()->json(['success' => 'Data berhasil diupdate.']);
    }
}
