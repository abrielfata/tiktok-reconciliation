<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class TikTokApiService
{
    private $appKey;
    private $appSecret;
    private $apiUrl;

    public function __construct()
    {
        $this->appKey = config('services.tiktok.app_key');
        $this->appSecret = config('services.tiktok.app_secret');
        $this->apiUrl = config('services.tiktok.api_url');
    }

    /**
     * Generate signature untuk TikTok API
     * Dokumentasi: https://partner.tiktokshop.com/docv2/page/6507ead7b99d5302be949ba9
     */
    private function generateSignature($path, $timestamp, $params = [])
    {
        // Sort parameters by key
        ksort($params);
        
        // Build query string
        $queryString = '';
        foreach ($params as $key => $value) {
            $queryString .= $key . $value;
        }
        
        // Create signature string
        $signString = $this->appSecret . $path . $timestamp . $queryString . $this->appSecret;
        
        // Generate HMAC SHA256
        return hash_hmac('sha256', $signString, $this->appSecret);
    }

    /**
     * Ambil data order dari TikTok Shop
     * Endpoint: /api/orders/search
     * 
     * @param Carbon $startDate Tanggal awal
     * @param Carbon $endDate Tanggal akhir
     * @return array
     */
    public function getOrders(Carbon $startDate, Carbon $endDate)
    {
        try {
            $path = '/api/orders/search';
            $timestamp = time();
            
            // Parameters untuk API
            $params = [
                'app_key' => $this->appKey,
                'timestamp' => $timestamp,
                'shop_cipher' => '', // Akan diisi setelah authorize shop
                'create_time_from' => $startDate->startOfDay()->timestamp,
                'create_time_to' => $endDate->endOfDay()->timestamp,
                'page_size' => 100, // Max 100 per request
            ];
            
            // Generate signature
            $params['sign'] = $this->generateSignature($path, $timestamp, $params);
            
            // Call API
            $response = Http::timeout(30)->get($this->apiUrl . $path, $params);
            
            // Log response untuk debugging
            Log::info('TikTok API Response', [
                'status' => $response->status(),
                'body' => $response->json()
            ]);
            
            if ($response->successful()) {
                $data = $response->json();
                
                // Check API response code
                if (isset($data['code']) && $data['code'] == 0) {
                    return $data['data']['orders'] ?? [];
                } else {
                    Log::error('TikTok API Error', [
                        'code' => $data['code'] ?? 'unknown',
                        'message' => $data['message'] ?? 'No message'
                    ]);
                    return [];
                }
            }
            
            Log::error('TikTok API HTTP Error', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            
            return [];
            
        } catch (\Exception $e) {
            Log::error('TikTok API Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return [];
        }
    }

    /**
     * Transform data order TikTok ke format yang kita butuhkan
     */
    public function transformOrders($orders)
    {
        $transformed = [];
        
        foreach ($orders as $order) {
            $transformed[] = [
                'order_id' => $order['order_id'],
                'tanggal_penjualan' => Carbon::createFromTimestamp($order['create_time'])->format('Y-m-d'),
                'total_harga' => $order['payment']['total_amount'] ?? 0,
            ];
        }
        
        return $transformed;
    }
}