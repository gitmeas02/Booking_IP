<?php

namespace App\Services;

use KHQR\BakongKHQR;
use KHQR\Helpers\KHQRData;
use KHQR\Models\IndividualInfo;
use KHQR\Models\KHQRResponse;
use Exception;
use App\Services\KHQRHashService;
use App\Models\Transactions;
use Carbon\Carbon;

class KHQRService
{
    private ?string $apiToken;
    private bool $isTestMode;

    public function __construct(?string $apiToken = null)
    {
        $this->apiToken = $apiToken ?? env('BAKONG_API_TOKEN');
        $this->isTestMode = env('BAKONG_API_ENV', 'production') === 'testing';
    }

    /**
     * Generate KHQR for an individual with dynamic parameters
     * 
     * @param float $amount Optional amount, defaults to 500 KHR
     * @param string $merchantName Optional merchant name
     * @param string $bakongAccountID Optional account ID
     * @return array Contains 'success', 'qr_string', 'md5', 'full_hash', 'short_hash', and 'message'
     */
    public function generateIndividual(
        float $amount = 500.0, 
        string $merchantName = 'Hotel Booking Payment',
        string $bakongAccountID = 'sok_chanmakara@aclb'
    ): array {
        try {
            $individualInfo = new IndividualInfo(
                bakongAccountID: $bakongAccountID,
                merchantName: $merchantName,
                merchantCity: 'PHNOM PENH',
                currency: KHQRData::CURRENCY_KHR,
                amount: $amount,
                expirationTimestamp: strval(floor(microtime(true) * 1000) + 300 * 1000), // 5 minutes expiry
                merchantCategoryCode: '5999'
            );

            $response = BakongKHQR::generateIndividual($individualInfo);
            
            // Check if the response is valid and successful
            if ($response instanceof KHQRResponse && 
                isset($response->status['code']) && 
                $response->status['code'] === 0 &&
                isset($response->data['qr'])) {
                
                $qrString = $response->data['qr'];
                $hashInfo = KHQRHashService::extractHashInfo($qrString);
                
                return [
                    'success' => true,
                    'qr_string' => $qrString,
                    'md5' => $hashInfo['md5'],
                    'full_hash' => $hashInfo['full_hash'],
                    'short_hash' => $hashInfo['short_hash'],
                    'message' => 'KHQR generated successfully',
                    'amount' => $amount,
                    'merchant_name' => $merchantName
                ];
            } else {
                return [
                    'success' => false,
                    'qr_string' => null,
                    'md5' => null,
                    'full_hash' => null,
                    'short_hash' => null,
                    'message' => $response->status['message'] ?? 'Failed to generate KHQR'
                ];
            }
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'qr_string' => null,
                'md5' => null,
                'full_hash' => null,
                'short_hash' => null,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Generate KHQR for actual payment testing with QR display
     */
    public function generateForTesting(float $amount = 100.0): array
    {
        try {
            $result = $this->generateIndividual($amount, 'Test Payment');
            
            if (!$result['success']) {
                return $result;
            }

            // Create transaction record
            $transaction = Transactions::create([
                'transaction_id' => 'LIVE_TEST_' . now()->format('YmdHis'),
                'bakong_account_id' => 'sok_chanmakara@aclb',
                'qr_string' => $result['qr_string'],
                'qr_md5' => $result['md5'],
                'qr_full_hash' => $result['full_hash'],
                'qr_short_hash' => $result['short_hash'],
                'amount' => $amount,
                'currency' => 'KHR',
                'merchant_name' => 'Test Payment',
                'status' => 'pending',
                'expires_at' => Carbon::now()->addMinutes(10), // 10 minutes to complete payment
                'booking_reference' => 'TEST_PAYMENT_' . now()->format('His'),
            ]);

            return [
                'success' => true,
                'transaction' => $transaction,
                'qr_string' => $result['qr_string'],
                'md5' => $result['md5'],
                'full_hash' => $result['full_hash'],
                'short_hash' => $result['short_hash'],
                'message' => 'Test payment QR generated. Please scan and complete payment to test status checking.',
                'instructions' => [
                    '1. Scan the QR code with a Bakong-compatible app',
                    '2. Complete the payment',
                    '3. Wait 1-2 minutes for processing',
                    '4. Then check status using the provided hashes'
                ]
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error generating test payment: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Check transaction status by MD5 hash
     * Requires a valid API token from Bakong
     */
    public function checkTransactionByMD5(string $md5Hash): array
    {
        if (!$this->apiToken) {
            return [
                'success' => false,
                'message' => 'API token is required to check transaction status. Please set BAKONG_API_TOKEN in your .env file.',
                'data' => null
            ];
        }

        try {
            // Check if token is expired first
            if (BakongKHQR::isExpiredToken($this->apiToken)) {
                return [
                    'success' => false,
                    'message' => 'API token has expired. Please renew your token.',
                    'data' => null,
                    'token_expired' => true
                ];
            }

            $bakongKhqr = new BakongKHQR($this->apiToken);
            $response = $bakongKhqr->checkTransactionByMD5($md5Hash, $this->isTestMode);
            
            return [
                'success' => true,
                'message' => 'Transaction status retrieved successfully',
                'data' => $response
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error checking transaction: ' . $e->getMessage(),
                'data' => null
            ];
        }
    }

    /**
     * Check multiple transactions by MD5 hash list
     */
    public function checkTransactionsByMD5List(array $md5HashList, bool $isTest = false): array
    {
        if (!$this->apiToken) {
            return [
                'success' => false,
                'message' => 'API token is required to check transaction status',
                'data' => null
            ];
        }

        try {
            $bakongKhqr = new BakongKHQR($this->apiToken);
            $response = $bakongKhqr->checkTransactionByMD5List($md5HashList, $isTest);
            
            return [
                'success' => true,
                'message' => 'Transaction statuses retrieved successfully',
                'data' => $response
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error checking transactions: ' . $e->getMessage(),
                'data' => null
            ];
        }
    }

    /**
     * Get API token status
     */
    public function getTokenStatus(): array
    {
        if (!$this->apiToken) {
            return [
                'has_token' => false,
                'is_expired' => null,
                'message' => 'No API token configured'
            ];
        }

        try {
            $isExpired = BakongKHQR::isExpiredToken($this->apiToken);
            
            return [
                'has_token' => true,
                'is_expired' => $isExpired,
                'message' => $isExpired ? 'Token is expired' : 'Token is valid'
            ];
        } catch (Exception $e) {
            return [
                'has_token' => true,
                'is_expired' => true,
                'message' => 'Error checking token: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Renew expired token
     */
    public static function renewToken(string $email, bool $isTest = false): array
    {
        try {
            $response = BakongKHQR::renewToken($email, $isTest);
            
            if (isset($response['responseCode']) && $response['responseCode'] === 0) {
                return [
                    'success' => true,
                    'token' => $response['data']['token'] ?? null,
                    'message' => $response['responseMessage'] ?? 'Token renewed successfully'
                ];
            } else {
                return [
                    'success' => false,
                    'token' => null,
                    'message' => $response['responseMessage'] ?? 'Failed to renew token'
                ];
            }
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'token' => null,
                'message' => 'Error renewing token: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Verify if a KHQR string is valid
     */
    public function verifyKHQR(string $khqrString): bool
    {
        try {
            $result = BakongKHQR::verify($khqrString);
            return $result->isValid ?? false;
        } catch (Exception $e) {
            return false;
        }
    }

    public function decodeKHQR(string $khqrString): array
    {
        try {
            $response = BakongKHQR::decode($khqrString);
            
            if ($response instanceof KHQRResponse && 
                isset($response->status['code']) && 
                $response->status['code'] === 0) {
                
                return [
                    'success' => true,
                    'data' => $response->data,
                    'message' => 'KHQR decoded successfully'
                ];
            } else {
                return [
                    'success' => false,
                    'data' => null,
                    'message' => $response->status['message'] ?? 'Failed to decode KHQR'
                ];
            }
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'data' => null,
                'message' => 'Error decoding KHQR: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Check transaction status by full hash
     * Requires a valid API token from Bakong
     */
    public function checkTransactionByFullHash(string $fullHash): array
    {
        if (!$this->apiToken) {
            return [
                'success' => false,
                'message' => 'API token is required to check transaction status',
                'data' => null
            ];
        }

        try {
            if (BakongKHQR::isExpiredToken($this->apiToken)) {
                return [
                    'success' => false,
                    'message' => 'API token has expired. Please renew your token.',
                    'data' => null,
                    'token_expired' => true
                ];
            }

            $bakongKhqr = new BakongKHQR($this->apiToken);
            $response = $bakongKhqr->checkTransactionByFullHash($fullHash, $this->isTestMode);
            
            return [
                'success' => true,
                'message' => 'Transaction status retrieved successfully',
                'data' => $response
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error checking transaction by full hash: ' . $e->getMessage(),
                'data' => null
            ];
        }
    }

    /**
     * Check multiple transactions by full hash list
     */
    public function checkTransactionsByFullHashList(array $fullHashList): array
    {
        if (!$this->apiToken) {
            return [
                'success' => false,
                'message' => 'API token is required to check transaction status',
                'data' => null
            ];
        }

        try {
            $bakongKhqr = new BakongKHQR($this->apiToken);
            $response = $bakongKhqr->checkTransactionByFullHashList($fullHashList, $this->isTestMode);
            
            return [
                'success' => true,
                'message' => 'Transaction statuses retrieved successfully',
                'data' => $response
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error checking transactions by full hash list: ' . $e->getMessage(),
                'data' => null
            ];
        }
    }

    /**
     * Check transaction status by short hash
     * Requires amount and currency for verification
     */
    public function checkTransactionByShortHash(string $shortHash, float $amount, string $currency = 'KHR'): array
    {
        if (!$this->apiToken) {
            return [
                'success' => false,
                'message' => 'API token is required to check transaction status',
                'data' => null
            ];
        }

        try {
            if (BakongKHQR::isExpiredToken($this->apiToken)) {
                return [
                    'success' => false,
                    'message' => 'API token has expired. Please renew your token.',
                    'data' => null,
                    'token_expired' => true
                ];
            }

            $bakongKhqr = new BakongKHQR($this->apiToken);
            $response = $bakongKhqr->checkTransactionByShortHash($shortHash, $amount, $currency, $this->isTestMode);
            
            return [
                'success' => true,
                'message' => 'Transaction status retrieved successfully',
                'data' => $response,
                'search_params' => [
                    'short_hash' => $shortHash,
                    'amount' => $amount,
                    'currency' => $currency
                ]
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error checking transaction by short hash: ' . $e->getMessage(),
                'data' => null
            ];
        }
    }

    /**
     * Check transaction status by instruction reference
     * This is typically an 8-digit reference number
     */
    public function checkTransactionByInstructionReference(string $instructionReference): array
    {
        if (!$this->apiToken) {
            return [
                'success' => false,
                'message' => 'API token is required to check transaction status',
                'data' => null
            ];
        }

        try {
            if (BakongKHQR::isExpiredToken($this->apiToken)) {
                return [
                    'success' => false,
                    'message' => 'API token has expired. Please renew your token.',
                    'data' => null,
                    'token_expired' => true
                ];
            }

            $bakongKhqr = new BakongKHQR($this->apiToken);
            $response = $bakongKhqr->checkTransactionByInstructionReference($instructionReference, $this->isTestMode);
            
            return [
                'success' => true,
                'message' => 'Transaction status retrieved successfully',
                'data' => $response,
                'instruction_reference' => $instructionReference
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error checking transaction by instruction reference: ' . $e->getMessage(),
                'data' => null
            ];
        }
    }

    /**
     * Check transaction status by external reference
     * This is a custom reference you can set when generating KHQR
     */
    public function checkTransactionByExternalReference(string $externalReference): array
    {
        if (!$this->apiToken) {
            return [
                'success' => false,
                'message' => 'API token is required to check transaction status',
                'data' => null
            ];
        }

        try {
            if (BakongKHQR::isExpiredToken($this->apiToken)) {
                return [
                    'success' => false,
                    'message' => 'API token has expired. Please renew your token.',
                    'data' => null,
                    'token_expired' => true
                ];
            }

            $bakongKhqr = new BakongKHQR($this->apiToken);
            $response = $bakongKhqr->checkTransactionByExternalReference($externalReference, $this->isTestMode);
            
            return [
                'success' => true,
                'message' => 'Transaction status retrieved successfully',
                'data' => $response,
                'external_reference' => $externalReference
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error checking transaction by external reference: ' . $e->getMessage(),
                'data' => null
            ];
        }
    }

    /**
     * Universal transaction checker - automatically detects the type of identifier
     * and uses the appropriate checking method
     */
    public function checkTransaction(string $identifier, ?float $amount = null, ?string $currency = null): array
    {
        // Determine the type of identifier and use appropriate method
        if (strlen($identifier) === 32 && ctype_xdigit($identifier)) {
            // MD5 hash (32 hex characters)
            return $this->checkTransactionByMD5($identifier);
        } elseif (strlen($identifier) === 64 && ctype_xdigit($identifier)) {
            // Full hash (64 hex characters)
            return $this->checkTransactionByFullHash($identifier);
        } elseif (strlen($identifier) === 8) {
            if (ctype_xdigit($identifier) && $amount !== null) {
                // Short hash (8 hex characters) - requires amount
                $currency = $currency ?? 'KHR';
                return $this->checkTransactionByShortHash($identifier, $amount, $currency);
            } elseif (ctype_digit($identifier)) {
                // Instruction reference (8 digits)
                return $this->checkTransactionByInstructionReference($identifier);
            }
        }
        
        // Assume it's an external reference for anything else
        return $this->checkTransactionByExternalReference($identifier);
    }
}