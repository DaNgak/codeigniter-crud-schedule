<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PeriodeKuliahSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['tahun_awal' => 2020, 'tahun_akhir' => 2021, 'semester' => 'Ganjil'],
            ['tahun_awal' => 2020, 'tahun_akhir' => 2021, 'semester' => 'Genap'],
            ['tahun_awal' => 2021, 'tahun_akhir' => 2022, 'semester' => 'Ganjil'],
            ['tahun_awal' => 2021, 'tahun_akhir' => 2022, 'semester' => 'Genap'],
            ['tahun_awal' => 2022, 'tahun_akhir' => 2023, 'semester' => 'Ganjil'],
            ['tahun_awal' => 2022, 'tahun_akhir' => 2023, 'semester' => 'Genap'],
            ['tahun_awal' => 2023, 'tahun_akhir' => 2024, 'semester' => 'Ganjil'],
            ['tahun_awal' => 2023, 'tahun_akhir' => 2024, 'semester' => 'Genap'],
        ];

        $this->db->table('periode_kuliah')->insertBatch($data);
    }
}
