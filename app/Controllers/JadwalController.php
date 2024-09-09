<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DosenModel;
use App\Models\KelasModel;
use App\Models\MataKuliahModel;
use App\Models\ProgramStudiModel;
use App\Models\RuanganModel;
use App\Models\WaktuKuliahModel;
use App\Services\GeneticAlgorithmService;

class JadwalController extends BaseController
{
    private $jadwalModel, $programStudiModel, $kelasModel, $mataKuliahModel, $ruanganModel, $waktuKuliahModel, $dosenModel, $periodeKuliahModel;
    private $geneticAlgorithmService;

    public function __construct()
    {
        $this->jadwalModel = new \App\Models\JadwalModel();
        $this->programStudiModel = new \App\Models\ProgramStudiModel();
        $this->geneticAlgorithmService = new \App\Services\GeneticAlgorithmService();
        $this->kelasModel = new \App\Models\KelasModel();
        $this->mataKuliahModel = new \App\Models\MataKuliahModel();
        $this->ruanganModel = new \App\Models\RuanganModel();
        $this->waktuKuliahModel = new \App\Models\WaktuKuliahModel();
        $this->dosenModel = new \App\Models\DosenModel();
        $this->periodeKuliahModel = new \App\Models\PeriodeKuliahModel();
    }

    public function index()
    {
        
    }

    public function create()
    {
        $data['programStudi'] = $this->programStudiModel->findAll();
        $data['tahunAjaran'] = [
            ['id' => 1, 'periode' => '2020/2021', 'semester' => 'Ganjil'],
            ['id' => 2, 'periode' => '2020/2021', 'semester' => 'Genap'],
            ['id' => 3, 'periode' => '2021/2022', 'semester' => 'Ganjil'],
            ['id' => 4, 'periode' => '2021/2022', 'semester' => 'Genap'],
            ['id' => 5, 'periode' => '2022/2023', 'semester' => 'Ganjil'],
            ['id' => 6, 'periode' => '2022/2023', 'semester' => 'Genap'],
            ['id' => 7, 'periode' => '2023/2024', 'semester' => 'Ganjil'],
            ['id' => 8, 'periode' => '2023/2024', 'semester' => 'Genap'],
        ];
        return view('dashboard/jadwal/create', $data);
    }

    public function generate()
    {
        // Validasi input
        $validation = \Config\Services::validation();
        $validation->setRules([
            'program_studi_id' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Program studi harus diisi.'
                ]
            ],
            'tahun_ajaran_id' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Tahun ajaran harus diisi.'
                ]
            ]
        ]);

        // Gunakan validasi kedalam request
        if (!$validation->withRequest($this->request)->run()) {
            $errors = $validation->getErrors();

            // Format validation error didalam response json
            return $this->response->setJSON([
                'code' => 422,
                'message' => 'Kesalahan Validasi',
                'data' => null,
                'errors' => $errors
            ])->setStatusCode(422);
        }

        try {
            // Ambil data dari request
            $programStudiId = $this->request->getPost('program_studi_id');
            $tahunAjaranId = $this->request->getPost('tahun_ajaran_id');
            
             // Ambil data dari model berdasarkan program_studi_id
            $kelasModel = new KelasModel();
            $mataKuliahModel = new MataKuliahModel();
            $ruanganModel = new RuanganModel();
            $dosenModel = new DosenModel();
            $waktuKuliahModel = new WaktuKuliahModel();
    
            $kelasList = $kelasModel->where('program_studi_id', $programStudiId)->findAll();
            $mataKuliahList = $mataKuliahModel->where('program_studi_id', $programStudiId)->findAll();
            $ruanganList = $ruanganModel->where('program_studi_id', $programStudiId)->findAll();
            $waktuKuliahList = $waktuKuliahModel->findAll(); // Mengambil semua data waktu kuliah
            $dosenList = $dosenModel->where('program_studi_id', $programStudiId)->findAll();       
            
            // Cek apakah data dari kelas, matkul, ruangan, waktu, dan dosen ada yang kosong
            if (empty($kelasList) || empty($mataKuliahList) || empty($ruanganList) || empty($waktuKuliahList) || empty($dosenList)) {
                $missingData = [];
                if (empty($kelasList)) $missingData[] = 'kelas';
                if (empty($mataKuliahList)) $missingData[] = 'mata kuliah';
                if (empty($ruanganList)) $missingData[] = 'ruangan';
                if (empty($waktuKuliahList)) $missingData[] = 'waktu kuliah';
                if (empty($dosenList)) $missingData[] = 'dosen';
    
                return $this->response->setJSON([
                    'code' => 400,
                    'message' => [
                        'title' => 'Failed',
                        'description' => 'Tidak dapat diproses karena data ' . implode(', ', $missingData) . ' pada program studi ini kosong, harap tambahkan data terlebih dahulu.',
                        'type' => 'warning'
                    ],
                    'data' => null
                ])->setStatusCode(400);
            }

            // Set parameter untuk genetic algorithm
            $population_size = 10;
            $max_generation = 300;
    
            // Hasil dari algoritma genetika
            $result = $this->geneticAlgorithmService->genetic_algorithm($kelasList, $mataKuliahList, $ruanganList, $waktuKuliahList, $dosenList, $population_size, $max_generation);

            // Return response success dari result data
            return $this->response->setJSON([
                'code' => 200,
                'message' => [
                    'title' => 'Success',
                    'description' => 'Berhasil melakukan generate jadwal',
                    'type' => 'success'
                ],
                'data' => $result
            ])->setStatusCode(200);
        } catch (\Exception $e) {
            // Return error response 
            return $this->response->setJSON([
                'code' => 500,
                'message' => [
                    'title' => 'Error',
                    'description' => $e->getMessage(),
                    'type' => 'error'
                ],
                'data' => null
            ])->setStatusCode(500);
        }
    }
    public function store()
    {
        // Load JadwalModel
        $jadwalModel = new \App\Models\JadwalModel();
    
        // Ambil data dari request body
        $jadwal = $this->request->getPost('jadwal');
    
        // Validasi bahwa jadwal harus berupa array
        if (!is_array($jadwal)) {
            return $this->response->setStatusCode(400)
                ->setJSON(['code' => 400, 'message' => 'Jadwal harus berupa array.']);
        }
    
        // Loop dan validasi setiap item di dalam jadwal
        foreach ($jadwal as $item) {
            if (!isset($item['kelas']) || !is_numeric($item['kelas']) ||
                !isset($item['mata_kuliah']) || !is_numeric($item['mata_kuliah']) ||
                !isset($item['ruangan']) || !is_numeric($item['ruangan']) ||
                !isset($item['waktu_kuliah']) || !is_numeric($item['waktu_kuliah']) ||
                !isset($item['dosen']) || !is_numeric($item['dosen']) ||
                !isset($item['periode_kuliah']) || !is_numeric($item['periode_kuliah'])) {
    
                return $this->response->setStatusCode(400)
                    ->setJSON(['code' => 400, 'message' => 'Data tidak valid.']);
            }
        }
    
        // Ambil semua ID dari array untuk validasi
        $kelas_ids = array_column($jadwal, 'kelas');
        $mata_kuliah_ids = array_column($jadwal, 'mata_kuliah');
        $ruangan_ids = array_column($jadwal, 'ruangan');
        $waktu_kuliah_ids = array_column($jadwal, 'waktu_kuliah');
        $dosen_ids = array_column($jadwal, 'dosen');
        $periode_kuliah_ids = array_column($jadwal, 'periode_kuliah');
    
        // Validasi ke database apakah ID-id tersebut valid
        $invalid_data = $this->validateAtributesJadwalId($kelas_ids, $mata_kuliah_ids, $ruangan_ids, $waktu_kuliah_ids, $dosen_ids, $periode_kuliah_ids);
    
        // Jika terdapat ID yang tidak valid
        if (!empty($invalid_data)) {
            return $this->response->setStatusCode(400)
                ->setJSON([
                    'code' => 400,
                    'message' => 'Data tidak valid pada atribut: ' . implode(', ', $invalid_data)
                ]);
        }
    
        // Lakukan penyimpanan data jadwal
        foreach ($jadwal as $item) {
            $data = [
                'kelas_id' => $item['kelas'],
                'mata_kuliah_id' => $item['mata_kuliah'],
                'ruangan_id' => $item['ruangan'],
                'waktu_kuliah_id' => $item['waktu_kuliah'],
                'dosen_id' => $item['dosen'],
                'periode_kuliah_id' => $item['periode_kuliah']
            ];
            $jadwalModel->insert($data);
        }
    
        return $this->response->setStatusCode(200)
            ->setJSON(['code' => 200, 'message' => 'Jadwal berhasil disimpan.']);
    }

    private function validateJadwalIds($kelas_ids, $mata_kuliah_ids, $ruangan_ids, $waktu_kuliah_ids, $dosen_ids, $periode_kuliah_ids)
    {
        // Initialize the invalid data array
        $invalid_data = [];

        // Load models
        $kelasModel = new \App\Models\KelasModel();
        $mataKuliahModel = new \App\Models\MataKuliahModel();
        $ruanganModel = new \App\Models\RuanganModel();
        $waktuKuliahModel = new \App\Models\WaktuKuliahModel();
        $dosenModel = new \App\Models\DosenModel();
        $periodeKuliahModel = new \App\Models\PeriodeKuliahModel();

        // Check for invalid Kelas IDs
        if (!$kelasModel->whereIn('id', $kelas_ids)->countAllResults(true)) {
            $invalid_data[] = 'kelas';
        }

        // Check for invalid Mata Kuliah IDs
        if (!$mataKuliahModel->whereIn('id', $mata_kuliah_ids)->countAllResults(true)) {
            $invalid_data[] = 'mata_kuliah';
        }

        // Check for invalid Ruangan IDs
        if (!$ruanganModel->whereIn('id', $ruangan_ids)->countAllResults(true)) {
            $invalid_data[] = 'ruangan';
        }

        // Check for invalid Waktu Kuliah IDs
        if (!$waktuKuliahModel->whereIn('id', $waktu_kuliah_ids)->countAllResults(true)) {
            $invalid_data[] = 'waktu_kuliah';
        }

        // Check for invalid Dosen IDs
        if (!$dosenModel->whereIn('id', $dosen_ids)->countAllResults(true)) {
            $invalid_data[] = 'dosen';
        }

        // Check for invalid Periode Kuliah IDs
        if (!$periodeKuliahModel->whereIn('id', $periode_kuliah_ids)->countAllResults(true)) {
            $invalid_data[] = 'periode_kuliah';
        }

        // Return invalid attributes if any
        return $invalid_data;
    }

}
