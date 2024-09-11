<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DosenModel;
use App\Models\JadwalModel;
use App\Models\KelasModel;
use App\Models\MataKuliahModel;
use App\Models\PeriodeKuliahModel;
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
        $this->geneticAlgorithmService = new GeneticAlgorithmService();
        $this->jadwalModel = new JadwalModel();
        $this->programStudiModel = new ProgramStudiModel();
        $this->kelasModel = new KelasModel();
        $this->mataKuliahModel = new MataKuliahModel();
        $this->ruanganModel = new RuanganModel();
        $this->waktuKuliahModel = new WaktuKuliahModel();
        $this->dosenModel = new DosenModel();
        $this->periodeKuliahModel = new PeriodeKuliahModel();
    }

    public function index()
    {
        // Fetch all schedules with relations
        $data['jadwal'] = $this->jadwalModel->findAllWithAllRelation();
        
        // Load the view and pass the data
        return view('dashboard/jadwal/index', $data);
    }

    public function delete($id)
    {
        try {
            $existingData = $this->jadwalModel->find($id);
            if (!$existingData) {
                return $this->response->setJSON([
                    'code' => 404,
                    'message' => [
                        'title' => 'Not Found',
                        'description' => 'Jadwal tidak ditemukan',
                        'type' => 'error'
                    ],
                    'data' => null
                ])->setStatusCode(404);
            }

            $this->jadwalModel->delete($id);

            return $this->response->setJSON([
                'code' => 200,
                'message' => [
                    'title' => 'Success',
                    'description' => 'Data berhasil dihapus.',
                    'type' => 'success'
                ],
                'data' => null
            ]);

        } catch (\Exception $e) {
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


    // =========== Generate Jadwal ===============
    public function generateView()
    {
        $data['programStudi'] = $this->programStudiModel->findAll();
        $data['tahunAjaran'] = $this->periodeKuliahModel->findAll();
        return view('dashboard/jadwal/generate', $data);
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

            // Cek apakah jadwal dengan periode ini sudah ada
            $existingJadwal = $this->jadwalModel
                ->where('program_studi_id', $programStudiId)
                ->where('periode_kuliah_id', $tahunAjaranId)
                ->findAll();

            // Jika sudah ada jadwal, berikan opsi kepada pengguna
            if (!empty($existingJadwal)) {
                return $this->response->setStatusCode(400)
                    ->setJSON([
                        'code' => 400,
                        'message' => [
                            'title' => 'Warning',
                            'description' => 'Data jadwal untuk periode ini sudah ada dengan total ' . count($existingJadwal) . ' data. Silakan pilih tahun ajaran lainnya atau hapus data jadwal pada periode di menu Kelola Jadwal.',
                        ]
                    ]);
            }
    
            $kelasList = $this->kelasModel->where('program_studi_id', $programStudiId)->findAll();
            $mataKuliahList = $this->mataKuliahModel->where('program_studi_id', $programStudiId)->findAll();
            $ruanganList = $this->ruanganModel->where('program_studi_id', $programStudiId)->findAll();
            $waktuKuliahList = $this->waktuKuliahModel->findAll(); // Mengambil semua data waktu kuliah
            $dosenList = $this->dosenModel->where('program_studi_id', $programStudiId)->findAll();       
            
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
            $max_generation = 1000;
    
            // Hasil dari algoritma genetika
            $result = $this->geneticAlgorithmService->execute($kelasList, $mataKuliahList, $ruanganList, $waktuKuliahList, $dosenList, $population_size, $max_generation);

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
    public function generateStore()
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

        // Ambil data dari request body
        $jadwal = $this->request->getPost('jadwal');
        $programStudiId = $this->request->getPost('program_studi_id');
        $tahunAjaranId = $this->request->getPost('tahun_ajaran_id');

        // Cek apakah jadwal dengan periode ini sudah ada
        $existingJadwal = $this->jadwalModel
            ->where('program_studi_id', $programStudiId)
            ->where('periode_kuliah_id', $tahunAjaranId)
            ->findAll();

        // Jika sudah ada jadwal, berikan opsi kepada pengguna
        if (!empty($existingJadwal)) {
            return $this->response->setStatusCode(400)
                ->setJSON([
                    'code' => 400,
                    'message' => [
                        'title' => 'Warning',
                        'description' => 'Data jadwal untuk periode ini sudah ada dengan total ' . count($existingJadwal) . ' data. Silakan pilih tahun ajaran lainnya atau hapus data jadwal pada periode di menu Kelola Jadwal.',
                    ],
                    'data' => null
                ]);
        }
    
        // Validasi bahwa jadwal harus berupa array
        if (!is_array($jadwal)) {
            return $this->response->setStatusCode(400)
                ->setJSON([
                    'code' => 400, 
                    'message' => [
                        'title' => 'Warning',
                        'description' => 'Data Jadwal harus berupa array.',
                    ],
                    'data' => null
            ]);
        }
    
        // Loop dan validasi setiap item di dalam jadwal
        foreach ($jadwal as $item) {
            if (!isset($item['kelas']) || !is_numeric($item['kelas']) ||
                !isset($item['mata_kuliah']) || !is_numeric($item['mata_kuliah']) ||
                !isset($item['ruangan']) || !is_numeric($item['ruangan']) ||
                !isset($item['waktu_kuliah']) || !is_numeric($item['waktu_kuliah']) ||
                !isset($item['dosen']) || !is_numeric($item['dosen'])) {
    
                return $this->response->setStatusCode(400)
                    ->setJSON([
                        'code' => 400, 
                        'message' => [
                            'title' => 'Warning',
                            'description' => 'Data atribut pada Jadwal tidak valid.',
                        ],
                        'data' => null
                    ]);
            }
        }
    
        // Ambil semua ID dari array untuk validasi
        $kelas_ids = array_column($jadwal, 'kelas');
        $mata_kuliah_ids = array_column($jadwal, 'mata_kuliah');
        $ruangan_ids = array_column($jadwal, 'ruangan');
        $waktu_kuliah_ids = array_column($jadwal, 'waktu_kuliah');
        $dosen_ids = array_column($jadwal, 'dosen');
    
        // Validasi ke database apakah ID-id tersebut valid
        $invalid_data = $this->validateAtributesJadwalId($kelas_ids, $mata_kuliah_ids, $ruangan_ids, $waktu_kuliah_ids, $dosen_ids);
    
        // Jika terdapat ID yang tidak valid
        if (!empty($invalid_data)) {
            return $this->response->setStatusCode(400)
                ->setJSON([
                    'code' => 400,
                    'message' => [
                        'title' => 'Warning',
                        'description' => 'Data Jadwal tidak valid pada atribut: ' . implode(', ', $invalid_data),
                    ],
                    'data' => null
                ]);
        }
    
        // // Lakukan penyimpanan data jadwal
        // foreach ($jadwal as $item) {
        //     $data = [
        //         'kelas_id' => $item['kelas'],
        //         'mata_kuliah_id' => $item['mata_kuliah'],
        //         'ruangan_id' => $item['ruangan'],
        //         'waktu_kuliah_id' => $item['waktu_kuliah'],
        //         'dosen_id' => $item['dosen'],
        //         'periode_kuliah_id' => $tahunAjaranId,
        //         'program_studi_id' => $programStudiId
        //     ];
        //     $this->jadwalModel->insert($data);
        // }

        // Menggunakan data batching
        $dataBatch = []; // Array untuk menampung semua data

        foreach ($jadwal as $item) {
            $dataBatch[] = [
                'kelas_id' => $item['kelas'],
                'mata_kuliah_id' => $item['mata_kuliah'],
                'ruangan_id' => $item['ruangan'],
                'waktu_kuliah_id' => $item['waktu_kuliah'],
                'dosen_id' => $item['dosen'],
                'periode_kuliah_id' => $tahunAjaranId,
                'program_studi_id' => $programStudiId
            ];
        }

        // Melakukan batch insert sekali saja untuk seluruh data
        $this->jadwalModel->insertBatch($dataBatch);
    
        return $this->response->setStatusCode(200)
            ->setJSON([
                'code' => 200, 
                'message' => 'Data Jadwal berhasil disimpan. Total data : ' . count($dataBatch),
                'data' => $dataBatch
            ]);
    }



    // Helpers
    private function validateAtributesJadwalId($kelas_ids, $mata_kuliah_ids, $ruangan_ids, $waktu_kuliah_ids, $dosen_ids)
    {
        // Initialize the invalid data array
        $invalid_data = [];
        
        // Check for invalid Kelas IDs
        if (!$this->kelasModel->whereIn('id', $kelas_ids)->countAllResults(true)) {
            $invalid_data[] = 'kelas';
        }

        // Check for invalid Mata Kuliah IDs
        if (!$this->mataKuliahModel->whereIn('id', $mata_kuliah_ids)->countAllResults(true)) {
            $invalid_data[] = 'mata_kuliah';
        }

        // Check for invalid Ruangan IDs
        if (!$this->ruanganModel->whereIn('id', $ruangan_ids)->countAllResults(true)) {
            $invalid_data[] = 'ruangan';
        }

        // Check for invalid Waktu Kuliah IDs
        if (!$this->waktuKuliahModel->whereIn('id', $waktu_kuliah_ids)->countAllResults(true)) {
            $invalid_data[] = 'waktu_kuliah';
        }

        // Check for invalid Dosen IDs
        if (!$this->dosenModel->whereIn('id', $dosen_ids)->countAllResults(true)) {
            $invalid_data[] = 'dosen';
        }

        // Return invalid attributes if any
        return $invalid_data;
    }
}
