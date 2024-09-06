<?php

namespace Database\Seeders;

use App\Models\Hall;
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
        ]);

        Hall::query()->insert([
            ['name' => 'Habibur Rahman Hall'],
            ['name' => 'Shahidullah Hall'],
            ['name' => 'Bangabandhu Hall'],
            ['name' => 'Begum Rokeya Hall'],
            ['name' => 'Begum Sufia Kamal Hall'],
            ['name' => 'Begum Shamsunnahar Hall'],
            ['name' => 'Begum Khaleda Zia Hall'],
            ['name' => 'Begum Fazilatunnesa Mujib Hall'],
        ]);

        Student::factory(10)->create();
    }
}
