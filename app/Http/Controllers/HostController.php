<?php

namespace App\Http\Controllers;

use App\Models\LaporanHost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class HostController extends Controller
{
    /**
     * Tampilkan form input laporan dan history laporan
     * 
     * Hanya user dengan role HOST yang bisa mengakses
     * Menampilkan 7 laporan terakhir milik HOST yang sedang login
     */
    public function create()
    {
        // Ambil 7 laporan terakhir milik HOST yang sedang login
        // Urutkan dari yang terbaru (DESC)
        $laporans = LaporanHost::where('user_id', Auth::id())
            ->orderBy('tanggal_laporan', 'desc')
            ->take(7)
            ->get();

        return view('host.create', compact('laporans'));
    }

    /**
     * Simpan laporan baru ke database
     * 
     * Validasi:
     * - tanggal_laporan: required, harus berformat date, tidak boleh lebih dari hari ini
     * - total_penjualan_host: required, numeric, minimal 0
     * - Kombinasi user_id + tanggal_laporan harus UNIQUE (tidak boleh duplikat)
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'tanggal_laporan' => [
                'required',
                'date',
                'before_or_equal:today', // Tidak boleh input tanggal masa depan
            ],
            'total_penjualan_host' => [
                'required',
                'numeric',
                'min:0', // Tidak boleh negatif
            ],
        ], [
            // Custom error messages (Bahasa Indonesia)
            'tanggal_laporan.required' => 'Tanggal laporan harus diisi.',
            'tanggal_laporan.date' => 'Format tanggal tidak valid.',
            'tanggal_laporan.before_or_equal' => 'Tanggal laporan tidak boleh lebih dari hari ini.',
            'total_penjualan_host.required' => 'Total penjualan harus diisi.',
            'total_penjualan_host.numeric' => 'Total penjualan harus berupa angka.',
            'total_penjualan_host.min' => 'Total penjualan tidak boleh kurang dari 0.',
        ]);

        // Cek apakah sudah ada laporan untuk tanggal ini (untuk user yang sama)
        $existingLaporan = LaporanHost::where('user_id', Auth::id())
            ->where('tanggal_laporan', $validated['tanggal_laporan'])
            ->first();

        if ($existingLaporan) {
            // Jika sudah ada, kembalikan error dengan flash message
            throw ValidationException::withMessages([
                'tanggal_laporan' => 'Anda sudah membuat laporan untuk tanggal ini. Silakan pilih tanggal lain.',
            ]);
        }

        // Simpan laporan baru
        LaporanHost::create([
            'user_id' => Auth::id(),
            'tanggal_laporan' => $validated['tanggal_laporan'],
            'total_penjualan_host' => $validated['total_penjualan_host'],
        ]);

        // Redirect dengan flash message sukses
        return redirect()
            ->route('host.laporan.create')
            ->with('success', 'Laporan berhasil disimpan! Data akan diproses dalam rekonsiliasi otomatis.');
    }
}