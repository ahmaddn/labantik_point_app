<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('p_categories')->insert([
            ['id' => (string) Str::uuid(), 'name' => 'Ringan'],
            ['id' => (string) Str::uuid(), 'name' => 'Sedang'],
            ['id' => (string) Str::uuid(), 'name' => 'Berat'],
        ]);
    }
}
