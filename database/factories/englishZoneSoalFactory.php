<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\englishZoneSoal>
 */
class englishZoneSoalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
{
    return [
        'nama_lengkap' => $this->faker->name(),
        'status' => 'Administrator', // Langsung tetapkan nilai jika tidak berubah
        'modul' => $this->faker->randomElement([
            'Modul 1', 'Modul 2', 'Modul 3', 'Modul 4', 
            'Modul 5', 'Modul 6', 'Modul 7', 'Modul 8', 
            'Modul 9', 'Modul 10', 'Modul 11', 'Modul 12',
        ]),
        'jenjang' => $this->faker->randomElement(['SD', 'SMP', 'SMA']),
        'soal' => $this->faker->sentence(), // Lebih cocok untuk soal
        'pilihan_A' => $this->faker->word(),
        'bobot_A' => $this->faker->numberBetween(1, 5), // Angka lebih cocok untuk poin
        'pilihan_B' => $this->faker->word(),
        'bobot_B' => $this->faker->numberBetween(1, 5),
        'pilihan_C' => $this->faker->word(),
        'bobot_C' => $this->faker->numberBetween(1, 5),
        'pilihan_D' => $this->faker->word(),
        'bobot_D' => $this->faker->numberBetween(1, 5),
        'pilihan_E' => $this->faker->word(),
        'bobot_E' => $this->faker->numberBetween(1, 5),
        'tingkat_kesulitan' => $this->faker->randomElement(['Mudah', 'Sedang', 'Sukar']), // Perbaikan typo
        'jawaban' => $this->faker->randomElement(['A', 'B', 'C', 'D', 'E']),
        'deskripsi_jawaban' => $this->faker->sentence(), // Lebih cocok untuk deskripsi
        'tipe_upload' => 'Soal', // Langsung tetapkan nilai jika tidak berubah
        'status_soal' => 'unpublish', // Langsung tetapkan nilai jika tidak berubah
    ];
}

}