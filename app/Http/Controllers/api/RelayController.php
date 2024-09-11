<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\relay;
use App\Models\alat;
use App\Models\lokasi;

class RelayController extends Controller
{
    public function getRelayState($kode_board) {
        $relayState = Relay::join('alat', 'relay.id_alat', '=', 'alat.id_alat')
            ->join('lokasi', 'alat.id_lokasi', '=', 'lokasi.id_lokasi')
            ->where('alat.kode_board', $kode_board)
            ->select('relay.state', 'alat.nama_device', 'lokasi.nama_lokasi')
            ->first();

        if ($relayState) {
            return response()->json([
                'success' => true,
                'data' => $relayState
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Relay state not found'
            ], 404);
        }
    }
}
