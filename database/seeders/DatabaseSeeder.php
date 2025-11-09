<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * 
     * Urutan Eksekusi (PENTING!):
     * 1. UserSeeder - buat users dulu (karena ada FK di laporan_host)
     * 2. DataPenjualanApiSeeder - data API harus ada sebelum host lapor
     * 3. LaporanHostSeeder - host bikin laporan berdasarkan data API
     * 4. Auto-run reconcile command - generate hasil rekonsiliasi
     * 
     * Kenapa urutan ini penting?
     * - Foreign key constraint (user_id harus exist)
     * - LaporanHostSeeder membandingkan dengan data API yang sudah ada
     * - Reconcile command membutuhkan data API + laporan host
     */
    public function run(): void
    {
        $this->command->info('');
        $this->command->info('═════════════════════════════════════════════');
        $this->command->info('  🚀 KEPSWELL - Database Seeding Started');
        $this->command->info('═════════════════════════════════════════════');
        $this->command->newLine();

        // ========================================
        // Step 1: Buat Users (HOST & MANAJER)
        // ========================================
        $this->command->info('📝 Step 1/4: Creating Users (HOST & MANAJER)...');
        $this->command->newLine();
        
        $this->call(UserSeeder::class);
        
        $this->command->newLine();
        $this->command->info('─────────────────────────────────────────────');
        $this->command->newLine();

        // ========================================
        // Step 2: Generate Data API TikTok
        // ========================================
        $this->command->info('📝 Step 2/4: Generating TikTok API Data...');
        $this->command->newLine();
        
        $this->call(DataPenjualanApiSeeder::class);
        
        $this->command->newLine();
        $this->command->info('─────────────────────────────────────────────');
        $this->command->newLine();

        // ========================================
        // Step 3: Generate Laporan Host
        // ========================================
        $this->command->info('📝 Step 3/4: Generating Host Reports...');
        $this->command->newLine();
        
        $this->call(LaporanHostSeeder::class);
        
        $this->command->newLine();
        $this->command->info('─────────────────────────────────────────────');
        $this->command->newLine();

        // ========================================
        // Step 4: Auto-run Reconcile Command
        // ========================================
        $this->command->info('📝 Step 4/4: Running Reconciliation Process...');
        $this->command->newLine();
        $this->command->info('⚙️  Executing: php artisan reconcile:tiktok');
        $this->command->newLine();

        try {
            // Run reconcile command untuk generate hasil_rekonsiliasi
            Artisan::call('reconcile:tiktok', [], $this->command->getOutput());
            
            $this->command->newLine();
            $this->command->info('✅ Reconciliation completed successfully!');
        } catch (\Exception $e) {
            $this->command->error('❌ Reconciliation failed: ' . $e->getMessage());
        }

        // ========================================
        // Final Summary
        // ========================================
        $this->command->newLine();
        $this->command->info('═════════════════════════════════════════════');
        $this->command->info('  ✅ SEEDING COMPLETE!');
        $this->command->info('═════════════════════════════════════════════');
        $this->command->newLine();
        
        $this->command->info('📊 Database Summary:');
        $this->command->info('   ✓ Users: 4 (3 HOST, 1 MANAJER)');
        $this->command->info('   ✓ API Data: ~140-210 transactions (14 days)');
        $this->command->info('   ✓ Host Reports: Varies by host performance');
        $this->command->info('   ✓ Reconciliation Results: Auto-generated');
        $this->command->newLine();
        
        $this->command->info('🔐 Test Accounts:');
        $this->command->info('   Manager: manager@kepswell.com / password');
        $this->command->info('   Host 1:  siti@kepswell.com / password');
        $this->command->info('   Host 2:  rina@kepswell.com / password');
        $this->command->info('   Host 3:  dewi@kepswell.com / password');
        $this->command->newLine();
        
        $this->command->info('🎯 Next Steps:');
        $this->command->info('   1. Login as manager@kepswell.com');
        $this->command->info('   2. Check dashboard rekonsiliasi');
        $this->command->info('   3. Verify data dengan filter tanggal');
        $this->command->info('   4. Test host login & form input');
        $this->command->newLine();
    }
}