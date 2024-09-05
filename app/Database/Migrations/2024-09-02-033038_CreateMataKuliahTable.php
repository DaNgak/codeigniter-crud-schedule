<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMataKuliahTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nama'        => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'kode'        => [
                'type'       => 'VARCHAR',
                'constraint' => '10',
                'unique'     => true, // Unik
            ],
            'program_studi_id' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'null'       => true, // Kolom program_studi_id bisa NULL
            ],
            'created_at'  => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
            'updated_at'  => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],

        ]);
        
        // Menambahkan primary key
        $this->forge->addKey('id', true);

        // Menambahkan foreign key untuk program_studi_id dengan ON DELETE SET NULL
        $this->forge->addForeignKey('program_studi_id', 'program_studi', 'id', 'SET NULL', 'SET NULL');

        // Membuat tabel
        $this->forge->createTable('mata_kuliah');
    }

    public function down()
    {
        $this->forge->dropTable('mata_kuliah');
    }
}