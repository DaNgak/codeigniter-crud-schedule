<?php namespace App\Models;

use CodeIgniter\Model;

class MahasiswaModel extends Model
{
    protected $table = 'mahasiswa';
    protected $primaryKey = 'id';

    protected $allowedFields = ['nama', 'nomer_identitas', 'program_studi_id', 'kelas_id'];

    protected $useTimestamps = true;

    // Method untuk join dengan program studi dan membentuk hasil array bersarang
    public function findAllWithProgramStudi()
    {
        $result = $this->select('mahasiswa.*, program_studi.nama as program_studi_nama')
            ->join('program_studi', 'program_studi.id = mahasiswa.program_studi_id', 'left')
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

    // Method untuk join dengan program studi dan membentuk hasil array bersarang
    public function findAllWithKelas()
    {
        $result = $this->select('mahasiswa.*, kelas.nama as kelas_nama. kelas.kode as kelas_kode')
            ->join('program_studi', 'kelas.id = mahasiswa.kelas_id', 'left')
            ->get()
            ->getResultArray();

        // Membentuk array bersarang
        foreach ($result as &$row) {
            $row['kelas'] = [
                'nama' => $row['kelas_nama'],
                'kode' => $row['kelas_kode']
            ];
            unset($row['kelas_nama']);  // Hapus alias yang tidak diperlukan
        }

        return $result;
    }

    // Method untuk join dengan program studi dan kelas, membentuk array bersarang
    public function findAllWithAllRelation()
    {
        $result = $this->select('mahasiswa.*, program_studi.nama as program_studi_nama, program_studi.kode as program_studi_kode, kelas.nama as kelas_nama, kelas.kode as kelas_kode')
            ->join('program_studi', 'program_studi.id = mahasiswa.program_studi_id', 'left')
            ->join('kelas', 'kelas.id = mahasiswa.kelas_id', 'left')
            ->get()
            ->getResultArray();

        // Membentuk array bersarang
        foreach ($result as &$row) {
            // Array bersarang untuk program_studi
            $row['program_studi'] = [
                'nama' => $row['program_studi_nama'],
                'kode' => $row['program_studi_kode']
            ];
            unset($row['program_studi_nama'], $row['program_studi_kode']);  // Hapus alias yang tidak diperlukan

            // Array bersarang untuk kelas
            $row['kelas'] = [
                'nama' => $row['kelas_nama'],
                'kode' => $row['kelas_kode']
            ];
            unset($row['kelas_nama'], $row['kelas_kode']);  // Hapus alias yang tidak diperlukan
        }

        return $result;
    }
}
