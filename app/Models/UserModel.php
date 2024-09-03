<?php namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table      = 'user';
    protected $primaryKey = 'id';

    protected $allowedFields = ['name', 'email', 'password', 'profil'];

    protected $useTimestamps = true;

    protected $validationRules    = [
        'email'    => 'required|valid_email',
        'password' => 'required|min_length[8]'
    ];
    protected $validationMessages = [
        'email' => [
            'valid_email' => 'The email address is not valid.',
        ],
        'password' => [
            'min_length' => 'Password must be at least 8 characters long.',
        ],
    ];
}