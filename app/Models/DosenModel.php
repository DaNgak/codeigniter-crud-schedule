<?php namespace App\Models;

use CodeIgniter\Model;

class DosenModel extends Model
{
    protected $table = 'dosen';
    protected $primaryKey = 'id';

    protected $allowedFields = ['nama', 'nomer_pegawai', 'program_studi_id'];

    protected $useTimestamps = true;

    // Method untuk join dengan program studi dan membentuk hasil array bersarang
    public function findAllWithProgramStudi()
    {
        $result = $this->select('dosen.*, program_studi.nama as program_studi_nama')
            ->join('program_studi', 'program_studi.id = dosen.program_studi_id', 'left')
            ->get()
            ->getResultArray();

        // Membentuk array bersarang
        foreach ($result as &$row) {
            $row['program_studi'] = [
                'nama' => $row['program_studi_nama']
            ];
            unset($row['program_studi_nama']);  // Hapus alias yang tidak diperlukan
        }

        return $result;
    }
}
