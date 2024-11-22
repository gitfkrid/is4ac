<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\alat;
use App\Models\jenis_alat;
use App\Models\lokasi;
use App\Models\relay;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (Auth::user()->id_level == '1') {
            $alat = Alat::join('jenis_alat', 'alat.id_jenis_alat', '=', 'jenis_alat.id_jenis_alat')
                ->join('lokasi', 'alat.id_lokasi', '=', 'lokasi.id_lokasi')
                ->leftJoin('relay', 'alat.id_alat', '=', 'relay.id_alat')
                ->leftJoin(DB::raw('(SELECT id_alat, suhu, kelembaban, created_at FROM dht WHERE (id_alat, created_at) IN (SELECT id_alat, MAX(created_at) FROM dht GROUP BY id_alat)) as dht'), 'alat.id_alat', '=', 'dht.id_alat')
                ->leftJoin(DB::raw('(SELECT id_alat, fosfin, created_at FROM fosfin WHERE (id_alat, created_at) IN (SELECT id_alat, MAX(created_at) FROM fosfin GROUP BY id_alat)) as fosfin'), function ($join) {
                    $join->on('alat.id_alat', '=', 'fosfin.id_alat')
                        ->where('alat.id_jenis_alat', '=', 1); // Hanya untuk jenis PH3
                })
                ->select('alat.*', 'jenis_alat.jenis_alat', 'lokasi.nama_lokasi', 'relay.state', 'dht.suhu', 'dht.kelembaban', 'fosfin.fosfin')
                ->orderBy('alat.id_alat', 'asc')
                ->get();

            $jenis_alat = jenis_alat::pluck('jenis_alat', 'id_jenis_alat');
            $filter = jenis_alat::pluck('jenis_alat', 'id_jenis_alat');
            $lokasi = Lokasi::join('user_lokasi', 'lokasi.id_lokasi', '=', 'user_lokasi.id_lokasi')
                ->join('users', 'user_lokasi.id_user', '=', 'users.id')
                ->where('users.id', Auth::user()->id)
                ->select('lokasi.id_lokasi', 'lokasi.nama_lokasi')
                ->get();

            return view('dashboard.admin', compact('alat', 'jenis_alat', 'filter', 'lokasi'));
        } else if (Auth::user()->id_level == '2') {
            $alat = Alat::join('jenis_alat', 'alat.id_jenis_alat', '=', 'jenis_alat.id_jenis_alat')
                ->join('lokasi', 'alat.id_lokasi', '=', 'lokasi.id_lokasi')
                ->leftJoin('relay', 'alat.id_alat', '=', 'relay.id_alat')
                ->leftJoin(DB::raw('(SELECT id_alat, suhu, kelembaban, created_at FROM dht WHERE (id_alat, created_at) IN (SELECT id_alat, MAX(created_at) FROM dht GROUP BY id_alat)) as dht'), 'alat.id_alat', '=', 'dht.id_alat')
                ->leftJoin(DB::raw('(SELECT id_alat, fosfin, created_at FROM fosfin WHERE (id_alat, created_at) IN (SELECT id_alat, MAX(created_at) FROM fosfin GROUP BY id_alat)) as fosfin'), function ($join) {
                    $join->on('alat.id_alat', '=', 'fosfin.id_alat')
                        ->where('alat.id_jenis_alat', '=', 1); // Hanya untuk jenis PH3
                })
                ->select('alat.*', 'jenis_alat.jenis_alat', 'lokasi.nama_lokasi', 'relay.state', 'dht.suhu', 'dht.kelembaban', 'fosfin.fosfin')
                ->orderBy('alat.id_alat', 'asc')
                ->get();

            $jenis_alat = jenis_alat::pluck('jenis_alat', 'id_jenis_alat');
            $filter = jenis_alat::pluck('jenis_alat', 'id_jenis_alat');
            $lokasi = Lokasi::join('user_lokasi', 'lokasi.id_lokasi', '=', 'user_lokasi.id_lokasi')
                ->join('users', 'user_lokasi.id_user', '=', 'users.id')
                ->where('users.id', Auth::user()->id)
                ->select('lokasi.id_lokasi', 'lokasi.nama_lokasi')
                ->get();

            return view('dashboard.user', compact('alat', 'jenis_alat', 'filter', 'lokasi'));
        } else {
        }
    }

    public function getAlatCards()
    {
        $alat = Alat::join('jenis_alat', 'alat.id_jenis_alat', '=', 'jenis_alat.id_jenis_alat')
            ->join('lokasi', 'alat.id_lokasi', '=', 'lokasi.id_lokasi')
            ->leftJoin('relay', 'alat.id_alat', '=', 'relay.id_alat')
            ->leftJoin(DB::raw('(SELECT id_alat, suhu, kelembaban, created_at FROM dht WHERE (id_alat, created_at) IN (SELECT id_alat, MAX(created_at) FROM dht GROUP BY id_alat)) as dht'), 'alat.id_alat', '=', 'dht.id_alat')
            ->leftJoin(DB::raw('(SELECT id_alat, fosfin, created_at FROM fosfin WHERE (id_alat, created_at) IN (SELECT id_alat, MAX(created_at) FROM fosfin GROUP BY id_alat)) as fosfin'), function ($join) {
                $join->on('alat.id_alat', '=', 'fosfin.id_alat')
                    ->where('alat.id_jenis_alat', '=', 1); // Hanya untuk jenis PH3
            })
            ->select('alat.*', 'jenis_alat.jenis_alat', 'lokasi.nama_lokasi', 'relay.state', 'dht.suhu', 'dht.kelembaban', 'fosfin.fosfin')
            ->orderBy('alat.id_alat', 'asc')
            ->get();

        return view('dashboard.alatCards', compact('alat'));
    }

    public function getSensorNow()
    {
        $alat = Alat::join('jenis_alat', 'alat.id_jenis_alat', '=', 'jenis_alat.id_jenis_alat')
            ->join('lokasi', 'alat.id_lokasi', '=', 'lokasi.id_lokasi')
            ->leftJoin('relay', 'alat.id_alat', '=', 'relay.id_alat')
            ->leftJoin(DB::raw('(SELECT id_alat, suhu, kelembaban, created_at FROM dht WHERE (id_alat, created_at) IN (SELECT id_alat, MAX(created_at) FROM dht GROUP BY id_alat)) as dht'), 'alat.id_alat', '=', 'dht.id_alat')
            ->leftJoin(DB::raw('(SELECT id_alat, fosfin, created_at FROM fosfin WHERE (id_alat, created_at) IN (SELECT id_alat, MAX(created_at) FROM fosfin GROUP BY id_alat)) as fosfin'), function ($join) {
                $join->on('alat.id_alat', '=', 'fosfin.id_alat')
                    ->where('alat.id_jenis_alat', '=', 1); // Hanya untuk jenis PH3
            })
            ->select('alat.*', 'jenis_alat.jenis_alat', 'lokasi.nama_lokasi', 'relay.state', 'dht.suhu', 'dht.kelembaban', 'fosfin.fosfin')
            ->orderBy('alat.id_alat', 'asc')
            ->get();

        $alatArray = $alat->toArray();
        return response()->json($alatArray);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_device' => 'required|unique:alat,nama_device',
            'kode_board' => 'required|unique:alat,kode_board',
            'keterangan' => 'required',
            'id_jenis_alat' => 'required|integer',
            'id_lokasi' => 'required',
        ]);

        $alat = new alat;
        $alat->nama_device = $request->nama_device;
        $alat->keterangan = $request->keterangan;
        $alat->kode_board = $request->kode_board;
        $alat->id_jenis_alat = $request->id_jenis_alat;
        $alat->id_lokasi = $request->id_lokasi;

        if ($alat->save()) {
            if ($request->id_jenis_alat == 3) {
                Relay::create([
                    'id_alat' => $alat->id_alat,
                    'state' => 0,
                ]);
            }
            return response()->json(['success' => true, 'message' => 'Data saved successfully.']);
        } else {
            return response()->json(['success' => false, 'message' => 'Failed to save data.'], 500);
        }
    }

    public function toggleRelay(Request $request, $kode_board)
    {
        $alat = DB::table('alat')->where('kode_board', $kode_board)->first();
        $batas = DB::table('nilaibatas')->first();

        if ($alat && $batas->status == 0) {
            DB::table('relay')
                ->where('id_alat', $alat->id_alat)
                ->update(['state' => $request->state]);
            if ($request->state == 0) {
                DB::table('relay')->update(['state' => 0]);

                // Cek data terakhir pada tabel log_relay untuk hari ini
                $lastLog = DB::table('log_relay')
                    // ->whereDate('waktu', now()->toDateString())
                    ->orderBy('waktu', 'desc')
                    ->first();

                if ($lastLog && $lastLog->keterangan == 'Exhaust Hidup') {
                    // Insert log baru untuk Exhaust Mati
                    DB::table('log_relay')->insert([
                        'waktu' => now(),
                        'keterangan' => 'Exhaust Mati',
                    ]);
                }
            } else if ($request->state == 1) {
                DB::table('relay')->update(['state' => 1]);

                // Cek data terakhir pada tabel log_relay untuk hari ini
                $lastLog = DB::table('log_relay')
                    // ->whereDate('waktu', now()->toDateString())
                    ->orderBy('waktu', 'desc')
                    ->first();

                if (!$lastLog || $lastLog->keterangan == 'Exhaust Mati') {
                    // Insert log baru untuk Exhaust Hidup
                    DB::table('log_relay')->insert([
                        'waktu' => now(),
                        'keterangan' => 'Exhaust Hidup',
                    ]);
                }
            }

            return response()->json(['success' => true]);
        } else if ($alat && $batas->status == 1) {
            return response()->json([
                'success' => false,
                'message' => 'Sedang pada mode otomatis, perubahan tidak dapat dilakukan.'
            ]);
        } else {
            return response()->json(['success' => false, 'message' => 'Relay not found']);
        }
    }

    public function edit($id)
    {
        $alat = alat::join('jenis_alat', 'alat.id_jenis_alat', '=', 'jenis_alat.id_jenis_alat')
            ->join('lokasi', 'alat.id_lokasi', '=', 'lokasi.id_lokasi')
            ->where('alat.id_alat', $id)
            ->select('alat.*', 'jenis_alat.jenis_alat', 'lokasi.nama_lokasi')
            ->first();

        echo json_encode($alat);
    }

    public function update(Request $request, $id)
    {
        $alat = alat::find($id);
        $alat->kode_board = $request['kode_board'];
        $alat->nama_device = $request['nama_device'];
        $alat->id_jenis_alat = $request['id_jenis_alat'];
        $alat->id_lokasi = $request['id_lokasi'];
        $alat->keterangan = $request['keterangan'];
        $alat->update();
    }

    public function destroy($id)
    {
        $alat = alat::find($id);

        if ($alat) {
            $alat->delete();
            return response()->json(['success' => 'Device deleted successfully']);
        }

        return response()->json(['error' => 'Device not found'], 404);
    }
}
