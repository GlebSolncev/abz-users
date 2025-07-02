<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('positions')->insertOrIgnore([
            ['id' => 1, 'name' => 'Lawyer'],
            ['id' => 2, 'name' => 'Content manager'],
            ['id' => 3, 'name' => 'Security'],
            ['id' => 4, 'name' => 'Designer'],
        ]);
    }
}
