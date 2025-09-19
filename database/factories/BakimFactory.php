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
        $bakimDurumu = $this->faker->randomElement(['Devam Ediyor', 'Tamamlandı']);
        $odemeDurumu = $bakimDurumu === 'Tamamlandı' ? $this->faker->randomElement([0, 1]) : 0;
        
        // Mevcut kullanıcıları al
        $userIds = User::pluck('id')->toArray();
        $adminId = $userIds[0] ?? 1; // İlk kullanıcıyı admin olarak kullan
        
        return [
            'plaka' => $this->faker->regexify('[0-9]{2}[A-Z]{3}[0-9]{3}'),
            'sase' => $this->faker->regexify('[A-Z0-9]{17}'),
            'tahmini_teslim_tarihi' => $this->faker->dateTimeBetween('now', '+30 days'),
            'telefon_numarasi' => $this->faker->numerify('05## ### ## ##'),
            'musteri_adi' => $this->faker->name(),
            'odeme_durumu' => $odemeDurumu,
            'bakim_durumu' => $bakimDurumu,
            'ucret' => $this->faker->randomFloat(2, 150, 3500),
            'genel_aciklama' => $this->faker->randomElement([
                'Motor bakımı',
                'Fren sistemi kontrolü',
                'Yağ değişimi',
                'Lastik değişimi',
                'Elektrik sistemi kontrolü',
                'Klima bakımı',
                'Egzoz sistemi kontrolü',
                'Şanzıman bakımı',
                'Süspansiyon kontrolü',
                'Genel kontrol'
            ]),
            'admin_id' => $adminId,
            'bakim_tarihi' => $this->faker->dateTimeBetween('-15 days', 'now'),
            'personel_id' => null, // Personel atanmamış
            'tamamlayan_personel_id' => $bakimDurumu === 'Tamamlandı' && count($userIds) > 1 ? $this->faker->randomElement($userIds) : null,
            'tamamlanma_tarihi' => $bakimDurumu === 'Tamamlandı' ? $this->faker->dateTimeBetween('-10 days', 'now') : null,
            'tamamlanma_notu' => $bakimDurumu === 'Tamamlandı' ? $this->faker->sentence() : null,
        ];
    }
}
