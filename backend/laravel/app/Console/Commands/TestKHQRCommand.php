<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\KHQRService;
use KHQR\BakongKHQR;
use Exception;

class TestKHQRCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:khqr {--amount=1000}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test KHQR service and diagnose connection issues';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧪 KHQR Service Diagnostic Tests');
        $this->info('================================');
        
        $this->testEnvironment();
        $this->testBakongConnection();
        $this->testQRGeneration();
    }
    
    private function testEnvironment()
    {
        $this->info('⚙️ Testing Environment Configuration...');
        
        $token = env('BAKONG_API_TOKEN');
        $url = env('BAKONG_API_URL');
        $env = env('BAKONG_API_ENV');
        
        $this->info('🔑 BAKONG_API_TOKEN: ' . (strlen($token) > 10 ? "✅ SET (" . strlen($token) . " chars)" : "❌ NOT SET"));
        $this->info('🌐 BAKONG_API_URL: ' . ($url ? "✅ " . $url : "❌ NOT SET"));
        $this->info('🔧 BAKONG_API_ENV: ' . ($env ? "✅ " . $env : "❌ NOT SET"));
    }
    
    private function testBakongConnection()
    {
        $this->info('🔍 Testing Bakong API Connection...');
        
        $apiToken = env('BAKONG_API_TOKEN');
        
        if (!$apiToken) {
            $this->error('❌ No API token found in environment');
            return false;
        }
        
        try {
            $isExpired = BakongKHQR::isExpiredToken($apiToken);
            $this->info('🔑 Token Status: ' . ($isExpired ? "❌ EXPIRED" : "✅ VALID"));
            
            if ($isExpired) {
                $this->error('❌ The API token has expired. Please renew it.');
                return false;
            }
            
            return true;
        } catch (Exception $e) {
            $this->error('❌ Error checking token: ' . $e->getMessage());
            return false;
        }
    }
    
    private function testQRGeneration()
    {
        $this->info('🔧 Testing QR Code Generation...');
        
        $khqrService = new KHQRService();
        $amount = (float) $this->option('amount');
        
        $result = $khqrService->generateIndividual(
            amount: $amount,
            merchantName: 'Test Hotel Booking',
            bakongAccountID: 'khun_meas@aclb'
        );
        
        $this->info('📊 Generation Result: ' . ($result['success'] ? "✅ SUCCESS" : "❌ FAILED"));
        
        if ($result['success']) {
            $this->info('📄 QR String Length: ' . strlen($result['qr_string']) . ' characters');
            $this->info('📄 QR String: ' . $result['qr_string']);
            $this->info('🔑 MD5 Hash: ' . $result['md5']);
            $this->info('💰 Amount: ' . $result['amount'] . ' KHR');
            $this->info('🏪 Merchant: ' . $result['merchant_name']);
            
            // Analyze QR code format
            $qrString = $result['qr_string'];
            $this->info('🔍 QR Code Analysis:');
            
            if (strpos($qrString, '00020101') === 0) {
                $this->info('✅ QR code starts with correct EMVCo identifier');
            } else {
                $this->error('❌ QR code does not start with EMVCo identifier');
            }
            
            if (strpos($qrString, 'khun_meas@aclb') !== false) {
                $this->info('✅ QR code contains correct Bakong account ID');
            } else {
                $this->error('❌ QR code missing Bakong account ID');
            }
            
            if (strpos($qrString, (string)$amount) !== false) {
                $this->info('✅ QR code contains correct amount');
            } else {
                $this->error('❌ QR code missing amount');
            }
            
            $this->info('📱 Please scan this QR code with a Bakong-compatible app');
            $this->info('🔗 QR Code URL: https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode($qrString));
            
        } else {
            $this->error('❌ Error: ' . $result['message']);
        }
        
        return $result;
    }
}
