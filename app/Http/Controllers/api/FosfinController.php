<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\fosfin;
use App\Models\alat;

class FosfinController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_board' => 'required',
            'fosfin' => 'required|numeric',
        ]);

        $board = alat::where('kode_board', $request->kode_board)->first();

        if (!$board) {
            return response()->json(['success' => false, 'message' => 'Board tidak ditemukan.'], 404);
        }

        $data = fosfin::create([
            'id_alat' => $board->id_alat,
            'fosfin' => $validated['fosfin']
        ]);

        return response()->json(['success' => true, 'data' => $data], 201);
    }
}
