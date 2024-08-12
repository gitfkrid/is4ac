<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\alat;
use App\Models\detail_alat;
use Illuminate\Support\Facades\Session;
use App\Models\dht;
use App\Models\fosfin;

class DetailDashboardController extends Controller
{
    public function index($uuid) {
        $alat = Alat::where('uuid', $uuid)->first();
    
        if (!$alat) {
            abort(404, 'Alat tidak ditemukan');
        }

        $nilaisensor = Dht::join('fosfin', 'dht.id_alat', '=', 'fosfin.id_alat')
        ->where('dht.id_alat', $alat->id)
        ->select('dht.id_alat', 'dht.suhu', 'dht.kelembaban', 'fosfin.fosfin')
        ->first();

        if (!$nilaisensor) {
            Session::flash('alert', 'Data sensor tidak tersedia untuk alat ini.');
            return redirect()->route('dashboard');
        }

        if(Auth::user()->id_level == '1') {
            return view('detail.admin', compact('alat', 'detailAlat'));
        } else if(Auth::user()->id_level == '2') {
            return view('detail.user', compact('alat', 'detailAlat'));
        } else {
        }
    }
}
