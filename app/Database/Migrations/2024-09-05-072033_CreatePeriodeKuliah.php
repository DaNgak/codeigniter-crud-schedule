<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePeriodeKuliah extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'tahun_awal' => [
                'type'           => 'INT',
                'constraint'     => 4,
                'null'           => false,
            ],
            'tahun_akhir' => [
                'type'           => 'INT',
                'constraint'     => 4,
                'null'           => false,
            ],
            'semester' => [
                'type'           => 'ENUM',
                'constraint'     => ['Ganjil', 'Genap'],
                'default'        => 'Ganjil',
            ],
            'created_at' => [
                'type'           => 'DATETIME',
                'null'           => true,
            ],
            'updated_at' => [
                'type'           => 'DATETIME',
                'null'           => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('periode_kuliah');
    }

    public function down()
    {
        $this->forge->dropTable('periode_kuliah');
    }
}
