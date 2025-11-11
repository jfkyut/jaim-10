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

        $users = [
            [
                'first_name' => 'Test',
                'last_name' => 'User',
                'email' => 'test@test.test',
                'role_id' => 3, // admin
                'password' => Hash::make('testtest')
            ],
            [
                'first_name' => 'Jane',
                'last_name' => 'Doe',
                'email' => 'janedoe@email.com',
                'role_id' => 2, // creator
                'password' => Hash::make('testtest')
            ],
            [
                'first_name' => 'John',
                'last_name' => 'Smith',
                'email' => 'johnsmith@email.com',
                'role_id' => 1, // listener
                'password' => Hash::make('testtest')
            ]
        ];

        

        foreach ($roles as $role) {
            \App\Models\UserRole::create([
                'name' => $role
            ]);
        }

        foreach ($users as $user) {
            \App\Models\User::create($user);
        }

        $this->call([
            RedeemStatusSeeder::class,
            PlanSeeder::class,
        ]);
    }
}
