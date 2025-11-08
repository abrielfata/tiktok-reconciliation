<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HostController extends Controller
{
    /**
     * Tampilkan halaman form input laporan (sementara)
     */
    public function create()
    {
        return view('host.create');
    }

    /**
     * Simpan laporan (akan kita isi nanti)
     */
    public function store(Request $request)
    {
        return redirect()->back()->with('success', 'Fitur ini akan aktif di Langkah 5!');
    }
}