<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
     public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'role' => 'admin'
        ]);

        User::factory()->create([
            'name' => 'jokow',
            'email' => 'jokow@gmail.com',
        ]);

        User::factory()->create([
            'name' => 'prabow',
            'email' => 'prabow@gmail.com',
        ]);

        User::factory()->create([
            'name' => 'bahlil',
            'email' => 'bahlil@gmail.com',
        ]);

        User::factory()->create([
            'name' => 'anis',
            'email' => 'anis@gmail.com',
        ]);
        
        User::factory()->create([
            'name' => 'luhut',
            'email' => 'luhut@gmail.com',
        ]);

        $this->call([
            CategorySeeder::class,
            VideoSeeder::class,
        ]);
    }
}
