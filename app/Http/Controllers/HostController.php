<?php

namespace App\Http\Controllers;

use App\Models\LaporanHost;
use App\Models\LaporanHostHistory;
use App\Models\HasilRekonsiliasi;
use App\Models\DataPenjualanApi;
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
        // Urutkan dari yang terbaru berdasarkan tanggal laporan, kemudian created_at
        $laporans = LaporanHost::where('user_id', Auth::id())
            ->orderBy('tanggal_laporan', 'desc')
            ->orderBy('created_at', 'desc')
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
        $laporan = LaporanHost::create([
            'user_id' => Auth::id(),
            'tanggal_laporan' => $validated['tanggal_laporan'],
            'total_penjualan_host' => $validated['total_penjualan_host'],
        ]);

        // Simpan history
        LaporanHostHistory::create([
            'laporan_host_id' => $laporan->id,
            'user_id' => Auth::id(),
            'action' => 'created',
            'tanggal_laporan_new' => $validated['tanggal_laporan'],
            'total_penjualan_host_new' => $validated['total_penjualan_host'],
            'notes' => 'Laporan baru dibuat',
        ]);

        // Update hasil rekonsiliasi untuk tanggal ini
        $this->updateReconciliation($validated['tanggal_laporan'], Auth::id());

        // Redirect dengan flash message sukses
        return redirect()
            ->route('host.laporan.create')
            ->with('success', 'Laporan berhasil disimpan! Data rekonsiliasi telah diperbarui.');
    }

    /**
     * Tampilkan form edit laporan
     * 
     * Hanya bisa edit laporan milik sendiri
     */
    public function edit($id)
    {
        $laporan = LaporanHost::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('host.edit', compact('laporan'));
    }

    /**
     * Update laporan yang sudah ada
     * 
     * Validasi sama seperti store, tapi tidak perlu cek duplikat untuk laporan yang sama
     */
    public function update(Request $request, $id)
    {
        // Pastikan laporan milik user yang sedang login
        $laporan = LaporanHost::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Validasi input
        $validated = $request->validate([
            'tanggal_laporan' => [
                'required',
                'date',
                'before_or_equal:today',
            ],
            'total_penjualan_host' => [
                'required',
                'numeric',
                'min:0',
            ],
        ], [
            'tanggal_laporan.required' => 'Tanggal laporan harus diisi.',
            'tanggal_laporan.date' => 'Format tanggal tidak valid.',
            'tanggal_laporan.before_or_equal' => 'Tanggal laporan tidak boleh lebih dari hari ini.',
            'total_penjualan_host.required' => 'Total penjualan harus diisi.',
            'total_penjualan_host.numeric' => 'Total penjualan harus berupa angka.',
            'total_penjualan_host.min' => 'Total penjualan tidak boleh kurang dari 0.',
        ]);

        // Cek apakah ada laporan lain dengan tanggal yang sama (kecuali laporan yang sedang di-edit)
        $existingLaporan = LaporanHost::where('user_id', Auth::id())
            ->where('tanggal_laporan', $validated['tanggal_laporan'])
            ->where('id', '!=', $id)
            ->first();

        if ($existingLaporan) {
            throw ValidationException::withMessages([
                'tanggal_laporan' => 'Anda sudah membuat laporan untuk tanggal ini. Silakan pilih tanggal lain.',
            ]);
        }

        // Simpan data lama untuk history
        $oldData = [
            'tanggal_laporan' => $laporan->tanggal_laporan,
            'total_penjualan_host' => $laporan->total_penjualan_host,
        ];

        // Update laporan
        $laporan->update([
            'tanggal_laporan' => $validated['tanggal_laporan'],
            'total_penjualan_host' => $validated['total_penjualan_host'],
        ]);

        // Simpan history
        LaporanHostHistory::create([
            'laporan_host_id' => $laporan->id,
            'user_id' => Auth::id(),
            'action' => 'updated',
            'tanggal_laporan_old' => $oldData['tanggal_laporan'],
            'tanggal_laporan_new' => $validated['tanggal_laporan'],
            'total_penjualan_host_old' => $oldData['total_penjualan_host'],
            'total_penjualan_host_new' => $validated['total_penjualan_host'],
            'notes' => 'Laporan diperbarui',
        ]);

        // Update hasil rekonsiliasi untuk tanggal lama dan baru (jika berbeda)
        if ($oldData['tanggal_laporan'] != $validated['tanggal_laporan']) {
            // Jika tanggal berubah, update kedua tanggal
            $this->updateReconciliation($oldData['tanggal_laporan'], Auth::id());
            $this->updateReconciliation($validated['tanggal_laporan'], Auth::id());
        } else {
            // Jika tanggal sama, hanya update sekali
            $this->updateReconciliation($validated['tanggal_laporan'], Auth::id());
        }

        // Redirect dengan flash message sukses
        return redirect()
            ->route('host.laporan.create')
            ->with('success', 'Laporan berhasil diperbarui! Data rekonsiliasi telah diperbarui.');
    }

    /**
     * Hapus laporan
     * 
     * Hanya bisa hapus laporan milik sendiri
     */
    public function destroy($id)
    {
        // Pastikan laporan milik user yang sedang login
        $laporan = LaporanHost::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Simpan tanggal laporan untuk update rekonsiliasi
        $tanggalLaporan = $laporan->tanggal_laporan;

        // Simpan data untuk history sebelum dihapus
        LaporanHostHistory::create([
            'laporan_host_id' => $laporan->id,
            'user_id' => Auth::id(),
            'action' => 'deleted',
            'tanggal_laporan_old' => $laporan->tanggal_laporan,
            'total_penjualan_host_old' => $laporan->total_penjualan_host,
            'notes' => 'Laporan dihapus',
        ]);

        // Hapus laporan
        $laporan->delete();

        // Update hasil rekonsiliasi untuk tanggal ini (karena laporan sudah dihapus)
        $this->updateReconciliation($tanggalLaporan, Auth::id());

        // Redirect dengan flash message sukses
        return redirect()
            ->route('host.laporan.create')
            ->with('success', 'Laporan berhasil dihapus! Data rekonsiliasi telah diperbarui.');
    }

    /**
     * Tampilkan history perubahan laporan
     */
    public function history($id)
    {
        // Cek apakah laporan masih ada atau sudah dihapus
        $laporan = LaporanHost::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        // Ambil semua history untuk laporan ini (termasuk jika sudah dihapus)
        $histories = LaporanHostHistory::where('laporan_host_id', $id)
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        // Jika laporan tidak ada, ambil data dari history terakhir (untuk deleted)
        if (!$laporan && $histories->count() > 0) {
            $lastHistory = $histories->first();
            // Buat object dummy untuk menampilkan data
            $laporan = (object) [
                'id' => $id,
                'tanggal_laporan' => $lastHistory->tanggal_laporan_old ?? $lastHistory->tanggal_laporan_new,
                'total_penjualan_host' => $lastHistory->total_penjualan_host_old ?? $lastHistory->total_penjualan_host_new,
                'deleted' => true,
            ];
        }

        if (!$laporan) {
            abort(404, 'Laporan tidak ditemukan');
        }

        return view('host.history', compact('laporan', 'histories'));
    }

    /**
     * Tampilkan semua aktivitas (activity log) user
     * Menampilkan semua create, update, delete dari semua laporan
     */
    public function activities()
    {
        // Ambil semua history aktivitas user yang sedang login untuk pagination
        $activities = LaporanHostHistory::where('user_id', Auth::id())
            ->with('laporanHost')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Hitung statistik dari semua data (bukan hanya yang di-paginate)
        $stats = [
            'created' => LaporanHostHistory::where('user_id', Auth::id())->where('action', 'created')->count(),
            'updated' => LaporanHostHistory::where('user_id', Auth::id())->where('action', 'updated')->count(),
            'deleted' => LaporanHostHistory::where('user_id', Auth::id())->where('action', 'deleted')->count(),
        ];

        return view('host.activities', compact('activities', 'stats'));
    }

    /**
     * Update hasil rekonsiliasi untuk tanggal dan user tertentu
     * 
     * @param string $tanggal Tanggal laporan (format: Y-m-d)
     * @param int $userId ID user (HOST)
     */
    private function updateReconciliation($tanggal, $userId)
    {
        // Ambil total penjualan API untuk tanggal ini
        $totalApi = DataPenjualanApi::whereDate('tanggal_penjualan', $tanggal)
            ->sum('total_harga');

        // Ambil laporan HOST untuk tanggal ini
        $laporanHost = LaporanHost::where('user_id', $userId)
            ->whereDate('tanggal_laporan', $tanggal)
            ->first();

        // Jika HOST belum lapor atau laporan sudah dihapus
        if (!$laporanHost) {
            HasilRekonsiliasi::updateOrCreate(
                [
                    'user_id' => $userId,
                    'tanggal' => $tanggal,
                ],
                [
                    'total_api' => $totalApi,
                    'total_host' => 0,
                    'selisih' => $totalApi,
                    'status' => 'HOST_BELUM_LAPOR',
                ]
            );
            return;
        }

        // Jika HOST sudah lapor, hitung selisih
        $totalHost = $laporanHost->total_penjualan_host;
        $selisih = $totalApi - $totalHost;

        // Tentukan status berdasarkan selisih
        // Toleransi: jika selisih < Rp 1.000, dianggap COCOK
        $status = abs($selisih) < 1000 ? 'COCOK' : 'SELISIH';

        // Update atau create hasil rekonsiliasi
        HasilRekonsiliasi::updateOrCreate(
            [
                'user_id' => $userId,
                'tanggal' => $tanggal,
            ],
            [
                'total_api' => $totalApi,
                'total_host' => $totalHost,
                'selisih' => $selisih,
                'status' => $status,
            ]
        );
    }
}