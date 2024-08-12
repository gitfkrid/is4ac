<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\alat;
use App\Models\jenis_alat;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (Auth::user()->id_level == '1') {
            $alat = Alat::where('status', 1)
                ->join('jenis_alat', 'alat.id_jenis_alat', '=', 'jenis_alat.id_jenis_alat')
                ->select('alat.*', 'jenis_alat.jenis_alat')
                ->orderBy('id_alat', 'asc')
                ->get();

            $jenis_alat = jenis_alat::pluck('jenis_alat', 'id_jenis_alat');
            $filter = jenis_alat::pluck('jenis_alat', 'id_jenis_alat');

            return view('dashboard.admin', compact('alat', 'jenis_alat', 'filter'));
        } else if (Auth::user()->id_level == '2') {
            $alat = Alat::where('status', 1)
                ->join('jenis_alat', 'alat.id_jenis_alat', '=', 'jenis_alat.id_jenis_alat')
                ->select('alat.*', 'jenis_alat.jenis_alat')
                ->orderBy('id_alat', 'asc')
                ->get();
            return view('dashboard.user', compact('alat'));
        } else {
        }
    }

    public function getAlatCards()
    {
        $alat = Alat::where('status', 1)
                ->join('jenis_alat', 'alat.id_jenis_alat', '=', 'jenis_alat.id_jenis_alat')
                ->select('alat.*', 'jenis_alat.jenis_alat')
                ->orderBy('id_alat', 'asc')
                ->get();
        return view('dashboard.alatCards', compact('alat'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_device' => 'required|unique:alat,nama_device',
            'topic_mqtt' => 'required',
            'jenis_alat' => 'required|integer',
            'status' => 'required|boolean',
        ]);

        $alat = new alat;
        $alat->nama_device = $request->nama_device;
        $alat->topic_mqtt = $request->topic_mqtt;
        $alat->id_jenis_alat = $request->jenis_alat;
        $alat->status = $request->status;
        $alat->save();
    }

    public function edit($id)
    {
        $alat = alat::find($id);
        echo json_encode($alat);
    }

    public function update(Request $request, $id)
    {
        $alat = alat::find($id);
        $alat->nama_device = $request['nama_device'];
        $alat->topic_mqtt = $request['topic_mqtt'];
        $alat->id_jenis_alat = $request['jenis_alat'];
        $alat->status = $request['status'];
        $alat->update();
    }

    public function destroy($id)
    {
        $alat = alat::find($id);
        $alat->delete();
    }
}
