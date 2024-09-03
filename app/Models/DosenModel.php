<?php namespace App\Models;

use CodeIgniter\Model;

class DosenModel extends Model
{
    protected $table = 'dosen';
    protected $primaryKey = 'id';

    protected $allowedFields = ['nama', 'nomer_pegawai'];

    protected $useTimestamps = true;
}
