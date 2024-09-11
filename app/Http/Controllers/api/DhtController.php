<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\dht;
use App\Models\alat;

class DhtController extends Controller
{
    public function store(Request $request) {
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

        return response()->json(['success' => true, 'data' => $data], 201);
    }
}
