<?php namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Menjalankan seeders
        $this->call('UserSeeder');
        $this->call('MahasiswaSeeder');
        $this->call('RuanganSeeder');
        $this->call('ProgramStudiSeeder');
    }
}
