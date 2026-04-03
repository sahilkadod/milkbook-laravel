<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['user_id', 'name', 'phone', 'address'];

    public function milkEntries()
    {
        return $this->hasMany(MilkEntry::class);
    }

    public function rates()
    {
        return $this->hasMany(CustomerRate::class);
    }
}
