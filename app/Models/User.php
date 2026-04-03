<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $fillable = ['name', 'phone', 'password', 'dob'];
    protected $hidden   = ['password'];

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }
}
