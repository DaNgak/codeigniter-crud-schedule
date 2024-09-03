<?php namespace App\Models;

use CodeIgniter\Model;

class MahasiswaModel extends Model
{
    protected $table = 'mahasiswa';
    protected $primaryKey = 'id';

    protected $allowedFields = ['nama', 'nomer_identitas'];

    protected $useTimestamps = true;
}
