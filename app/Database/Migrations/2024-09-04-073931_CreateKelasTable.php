<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateKelasTable extends Migration
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
            'program_studi_id' => [
                'type'       => 'INT',
                'constraint' => 5,
                'unsigned'   => true,
                'null'       => true,  // Diatur menjadi null jika dihapus
            ],
            'kode' => [
                'type'          => 'VARCHAR',
                'constraint'    => '10',
                'unique'        => true,
            ],
            'nama' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'created_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
            ],
            'updated_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
            ],
        ]);

        // Primary key
        $this->forge->addKey('id', true);

        // Foreign key untuk program_studi_id dengan onDelete setNull
        $this->forge->addForeignKey('program_studi_id', 'program_studi', 'id', 'SET NULL', 'CASCADE');

        // Membuat tabel
        $this->forge->createTable('kelas');
    }

    public function down()
    {
        $this->forge->dropTable('kelas');
    }
}
