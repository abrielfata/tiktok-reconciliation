<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Strategi:
     * - Buat 2 HOST dengan nama yang mudah diidentifikasi
     * - Buat 1 MANAJER untuk monitoring
     * - Password sama untuk kemudahan testing: "password"
     * - Email verified otomatis (skip email verification)
     */
    public function run(): void
    {
        // ========================================
        // 1. MANAJER (untuk monitoring dashboard)
        // ========================================
        User::create([
            'name' => 'Manager Kepswell',
            'email' => 'manager@kepswell.com',
            'password' => Hash::make('password'),
            'role' => 'MANAJER',
            'email_verified_at' => now(), // Auto verified
        ]);

        $this->command->info('✅ Manager created: manager@kepswell.com');

        // ========================================
        // 2. HOST 1 - Siti (Performa Bagus)
        // ========================================
        User::create([
            'name' => 'Siti Nurhaliza',
            'email' => 'siti@kepswell.com',
            'password' => Hash::make('password'),
            'role' => 'HOST',
            'email_verified_at' => now(),
        ]);

        $this->command->info('✅ Host 1 created: siti@kepswell.com (Performa: Bagus)');

        // ========================================
        // 3. HOST 2 - Rina (Kadang Ada Selisih)
        // ========================================
        User::create([
            'name' => 'Rina Pratiwi',
            'email' => 'rina@kepswell.com',
            'password' => Hash::make('password'),
            'role' => 'HOST',
            'email_verified_at' => now(),
        ]);

        $this->command->info('✅ Host 2 created: rina@kepswell.com (Performa: Kadang Selisih)');

        // ========================================
        // 4. HOST 3 - Dewi (Sering Lupa Lapor)
        // ========================================
        User::create([
            'name' => 'Dewi Lestari',
            'email' => 'dewi@kepswell.com',
            'password' => Hash::make('password'),
            'role' => 'HOST',
            'email_verified_at' => now(),
        ]);

        $this->command->info('✅ Host 3 created: dewi@kepswell.com (Performa: Sering Lupa)');

        // ========================================
        // Summary
        // ========================================
        $this->command->newLine();
        $this->command->info('📊 Summary:');
        $this->command->info('   - Total Users: 4');
        $this->command->info('   - Manajer: 1');
        $this->command->info('   - Host: 3');
        $this->command->info('   - Default Password: password');
    }
}