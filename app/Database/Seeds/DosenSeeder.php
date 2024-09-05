<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DosenSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['nama' => 'Dr. A', 'nomer_pegawai' => '1234567890', 'program_studi_id' => 1],
            ['nama' => 'Dr. B', 'nomer_pegawai' => '1234567891', 'program_studi_id' => 1],
            ['nama' => 'Dr. C', 'nomer_pegawai' => '1234567892', 'program_studi_id' => 1],
            ['nama' => 'Dr. D', 'nomer_pegawai' => '1234567893', 'program_studi_id' => 1],
            ['nama' => 'Dr. E', 'nomer_pegawai' => '1234567894', 'program_studi_id' => 1],
            ['nama' => 'Dr. F', 'nomer_pegawai' => '1234567895', 'program_studi_id' => 1],
            ['nama' => 'Dr. G', 'nomer_pegawai' => '1234567896', 'program_studi_id' => 1],
            ['nama' => 'Dr. H', 'nomer_pegawai' => '1234567897', 'program_studi_id' => 1],
            ['nama' => 'Dr. I', 'nomer_pegawai' => '1234567898', 'program_studi_id' => 1],
            ['nama' => 'Dr. J', 'nomer_pegawai' => '1234567899', 'program_studi_id' => 1],
        ];

        // Insert ke tabel dosen
        $this->db->table('dosen')->insertBatch($data);
    }
}
