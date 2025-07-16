<?php

require __DIR__ . '/vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

use App\Services\KHQRService;
use KHQR\BakongKHQR;

// Test Bakong API connection
function testBakongConnection() {
    echo "🔍 Testing Bakong API Connection...\n";
    
    $apiToken = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJkYXRhIjp7ImlkIjoiMzZhYzE3MWM0YTcwNDA0YSJ9LCJpYXQiOjE3NDc0MTQwMTMsImV4cCI6MTc1NTE5MDAxM30.tOiwZ7dwJdl-r0S5Neft0WNsLBZBp8vCvTjhIrGPahw';
    
    // Check if token is expired
    try {
        $isExpired = BakongKHQR::isExpiredToken($apiToken);
        echo "🔑 Token Status: " . ($isExpired ? "❌ EXPIRED" : "✅ VALID") . "\n";
        
        if ($isExpired) {
            echo "❌ The API token has expired. Please renew it.\n";
            return false;
        }
        
        return true;
    } catch (Exception $e) {
        echo "❌ Error checking token: " . $e->getMessage() . "\n";
        return false;
    }
}

// Test QR code generation
function testQRGeneration() {
    echo "\n🔧 Testing QR Code Generation...\n";
    
    $khqrService = new KHQRService();
    
    $result = $khqrService->generateIndividual(
        amount: 1000.0,
        merchantName: 'Test Hotel Booking',
        bakongAccountID: 'khun_meas@aclb'
    );
    
    echo "📊 Generation Result: " . ($result['success'] ? "✅ SUCCESS" : "❌ FAILED") . "\n";
    
    if ($result['success']) {
        echo "📄 QR String Length: " . strlen($result['qr_string']) . " characters\n";
        echo "📄 QR String Preview: " . substr($result['qr_string'], 0, 100) . "...\n";
        echo "🔑 MD5 Hash: " . $result['md5'] . "\n";
        echo "💰 Amount: " . $result['amount'] . " KHR\n";
        echo "🏪 Merchant: " . $result['merchant_name'] . "\n";
        
        // Check if QR string looks valid
        if (strpos($result['qr_string'], 'bakong') !== false || 
            strpos($result['qr_string'], 'khqr') !== false) {
            echo "✅ QR code appears to contain Bakong/KHQR data\n";
        } else {
            echo "⚠️ QR code format may be incorrect\n";
        }
    } else {
        echo "❌ Error: " . $result['message'] . "\n";
    }
    
    return $result;
}

// Test environment variables
function testEnvironment() {
    echo "\n⚙️ Testing Environment Configuration...\n";
    
    $token = $_ENV['BAKONG_API_TOKEN'] ?? 'NOT_SET';
    $url = $_ENV['BAKONG_API_URL'] ?? 'NOT_SET';
    $env = $_ENV['BAKONG_API_ENV'] ?? 'NOT_SET';
    
    echo "🔑 BAKONG_API_TOKEN: " . (strlen($token) > 10 ? "✅ SET (" . strlen($token) . " chars)" : "❌ NOT SET") . "\n";
    echo "🌐 BAKONG_API_URL: " . ($url !== 'NOT_SET' ? "✅ " . $url : "❌ NOT SET") . "\n";
    echo "🔧 BAKONG_API_ENV: " . ($env !== 'NOT_SET' ? "✅ " . $env : "❌ NOT SET") . "\n";
}

// Main test function
function runTests() {
    echo "🧪 KHQR Service Diagnostic Tests\n";
    echo "================================\n";
    
    testEnvironment();
    
    if (testBakongConnection()) {
        $result = testQRGeneration();
        
        if ($result['success']) {
            echo "\n✅ All tests passed! QR code should work when scanned.\n";
            echo "📱 Please scan the QR code with a Bakong-compatible app.\n";
        } else {
            echo "\n❌ QR generation failed. Please check the configuration.\n";
        }
    } else {
        echo "\n❌ API connection failed. Please check your token.\n";
    }
}

// Run the tests
runTests();
