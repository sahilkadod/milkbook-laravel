<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerRate extends Model
{
    protected $fillable = ['user_id', 'customer_id', 'month', 'year', 'rate'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // ── Get rate for a customer/month/year ────────────────────────────────────
    public static function getRate($userId, $customerId, $month, $year): float
    {
        $record = static::where('user_id', $userId)
            ->where('customer_id', $customerId)
            ->where('month', $month)
            ->where('year',  $year)
            ->first();
        return $record ? (float) $record->rate : 0.0;
    }

    // ── Set rate (upsert) ────────────────────────────────────────────────────
    public static function setRate($userId, $customerId, $month, $year, $rate): void
    {
        static::updateOrCreate(
            ['user_id' => $userId, 'customer_id' => $customerId, 'month' => $month, 'year' => $year],
            ['rate' => $rate]
        );
    }
}
