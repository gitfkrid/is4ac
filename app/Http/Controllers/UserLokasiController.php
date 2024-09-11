<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\user_lokasi;
use App\Models\lokasi;
use App\Models\User;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class UserLokasiController extends Controller
{
    public function index()
    {
        $userIdsInUserLokasi = DB::table('user_lokasi')->pluck('id_user');
        $users = User::whereNotIn('id', $userIdsInUserLokasi)->get();
        $lokasi = Lokasi::all();

        return view('userlokasi.index', compact('users', 'lokasi'));
    }

    public function getUsers()
    {
        $userIdsInUserLokasi = DB::table('user_lokasi')->pluck('id_user');
        $users = User::whereNotIn('id', $userIdsInUserLokasi)->get();
        $lokasi = Lokasi::all();

        return response()->json([
            'users' => $users,
            // 'lokasi' => $lokasi
        ]);
    }

    public function dataUserLokasi()
    {
        $userLokasi = User::join('level', 'level.id_level', '=', 'users.id_level')
            ->join('user_lokasi', 'user_lokasi.id_user', '=', 'users.id')
            ->join('lokasi', 'lokasi.id_lokasi', '=', 'user_lokasi.id_lokasi')
            ->select('users.id', 'users.name', 'users.email', 'level.nama_level', DB::raw('GROUP_CONCAT(lokasi.nama_lokasi SEPARATOR ", ") as lokasi'))
            // ->where('level.id_level', '!=', 1)
            // ->where('users.id', '!=', Auth()->user()->id)
            ->groupBy('users.id', 'users.name', 'users.email', 'level.nama_level')
            ->orderBy('users.id', 'desc')
            ->get();

        $no = 0;
        $data = array();
        foreach ($userLokasi as $list) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $list->name;
            $row[] = $list->email;
            $row[] = $list->lokasi;
            $row[] = '<a href="javascript:void(0)" class="btn btn-warning btn-sm" onclick="editData(' . $list->id . ')"><i class="fa fa-edit"></i></a>
            <a href="javascript:void(0)" class="btn btn-danger btn-sm" onclick="deleteData(' . $list->id . ')"><i class="fa fa-trash"></i></a>';
            $data[] = $row;
        }
        return DataTables::of($data)->escapeColumns([])->make(true);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_user' => 'required|exists:users,id',
            'lokasi' => 'required|array',
            'lokasi.*' => 'exists:lokasi,id_lokasi',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $userId = $request->input('id_user');
        $locations = $request->input('lokasi');

        DB::table('user_lokasi')->where('id_user', $userId)->delete();

        foreach ($locations as $locationId) {
            DB::table('user_lokasi')->insert([
                'id_user' => $userId,
                'id_lokasi' => $locationId,
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil disimpan.',
        ]);
    }

    //Edit - Belum
    public function edit($id)
    {
        $user = User::select('id', 'name')
            ->where('id', $id)
            ->first();

        $userLokasi = user_lokasi::select('user_lokasi.id_lokasi', 'lokasi.nama_lokasi')
            ->join('lokasi', 'user_lokasi.id_lokasi', '=', 'lokasi.id_lokasi')
            ->where('user_lokasi.id_user', $id)
            ->get();

        $response = [
            'id_user' => $user->id,
            'name' => $user->name,
            'lokasi' => $userLokasi->map(function ($lokasi) {
                return [
                    'id_lokasi' => $lokasi->id_lokasi,
                    'nama_lokasi' => $lokasi->nama_lokasi,
                ];
            })->toArray(),
        ];

        return response()->json($response);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'lok' => 'required|array',
            'lok.*' => 'exists:lokasi,id_lokasi',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $userId = $request->input('user_id');
        $locations = $request->input('lok');

        // Gunakan transaksi untuk memastikan konsistensi data
        DB::beginTransaction();
        try {
            // Hapus data lama
            DB::table('user_lokasi')->where('id_user', $userId)->delete();

            // Insert data baru
            foreach ($locations as $locationId) {
                DB::table('user_lokasi')->insert([
                    'id_user' => $userId,
                    'id_lokasi' => $locationId,
                ]);
            }

            // Commit transaksi jika semua berjalan lancar
            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil disimpan.',
            ]);
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        DB::table('user_lokasi')
            ->where('id_user', $id)
            ->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Semua lokasi untuk pengguna berhasil dihapus.',
        ]);
    }
}
