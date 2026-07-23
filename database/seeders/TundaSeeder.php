<?php

namespace Database\Seeders;

use App\Models\Interest;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TundaSeeder extends Seeder
{
    public function run(): void
    {
        $interests = [
            'Music', 'Travel', 'Sports', 'Cooking', 'Technology',
            'Fashion', 'Photography', 'Movies', 'Reading', 'Business',
            'Gaming', 'Art', 'Dancing', 'Fitness', 'Food',
        ];

        foreach ($interests as $name) {
            Interest::firstOrCreate(['name' => $name]);
        }

        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@tunda.app',
            'password' => Hash::make('admin12345'),
            'role' => 'super_admin',
            'status' => 'active',
            'gender' => 'male',
        ]);

        Profile::create([
            'user_id' => $admin->id,
            'bio' => 'System Administrator',
            'occupation' => 'Administrator',
            'is_verified' => true,
        ]);

        $users = [
            ['name' => 'Aisha', 'email' => 'aisha@tunda.app', 'gender' => 'female', 'region' => 'Dar es Salaam'],
            ['name' => 'Zainab', 'email' => 'zainab@tunda.app', 'gender' => 'female', 'region' => 'Dar es Salaam'],
            ['name' => 'Rehema', 'email' => 'rehema@tunda.app', 'gender' => 'female', 'region' => 'Dar es Salaam'],
            ['name' => 'Neema', 'email' => 'neema@tunda.app', 'gender' => 'female', 'region' => 'Dodoma'],
            ['name' => 'Fatuma', 'email' => 'fatuma@tunda.app', 'gender' => 'female', 'region' => 'Dar es Salaam'],
            ['name' => 'John', 'email' => 'john@tunda.app', 'gender' => 'male', 'region' => 'Dar es Salaam'],
            ['name' => 'Joseph', 'email' => 'joseph@tunda.app', 'gender' => 'male', 'region' => 'Arusha'],
            ['name' => 'James', 'email' => 'james@tunda.app', 'gender' => 'male', 'region' => 'Mwanza'],
        ];

        foreach ($users as $u) {
            $user = User::create([
                'name' => $u['name'],
                'email' => $u['email'],
                'password' => Hash::make('password123'),
                'gender' => $u['gender'],
                'region' => $u['region'],
                'role' => 'user',
                'status' => 'active',
            ]);

            Profile::create([
                'user_id' => $user->id,
                'bio' => "Hi, I'm {$u['name']}! Looking to meet new people.",
                'occupation' => 'Professional',
                'education' => 'University Graduate',
                'relationship_goal' => 'serious_relationship',
                'is_verified' => rand(0, 1) === 1,
            ]);

            $user->interests()->attach(
                Interest::inRandomOrder()->limit(rand(3, 5))->pluck('id')
            );
        }
    }
}
