<?php

namespace App\Services;

use App\Models\Transactions;

class KHQRHashService
{
    /**
     * Generate full hash (SHA256) from QR string
     */
    public static function generateFullHash(string $qrString): string
    {
        return hash('sha256', $qrString);
    }

    /**
     * Generate short hash (first 8 characters of SHA256)
     */
    public static function generateShortHash(string $qrString): string
    {
        return substr(self::generateFullHash($qrString), 0, 8);
    }

    /**
     * Extract hash information from QR string
     */
    public static function extractHashInfo(string $qrString): array
    {
        $md5Hash = md5($qrString);
        $fullHash = self::generateFullHash($qrString);
        $shortHash = self::generateShortHash($qrString);

        return [
            'md5' => $md5Hash,          // 32 characters
            'full_hash' => $fullHash,   // 64 characters
            'short_hash' => $shortHash, // 8 characters
            'qr_length' => strlen($qrString)
        ];
    }

    /**
     * Update existing transaction with hash information
     */
    public static function updateTransactionHashes(string $transactionId): ?array
    {
        $transaction = Transactions::where('transaction_id', $transactionId)->first();
        
        if (!$transaction || !$transaction->qr_string) {
            return null;
        }

        $hashInfo = self::extractHashInfo($transaction->qr_string);
        
        $transaction->update([
            'qr_full_hash' => $hashInfo['full_hash'],
            'qr_short_hash' => $hashInfo['short_hash']
        ]);

        return $hashInfo;
    }

    /**
     * Batch update all transactions missing hash information
     */
    public static function updateAllMissingHashes(): array
    {
        $transactions = Transactions::whereNull('qr_full_hash')
            ->orWhereNull('qr_short_hash')
            ->get();

        $updated = 0;
        $results = [];

        foreach ($transactions as $transaction) {
            if ($transaction->qr_string) {
                $hashInfo = self::extractHashInfo($transaction->qr_string);
                
                $transaction->update([
                    'qr_full_hash' => $hashInfo['full_hash'],
                    'qr_short_hash' => $hashInfo['short_hash']
                ]);

                $results[] = [
                    'transaction_id' => $transaction->transaction_id,
                    'hashes' => $hashInfo
                ];
                $updated++;
            }
        }

        return [
            'updated_count' => $updated,
            'total_transactions' => $transactions->count(),
            'results' => $results
        ];
    }

    /**
     * Find transaction by any hash type
     */
    public static function findTransactionByHash(string $hash): ?Transactions
    {
        $hashLength = strlen($hash);

        return match ($hashLength) {
            32 => Transactions::where('qr_md5', $hash)->first(),        // MD5
            64 => Transactions::where('qr_full_hash', $hash)->first(),  // SHA256
            8 => Transactions::where('qr_short_hash', $hash)->first(),  // Short hash
            default => null
        };
    }

    /**
     * Get all hash variants for a transaction
     */
    public static function getTransactionHashes(string $transactionId): ?array
    {
        $transaction = Transactions::where('transaction_id', $transactionId)->first();
        
        if (!$transaction) {
            return null;
        }

        return [
            'transaction_id' => $transaction->transaction_id,
            'md5' => $transaction->qr_md5,
            'full_hash' => $transaction->qr_full_hash,
            'short_hash' => $transaction->qr_short_hash,
            'qr_string' => $transaction->qr_string,
            'amount' => $transaction->amount,
            'currency' => $transaction->currency
        ];
    }
}