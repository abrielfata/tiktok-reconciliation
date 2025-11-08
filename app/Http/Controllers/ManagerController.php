<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ManagerController extends Controller
{
    /**
     * Tampilkan dashboard rekonsiliasi (sementara)
     */
    public function dashboard()
    {
        return view('manager.dashboard');
    }
}