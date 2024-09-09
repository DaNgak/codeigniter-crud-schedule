<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateJadwalTable extends Migration
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
            'periode_kuliah_id' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'null'           => true,
            ],
            'program_studi_id' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'null'           => true, 
            ],
            'kelas_id' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'null'           => true,
            ],
            'mata_kuliah_id' => [  
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'null'           => true,
            ],
            'ruangan_id' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'null'           => true, 
            ],
            'dosen_id' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'null'           => true, 
            ],
            'waktu_kuliah_id' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'null'           => true,
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

        // Foreign Keys
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('periode_kuliah_id', 'periode_kuliah', 'id', 'SET NULL', 'SET NULL');
        $this->forge->addForeignKey('program_studi_id', 'program_studi', 'id', 'SET NULL', 'SET NULL');
        $this->forge->addForeignKey('mata_kuliah_id', 'mata_kuliah', 'id', 'SET NULL', 'SET NULL'); 
        $this->forge->addForeignKey('ruangan_id', 'ruangan', 'id', 'SET NULL', 'SET NULL');
        $this->forge->addForeignKey('dosen_id', 'dosen', 'id', 'SET NULL', 'SET NULL');
        $this->forge->addForeignKey('waktu_kuliah_id', 'waktu_kuliah', 'id', 'SET NULL', 'SET NULL');
        $this->forge->addForeignKey('kelas_id', 'kelas', 'id', 'SET NULL', 'SET NULL');
        
        $this->forge->createTable('jadwal');
    }

    public function down()
    {
        $this->forge->dropTable('jadwal');
    }
}
