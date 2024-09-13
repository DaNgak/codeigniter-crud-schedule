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

    function calculate_conflict($individual) {
        $conflicts = 0;
        $ruangan_with_time_map = [];
        $dosen_with_time_map = [];
        $conflict_details = [];

        // Tambahkan atribut 'jam' ke setiap entri di $individual
        foreach ($individual as $index => &$schedule) {
            // Pengecekan untuk memastikan kunci yang diperlukan ada
            if (isset($schedule['waktu_kuliah']['jam_mulai']) && isset($schedule['waktu_kuliah']['jam_selesai'])) {
                // Gabungkan jam_mulai dan jam_selesai menjadi atribut 'jam'
                $waktuKuliah = $schedule['waktu_kuliah'];
                $jam_kuliah = $waktuKuliah['jam_mulai'] . ' - ' . $waktuKuliah['jam_selesai'];
                $schedule['waktu_kuliah'] = array_merge($waktuKuliah, ['jam' => $jam_kuliah]);
            } else {
                // Jika jam_mulai atau jam_selesai tidak ada, set 'jam' ke nilai default
                $schedule['waktu_kuliah']['jam'] = 'Unknown';
            }
        }

        // Buat salinan data untuk pemformatan yang lebih baik
        $formatted_individual = array_map(function($schedule) {
            return [
                'kelas' => $schedule['kelas'],
                'mata_kuliah' => $schedule['mata_kuliah'],
                'ruangan' => $schedule['ruangan'],
                'waktu_kuliah' => $schedule['waktu_kuliah'],
                'dosen' => $schedule['dosen']
            ];
        }, $individual);

        foreach ($formatted_individual as $index => $schedule) {
            // Create unique keys for room-time and instructor-time conflicts
            $ruangan_time_key = "{$schedule['ruangan']['kode']}-{$schedule['waktu_kuliah']['hari']}-{$schedule['waktu_kuliah']['jam']}";
            $dosen_time_key = "{$schedule['dosen']['nama']}-{$schedule['waktu_kuliah']['hari']}-{$schedule['waktu_kuliah']['jam']}";
    
            // Format the schedule data for better readability in conflicts
            $formatted_data = function($data) {
                return "[Kelas: {$data['kelas']['kode']}, Matkul: {$data['mata_kuliah']['kode']}, Ruang: {$data['ruangan']['kode']}, Waktu: ({$data['waktu_kuliah']['id']}) {$data['waktu_kuliah']['hari']}/{$data['waktu_kuliah']['jam']}, Dosen: {$data['dosen']['nama']}]";
            };
            
            // Check for room-time conflicts
            if (isset($ruangan_with_time_map[$ruangan_time_key])) {
                $conflicts++;
                $conflict_details[] = "Konflik Ke-" . $conflicts;
                $conflict_details[] = "Konflik Ruangan: Baris " . ($index + 1) . " dan Baris " . ($ruangan_with_time_map[$ruangan_time_key] + 1);
                $conflict_details[] = "Baris " . $index + 1 . " \t: " . $formatted_data($individual[$index]);
                $conflict_details[] = "Baris " . ($ruangan_with_time_map[$ruangan_time_key] + 1) . " \t: " . $formatted_data($individual[$ruangan_with_time_map[$ruangan_time_key]]);
                $conflict_details[] = ""; // Add empty line as a separator
            } else {
                // No conflict, store this room-time pairing
                $ruangan_with_time_map[$ruangan_time_key] = $index;
            }
    
            // Check for instructor-time conflicts
            if (isset($dosen_with_time_map[$dosen_time_key])) {
                $conflicts++;
                $conflict_details[] = "Konflik Ke-" . $conflicts;
                $conflict_details[] = "Konflik Dosen: Baris " . ($index + 1) . " dan Baris " . ($dosen_with_time_map[$dosen_time_key] + 1);
                $conflict_details[] = "Baris " . $index + 1 . " \t: " . $formatted_data($individual[$index]);
                $conflict_details[] = "Baris " . ($dosen_with_time_map[$dosen_time_key] + 1) . " \t: " . $formatted_data($individual[$dosen_with_time_map[$dosen_time_key]]);
                $conflict_details[] = ""; // Add empty line as a separator
            } else {
                // No conflict, store this instructor-time pairing
                $dosen_with_time_map[$dosen_time_key] = $index;
            }
        }
    
        // Return conflict details
        $console = "<pre>" . implode("\n", $conflict_details) . "</pre>";
    
        return [
            'conflict' => $conflicts,
            'debug_conflict' => $console
        ];
    }
    
    public function index()
    {
        // Fetch all schedules with relations
        $data['jadwal'] = $this->jadwalModel->findAllWithAllRelation();
        // Load the view and pass the data
        return view('dashboard/jadwal/index', $data);
    }

    public function create()
    {
        $data['programStudi'] = $this->programStudiModel->findAll();
        $data['periodeKuliah'] = $this->periodeKuliahModel->findAll();
        $data['waktuKuliah'] = $this->waktuKuliahModel->findAll();
        return view('dashboard/jadwal/create', $data);
    }

    public function store()
    {
        $session = session();

        // Setup validation rules
        $validation = \Config\Services::validation();
        $validation->setRules([
            'periode_kuliah_id' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Periode kuliah harus dipilih.',
                ]
            ],
            'program_studi_id' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Program studi harus dipilih.',
                ]
            ],
            'kelas_id' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Kelas harus dipilih.',
                ]
            ],
            'mata_kuliah_id' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Mata kuliah harus dipilih.',
                ]
            ],
            'ruangan_id' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Ruangan harus dipilih.',
                ]
            ],
            'waktu_kuliah_id' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Waktu kuliah harus dipilih.',
                ]
            ],
            'dosen_id' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Dosen harus dipilih.',
                ]
            ],
        ]);

        // Check if validation fails
        if (!$validation->withRequest($this->request)->run()) {
            $errors = $validation->getErrors();

            $errorList = '<ul>';
            foreach ($errors as $error) {
                $errorList .= '<li>' . esc($error) . '</li>';
            }
            $errorList .= '</ul>';

            $session->setFlashdata('message', [
                'title' => 'Kesalahan Validasi',
                'description' => $errorList,
                'type' => 'danger'
            ]);

            return redirect()->to('/dashboard/jadwal/create')->withInput();
        }

        // Fetch existing data based on program_studi_id and periode_kuliah_id
        $programStudiId = $this->request->getPost('program_studi_id');
        $periodeKuliahId = $this->request->getPost('periode_kuliah_id');
        
        $existingSchedules = $this->jadwalModel
            ->where('jadwal.program_studi_id', $programStudiId)
            ->where('jadwal.periode_kuliah_id', $periodeKuliahId)
            ->findAllWithAllRelation();
        
        $debug_listSchedules = '';
        foreach ($existingSchedules as $index => $schedule) {
            $schedule['waktu_kuliah']['jam'] = $schedule['waktu_kuliah']['jam_mulai'] . ' - ' . $schedule['waktu_kuliah']['jam_selesai'];
            // Menyusun data menjadi format JSON yang terstruktur dan rapi
            // $formatted_schedule = json_encode($schedule, JSON_PRETTY_PRINT);
            
            // return '<pre>' . $formatted_schedule . '</pre>';
            $number = $index + 1;
            $debug_listSchedules .= "[{$number}][Kelas: {$schedule['kelas']['kode']}, Matkul: {$schedule['mata_kuliah']['kode']}, Ruang: {$schedule['ruangan']['kode']}, Waktu: ({$schedule['waktu_kuliah']['id']}) {$schedule['waktu_kuliah']['hari']}/{$schedule['waktu_kuliah']['jam']}, Dosen: {$schedule['dosen']['nama']}]\n";
        } 

        if (!empty($existingSchedules)) {
            $kelasId = $this->request->getPost('kelas_id');
            $mataKuliahId = $this->request->getPost('mata_kuliah_id');

            // Cek apakah kelas dan mata kuliah sudah ada di database
            $existingScheduleWithClassAndMatkul = $this->jadwalModel
                ->where('jadwal.program_studi_id', $programStudiId)
                ->where('jadwal.periode_kuliah_id', $periodeKuliahId)
                ->where('jadwal.kelas_id', $kelasId)
                ->where('jadwal.mata_kuliah_id', $mataKuliahId)
                ->first();

            if ($existingScheduleWithClassAndMatkul) {
                // Ambil informasi program studi dan periode kuliah dari data jadwal yang ditemukan
                $programStudi = $this->programStudiModel->find($programStudiId);
                $periodeKuliah = $this->periodeKuliahModel->find($periodeKuliahId);
            
                // Format pesan error
                $errorMessage = "<ul>";
                $errorMessage .= "<li>Jadwal untuk kelas dengan mata kuliah ini sudah ada di database pada program studi {$programStudi['nama']} ({$programStudi['kode']}) dan tahun ajaran {$periodeKuliah['tahun_awal']}-{$periodeKuliah['tahun_akhir']} (Semester: {$periodeKuliah['semester']}). <br/>Silakan lakukan edit data atau ganti kelas dan mata kuliahnya jika ingin menambahkan data baru.</li>";
                $errorMessage .= "</ul>";
            
                // Set pesan error sebagai flashdata dan redirect kembali
                $session->setFlashdata('message', [
                    'title' => 'Kesalahan Validasi',
                    'description' => $errorMessage,
                    'type' => 'danger'
                ]);
            
                return redirect()->to('/dashboard/jadwal/create')->withInput();
            }

            $kelas = $this->kelasModel->find( $kelasId);
            $mataKuliah = $this->mataKuliahModel->find($mataKuliahId);
            $ruangan = $this->ruanganModel->find($this->request->getPost('ruangan_id'));
            $waktuKuliah = $this->waktuKuliahModel->find($this->request->getPost('waktu_kuliah_id'));
            $dosen = $this->dosenModel->find($this->request->getPost('dosen_id'));
            
            // Prepare data for new schedule
            $newSchedule = [
                'kelas' => $kelas,
                'mata_kuliah' => $mataKuliah,
                'ruangan' => $ruangan,
                'waktu_kuliah' => $waktuKuliah,
                'dosen' => $dosen,
            ];
    
            // Append the new schedule to the existing schedules
            // $individual = $existingSchedules;
            $individual = array_merge($existingSchedules, [$newSchedule]);
            
            // Calculate conflicts
            $conflictResult = $this->calculate_conflict($individual);
            // If there are conflicts, set flashdata and redirect back
            if ($conflictResult['conflict'] > 0) {
                $debug_conflict = str_replace('Baris ' . (count($existingSchedules) + 1), 'Data input', $conflictResult['debug_conflict']);
    
                $session->setFlashdata('message', [
                    'title' => 'Terdapat Konflik Jadwal sebanyak ' . $conflictResult['conflict'],
                    'description' => '<ul><li>' . $debug_conflict . '</li><li>List Jadwal pada prodi dan periode ini<br/><pre>' . $debug_listSchedules . '</pre></li></ul>',
                    'type' => 'danger'
                ]);
    
                return redirect()->to('/dashboard/jadwal/create')->withInput();
            }
        }

        // Prepare data for saving
        $data = [
            'periode_kuliah_id' => $this->request->getPost('periode_kuliah_id'),
            'program_studi_id' => $this->request->getPost('program_studi_id'),
            'kelas_id' => $this->request->getPost('kelas_id'),
            'mata_kuliah_id' => $this->request->getPost('mata_kuliah_id'),
            'ruangan_id' => $this->request->getPost('ruangan_id'),
            'waktu_kuliah_id' => $this->request->getPost('waktu_kuliah_id'),
            'dosen_id' => $this->request->getPost('dosen_id'),
        ];

        // Save data to the database
        $this->jadwalModel->save($data);

        // Set success message and redirect
        $session->setFlashdata('message', [
            'title' => 'Success',
            'description' => 'Data jadwal berhasil ditambahkan.',
            'type' => 'success'
        ]);

        return redirect()->to('/dashboard/jadwal');
    }

    public function edit($id)
    {
        // Ambil data jadwal berdasarkan ID
        $jadwal = $this->jadwalModel->find($id);
        if (!$jadwal) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Dosen tidak ditemukan');
        }

        // Ambil data untuk dropdown
        $periodeKuliah = $this->periodeKuliahModel->findAll();
        $programStudi = $this->programStudiModel->findAll();
        $kelas = $this->kelasModel->where('program_studi_id', $jadwal['program_studi_id'])->findAll();
        $mataKuliah = $this->mataKuliahModel->where('program_studi_id', $jadwal['program_studi_id'])->findAll();
        $ruangan = $this->ruanganModel->where('program_studi_id', $jadwal['program_studi_id']);
        $waktuKuliah = $this->waktuKuliahModel->findAll();
        $dosen = $this->dosenModel->where('program_studi_id', $jadwal['program_studi_id']);

        // Kirim data ke view
        return view('dashboard/jadwal/edit', [
            'jadwal' => $jadwal,
            'periodeKuliah' => $periodeKuliah,
            'programStudi' => $programStudi,
            'kelas' => $kelas,
            'mataKuliah' => $mataKuliah,
            'ruangan' => $ruangan,
            'waktuKuliah' => $waktuKuliah,
            'dosen' => $dosen
        ]);
    }

    public function update($id)
    {
        $session = session();

        // Setup validation rules
        $validation = \Config\Services::validation();
        $validation->setRules([
            'periode_kuliah_id' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Periode kuliah harus dipilih.',
                ]
            ],
            'program_studi_id' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Program studi harus dipilih.',
                ]
            ],
            'kelas_id' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Kelas harus dipilih.',
                ]
            ],
            'mata_kuliah_id' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Mata kuliah harus dipilih.',
                ]
            ],
            'ruangan_id' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Ruangan harus dipilih.',
                ]
            ],
            'waktu_kuliah_id' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Waktu kuliah harus dipilih.',
                ]
            ],
            'dosen_id' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Dosen harus dipilih.',
                ]
            ],
        ]);

        // Check if validation fails
        if (!$validation->withRequest($this->request)->run()) {
            $errors = $validation->getErrors();

            $errorList = '<ul>';
            foreach ($errors as $error) {
                $errorList .= '<li>' . esc($error) . '</li>';
            }
            $errorList .= '</ul>';

            $session->setFlashdata('message', [
                'title' => 'Kesalahan Validasi',
                'description' => $errorList,
                'type' => 'danger'
            ]);

            return redirect()->back()->withInput();
        }

        // Fetch existing data based on program_studi_id and periode_kuliah_id
        $programStudiId = $this->request->getPost('program_studi_id');
        $periodeKuliahId = $this->request->getPost('periode_kuliah_id');

        $existingSchedules = $this->jadwalModel
            ->where('jadwal.program_studi_id', $programStudiId)
            ->where('jadwal.periode_kuliah_id', $periodeKuliahId)
            ->where('jadwal.id !=', $id)
            ->findAllWithAllRelation();

        $debug_listSchedules = '';
        foreach ($existingSchedules as $index => $schedule) {
            $schedule['waktu_kuliah']['jam'] = $schedule['waktu_kuliah']['jam_mulai'] . ' - ' . $schedule['waktu_kuliah']['jam_selesai'];
            // Menyusun data menjadi format JSON yang terstruktur dan rapi
            // $formatted_schedule = json_encode($schedule, JSON_PRETTY_PRINT);
            
            // return '<pre>' . $formatted_schedule . '</pre>';
            $number = $index + 1;
            $debug_listSchedules .= "[{$number}][Kelas: {$schedule['kelas']['kode']}, Matkul: {$schedule['mata_kuliah']['kode']}, Ruang: {$schedule['ruangan']['kode']}, Waktu: ({$schedule['waktu_kuliah']['id']}) {$schedule['waktu_kuliah']['hari']}/{$schedule['waktu_kuliah']['jam']}, Dosen: {$schedule['dosen']['nama']}]\n";
        } 

        if (!empty($existingSchedules)) {
            $kelasId = $this->request->getPost('kelas_id');
            $mataKuliahId = $this->request->getPost('mata_kuliah_id');

            // Cek apakah kelas dan mata kuliah sudah ada di database
            $existingScheduleWithClassAndMatkul = $this->jadwalModel
                ->where('jadwal.program_studi_id', $programStudiId)
                ->where('jadwal.periode_kuliah_id', $periodeKuliahId)
                ->where('jadwal.kelas_id', $kelasId)
                ->where('jadwal.mata_kuliah_id', $mataKuliahId)
                ->where('jadwal.id !=', $id) // Exclude current record from check
                ->first();
            
                // Ambil informasi program studi dan periode kuliah dari data jadwal yang ditemukan
            $programStudi = $this->programStudiModel->find($programStudiId);
            $periodeKuliah = $this->periodeKuliahModel->find($periodeKuliahId);

            if ($existingScheduleWithClassAndMatkul) {
            
                // Format pesan error
                $errorMessage = "<ul>";
                $errorMessage .= "<li>Jadwal untuk kelas dengan mata kuliah ini sudah ada di database pada program studi {$programStudi['nama']} ({$programStudi['kode']}) dan tahun ajaran {$periodeKuliah['tahun_awal']}-{$periodeKuliah['tahun_akhir']} (Semester: {$periodeKuliah['semester']}). <br/>Silakan lakukan edit data atau ganti kelas dan mata kuliahnya jika ingin menambahkan data baru.</li>";
                $errorMessage .= "</ul>";
            
                // Set pesan error sebagai flashdata dan redirect kembali
                $session->setFlashdata('message', [
                    'title' => 'Kesalahan Validasi',
                    'description' => $errorMessage,
                    'type' => 'danger'
                ]);
            
                return redirect()->to('/dashboard/jadwal/edit/' . $id)->withInput();
            }

            $kelas = $this->kelasModel->find( $kelasId);
            $mataKuliah = $this->mataKuliahModel->find($mataKuliahId);
            $ruangan = $this->ruanganModel->find($this->request->getPost('ruangan_id'));
            $waktuKuliah = $this->waktuKuliahModel->find($this->request->getPost('waktu_kuliah_id'));
            $dosen = $this->dosenModel->find($this->request->getPost('dosen_id'));
    
            // Prepare data for new schedule
            $newSchedule = [
                'kelas' => $kelas,
                'mata_kuliah' => $mataKuliah,
                'ruangan' => $ruangan,
                'waktu_kuliah' => $waktuKuliah,
                'dosen' => $dosen,
            ];
    
            // Append the new schedule to the existing schedules
            // $individual = $existingSchedules;
            $individual = array_merge($existingSchedules, [$newSchedule]);
            
            // Calculate conflicts
            $conflictResult = $this->calculate_conflict($individual);
            // If there are conflicts, set flashdata and redirect back
            if ($conflictResult['conflict'] > 0) {
                $debug_conflict = str_replace('Baris ' . (count($existingSchedules) + 1), 'Data input', $conflictResult['debug_conflict']);
    
                $session->setFlashdata('message', [
                    'title' => 'Terdapat Konflik Jadwal sebanyak ' . $conflictResult['conflict'],
                    'description' => '<ul><li>' . $debug_conflict . '</li><li>List Jadwal pada ' . "program studi {$programStudi['nama']} ({$programStudi['kode']}) dan tahun ajaran {$periodeKuliah['tahun_awal']}-{$periodeKuliah['tahun_akhir']} (Semester: {$periodeKuliah['semester']})" .  ' ini<br/><pre>' . $debug_listSchedules . '</pre></li></ul>',
                    'type' => 'danger'
                ]);
    
                return redirect()->to('/dashboard/jadwal/edit/' . $id)->withInput();
            }
        }

        // Prepare data for updating
        $data = [
            'periode_kuliah_id' => $this->request->getPost('periode_kuliah_id'),
            'program_studi_id' => $this->request->getPost('program_studi_id'),
            'kelas_id' => $this->request->getPost('kelas_id'),
            'mata_kuliah_id' => $this->request->getPost('mata_kuliah_id'),
            'ruangan_id' => $this->request->getPost('ruangan_id'),
            'waktu_kuliah_id' => $this->request->getPost('waktu_kuliah_id'),
            'dosen_id' => $this->request->getPost('dosen_id'),
        ];

        // Update the record in the database
        $this->jadwalModel->update($id, $data);

        // Set success message and redirect
        $session->setFlashdata('message', [
            'title' => 'Success',
            'description' => 'Data jadwal berhasil diperbarui.',
            'type' => 'success'
        ]);

        return redirect()->to('/dashboard/jadwal');
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
            $max_generation = 100;
    
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
                'message' => [
                    'title' => 'Success',
                    'description' => 'Data Jadwal berhasil disimpan. Total data : ' . count($dataBatch),
                ], 
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

    // Dropdown

    public function getOptionsByProgramStudi($programStudiId)
    {
        try {
            // Fetch the relevant data based on the program_studi_id
            $kelas = $this->kelasModel->where('program_studi_id', $programStudiId)->findAll();
            $mataKuliah = $this->mataKuliahModel->where('program_studi_id', $programStudiId)->findAll();
            $ruangan = $this->ruanganModel->where('program_studi_id', $programStudiId)->findAll(); // Filter sesuai dengan program_studi_id
            $dosen = $this->dosenModel->where('program_studi_id', $programStudiId)->findAll(); // Filter sesuai dengan program_studi_id

            // Prepare the success response
            return $this->response->setJSON([
                'code' => 200,
                'message' => 'Success',
                'data' => [
                    'kelas' => $kelas,
                    'mata_kuliah' => $mataKuliah,
                    'ruangan' => $ruangan,
                    'dosen' => $dosen
                ],
            ]);
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
}
