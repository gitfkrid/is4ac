<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\dht;
use App\Models\alat;
use Illuminate\Support\Facades\DB;

class DhtController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_board' => 'required',
            'suhu' => 'required|numeric',
            'kelembaban' => 'required|numeric',
        ]);

        $board = alat::where('kode_board', $request->kode_board)->first();

        if (!$board) {
            return response()->json(['success' => false, 'message' => 'Board tidak ditemukan.'], 404);
        }

        $data = dht::create([
            'id_alat' => $board->id_alat,
            'suhu' => $validated['suhu'],
            'kelembaban' => $validated['kelembaban']
        ]);

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

        $batas = DB::table('nilaibatas')->first();

        if (
            $batas->status == 1 &&
            (($avgSuhu > $batas->nb_suhu_atas || $avgSuhu < $batas->nb_suhu_bawah) ||
                ($avgKelembaban > $batas->nb_rh_atas || $avgKelembaban < $batas->nb_rh_bawah))
        ) {
            DB::table('relay')->update(['state' => 1]);
        } else if (
            $batas->status == 1 &&
            (($avgSuhu >= $batas->nb_suhu_bawah && $avgSuhu <= $batas->nb_suhu_atas) ||
                ($avgKelembaban >= $batas->nb_rh_bawah && $avgKelembaban <= $batas->nb_rh_atas))
        ) {
        }

        if ($validated['kelembaban'] >= $batas->nb_rh_atas || $validated['kelembaban'] <= $batas->nb_rh_bawah) {
            DB::table('log')->insert([
                'id_alat' => $board->id_alat,
                'suhu' => $validated['suhu'],
                'kelembaban' => $validated['kelembaban'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return response()->json(['success' => true, 'data' => $data], 201);
    }
}
