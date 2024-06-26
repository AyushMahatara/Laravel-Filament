<?php

namespace Database\Seeders;

use App\Models\Guardian;
use App\Models\Standard;
use App\Models\Student;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password')
        ]);

        Student::factory(10)
            ->has(Guardian::factory()->count(3))
            ->create();
        $this->call(StandardSeeder::class);
    }
}
