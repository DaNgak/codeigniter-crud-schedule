<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRuangan extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'              => 'INT',
                'constraint'        => 5,
                'unsigned'          => true,
                'auto_increment'    => true,
            ],
            'nama' => [
                'type'              => 'VARCHAR',
                'constraint'        => '255',
            ],
            'kode' => [
                'type'              => 'VARCHAR',
                'constraint'        => '50',
                'unique'            => true,
            ],
            'keterangan' => [
                'type'              => 'TEXT',
                'null'              => true,
            ],
            'kapasitas' => [
                'type'              => 'INT',
                'constraint'        => 5,
            ],
            'program_studi_id' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'null'       => true, // Set null jika program studi dihapus
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

        // Menambahkan foreign key untuk program_studi_id
        $this->forge->addForeignKey('program_studi_id', 'program_studi', 'id', 'SET NULL', 'SET NULL');

        // Membuat tabel
        $this->forge->createTable('ruangan');
    }

    public function down()
    {
        $this->forge->dropTable('ruangan');
    }
}
    
