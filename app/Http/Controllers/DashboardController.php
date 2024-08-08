<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {
        if(Auth::user()->id_level == '1') {
            return view('dashboard.admin');
        } else if(Auth::user()->id_level == '2') {
            return view('dashboard.user');
        } else {
            
        }
    }
}
