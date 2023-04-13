<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Note;
use App\Models\Todolist;
use App\Models\User;
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

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);


        $user = User::create([
            'name' => 'qohar',
            'email' => 'qohar@gmail.com',
            'password' => Hash::make('12345678'),
        ]);

        Note::create([
            'user_id' => $user->id_user,
            'note' => 'Tes Not',
        ]);

        Todolist::create([
            'note' => 'Test todolist Belum',
            'complete' => '0',
        ]);
        Todolist::create([
            'note' => 'Test todolist Selesai',
            'complete' => '1',
        ]);
    }
}
