<?php

namespace Database\Seeders;

use App\Models\Tanya;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TanyaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Tanya::factory()->count(10)->create();
    }
}