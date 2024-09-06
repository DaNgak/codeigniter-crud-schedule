<?php namespace App\Models;

use CodeIgniter\Model;

class KelasModel extends Model
{
    protected $table = 'kelas';
    protected $primaryKey = 'id';

    protected $allowedFields = ['nama', 'kode', 'program_studi_id'];

    protected $useTimestamps = true;

    // Method untuk join dengan program studi dan membentuk hasil array bersarang
    public function findAllWithProgramStudi()
    {
        $result = $this->select('kelas.*, program_studi.nama as program_studi_nama')
            ->join('program_studi', 'program_studi.id = kelas.program_studi_id', 'left')
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
