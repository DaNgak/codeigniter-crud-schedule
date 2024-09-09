<?php namespace App\Models;

use CodeIgniter\Model;

class PeriodeKuliahModel extends Model
{
    protected $table = 'periode_kuliah';
    
    protected $primaryKey = 'id';

    protected $allowedFields = ['tahun_awal', 'tahun_akhir', 'semester'];

    protected $useTimestamps = true;
}