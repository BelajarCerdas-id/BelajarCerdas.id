<?php

namespace Database\Seeders;

use App\Models\Crud;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CrudSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Crud::factory()->count(10)->create();
    }
}