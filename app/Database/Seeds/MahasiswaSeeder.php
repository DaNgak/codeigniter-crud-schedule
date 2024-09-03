<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MahasiswaSeeder extends Seeder
{
    public function run()
    {
        $data = [];
        $faker = \Faker\Factory::create();
        
        for ($i = 0; $i < 30; $i++) {
            $data[] = [
                'nama' => $faker->name,
                'nomer_identitas' => $faker->unique()->numerify('##########'),
            ];
        }

        $this->db->table('mahasiswa')->insertBatch($data);
    }
}
