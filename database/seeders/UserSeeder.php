<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        User::query()->create([
            'username' => 'admin',
            'email' => 'admin@admin.admin',
            'password' => Hash::make('admin1111'),
            'email_verified_at' => now()
        ]);

        User::factory()
            ->times(1)
            ->hasNotes(3)
            ->create();
    }
}
