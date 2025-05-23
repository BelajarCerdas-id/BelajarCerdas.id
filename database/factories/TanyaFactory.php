<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserAccount;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tanya>
 */
class TanyaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $getUser = UserAccount::where('status', 'Siswa')->orWhere('status', 'Murid')->get();
        $getMentor = UserAccount::where('status', 'Mentor')->get();
        if($getUser->isNotEmpty() && $getMentor->isNotEmpty()){
            $randomUser = $getUser->random();
            $randomMentor = $getMentor->random();

            $randomName = $randomUser->nama_lengkap;
            $randomEmail = $randomUser->email;
            $randomSekolah = $randomUser->sekolah;
            $randomFase = $randomUser->fase;
            $randomKelas = $randomUser->kelas;
            $randomPhone = $randomUser->no_hp;

            $randomMentorName = $randomMentor->nama_lengkap;
            $randomMentorId = $randomMentor->id;
            $randomEmailMentor = $randomMentor->email;
            $randomAsalMengajar = $randomMentor->sekolah;

        }else{

        };
        $mentorId = $this->faker->randomElement(['8', '9']);
        return [
            'user_id' => '16',
            'fase_id' => '1',
            'kelas_id' => '1',
            'mapel_id' => '1',
            'bab_id' => '1',
            'harga_koin' => '4',
            'pertanyaan' => $this->faker->text(50),


            // 'nama_lengkap' => $randomName,
            // 'email' => $randomEmail,
            // 'sekolah' => $randomSekolah,
            // 'fase' => $randomFase,
            // 'kelas' => $randomKelas,
            // 'mapel' => $this->faker->word(),
            // 'bab' => $this->faker->word(),
            // 'pertanyaan' => $this->faker->text(50),
            // 'jawaban' => $this->faker->text(50),
            // 'status' => $this->faker->randomElement(['Diterima']),
            // 'no_hp' => $randomPhone,
            // 'mentor' => $randomMentorName,
            // 'id_mentor' => $randomMentorId,
            // 'email_mentor' => $randomEmailMentor,
            // 'asal_mengajar' => $randomAsalMengajar,

            // 'created_at' => Carbon::create(2025, rand(12, 12), rand(1, 31), rand(0, 23), rand(0, 59), rand(0, 59)),
            'created_at' => now(),
            'deleted_at' => $this->faker->boolean(100) ? Carbon::now()->subDays(rand(1, 30)) : null,
        ];
    }
}