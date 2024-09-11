<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Login Gagal',
                'data' => $validator->errors()
            ], 400);
        }

        $user = User::where('email', $request->email)->first();
        $success['token'] = $user->createToken('IS4AC')->plainTextToken;
        $success['id'] = $user->id;
        $success['nama'] = $user->name;
        $success['email'] = $user->email;

        if ($success) {
            if (password_verify($request->password, $user->password)) {
                return response()->json([
                    'success' => true,
                    'message' => 'Login Berhasil',
                    'data' => $success
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Login Gagal, password salah',
                    'data' => ''
                ], 400);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Login Gagal, username tidak ditemukan',
                'data' => ''
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        if ($request) {
            return response()->json([
                'success' => true,
                'message' => 'Logout Berhasil',
                'data' => ''
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Logout Gagal',
                'data' => ''
            ], 500);
        }
    }

    public function profile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal',
                'data' => $validator->errors()
            ], 400);
        }

        $user = User::join('level', 'level.id_level', '=', 'users.id_level')
            ->join('user_lokasi', 'user_lokasi.id_user', '=', 'users.id')
            ->join('lokasi', 'lokasi.id_lokasi', '=', 'user_lokasi.id_lokasi')
            ->select('users.id', 'users.name', 'users.email', 'level.nama_level', 'lokasi.id_lokasi', 'lokasi.nama_lokasi')
            ->where('users.id', $request->id)
            ->orderBy('users.id', 'desc')
            ->get();

        $data['name'] = $user->first()->name;
        $data['email'] = $user->first()->email;
        $data['level'] = $user->first()->nama_level;
        $data['lokasi'] = [];

        foreach ($user as $lokasi) {
            $data['lokasi'][] = [
                'id_lokasi' => $lokasi->id_lokasi,
                'nama_lokasi' => $lokasi->nama_lokasi,
            ];
        }

        if ($user) {
            return response()->json([
                'success' => true,
                'message' => 'Berhasil',
                'data' => $data
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Gagal',
                'data' => ''
            ], 500);
        }
    }

    public function editprofile(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Edit Profile gagal, cek kembali data yang anda masukkan',
                'data' => $validator->errors()
            ], 400);
        }

        $data = $request->all();
        $user = User::where('id', $id)->first();
        $user->name = $request['name'];
        $user->email = $request['email'];

        if ($user->save()) {
            return response()->json([
                'success' => true,
                'message' => 'Edit Profile Berhasil',
                'data' => $data
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Edit Profile Gagal, coba lagi nanti',
                'data' => ''
            ], 500);
        }
    }

    public function editpassword(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'password_lama' => 'required',
            'password_baru' => 'required',
            'c_password_baru' => 'required|same:password_baru',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Edit Password gagal, cek kembali data yang anda masukkan',
                'data' => $validator->errors()
            ], 400);
        }

        if (!Hash::check($request->password_lama, auth()->user()->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Edit Password gagal, password lama tidak sama',
            ]);
        }

        $user = User::where('id', $id)->first();
        $user->password = bcrypt($request['password_baru']);

        if ($user->save()) {
            return response()->json([
                'success' => true,
                'message' => 'Edit Password Berhasil',
                'data' => ''
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Edit Password Gagal, coba lagi nanti',
                'data' => ''
            ], 500);
        }
    }
}
