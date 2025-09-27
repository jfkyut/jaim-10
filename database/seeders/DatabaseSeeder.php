<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        $roles = [
            'listener',
            'creator',
            'admin'
        ];

        foreach ($roles as $role) {
            \App\Models\UserRole::create([
                'name' => $role
            ]);
        }

        \App\Models\User::create([
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@test.test',
            'role_id' => 3, // admin
            'password' => Hash::make('testtest')
        ]);
    }
}
