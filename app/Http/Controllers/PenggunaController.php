<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Level;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class PenggunaController extends Controller
{
    public function index() {
        return view('pengguna.index');
    }

    public function dataPengguna() {
        $pengguna = User::join('level', 'level.id_level', '=', 'users.id_level')
                    ->where('level.id_level', '!=', 1)
                    ->where('users.id', '!=', Auth()->user()->id)
                    ->orderBy('id', 'desc')
                    ->get();
        $no = 0;
        $data = array();
        foreach ($pengguna as $list) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $list->name;
            $row[] = $list->email;
            $row[] = $list->created_at->format('Y-m-d H:i:s');
            $row[] = '<a href="javascript:void(0)" class="btn btn-danger btn-sm" onclick="deleteData('.$list->id.')"><i class="fa fa-trash"></i></a>';
            $data[] = $row;
        }
        return DataTables::of($data)->escapeColumns([])->make(true);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:users,name',
            'email' => 'required|unique:users,email',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }        

        $pengguna = new User;
        $pengguna->name = $request->name;
        $pengguna->email = $request->email;
        $pengguna->password = bcrypt($request->password);
        $pengguna->id_level = '2';
        $pengguna->save();
    }

    public function destroy($id)
    {
        $pengguna = User::find($id);
        $pengguna->delete();
    }
}
