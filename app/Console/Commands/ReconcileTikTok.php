<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\DataPenjualanApi;
use App\Models\LaporanHost;
use App\Models\HasilRekonsiliasi;
use App\Services\TikTokApiService;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReconcileTikTok extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reconcile:tiktok {--date=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rekonsiliasi data penjualan TikTok dengan laporan HOST';

    /**
     * TikTok API Service
     */
    private $tiktokApi;

    /**
     * Create a new command instance.
     */
    public function __construct(TikTokApiService $tiktokApi)
    {
        parent::__construct();
        $this->tiktokApi = $tiktokApi;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Memulai proses rekonsiliasi...');

        // ========================================
        // 1. TENTUKAN TANGGAL YANG AKAN DIREKONSILIASI
        // ========================================
        $tanggal = $this->option('date') 
            ? Carbon::parse($this->option('date')) 
            : Carbon::yesterday(); // Default: kemarin

        $this->info("📅 Tanggal rekonsiliasi: {$tanggal->format('Y-m-d')}");

        // ========================================
        // 2. FETCH DATA DARI API TIKTOK (REAL)
        // ========================================
        $this->info('🌐 Mengambil data dari TikTok API...');
        
        try {
            // Ambil data order dari TikTok
            $orders = $this->tiktokApi->getOrders($tanggal, $tanggal);
            
            if (empty($orders)) {
                $this->warn('⚠️  Tidak ada data order dari TikTok API');
                
                // Gunakan simulasi sebagai fallback (untuk development)
                if (app()->environment('local')) {
                    $this->info('🔄 Menggunakan data simulasi (development mode)...');
                    $apiData = $this->simulateApiData($tanggal);
                } else {
                    $this->error('❌ Tidak bisa melanjutkan rekonsiliasi tanpa data API');
                    return Command::FAILURE;
                }
            } else {
                // Transform data TikTok ke format kita
                $apiData = $this->tiktokApi->transformOrders($orders);
                $this->info("✅ Berhasil mengambil " . count($apiData) . " order dari TikTok");
            }
            
        } catch (\Exception $e) {
            $this->error('❌ Error saat mengambil data dari TikTok API: ' . $e->getMessage());
            
            // Fallback ke simulasi di development mode
            if (app()->environment('local')) {
                $this->info('🔄 Menggunakan data simulasi (development mode)...');
                $apiData = $this->simulateApiData($tanggal);
            } else {
                return Command::FAILURE;
            }
        }
        
        // Simpan data mentah API ke database
        foreach ($apiData as $order) {
            DataPenjualanApi::updateOrCreate(
                ['order_id' => $order['order_id']], // Cek berdasarkan order_id
                [
                    'tanggal_penjualan' => $order['tanggal_penjualan'],
                    'total_harga' => $order['total_harga'],
                ]
            );
        }
        $this->info("✅ Berhasil menyimpan " . count($apiData) . " data penjualan API");

        // ========================================
        // 3. AGREGASI: HITUNG TOTAL PENJUALAN API PER HARI
        // ========================================
        $totalApi = DataPenjualanApi::whereDate('tanggal_penjualan', $tanggal)
            ->sum('total_harga');
        
        $this->info("💰 Total Penjualan API: Rp " . number_format($totalApi, 0, ',', '.'));

        // ========================================
        // 4. AMBIL SEMUA HOST YANG AKTIF
        // ========================================
        $hosts = User::where('role', 'HOST')->get();
        $this->info("👥 Menemukan " . $hosts->count() . " HOST yang terdaftar");

        // ========================================
        // 5. LOOP SETIAP HOST: BANDINGKAN DATA
        // ========================================
        foreach ($hosts as $host) {
            $this->info("🔍 Memproses HOST: {$host->name}...");

            // Ambil laporan HOST untuk tanggal ini
            $laporanHost = LaporanHost::where('user_id', $host->id)
                ->whereDate('tanggal_laporan', $tanggal)
                ->first();

            // Cek apakah HOST sudah lapor
            if (!$laporanHost) {
                $this->warn("   ⚠️  {$host->name} belum submit laporan untuk tanggal {$tanggal->format('Y-m-d')}");
                
                // Simpan hasil dengan status "HOST_BELUM_LAPOR"
                HasilRekonsiliasi::updateOrCreate(
                    [
                        'user_id' => $host->id,
                        'tanggal' => $tanggal,
                    ],
                    [
                        'total_api' => $totalApi,
                        'total_host' => 0,
                        'selisih' => $totalApi, // Selisih = total API (karena host belum lapor)
                        'status' => 'HOST_BELUM_LAPOR',
                    ]
                );
                continue; // Skip ke HOST berikutnya
            }

            // Jika HOST sudah lapor, hitung selisih
            $totalHost = $laporanHost->total_penjualan_host;
            $selisih = $totalApi - $totalHost;

            // Tentukan status berdasarkan selisih
            // Toleransi: jika selisih < Rp 1.000, dianggap COCOK
            $status = abs($selisih) < 1000 ? 'COCOK' : 'SELISIH';

            $this->info("   💵 Total Host: Rp " . number_format($totalHost, 0, ',', '.'));
            $this->info("   📊 Selisih: Rp " . number_format($selisih, 0, ',', '.'));
            $this->info("   ✔️  Status: {$status}");

            // Simpan hasil rekonsiliasi
            HasilRekonsiliasi::updateOrCreate(
                [
                    'user_id' => $host->id,
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

        // ========================================
        // 6. SELESAI
        // ========================================
        $this->info('');
        $this->info('✅ Proses rekonsiliasi selesai!');
        
        return Command::SUCCESS;
    }

    /**
     * Simulasi data API TikTok
     * Nanti ini akan diganti dengan real API call ke TikTok
     */
    private function simulateApiData($tanggal)
    {
        // Simulasi: Generate 10 order random untuk tanggal yang ditentukan
        $orders = [];
        
        for ($i = 1; $i <= 10; $i++) {
            $orders[] = [
                'order_id' => 'TT-' . $tanggal->format('Ymd') . '-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'tanggal_penjualan' => $tanggal->format('Y-m-d'),
                'total_harga' => rand(50000, 500000), // Random antara 50rb - 500rb
            ];
        }

        return $orders;
    }
}