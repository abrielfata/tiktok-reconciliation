<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\LaporanHost;
use App\Models\DataPenjualanApi;
use Carbon\Carbon;

class LaporanHostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Strategi Skenario Testing:
     * 
     * HOST 1 (Siti) - Performa Bagus:
     * - Lapor 12 dari 14 hari (85% kehadiran)
     * - 90% data COCOK dengan API
     * - 10% data SELISIH kecil (±5%)
     * 
     * HOST 2 (Rina) - Kadang Selisih:
     * - Lapor 10 dari 14 hari (70% kehadiran)
     * - 70% data COCOK
     * - 30% data SELISIH sedang (±10-20%)
     * 
     * HOST 3 (Dewi) - Sering Lupa:
     * - Lapor 7 dari 14 hari (50% kehadiran)
     * - 60% data COCOK
     * - 40% data SELISIH besar (±20-30%)
     */
    public function run(): void
    {
        // Ambil semua HOST
        $hosts = User::where('role', 'HOST')->get();

        if ($hosts->count() === 0) {
            $this->command->error('❌ No HOST users found! Please run UserSeeder first.');
            return;
        }

        $startDate = Carbon::now()->subDays(13); // 14 hari terakhir
        $endDate = Carbon::now();

        $this->command->info('🔄 Generating Host Reports...');
        $this->command->newLine();

        // ========================================
        // HOST 1: Siti Nurhaliza (Performa Bagus)
        // ========================================
        $siti = $hosts->firstWhere('email', 'siti@kepswell.com');
        if ($siti) {
            $this->command->info('👤 HOST 1: ' . $siti->name);
            $this->generateReports($siti, $startDate, $endDate, [
                'report_rate' => 0.85, // Lapor 85% hari
                'accuracy_rate' => 0.90, // 90% cocok
                'variance_max' => 0.05, // Max selisih 5%
            ]);
        }

        // ========================================
        // HOST 2: Rina Pratiwi (Kadang Selisih)
        // ========================================
        $rina = $hosts->firstWhere('email', 'rina@kepswell.com');
        if ($rina) {
            $this->command->info('👤 HOST 2: ' . $rina->name);
            $this->generateReports($rina, $startDate, $endDate, [
                'report_rate' => 0.70, // Lapor 70% hari
                'accuracy_rate' => 0.70, // 70% cocok
                'variance_max' => 0.20, // Max selisih 20%
            ]);
        }

        // ========================================
        // HOST 3: Dewi Lestari (Sering Lupa)
        // ========================================
        $dewi = $hosts->firstWhere('email', 'dewi@kepswell.com');
        if ($dewi) {
            $this->command->info('👤 HOST 3: ' . $dewi->name);
            $this->generateReports($dewi, $startDate, $endDate, [
                'report_rate' => 0.50, // Lapor 50% hari
                'accuracy_rate' => 0.60, // 60% cocok
                'variance_max' => 0.30, // Max selisih 30%
            ]);
        }

        $this->command->newLine();
        $this->command->info('✅ Host Reports Generation Complete!');
    }

    /**
     * Generate laporan untuk satu host dengan skenario tertentu
     */
    private function generateReports(User $host, Carbon $startDate, Carbon $endDate, array $config): void
    {
        $reportCount = 0;
        $cocokCount = 0;
        $selisihCount = 0;

        for ($date = $startDate->copy(); $date <= $endDate; $date->addDay()) {
            // Tentukan apakah host lapor di hari ini (berdasarkan report_rate)
            $shouldReport = (rand(1, 100) / 100) <= $config['report_rate'];

            if (!$shouldReport) {
                $this->command->line("   ⚠️  {$date->format('Y-m-d')}: TIDAK LAPOR");
                continue;
            }

            // Ambil total API untuk tanggal ini
            $totalApi = DataPenjualanApi::where('tanggal_penjualan', $date->format('Y-m-d'))
                ->sum('total_harga');

            // Jika tidak ada data API, skip
            if ($totalApi == 0) {
                continue;
            }

            // Tentukan apakah laporan ini COCOK atau SELISIH
            $isCocok = (rand(1, 100) / 100) <= $config['accuracy_rate'];

            if ($isCocok) {
                // Data COCOK (sama persis dengan API)
                $totalHost = $totalApi;
                $status = '✅ COCOK';
                $cocokCount++;
            } else {
                // Data SELISIH (tambahkan variance random)
                $variance = rand(-100, 100) / 100 * $config['variance_max']; // -30% to +30%
                $totalHost = $totalApi + ($totalApi * $variance);
                $totalHost = round($totalHost, 2); // Bulatkan ke 2 desimal
                $status = '⚠️  SELISIH';
                $selisihCount++;
            }

            // Insert laporan
            LaporanHost::create([
                'user_id' => $host->id,
                'tanggal_laporan' => $date->format('Y-m-d'),
                'total_penjualan_host' => $totalHost,
            ]);

            $reportCount++;
            $this->command->line(sprintf(
                "   %s %s: Rp %s (API: Rp %s)",
                $status,
                $date->format('Y-m-d'),
                number_format($totalHost, 0, ',', '.'),
                number_format($totalApi, 0, ',', '.')
            ));
        }

        // Summary per host
        $this->command->info(sprintf(
            "   📊 Summary: %d reports (%d cocok, %d selisih)",
            $reportCount,
            $cocokCount,
            $selisihCount
        ));
        $this->command->newLine();
    }
}