<?php namespace App\Models;

use CodeIgniter\Model;

class RuanganModel extends Model
{
    protected $table = 'ruangan';
    protected $primaryKey = 'id';

    protected $allowedFields = ['nama', 'kode', 'keterangan', 'kapasitas', 'program_studi_id'];

    protected $useTimestamps = true;

    // Method untuk join dengan program studi dan membentuk hasil array bersarang
    public function findAllWithProgramStudi()
    {
        $result = $this->select('ruangan.*, program_studi.nama as program_studi_nama')
            ->join('program_studi', 'program_studi.id = ruangan.program_studi_id', 'left')
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
