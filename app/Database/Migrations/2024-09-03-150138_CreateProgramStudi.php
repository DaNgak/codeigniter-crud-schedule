<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProgramStudi extends Migration
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
                'constraint'        => '10',
                'unique'            => true,
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
        $this->forge->addKey('id', true);
        $this->forge->createTable('program_studi');
    }

    public function down()
    {
        $this->forge->dropTable('program_studi');
    }
}
