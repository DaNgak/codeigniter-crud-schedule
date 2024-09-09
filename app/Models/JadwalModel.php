<?php

namespace App\Models;

use CodeIgniter\Model;

class JadwalModel extends Model
{
    protected $table = 'jadwal';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'periode_kuliah_id',
        'program_studi_id', 
        'kelas_id', 
        'mata_kuliah_id', 
        'ruangan_id', 
        'waktu_kuliah_id', 
        'dosen_id', 
    ];

    protected $useTimestamps = true;

    // Helper function to format the result with relations
    private function formatRelations($result)
    {
        foreach ($result as &$row) {
            $row['program_studi'] = [
                'id' => $row['program_studi_id'],
                'nama' => $row['program_studi_nama'],
                'kode' => $row['program_studi_kode']
            ];
            unset($row['program_studi_nama'], $row['program_studi_kode']);

            $row['mata_kuliah'] = [
                'id' => $row['mata_kuliah_id'],
                'nama' => $row['mata_kuliah_name'],
                'kode' => $row['mata_kuliah_kode']
            ];
            unset($row['mata_kuliah_name'], $row['mata_kuliah_kode']);

            $row['ruangan'] = [
                'id' => $row['ruangan_id'],
                'nama' => $row['ruangan_name'],
                'kode' => $row['ruangan_kode']
            ];
            unset($row['ruangan_name'], $row['ruangan_kode']);

            $row['kelas'] = [
                'id' => $row['kelas_id'],
                'nama' => $row['kelas_name']
            ];
            unset($row['kelas_name']);

            $row['dosen'] = [
                'id' => $row['dosen_id'],
                'nama' => $row['dosen_name'],
                'nomor_pegawai' => $row['dosen_nomor_pegawai']
            ];
            unset($row['dosen_name'], $row['dosen_nomor_pegawai']);

            $row['waktu_kuliah'] = [
                'id' => $row['waktu_kuliah_id'],
                'hari' => $row['waktu_kuliah_hari'],
                'jam_mulai' => $row['waktu_kuliah_jam_mulai'],
                'jam_selesai' => $row['waktu_kuliah_jam_selesai']
            ];
            unset($row['waktu_kuliah_hari'], $row['waktu_kuliah_jam_mulai'], $row['waktu_kuliah_jam_selesai']);

            $row['periode_kuliah'] = [
                'id' => $row['periode_kuliah_id'],
                'tahun_awal' => $row['periode_kuliah_tahun_awal'],
                'tahun_akhir' => $row['periode_kuliah_tahun_akhir'],
                'semester' => $row['periode_kuliah_semester']
            ];
            unset($row['periode_kuliah_tahun_awal'], $row['periode_kuliah_tahun_akhir'], $row['periode_kuliah_semester']);
        }

        return $result;
    }

    // Get all jadwal with all relations
    public function findAllWithAllRelation()
    {
        $result = $this->select('jadwal.*, program_studi.id as program_studi_id, program_studi.nama as program_studi_nama, program_studi.kode as program_studi_kode, 
            mata_kuliah.id as mata_kuliah_id, mata_kuliah.nama as mata_kuliah_name, mata_kuliah.kode as mata_kuliah_kode, 
            kelas.id as kelas_id, kelas.nama as kelas_name, 
            ruangan.id as ruangan_id, ruangan.nama as ruangan_name, ruangan.kode as ruangan_kode, 
            dosen.id as dosen_id, dosen.nama as dosen_name, dosen.nomor_pegawai as dosen_nomor_pegawai, 
            waktu_kuliah.id as waktu_kuliah_id, waktu_kuliah.hari as waktu_kuliah_hari, waktu_kuliah.jam_mulai as waktu_kuliah_jam_mulai, waktu_kuliah.jam_selesai as waktu_kuliah_jam_selesai, 
            periode_kuliah.id as periode_kuliah_id, periode_kuliah.tahun_awal as periode_kuliah_tahun_awal, periode_kuliah.tahun_akhir as periode_kuliah_tahun_akhir, periode_kuliah.semester as periode_kuliah_semester')
            ->join('program_studi', 'program_studi.id = jadwal.program_studi_id', 'left')
            ->join('mata_kuliah', 'mata_kuliah.id = jadwal.mata_kuliah_id', 'left')
            ->join('kelas', 'kelas.id = jadwal.kelas_id', 'left')
            ->join('ruangan', 'ruangan.id = jadwal.ruangan_id', 'left')
            ->join('dosen', 'dosen.id = jadwal.dosen_id', 'left')
            ->join('waktu_kuliah', 'waktu_kuliah.id = jadwal.waktu_kuliah_id', 'left')
            ->join('periode_kuliah', 'periode_kuliah.id = jadwal.periode_kuliah_id', 'left')
            ->get()
            ->getResultArray();

        return $this->formatRelations($result);
    }

    // Get all jadwal by program studi with relations
    public function findAllWithAllRelationByProgramStudi($programStudiId)
    {
        $result = $this->select('jadwal.*, program_studi.id as program_studi_id, program_studi.nama as program_studi_nama, program_studi.kode as program_studi_kode, 
            mata_kuliah.id as mata_kuliah_id, mata_kuliah.nama as mata_kuliah_name, mata_kuliah.kode as mata_kuliah_kode, 
            kelas.id as kelas_id, kelas.nama as kelas_name, 
            ruangan.id as ruangan_id, ruangan.nama as ruangan_name, ruangan.kode as ruangan_kode, 
            dosen.id as dosen_id, dosen.nama as dosen_name, dosen.nomor_pegawai as dosen_nomor_pegawai, 
            waktu_kuliah.id as waktu_kuliah_id, waktu_kuliah.hari as waktu_kuliah_hari, waktu_kuliah.jam_mulai as waktu_kuliah_jam_mulai, waktu_kuliah.jam_selesai as waktu_kuliah_jam_selesai, 
            periode_kuliah.id as periode_kuliah_id, periode_kuliah.tahun_awal as periode_kuliah_tahun_awal, periode_kuliah.tahun_akhir as periode_kuliah_tahun_akhir, periode_kuliah.semester as periode_kuliah_semester')
            ->join('program_studi', 'program_studi.id = jadwal.program_studi_id', 'left')
            ->join('mata_kuliah', 'mata_kuliah.id = jadwal.mata_kuliah_id', 'left')
            ->join('kelas', 'kelas.id = jadwal.kelas_id', 'left')
            ->join('ruangan', 'ruangan.id = jadwal.ruangan_id', 'left')
            ->join('dosen', 'dosen.id = jadwal.dosen_id', 'left')
            ->join('waktu_kuliah', 'waktu_kuliah.id = jadwal.waktu_kuliah_id', 'left')
            ->join('periode_kuliah', 'periode_kuliah.id = jadwal.periode_kuliah_id', 'left')
            ->where('jadwal.program_studi_id', $programStudiId)
            ->get()
            ->getResultArray();

        return $this->formatRelations($result);
    }

    // Get a specific jadwal by ID with all relations
    public function getByIdWithAllRelation($id)
    {
        $result = $this->select('jadwal.*, program_studi.id as program_studi_id, program_studi.nama as program_studi_nama, program_studi.kode as program_studi_kode, 
            mata_kuliah.id as mata_kuliah_id, mata_kuliah.nama as mata_kuliah_name, mata_kuliah.kode as mata_kuliah_kode, 
            kelas.id as kelas_id, kelas.nama as kelas_name, 
            ruangan.id as ruangan_id, ruangan.nama as ruangan_name, ruangan.kode as ruangan_kode, 
            dosen.id as dosen_id, dosen.nama as dosen_name, dosen.nomor_pegawai as dosen_nomor_pegawai, 
            waktu_kuliah.id as waktu_kuliah_id, waktu_kuliah.hari as waktu_kuliah_hari, waktu_kuliah.jam_mulai as waktu_kuliah_jam_mulai, waktu_kuliah.jam_selesai as waktu_kuliah_jam_selesai, 
            periode_kuliah.id as periode_kuliah_id, periode_kuliah.tahun_awal as periode_kuliah_tahun_awal, periode_kuliah.tahun_akhir as periode_kuliah_tahun_akhir, periode_kuliah.semester as periode_kuliah_semester')
            ->join('program_studi', 'program_studi.id = jadwal.program_studi_id', 'left')
            ->join('mata_kuliah', 'mata_kuliah.id = jadwal.mata_kuliah_id', 'left')
            ->join('kelas', 'kelas.id = jadwal.kelas_id', 'left')
            ->join('ruangan', 'ruangan.id = jadwal.ruangan_id', 'left')
            ->join('dosen', 'dosen.id = jadwal.dosen_id', 'left')
            ->join('waktu_kuliah', 'waktu_kuliah.id = jadwal.waktu_kuliah_id', 'left')
            ->join('periode_kuliah', 'periode_kuliah.id = jadwal.periode_kuliah_id', 'left')
            ->where('jadwal.id', $id)
            ->get()
            ->getRowArray();

        return $this->formatRelations([$result])[0];
    }
}
