<?php

namespace Database\Seeders;

use BcMath\Number;
use DB;
use Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => Str::random(10),
            'cpf' => "11122233347",
            'password' => Hash::make('password'),
            'email' => 'lagado75@gmail.com',
            'status' => true,
            'role' => 'admin'
        ]);
    }
}
