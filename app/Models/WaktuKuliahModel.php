<?php

namespace App\Models;

use CodeIgniter\Model;

class WaktuKuliahModel extends Model
{
    protected $table = 'waktu_kuliah';
    protected $primaryKey = 'id';

    protected $allowedFields = ['hari', 'jam_mulai', 'jam_selesai'];

    protected $useTimestamps = true;
}
