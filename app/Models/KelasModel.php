<?php namespace App\Models;

use CodeIgniter\Model;

class KelasModel extends Model
{
    protected $table = 'kelas';
    protected $primaryKey = 'id';

    protected $allowedFields = ['program_studi_id', 'nama', 'kode'];

    protected $useTimestamps = true;

    // Method to get all kelas with related program studi
    public function withProgramStudi()
    {
        $builder = $this->builder();
        $builder->select('kelas.*, program_studi.nama as program_studi_nama');
        $builder->join('program_studi', 'program_studi.id = kelas.program_studi_id');
        return $builder->get()->getResultArray();
    }
}
