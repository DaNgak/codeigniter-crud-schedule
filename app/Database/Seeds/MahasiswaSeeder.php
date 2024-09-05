<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MahasiswaSeeder extends Seeder
{
    public function run()
    { 
        // 5 kelas dengan masing-masing 20 mahasiswa untuk kelas Teknik Informatika (1 - 5)
        for ($kelasId = 1; $kelasId <= 5; $kelasId++) {
            for ($i = 1; $i <= 20; $i++) {
                $data[] = [
                    'nama'            => "Mahasiswa Kelas {$kelasId} No {$i}",
                    'nomer_identitas' => "IDK{$kelasId}M{$i}",
                    'program_studi_id'=> 1, // Semua program studi sama
                    'kelas_id'        => $kelasId, // ID kelas
                ];
            }
        }

        // Insert ke tabel mahasiswa
        $this->db->table('mahasiswa')->insertBatch($data);
    }
}
