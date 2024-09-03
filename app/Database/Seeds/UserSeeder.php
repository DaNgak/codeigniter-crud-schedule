<?php namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name'     => 'Admin',
                'email'    => 'admin@gmail.com',
                'password' => password_hash('password', PASSWORD_BCRYPT),
            ],
            [
                'name'     => 'User',
                'email'    => 'user@gmail.com',
                'password' => password_hash('password', PASSWORD_BCRYPT),
            ],
        ];

        // Insert data
        $this->db->table('user')->insertBatch($data);
    }
}