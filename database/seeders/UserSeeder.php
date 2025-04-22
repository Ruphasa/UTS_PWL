<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'user_id' => 1,
                'level_id' => 1,
                'username' => 'sakamoto',
                'nama' => 'sakamoto taro',
                'password' => Hash::make('12345'),
            ],
            [
                'user_id' => 2,
                'level_id' => 2,
                'username' => 'Bambang',
                'nama' => 'Bambang Supratman',
                'password' => Hash::make('12345'),
            ],
            [
                'user_id' => 3,
                'level_id' => 3,
                'username' => 'Jokowi',
                'nama' => 'Joko Widodo',
                'password' => Hash::make('12345'),
            ],
            [
                'user_id' => 4,
                'level_id' => 4,
                'username' => 'Ruphasa',
                'nama' => 'Ruphasa Mafahl',
                'password' => Hash::make('12345'),
            ],
            [
                'user_id' => 5,
                'level_id' => 4,
                'username' => 'Voxuro',
                'nama' => 'Vexuro',
                'password' => Hash::make('12345'),
            ],
            [
                'user_id' => 6,
                'level_id' => 4,
                'username' => 'Ren',
                'nama' => 'Amamiya Ren',
                'password' => Hash::make('12345'),
            ],
            [
                'user_id' => 7,
                'level_id' => 4,
                'username' => 'Makoto',
                'nama' => 'Makoto Yuki',
                'password' => Hash::make('12345'),
            ],
        ];
        DB::table('m_user')->insert($data);
    }
}
