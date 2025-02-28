<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Crud>
 */
class CrudFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $kelas = $this->faker->randomElement(['Kelas 1', 'Kelas 2', 'Kelas 3', 'Kelas 4', 'Kelas 5', 'Kelas 6', 'Kelas 7', 'Kelas 8', 'Kelas 9', 'Kelas 10', 'Kelas 11', 'Kelas 12']);

        $fase = match (true) {
            $kelas >= 'Kelas 1' && $kelas <= 'Kelas 2' => 'Fase A',
            $kelas >= 'Kelas 3' && $kelas <= 'Kelas 4' => 'Fase B',
            $kelas >= 'Kelas 5' && $kelas <= 'Kelas 6' => 'Fase C',
            $kelas >= 'Kelas 7' && $kelas <= 'Kelas 9' => 'Fase D',
            $kelas == 'Kelas 10'  => 'Fase E',
            $kelas >= 'Kelas 11' && $kelas <= 'Kelas 12' => 'Fase F',
        };
        $sekolah = match (true) {
            $kelas >= 'Kelas 1' && $kelas <= 'Kelas 6' => 'SDN Taman Rahayu 01',
            $kelas >= 'Kelas 7' && $kelas <= 'Kelas 9' => 'SMPN 1 Setu Bekasi',
            $kelas >= 'Kelas 10' && $kelas <= 'Kelas 12' => 'SMK Karya Bahana Mandiri 1',
        };
        $kode_jenjang_murid = match (true) {
            $kelas >= 'Kelas 1' && $kelas <= 'Kelas 6' => 'SD',
            $kelas >= 'Kelas 7' && $kelas <= 'Kelas 9' => 'SMP',
            $kelas >= 'Kelas 10' && $kelas <= 'Kelas 12' => 'SMK',
        };
        return [
            'nama_lengkap' => $this->faker->name(),
            'sekolah' => $sekolah,
            'fase' => $fase,
            'kelas' => $kelas,
            'email' => $this->faker->unique()->safeEmail(),
            'password' => $this->faker->randomElement(['123456']),
            'no_hp' => $this->faker->numerify('##########'),
            'status' => $this->faker->randomElement(['Siswa', 'Murid']),
            'kode_jenjang_murid' => $kode_jenjang_murid,
        ];
    }
}