<?php

namespace Database\Seeders;

use App\Models\User;
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
        $this->call(PermissionRoleSeeder::class);

        $student1 = User::factory()->create([
            'name' => 'Student 1',
            'email' => 'student1@example.com',
            'password' => Hash::make('password'),
        ]);
        $student2 = User::factory()->create([
            'name' => 'Student 2',
            'email' => 'student2@example.com',
            'password' => Hash::make('password'),
        ]);

        $student1->assignRole('student');
        $student2->assignRole('student');

    }
}
