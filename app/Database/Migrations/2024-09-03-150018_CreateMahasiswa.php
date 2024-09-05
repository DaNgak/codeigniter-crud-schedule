<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMahasiswa extends Migration
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
            'nomer_identitas' => [
                'type'              => 'VARCHAR',
                'constraint'        => '10',
                'unique'            => true,
            ],
            'program_studi_id' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'null'       => true, // Nullable jika program studi dihapus
            ],
            'kelas_id' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'null'       => true, // Nullable jika kelas dihapus
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

        // Menambahkan foreign key untuk kelas_id
        $this->forge->addForeignKey('kelas_id', 'kelas', 'id', 'SET NULL', 'SET NULL');

        // Membuat tabel
        $this->forge->createTable('mahasiswa');
    }

    public function down()
    {
        $this->forge->dropTable('mahasiswa');
    }
}
