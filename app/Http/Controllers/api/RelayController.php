<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\relay;
use Illuminate\Support\Facades\Log;
use App\Models\alat;
use Illuminate\Support\Facades\DB;
use App\Models\lokasi;

class RelayController extends Controller
{
    public function getRelayState($kode_board)
    {
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
    public function getRelay(Request $request)
    {
        $id_lokasi = $request->input('id_lokasi');
        try {
            $relay = DB::select(
                "SELECT alat.kode_board, alat.nama_device,alat.keterangan, relay.state
                FROM alat
                JOIN lokasi ON alat.id_lokasi = lokasi.id_lokasi
                JOIN relay ON alat.id_alat = relay.id_alat
                WHERE alat.id_jenis_alat = 3 AND lokasi.id_lokasi = ? ",
                [$id_lokasi] // Parameter binding untuk mencegah SQL injection
            );
            if (count($relay) > 0) {
                return response()->json([
                    'success' => true,
                    'data' => $relay,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Data not found',
                    'error' => 'Tidak terdapat exhaust'
                ], 404);
            }
        } catch (\Exception $e) {
            Log::error('Exception: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Internal server error',
                'Exception: ' => $e->getMessage()
            ], 500);
        }
    }
    public function toggleRelay(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'kode_board' => 'required|string',
            'state' => 'required|integer|in:0,1', // hanya 0 atau 1 yang diperbolehkan
        ]);

        $kode_board = $validatedData['kode_board'];
        $state = $validatedData['state'];

        // Cari relay berdasarkan kode_board
        $alat = DB::table('alat')->where('kode_board', $kode_board)->first();
        $batas = DB::table('nilaibatas')->first();

        // Jika relay ditemukan, lakukan update
        if ($alat && $batas->status == 0) {
            DB::table('relay')
                ->where('id_alat', $alat->id_alat)
                ->update(['state' => $state]);
            if ($request->state == 0) {
                DB::table('log_relay')->insert([
                    'waktu' => now(),
                    'keterangan' => 'Exhaust Mati',
                ]);
            } else if ($request->state == 1) {
                DB::table('log_relay')->insert([
                    'waktu' => now(),
                    'keterangan' => 'Exhaust Hidup',
                ]);
            }
            return response()->json(['success' => true]);
        } else if ($alat && $batas->status == 1) {
            return response()->json([
                'success' => false,
                'message' => 'Sedang pada mode otomatis, perubahan tidak dapat dilakukan.'
            ]);
        } else {
            // Jika relay tidak ditemukan, return error
            return response()->json(['success' => false, 'message' => 'Relay not found']);
        }
    }
}
