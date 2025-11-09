<?php

namespace App\Http\Controllers;

use App\Models\HasilRekonsiliasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ManagerController extends Controller
{
    /**
     * Dashboard Manajer - Menampilkan hasil rekonsiliasi
     * 
     * Fitur:
     * - Menampilkan hasil rekonsiliasi dengan informasi HOST
     * - Filter berdasarkan tanggal (date range)
     * - Pagination (15 data per halaman)
     * - Color coding berdasarkan status
     */
    public function dashboard(Request $request)
    {
        // Validasi input filter (optional)
        $validated = $request->validate([
            'tanggal_dari' => 'nullable|date',
            'tanggal_sampai' => 'nullable|date|after_or_equal:tanggal_dari',
        ], [
            'tanggal_sampai.after_or_equal' => 'Tanggal sampai harus lebih besar atau sama dengan tanggal dari.',
        ]);

        // Query hasil rekonsiliasi dengan join ke tabel users
        // Menggunakan Eloquent dengan eager loading untuk performa lebih baik
        $query = HasilRekonsiliasi::with('user') // Eager load relasi user
            ->orderBy('tanggal', 'desc'); // Urutkan dari tanggal terbaru

        // Apply filter tanggal jika ada
        if ($request->filled('tanggal_dari')) {
            $query->where('tanggal', '>=', $request->tanggal_dari);
        }

        if ($request->filled('tanggal_sampai')) {
            $query->where('tanggal', '<=', $request->tanggal_sampai);
        }

        // Pagination - 15 data per halaman
        // withQueryString() untuk retain filter di pagination links
        $data = $query->paginate(15)->withQueryString();

        // Hitung statistik untuk dashboard summary
        $stats = $this->getStatistics($request);

        return view('manager.dashboard', compact('data', 'stats'));
    }

    /**
     * Helper method untuk menghitung statistik dashboard
     * 
     * @param Request $request
     * @return array
     */
    private function getStatistics(Request $request)
    {
        $query = HasilRekonsiliasi::query();

        // Apply filter yang sama seperti di dashboard
        if ($request->filled('tanggal_dari')) {
            $query->where('tanggal', '>=', $request->tanggal_dari);
        }

        if ($request->filled('tanggal_sampai')) {
            $query->where('tanggal', '<=', $request->tanggal_sampai);
        }

        // Hitung jumlah per status menggunakan groupBy
        $statusCounts = $query->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        return [
            'total_cocok' => $statusCounts['COCOK'] ?? 0,
            'total_selisih' => $statusCounts['SELISIH'] ?? 0,
            'total_belum_lapor' => $statusCounts['HOST_BELUM_LAPOR'] ?? 0,
            'total_semua' => array_sum($statusCounts),
        ];
    }
}