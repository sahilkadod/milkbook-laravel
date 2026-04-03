<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with a demo user.
     * Phone: 9999999999 | Password: demo123
     */
    public function run(): void
    {
        DB::table('users')->insertOrIgnore([
            'name'       => 'Demo User',
            'phone'      => '9999999999',
            'password'   => password_hash('demo123', PASSWORD_DEFAULT),
            'dob'        => '1990-01-01',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
