<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Bakim;
use App\Models\DegisecekParca;

class BakimSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 20 tane test bakım verisi oluştur
        $bakimlar = Bakim::factory(20)->create();

        // Her bakım için rastgele parça bilgileri ekle
        foreach ($bakimlar as $bakim) {
            // %70 ihtimalle parça bilgisi ekle
            if (fake()->boolean(70)) {
                $parcaSayisi = fake()->numberBetween(1, 4);
                
                for ($i = 0; $i < $parcaSayisi; $i++) {
                    DegisecekParca::create([
                        'bakim_id' => $bakim->id,
                        'parca_adi' => fake()->randomElement([
                            'Motor yağı',
                            'Yağ filtresi',
                            'Hava filtresi',
                            'Fren balata',
                            'Fren diski',
                            'Ön lastik',
                            'Arka lastik',
                            'Akü',
                            'Klima filtresi',
                            'Egzoz borusu',
                            'Amortisör',
                            'Direksiyon rotu',
                            'Vites kutusu yağı',
                            'Fren hidroliği',
                            'Soğutma suyu',
                            'Cam sileceği',
                            'Far ampulü',
                            'Sinyal ampulü',
                            'Kontak anahtarı',
                            'Kapı kilidi'
                        ]),
                        'adet' => fake()->numberBetween(1, 3),
                        'birim_fiyat' => fake()->randomFloat(2, 25, 500),
                        'aciklama' => fake()->optional(0.6)->sentence()
                    ]);
                }
            }
        }

        $this->command->info('20 adet test bakım verisi başarıyla oluşturuldu!');
    }
}
