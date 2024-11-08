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
        }

        return response()->json(['success' => 'Data berhasil diupdate.']);
    }
}
