<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Transactions extends Model 
{
    protected $fillable = [
        'transaction_id',
        'bakong_account_id',
        'qr_string',
        'qr_md5',
        'qr_full_hash',
        'qr_short_hash',
        'amount',
        'currency',
        'merchant_name',
        'status',
        'expires_at',
        'response_data',
        'booking_reference',
    ];

    protected $casts = [
        'response_data' => 'array',
        'expires_at' => 'datetime',
        'amount' => 'decimal:2',
    ];

    /**
     * Check if the transaction is expired
     */
    public function isExpired(): bool
    {
        return $this->expires_at && Carbon::now()->isAfter($this->expires_at);
    }

    /**
     * Mark transaction as expired if past expiration time
     */
    public function checkAndMarkExpired(): bool
    {
        if ($this->isExpired() && $this->status === 'pending') {
            $this->update(['status' => 'expired']);
            return true;
        }
        return false;
    }

    /**
     * Scope for pending transactions
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for successful transactions
     */
    public function scopeSuccessful($query)
    {
        return $query->where('status', 'success');
    }

    /**
     * Get formatted amount with currency
     */
    public function getFormattedAmountAttribute(): string
    {
        return number_format($this->amount, 2) . ' ' . $this->currency;
    }
}
