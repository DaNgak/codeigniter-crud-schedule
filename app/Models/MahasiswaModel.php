<?php namespace App\Models;

use CodeIgniter\Model;

class MahasiswaModel extends Model
{
    protected $table = 'mahasiswa';
    protected $primaryKey = 'id';

    protected $allowedFields = ['nama', 'nomer_identitas', 'program_studi_id', 'kelas_id'];

    protected $useTimestamps = true;
}
