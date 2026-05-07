<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Create one demo admin account for Shomobay.
     */
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@shomobay.test'],
            [
                'name' => 'Shomobay Admin',
                'password' => 'password',
                'role' => User::ROLE_ADMIN,
            ]
        );

        Wallet::firstOrCreate(
            ['user_id' => $admin->id],
            [
                'balance_paisa' => 0,
                'escrow_paisa' => 0,
            ]
        );
    }
}