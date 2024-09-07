<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MataKuliahSeeder extends Seeder
{
    public function run()
    {
        // Nonaktifkan foreign key checks
        $this->db->query('SET FOREIGN_KEY_CHECKS=0;');

        // Drop semua data dari tabel mata_kuliah
        $this->db->table('mata_kuliah')->truncate();

        $data = [
            ['nama' => 'Pemrograman Web', 'kode' => 'PW101', 'program_studi_id' => 1],
            ['nama' => 'Basis Data', 'kode' => 'BD101', 'program_studi_id' => 1],
            ['nama' => 'Jaringan Komputer', 'kode' => 'JK102', 'program_studi_id' => 1],
            ['nama' => 'Algoritma dan Struktur Data', 'kode' => 'ASD103', 'program_studi_id' => 1],
            ['nama' => 'Sistem Operasi', 'kode' => 'SO104', 'program_studi_id' => 1],
            ['nama' => 'Keamanan Komputer', 'kode' => 'KK105', 'program_studi_id' => 1],
            ['nama' => 'Pengembangan Aplikasi Mobile', 'kode' => 'PAM106', 'program_studi_id' => 1],
            ['nama' => 'Kecerdasan Buatan', 'kode' => 'KB107', 'program_studi_id' => 1],
            ['nama' => 'Pemrograman Berbasis Objek', 'kode' => 'PBO108', 'program_studi_id' => 1],
            ['nama' => 'Rekayasa Perangkat Lunak', 'kode' => 'RPL109', 'program_studi_id' => 1],
        ];

        // Insert ke tabel mata_kuliah
        $this->db->table('mata_kuliah')->insertBatch($data);

        // Aktifkan kembali foreign key checks
        $this->db->query('SET FOREIGN_KEY_CHECKS=1;');
    }
}
