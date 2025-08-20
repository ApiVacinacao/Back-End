<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Hash;
use Illuminate\Database\Seeder;
use Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'CPF' => '12345678900',
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
            'telefone' => '(44) 3621-0000',
            'telefone_celular' => '(44) 99999-0000',
            'password' => Hash::make('admin123'),
            'CNS' => '987654321000000',
            'status' => 'ativo',
        ]);

    }
}
