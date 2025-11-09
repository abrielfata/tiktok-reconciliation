<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DataPenjualanApi;
use App\Models\User;
use Carbon\Carbon;

class DataPenjualanApiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Strategi:
     * - Generate data API untuk 14 hari terakhir
     * - Setiap hari ada 5-15 transaksi (random)
     * - Total per hari bervariasi: 500K - 5juta
     * - Order ID format realistis: TT2024XXXXXXXXXX
     */
    public function run(): void
    {
        $startDate = Carbon::now()->subDays(13); // 14 hari terakhir (termasuk hari ini)
        $endDate = Carbon::now();
        
        $this->command->info('🔄 Generating TikTok API Sales Data...');
        $this->command->newLine();

        $totalTransactions = 0;
        $totalAmount = 0;

        // Loop untuk setiap hari
        for ($date = $startDate->copy(); $date <= $endDate; $date->addDay()) {
            // Random jumlah transaksi per hari: 5-15 transaksi
            $transactionsPerDay = rand(5, 15);
            $dailyTotal = 0;

            for ($i = 0; $i < $transactionsPerDay; $i++) {
                // Random harga per transaksi: 20K - 500K
                $amount = rand(20000, 500000);
                
                // Generate order ID unik dengan format TikTok-like
                $orderId = 'TT' . $date->format('Ymd') . str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT);

                DataPenjualanApi::create([
                    'order_id' => $orderId,
                    'tanggal_penjualan' => $date->format('Y-m-d'),
                    'total_harga' => $amount,
                ]);

                $dailyTotal += $amount;
                $totalTransactions++;
                $totalAmount += $amount;
            }

            $this->command->info(sprintf(
                '✅ %s: %d transactions, Total: Rp %s',
                $date->format('Y-m-d'),
                $transactionsPerDay,
                number_format($dailyTotal, 0, ',', '.')
            ));
        }

        // Summary
        $this->command->newLine();
        $this->command->info('📊 Summary API Data:');
        $this->command->info(sprintf('   - Period: %s to %s', $startDate->format('Y-m-d'), $endDate->format('Y-m-d')));
        $this->command->info(sprintf('   - Total Transactions: %d', $totalTransactions));
        $this->command->info(sprintf('   - Total Amount: Rp %s', number_format($totalAmount, 0, ',', '.')));
        $this->command->info(sprintf('   - Average per Day: Rp %s', number_format($totalAmount / 14, 0, ',', '.')));
    }
}