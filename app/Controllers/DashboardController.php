<?php namespace App\Controllers;

use App\Models\DosenModel;
use App\Models\JadwalModel;
use App\Models\KelasModel;
use App\Models\MahasiswaModel;
use App\Models\MataKuliahModel;
use App\Models\ProgramStudiModel;
use App\Models\RuanganModel;
use App\Models\WaktuKuliahModel;

class DashboardController extends BaseController
{
    public function index() {
        // Inisialisasi model
        $programStudiModel = new ProgramStudiModel();
        $mataKuliahModel = new MataKuliahModel();
        $ruanganModel = new RuanganModel();
        $dosenModel = new DosenModel();
        $mahasiswaModel = new MahasiswaModel();
        $kelasModel = new KelasModel();
        $waktuKuliahModel = new WaktuKuliahModel();
        $jadwalModel = new JadwalModel();

        // Mengambil jumlah data dari setiap tabel
        $data = [
            'programStudi' => $programStudiModel->countAll(),
            'mataKuliah' => $mataKuliahModel->countAll(),
            'ruangan' => $ruanganModel->countAll(),
            'dosen' => $dosenModel->countAll(),
            'mahasiswa' => $mahasiswaModel->countAll(),
            'kelas' => $kelasModel->countAll(),
            'waktuKuliah' => $waktuKuliahModel->countAll(),
            'jadwal' => $jadwalModel->countAll(),
        ];

        // Mengirimkan data ke view
        return view('dashboard/index', [
            'data' => $data
        ]);
    }
}