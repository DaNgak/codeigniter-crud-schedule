<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RuanganSeeder extends Seeder
{
    public function run()
    {
        $data = [];
        $faker = \Faker\Factory::create();
        
        $kapasitasOptions = [15, 20];
        
        for ($i = 0; $i < 10; $i++) {
            $data[] = [
                'nama' => $faker->word . ' Room',
                'kode' => $faker->unique()->bothify('RU###'),
                'keterangan' => $faker->sentence,
                'kapasitas' => $kapasitasOptions[array_rand($kapasitasOptions)],
            ];
        }

        $this->db->table('ruangan')->insertBatch($data);
    }
}
