<?php namespace App\Models;

use CodeIgniter\Model;

class DosenModel extends Model
{
    protected $table = 'dosen';
    protected $primaryKey = 'id';

    protected $allowedFields = ['nama', 'nomer_pegawai', 'program_studi_id'];

    protected $useTimestamps = true;
}
