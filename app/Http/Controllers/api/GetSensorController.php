<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\alat;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class GetSensorController extends Controller
{

    public function getNilaiGudang($id_lokasi)
    {
        try {
            $nilaiGudang = DB::select(
                "SELECT
                ROUND(AVG(latest_dht.suhu), 1) AS rata_rata_suhu,
                ROUND(AVG(latest_dht.kelembaban), 1) AS rata_rata_kelembapan,
                ROUND(AVG(latest_fosfin.fosfin), 1) AS rata_rata_fosfin,
                alat.id_lokasi,
                lokasi.nama_lokasi,
                MAX(latest_dht.created_at) AS created_at
            FROM
                alat
            LEFT JOIN
                (SELECT id_alat, suhu, kelembaban, created_at
                FROM dht
                WHERE (id_alat, created_at) IN (
                    SELECT id_alat, MAX(created_at)
                    FROM dht
                    WHERE TIMESTAMPDIFF(MINUTE, created_at, NOW()) <= 5
                    GROUP BY id_alat
                )) AS latest_dht ON latest_dht.id_alat = alat.id_alat
            LEFT JOIN
                (SELECT id_alat, fosfin, created_at
                FROM fosfin
                WHERE (id_alat, created_at) IN (
                    SELECT id_alat, MAX(created_at)
                    FROM fosfin
                    WHERE TIMESTAMPDIFF(MINUTE, created_at, NOW()) <= 5
                    GROUP BY id_alat
                )) AS latest_fosfin ON latest_fosfin.id_alat = alat.id_alat
            LEFT JOIN
                lokasi ON lokasi.id_lokasi = alat.id_lokasi
            WHERE
                alat.id_lokasi = ?
            GROUP BY
                alat.id_lokasi, lokasi.nama_lokasi
            ",
                [$id_lokasi]
            );

            if ($nilaiGudang) {
                return response()->json([
                    'success' => true,
                    'data' => $nilaiGudang[0]
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Data not found'
                ], 404);
            }
        } catch (\Exception $e) {
            // Log the exception message
            Log::error('Exception: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Internal server error'
            ], 500);
        }
    }

    public function getAvgSuhu($id_lokasi)
    {
        try {
            $avgSuhu = DB::select(
                "SELECT
                        alat.id_lokasi,
                        DATE_FORMAT(dht.created_at, '%Y-%m-%d %H:00:00') AS jam,
                        ROUND(AVG(dht.suhu), 1) AS rata_rata
                    FROM
                        dht
                    JOIN
                        alat ON dht.id_alat = alat.id_alat
                    WHERE
                        alat.id_lokasi = ?
                        AND DATE(dht.created_at) = CURDATE()
                    GROUP BY
                        alat.id_lokasi,
                        DATE_FORMAT(dht.created_at, '%Y-%m-%d %H:00:00')
                    ORDER BY
                        alat.id_lokasi,
                        jam ASC;
                        ",
                [$id_lokasi] // Parameter binding untuk mencegah SQL injection
            );

            if (count($avgSuhu) > 0) {
                return response()->json([
                    'success' => true,
                    'data' => $avgSuhu
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Data not found',
                    'error' => 'Belum ada data hari ini'
                ], 404);
            }
        } catch (\Exception $e) {
            // Log the exception message
            Log::error('Exception: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Internal server error'
            ], 500);
        }
    }


    public function getAvgKelembapan($id_lokasi)
    {
        try {
            $avgKelembaban = DB::select(
                "SELECT
                        alat.id_lokasi,
                        DATE_FORMAT(dht.created_at, '%Y-%m-%d %H:00:00') AS jam,
                        ROUND(AVG(dht.kelembaban), 1) AS rata_rata
                    FROM
                        dht
                    JOIN
                        alat ON dht.id_alat = alat.id_alat
                    WHERE
                        alat.id_lokasi = ?
                        AND DATE(dht.created_at) = CURDATE()
                    GROUP BY
                        alat.id_lokasi,
                        DATE_FORMAT(dht.created_at, '%Y-%m-%d %H:00:00')
                    ORDER BY
                        alat.id_lokasi,
                        jam ASC;
                        ",
                [$id_lokasi] // Parameter binding untuk mencegah SQL injection
            );

            if (count($avgKelembaban) > 0) {
                return response()->json([
                    'success' => true,
                    'data' => $avgKelembaban
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Data not found',
                    'error' => 'Belum ada data hari ini'
                ], 404);
            }
        } catch (\Exception $e) {
            // Log the exception message
            Log::error('Exception: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Internal server error'
            ], 500);
        }
    }

    public function getAvgFosfina($id_lokasi)
    {
        try {
            $avgFosfin = DB::select(
                "SELECT
                        alat.id_lokasi,
                        DATE_FORMAT(fosfin.created_at, '%Y-%m-%d %H:00:00') AS jam,
                        ROUND(AVG(fosfin.fosfin), 1) AS rata_rata
                    FROM
                        fosfin
                    JOIN
                        alat ON fosfin.id_alat = alat.id_alat
                    WHERE
                        alat.id_lokasi = ?
                        AND DATE(fosfin.created_at) = CURDATE()
                    GROUP BY
                        alat.id_lokasi,
                        DATE_FORMAT(fosfin.created_at, '%Y-%m-%d %H:00:00')
                    ORDER BY
                        alat.id_lokasi,
                        jam ASC;
                        ",
                [$id_lokasi] // Parameter binding untuk mencegah SQL injection
            );

            if (count($avgFosfin) > 0) {
                return response()->json([
                    'success' => true,
                    'data' => $avgFosfin
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Data not found',
                    'error' => 'Belum ada data hari ini'
                ], 404);
            }
        } catch (\Exception $e) {
            // Log the exception message
            Log::error('Exception: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Internal server error'
            ], 500);
        }
    }

    public function getJamSuhu(Request $request)
    {
        try {
            // Mendapatkan id_lokasi dan jam_awal dari body request
            $id_lokasi = $request->input('id_lokasi');
            $jam_awal = $request->input('jam_awal');

            // Menentukan jam akhir berdasarkan jam awal + 1 jam
            $jam_akhir = date('H:i:s', strtotime($jam_awal . ' +59 minutes'));

            $avgFosfin = DB::select(
                "SELECT
                DATE_FORMAT(dht.created_at, '%H:%i') AS Waktu,
                ROUND(AVG(dht.suhu), 2) AS Nilai
            FROM
                alat
            JOIN
                dht ON dht.id_alat = alat.id_alat
            WHERE
                alat.id_lokasi = ?
                AND DATE(dht.created_at) = CURDATE()
                AND TIME(dht.created_at) BETWEEN ? AND ?
            GROUP BY
                Waktu
            ORDER BY
                Waktu ASC;",
                [$id_lokasi, $jam_awal, $jam_akhir] // Parameter binding
            );

            if (count($avgFosfin) > 0) {
                return response()->json([
                    'success' => true,
                    'data' => $avgFosfin
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Data not found',
                    'error' => 'Belum ada data hari ini'
                ], 404);
            }
        } catch (\Exception $e) {
            // Log the exception message
            Log::error('Exception: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Internal server error'
            ], 500);
        }
    }


    public function getJamKelembaban(Request $request)
    {
        try {
            // Mendapatkan id_lokasi dan jam_awal dari body request
            $id_lokasi = $request->input('id_lokasi');
            $jam_awal = $request->input('jam_awal');

            // Menentukan jam akhir berdasarkan jam awal + 1 jam
            $jam_akhir = date('H:i:s', strtotime($jam_awal . ' +1 hour'));

            $avgFosfin = DB::select(
                "SELECT
                DATE_FORMAT(dht.created_at, '%H:%i') AS Waktu,
                ROUND(AVG(dht.kelembaban), 2) AS Nilai
            FROM
                alat
            JOIN
                dht ON dht.id_alat = alat.id_alat
            WHERE
                alat.id_lokasi = ?
                AND DATE(dht.created_at) = CURDATE()
                AND TIME(dht.created_at) BETWEEN ? AND ?
            GROUP BY
                Waktu
            ORDER BY
                Waktu ASC;",
                [$id_lokasi, $jam_awal, $jam_akhir] // Parameter binding
            );

            if (count($avgFosfin) > 0) {
                return response()->json([
                    'success' => true,
                    'data' => $avgFosfin
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Data not found',
                    'error' => 'Belum ada data hari ini'
                ], 404);
            }
        } catch (\Exception $e) {
            // Log the exception message
            Log::error('Exception: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Internal server error'
            ], 500);
        }
    }


    public function getJamFosfin(Request $request)
    {
        try {
            // Mendapatkan id_lokasi dan jam_awal dari body request
            $id_lokasi = $request->input('id_lokasi');
            $jam_awal = $request->input('jam_awal');

            // Menentukan jam akhir berdasarkan jam awal + 1 jam
            $jam_akhir = date('H:i:s', strtotime($jam_awal . ' +1 hour'));

            $avgFosfin = DB::select(
                "SELECT
                DATE_FORMAT(fosfin.created_at, '%H:%i') AS Waktu,
                ROUND(AVG(fosfin.fosfin), 2) AS Nilai
            FROM
                alat
            JOIN
                fosfin ON fosfin.id_alat = alat.id_alat
            WHERE
                alat.id_lokasi = ?
                AND DATE(fosfin.created_at) = CURDATE()
                AND TIME(fosfin.created_at) BETWEEN ? AND ?
            GROUP BY
                Waktu
            ORDER BY
                Waktu ASC;",
                [$id_lokasi, $jam_awal, $jam_akhir] // Parameter binding
            );

            if (count($avgFosfin) > 0) {
                return response()->json([
                    'success' => true,
                    'data' => $avgFosfin
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Data not found',
                    'error' => 'Belum ada data hari ini'
                ], 404);
            }
        } catch (\Exception $e) {
            // Log the exception message
            Log::error('Exception: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Internal server error'
            ], 500);
        }
    }
}
