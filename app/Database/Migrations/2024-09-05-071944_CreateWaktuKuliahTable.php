<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateWaktuKuliahTable extends Migration
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
            'hari' => [
                'type'           => 'VARCHAR',
                'constraint'     => '50',
            ],
            'jam_mulai' => [
                'type'           => 'TIME',
            ],
            'jam_selesai' => [
                'type'           => 'TIME',
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
        $this->forge->createTable('waktu_kuliah');
    }

    public function down()
    {
        $this->forge->dropTable('waktu_kuliah');
    }
}
