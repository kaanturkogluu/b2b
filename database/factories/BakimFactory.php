<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Bakim>
 */
class BakimFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'plaka' => $this->faker->regexify('[0-9]{2}[A-Z]{3}[0-9]{3}'),
            'sase' => $this->faker->regexify('[A-Z0-9]{17}'),
            'tahmini_teslim_tarihi' => $this->faker->dateTimeBetween('now', '+30 days'),
            'telefon_numarasi' => $this->faker->phoneNumber(),
            'musteri_adi' => $this->faker->name(),
            'odeme_durumu' => $this->faker->randomElement([0, 1]),
            'bakim_durumu' => $this->faker->randomElement(['Devam Ediyor', 'Tamamlandı']),
            'ucret' => $this->faker->randomFloat(2, 100, 5000),
            'genel_aciklama' => $this->faker->sentence(),
            'admin_id' => User::factory(),
            'bakim_tarihi' => $this->faker->dateTimeBetween('-30 days', 'now'),
            'personel_id' => User::factory()->create(['role' => 'personel'])->id,
        ];
    }
}
