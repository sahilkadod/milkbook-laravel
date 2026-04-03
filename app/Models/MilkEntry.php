<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MilkEntry extends Model
{
    protected $fillable = [
        'user_id', 'customer_id', 'date',
        'morning_liter', 'morning_fat',
        'evening_liter', 'evening_fat',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // ── Scope: entries for a specific customer, month & year ─────────────────
    public function scopeForCustomer($query, $userId, $customerId, $month, $year)
    {
        $mm = str_pad($month, 2, '0', STR_PAD_LEFT);
        return $query
            ->where('user_id', $userId)
            ->where('customer_id', $customerId)
            ->where('date', 'like', "{$year}-{$mm}-%")
            ->orderBy('date');
    }
}
