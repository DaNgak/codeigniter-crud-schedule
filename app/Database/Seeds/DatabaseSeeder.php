<?php namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Menjalankan seeders
        $this->call('UserSeeder');
        $this->call('ProgramStudiSeeder');
        $this->call('MataKuliahSeeder');
        $this->call('KelasSeeder');
        $this->call('MahasiswaSeeder');
        $this->call('DosenSeeder');
        $this->call('RuanganSeeder');
    }
}
