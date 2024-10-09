<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\alat;
use App\Models\detail_alat;
use App\Models\jenis_alat;
use App\Models\lokasi;
use Illuminate\Support\Facades\Session;
use App\Models\dht;
use App\Models\fosfin;

class DetailDashboardController extends Controller
{
    public function index($uuid)
    {
        $alat = Alat::where('uuid', $uuid)->first();

        if (!$alat) {
            return response()->json(['error' => 'Alat tidak ditemukan'], 404);
        }

        $nilaisensor = null;

        if ($alat->id_jenis_alat == 1) { // PH3
            $nilaisensor = Fosfin::where('fosfin.id_alat', $alat->id_alat)
                ->join('alat', 'fosfin.id_alat', '=', 'alat.id_alat')
                ->join('lokasi', 'alat.id_lokasi', '=', 'lokasi.id_lokasi')
                ->select('fosfin.fosfin', 'fosfin.updated_at', 'lokasi.nama_lokasi')
                ->orderBy('fosfin.updated_at', 'desc')
                ->first();
        } elseif ($alat->id_jenis_alat == 2) { // DHT
            $nilaisensor = Dht::where('dht.id_alat', $alat->id_alat)
                ->join('alat', 'dht.id_alat', '=', 'alat.id_alat')
                ->join('lokasi', 'alat.id_lokasi', '=', 'lokasi.id_lokasi')
                ->select('dht.suhu', 'dht.kelembaban', 'dht.updated_at', 'lokasi.nama_lokasi')
                ->orderBy('dht.updated_at', 'desc')
                ->first();
        }

        if (!$nilaisensor) {
            $nilaisensor = new \stdClass();
            $nilaisensor->fosfin = 0;
            $nilaisensor->suhu = 0;
            $nilaisensor->kelembaban = 0;
            $nilaisensor->updated_at = '0';
            $nilaisensor->nama_lokasi = 'Unknown Location';
        } else {
            $nilaisensor->updated_at = Carbon::parse($nilaisensor->updated_at)->format('Y-m-d H:i:s');
        }

        $jenis_alat = jenis_alat::pluck('jenis_alat', 'id_jenis_alat');
        $lokasi = Lokasi::join('user_lokasi', 'lokasi.id_lokasi', '=', 'user_lokasi.id_lokasi')
            ->join('users', 'user_lokasi.id_user', '=', 'users.id')
            ->where('users.id', Auth::user()->id)
            ->select('lokasi.id_lokasi', 'lokasi.nama_lokasi')
            ->get();

        if (Auth::user()->id_level == '1') {
            return view('detail.admin', compact('alat', 'nilaisensor', 'jenis_alat', 'lokasi'));
        } else {
            return view('detail.user', compact('alat', 'nilaisensor', 'jenis_alat', 'lokasi'));
        }
    }

    public function getSensorData($uuid)
    {
        $alat = Alat::where('uuid', $uuid)->first();

        if (!$alat) {
            return response()->json(['error' => 'Alat tidak ditemukan'], 404);
        }

        $sensor = null;

        if ($alat->id_jenis_alat == 1) { // PH3
            $sensor = Fosfin::where('fosfin.id_alat', $alat->id_alat)
                ->join('alat', 'fosfin.id_alat', '=', 'alat.id_alat')
                ->join('lokasi', 'alat.id_lokasi', '=', 'lokasi.id_lokasi')
                ->select('fosfin.fosfin', 'fosfin.updated_at', 'lokasi.nama_lokasi')
                ->orderBy('fosfin.updated_at', 'desc')
                ->first();
        } elseif ($alat->id_jenis_alat == 2) { // DHT
            $sensor = Dht::where('dht.id_alat', $alat->id_alat)
                ->join('alat', 'dht.id_alat', '=', 'alat.id_alat')
                ->join('lokasi', 'alat.id_lokasi', '=', 'lokasi.id_lokasi')
                ->select('dht.suhu', 'dht.kelembaban', 'dht.updated_at', 'lokasi.nama_lokasi')
                ->orderBy('dht.updated_at', 'desc')
                ->first();
        }

        if (!$sensor) {
            return response()->json(['error' => 'Data sensor tidak tersedia'], 404);
        }

        $sensorArray = $sensor->toArray();
        $sensorArray['updated_at'] = Carbon::parse($sensor->updated_at)->format('Y-m-d H:i:s');

        return response()->json($sensorArray);
    }

    public function getSensorChartData($uuid)
    {
        $alat = Alat::where('uuid', $uuid)->first();

        if (!$alat) {
            return response()->json(['error' => 'Alat tidak ditemukan'], 404);
        }

        // Ambil semua alat dengan id_lokasi yang sama
        $alatLainnya = Alat::where('id_lokasi', $alat->id_lokasi)->get();

        // Ambil data untuk semua alat yang relevan
        $data = [];
        if ($alat->id_jenis_alat == 1) { // PH3
            $data = Fosfin::whereIn('id_alat', $alatLainnya->pluck('id_alat'))
                ->select('fosfin', 'id_alat', 'updated_at')
                ->orderBy('updated_at', 'desc')
                ->get()
                ->groupBy('id_alat'); // Kelompokkan data berdasarkan id_alat
        } elseif ($alat->id_jenis_alat == 2) { // DHT
            $data = Dht::whereIn('id_alat', $alatLainnya->pluck('id_alat'))
                ->select('suhu', 'id_alat', 'updated_at')
                ->orderBy('updated_at', 'desc')
                ->get()
                ->groupBy('id_alat'); // Kelompokkan data berdasarkan id_alat
        }

        if ($data->isEmpty()) {
            return response()->json(['error' => 'Data sensor tidak tersedia'], 404);
        }

        // Urutkan data berdasarkan id_alat (ascending)
        $data = $data->sortKeys();

        // Format data untuk chart
        $formattedData = [
            'labels' => [],
            'datasets' => []
        ];

        $alatTerlama = null;
        $waktuTerlama = null;

        // Temukan id_alat dengan waktu terlama
        foreach ($data as $idAlat => $entries) {
            // Ambil data terbaru untuk setiap alat
            $latestEntry = $entries->first(); // Karena sudah diurutkan berdasarkan waktu (desc)
            if (!$waktuTerlama || $latestEntry->updated_at > $waktuTerlama) {
                $alatTerlama = $idAlat;
                $waktuTerlama = $latestEntry->updated_at;
            }
        }

        // Ambil waktu dari alat dengan waktu terlama
        $labels = $data[$alatTerlama]->take(30)->pluck('updated_at')->map(function ($time) {
            return \Carbon\Carbon::parse($time)->format('H:i');
        })->reverse()->values()->toArray();

        // Array warna yang sudah ditentukan
        $colors = [
            'rgba(255, 99, 132, 1)',  // Merah
            'rgba(54, 162, 235, 1)',  // Biru
            'rgba(255, 206, 86, 1)',  // Kuning
            'rgba(75, 192, 192, 1)',  // Hijau
            'rgba(153, 102, 255, 1)', // Ungu
            'rgba(255, 159, 64, 1)',  // Oranye
            'rgba(201, 203, 207, 1)', // Abu-abu
            'rgba(255, 0, 255, 1)',   // Magenta
            'rgba(0, 255, 0, 1)',     // Hijau terang
            'rgba(0, 0, 255, 1)'      // Biru terang
        ];

        // Track warna yang sudah digunakan untuk memastikan tidak ada yang terulang
        $usedColors = [];

        // Mengelompokkan data berdasarkan id_alat dan membatasi data maksimal 30 per alat
        foreach ($data as $idAlat => $entries) {
            // Batasi maksimal 30 data per alat
            $entries = $entries->take(30);

            // Cari nama device dari alat
            $namaDevice = $alatLainnya->where('id_alat', $idAlat)->first()->nama_device ?? 'Alat ' . $idAlat;

            // Pilih warna yang belum digunakan
            $availableColors = array_diff($colors, $usedColors); // Ambil warna yang belum dipakai
            $selectedColor = array_shift($availableColors); // Ambil satu warna
            $usedColors[] = $selectedColor; // Simpan warna yang sudah dipakai

            // Tambahkan dataset
            $formattedData['datasets'][] = [
                'label' => $namaDevice, // Gunakan nama_device sebagai label
                'data' => $entries->map(function ($entry) use ($alat, $idAlat) {
                    return $alat->id_jenis_alat == 1 ? $entry->fosfin : $entry->suhu;
                })->reverse()->values()->toArray(),
                'borderColor' => $selectedColor, // Gunakan warna yang dipilih
                'borderWidth' => 2,
                'fill' => false,
            ];
        }

        // Set label berdasarkan waktu dari alat dengan waktu terlama
        $formattedData['labels'] = $labels;

        return response()->json($formattedData);
    }

    public function getSensorChartHumidity($uuid)
    {
        $alat = Alat::where('uuid', $uuid)->first();

        if (!$alat) {
            return response()->json(['error' => 'Alat tidak ditemukan'], 404);
        }

        // Ambil semua alat dengan id_lokasi yang sama
        $alatLainnya = Alat::where('id_lokasi', $alat->id_lokasi)->get();

        // Ambil data kelembaban untuk semua alat yang relevan
        $data = Dht::whereIn('id_alat', $alatLainnya->pluck('id_alat'))
            ->select('kelembaban', 'id_alat', 'updated_at')
            ->orderBy('updated_at', 'desc')
            ->get()
            ->groupBy('id_alat'); // Kelompokkan berdasarkan id_alat

        if ($data->isEmpty()) {
            return response()->json(['error' => 'Data sensor tidak tersedia'], 404);
        }

        // Urutkan data berdasarkan id_alat (ascending)
        $data = $data->sortKeys();

        // Format data untuk chart
        $formattedData = [
            'labels' => [],
            'datasets' => []
        ];

        $alatTerlama = null;
        $waktuTerlama = null;

        // Temukan id_alat dengan waktu terlama
        foreach ($data as $idAlat => $entries) {
            $latestEntry = $entries->first(); // Karena sudah diurutkan berdasarkan waktu (desc)
            if (!$waktuTerlama || $latestEntry->updated_at > $waktuTerlama) {
                $alatTerlama = $idAlat;
                $waktuTerlama = $latestEntry->updated_at;
            }
        }

        // Ambil waktu dari alat dengan waktu terlama
        $labels = $data[$alatTerlama]->take(30)->pluck('updated_at')->map(function ($time) {
            return \Carbon\Carbon::parse($time)->format('H:i');
        })->reverse()->values()->toArray();

        // Mengelompokkan data kelembaban berdasarkan id_alat dan membatasi data maksimal 30 per alat
        foreach ($data as $idAlat => $entries) {
            // Batasi maksimal 30 data per alat
            $entries = $entries->take(30);

            // Cari nama device dari alat
            $namaDevice = $alatLainnya->where('id_alat', $idAlat)->first()->nama_device ?? 'Alat ' . $idAlat;

            // Array warna yang sudah ditentukan
            $colors = [
                'rgba(255, 99, 132, 1)',  // Merah
                'rgba(54, 162, 235, 1)',  // Biru
                'rgba(255, 206, 86, 1)',   // Kuning
                'rgba(75, 192, 192, 1)',   // Hijau
                'rgba(153, 102, 255, 1)',  // Ungu
                'rgba(255, 159, 64, 1)',   // Oranye
                'rgba(201, 203, 207, 1)',  // Abu-abu
                'rgba(255, 0, 255, 1)',    // Magenta
                'rgba(0, 255, 0, 1)',      // Hijau terang
                'rgba(0, 0, 255, 1)'       // Biru terang
            ];

            // Buat dataset untuk setiap alat
            $formattedData['datasets'][] = [
                'label' => $namaDevice, // Gunakan nama_device sebagai label
                'data' => $entries->map(function ($entry) {
                    return $entry->kelembaban;
                })->reverse()->values()->toArray(),
                'borderColor' => $colors[array_rand($colors)],
                'borderWidth' => 2,
                'fill' => false,
            ];
        }

        // Set label berdasarkan waktu dari alat dengan waktu terlama
        $formattedData['labels'] = $labels;

        return response()->json($formattedData);
    }

    public function exportData(Request $request, $uuid)
    {
        $alat = Alat::where('uuid', $uuid)->first();

        if (!$alat) {
            return response()->json(['error' => 'Alat tidak ditemukan'], 404);
        }

        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
        $startDate = $startDate . ' 00:00:00';
        $endDate = $endDate . ' 23:59:59';

        // Cek jenis alat, jika 2 maka ambil dari dht, jika 1 dari fosfin
        if ($alat->id_jenis_alat == 2) {
            $data = dht::join('alat', 'dht.id_alat', '=', 'alat.id_alat')
                ->where('dht.id_alat', $alat->id_alat)
                ->whereBetween('dht.created_at', [$startDate, $endDate])
                ->select('dht.*', 'alat.nama_device', 'alat.kode_board')
                ->get();
        } elseif ($alat->id_jenis_alat == 1) {
            $data = fosfin::join('alat', 'fosfin.id_alat', '=', 'alat.id_alat')
                ->where('fosfin.id_alat', $alat->id_alat)
                ->whereBetween('fosfin.created_at', [$startDate, $endDate])
                ->select('fosfin.*', 'alat.nama_device', 'alat.kode_board')
                ->get();
        } else {
            return response()->json(['error' => 'Jenis alat tidak dikenali'], 400);
        }

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=sensor_data.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function () use ($data, $alat) {
            $file = fopen('php://output', 'w');

            // Buat header CSV berdasarkan jenis alat
            if ($alat->id_jenis_alat == 2) {
                fputcsv($file, ['Kode Board', 'Nama Device', 'Suhu', 'Kelembaban', 'Waktu']);
            } elseif ($alat->id_jenis_alat == 1) {
                fputcsv($file, ['Kode Board', 'Nama Device', 'Fosfin', 'Waktu']);
            }

            // Tulis data ke CSV
            foreach ($data as $row) {
                if ($alat->id_jenis_alat == 2) {
                    fputcsv($file, [
                        $row->kode_board,
                        $row->nama_device,
                        $row->suhu,
                        $row->kelembaban,
                        $row->created_at
                    ]);
                } elseif ($alat->id_jenis_alat == 1) {
                    fputcsv($file, [
                        $row->kode_board,
                        $row->nama_device,
                        $row->fosfin,
                        $row->created_at
                    ]);
                }
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function edit($uuid)
    {
        $alat = alat::where('uuid', $uuid)->first();
        return response()->json($alat);
    }

    public function update(Request $request, $uuid)
    {
        $alat = alat::where('uuid', $uuid)->first();
        $alat->kode_board = $request['kode_board'];
        $alat->nama_device = $request['nama_device'];
        $alat->id_jenis_alat = $request['id_jenis_alat'];
        $alat->id_lokasi = $request['id_lokasi'];
        $alat->keterangan = $request['keterangan'];
        $alat->update();
        // return response()->json(['success' => 'Data berhasil diubah.']);
        return redirect()->route('detail_dashboard.index', ['uuid' => $uuid])->with('success', 'Data berhasil diubah.');
    }

    public function destroy($uuid)
    {
        $alat = alat::where('uuid', $uuid)->first();
        $alat->delete();
        return redirect()->route('dashboard')->with('deleted', 'Data berhasil diubah.');
    }
}
