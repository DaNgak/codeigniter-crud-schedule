<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\WaktuKuliahModel;
use CodeIgniter\HTTP\ResponseInterface;

class WaktuKuliahController extends BaseController
{
    private $waktuKuliahModel;

    public function __construct()
    {
        $this->waktuKuliahModel = new WaktuKuliahModel();
    }

    public function index()
    {
        $data['waktuKuliah'] = $this->waktuKuliahModel->findAll();
        return view('dashboard/waktu-kuliah/index', $data);
    }

    public function create()
    {
        return view('dashboard/waktu-kuliah/create');
    }

    public function store()
    {
        $session = session();
        // return var_dump([
        //     'hari' => $this->request->getPost('hari'),
        //     'jam_mulai' => $this->request->getPost('jam_mulai'),
        //     'jam_selesai' => $this->request->getPost('jam_selesai'),
        // ]);
        // Validasi input
        $validation = \Config\Services::validation();
        $validation->setRules([
            'hari' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Hari harus dipilih.',
                ],
            ],
            'jam_mulai' => [
                // Regex diubah untuk hanya memperbolehkan jam 07:00 hingga 17:00
                'rules' => 'required|regex_match[/^(0[7-9]|1[0-6]):[0-5][0-9]$/]',
                'errors' => [
                    'required'    => 'Jam mulai harus diisi.',
                    'regex_match' => 'Jam mulai harus antara 07:00 dan 17:00 dengan format HH:MM.',
                ],
            ],
            'jam_selesai' => [
                // Regex diubah untuk hanya memperbolehkan jam 07:00 hingga 17:00
                'rules' => 'required|regex_match[/^(0[7-9]|1[0-6]):[0-5][0-9]$/]',
                'errors' => [
                    'required'    => 'Jam selesai harus diisi.',
                    'regex_match' => 'Jam selesai harus antara 07:00 dan 17:00 dengan format HH:MM.',
                ],
            ],
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            $errors = $validation->getErrors();

            $errorList = '<ul>';
            foreach ($errors as $error) {
                $errorList .= '<li>' . esc($error) . '</li>';
            }
            $errorList .= '</ul>';

            $session->setFlashdata('message', [
                'title' => 'Validation Error',
                'description' => $errorList,
                'type' => 'danger'
            ]);

            return redirect()->to('/dashboard/waktu-kuliah/create')->withInput();
        }

        $hari = $this->request->getPost('hari');
        $jamMulai = $this->request->getPost('jam_mulai');
        $jamSelesai = $this->request->getPost('jam_selesai');
    
        // Konversi jam_mulai dan jam_selesai ke format timestamp untuk pengecekan waktu
        $jamMulaiTimestamp = strtotime($jamMulai);
        $jamSelesaiTimestamp = strtotime($jamSelesai);

        // Validasi bahwa jam_mulai dan jam_selesai berada dalam rentang 07:00 hingga 17:00
        $startLimit = strtotime('07:00');
        $endLimit = strtotime('17:00');

        if ($jamMulaiTimestamp < $startLimit || $jamMulaiTimestamp > $endLimit) {
            $session->setFlashdata('message', [
                'title' => 'Validation Error',
                'description' => '<ul><li>Jam mulai harus berada di antara 07:00 dan 17:00.</li></ul>',
                'type' => 'danger'
            ]);
        
            return redirect()->to('/dashboard/waktu-kuliah/create')->withInput();
        }

           // Ambil jadwal yang sudah ada di hari yang dipilih
        $existingSchedules = $this->waktuKuliahModel->where('hari', $hari)->findAll();

        // Cek apakah ada tabrakan dengan jadwal yang sudah ada
        $errorList = '<ul>';
        $jadwalInfo = '<p>Jadwal yang sudah ada pada hari ' . esc($hari) . ':</p><ul>';

        foreach ($existingSchedules as $schedule) {
            $existingMulai = $schedule['jam_mulai'];
            $existingSelesai = $schedule['jam_selesai'];

            // Tampilkan jadwal yang sudah ada dengan format HH:MM
            $jadwalInfo .= '<li>' . date('H:i', strtotime($existingMulai)) . ' - ' . date('H:i', strtotime($existingSelesai)) . '</li>';

            // Validasi untuk tabrakan waktu
            if ($jamMulai >= $existingMulai && $jamMulai < $existingSelesai) {
                $errorList .= '<li>Jam mulai sudah digunakan oleh jadwal lain, gunakan waktu lain.</li>';
            }
            if ($jamSelesai > $existingMulai && $jamSelesai <= $existingSelesai) {
                $errorList .= '<li>Jam selesai sudah digunakan oleh jadwal lain, gunakan waktu lain.</li>';
            }
            if ($jamMulai <= $existingMulai && $jamSelesai >= $existingSelesai) {
                $errorList .= '<li>Rentang waktu bertabrakan dengan jadwal lain, pilih rentang waktu lain.</li>';
            }
        }
        $jadwalInfo .= '</ul>';

        if ($errorList !== '<ul>') {
            $errorList .= '</ul>';
            $session->setFlashdata('message', [
                'title' => 'Validation Error',
                'description' => $errorList . $jadwalInfo, // Gabungkan pesan error dan jadwal yang sudah ada
                'type' => 'danger'
            ]);

            return redirect()->to('/dashboard/waktu-kuliah/create')->withInput();
        }
    
        // Jika tidak ada tabrakan, simpan data
        $data = [
            'hari' => $hari,
            'jam_mulai' => $jamMulai,
            'jam_selesai' => $jamSelesai,
        ];

        $this->waktuKuliahModel->save($data);

        $session->setFlashdata('message', [
            'title' => 'Success',
            'description' => 'Waktu kuliah berhasil ditambahkan.',
            'type' => 'success'
        ]);

        return redirect()->to('/dashboard/waktu-kuliah');
    }

    public function edit($id)
    {
        $existingData = $this->waktuKuliahModel->find($id);
        if (!$existingData) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Waktu kuliah tidak ditemukan');
        }

        $data['waktuKuliah'] = $existingData;
        return view('dashboard/waktu-kuliah/edit', $data);
    }

    public function update($id)
    {
        $session = session();
        // return var_dump([
        //     'hari' => $this->request->getPost('hari'),
        //     'jam_mulai' => $this->request->getPost('jam_mulai'),
        //     'jam_selesai' => $this->request->getPost('jam_selesai'),
        // ]);
        // Ambil data yang ada
        $existingData = $this->waktuKuliahModel->find($id);
        if (!$existingData) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Waktu kuliah tidak ditemukan');
        }

        // Validasi input
        $validation = \Config\Services::validation();
        $validation->setRules([
            'hari' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Hari harus dipilih.',
                ],
            ],
            'jam_mulai' => [
                'rules' => 'required|regex_match[/^(0[7-9]|1[0-6]):[0-5][0-9]$/]',
                'errors' => [
                    'required'    => 'Jam mulai harus diisi.',
                    'regex_match' => 'Jam mulai harus antara 07:00 dan 17:00 dengan format HH:MM.',
                ],
            ],
            'jam_selesai' => [
                'rules' => 'required|regex_match[/^(0[7-9]|1[0-6]):[0-5][0-9]$/]',
                'errors' => [
                    'required'    => 'Jam selesai harus diisi.',
                    'regex_match' => 'Jam selesai harus antara 07:00 dan 17:00 dengan format HH:MM.',
                ],
            ],
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            $errors = $validation->getErrors();

            $errorList = '<ul>';
            foreach ($errors as $error) {
                $errorList .= '<li>' . esc($error) . '</li>';
            }
            $errorList .= '</ul>';

            $session->setFlashdata('message', [
                'title' => 'Validation Error',
                'description' => $errorList,
                'type' => 'danger'
            ]);

            return redirect()->to('/dashboard/waktu-kuliah/edit/' . $id)->withInput();
        }

        $hari = $this->request->getPost('hari');
        $jamMulai = $this->request->getPost('jam_mulai');
        $jamSelesai = $this->request->getPost('jam_selesai');
        
        // Konversi jam_mulai dan jam_selesai ke format timestamp
        $jamMulaiTimestamp = strtotime($jamMulai);
        $jamSelesaiTimestamp = strtotime($jamSelesai);

        // Validasi bahwa jam_mulai dan jam_selesai berada dalam rentang 07:00 hingga 17:00
        $startLimit = strtotime('07:00');
        $endLimit = strtotime('17:00');

        if ($jamMulaiTimestamp < $startLimit || $jamMulaiTimestamp > $endLimit) {
            $session->setFlashdata('message', [
                'title' => 'Validation Error',
                'description' => '<ul><li>Jam mulai harus berada di antara 07:00 dan 17:00.</li></ul>',
                'type' => 'danger'
            ]);

            return redirect()->to('/dashboard/waktu-kuliah/edit/' . $id)->withInput();
        }

        if ($jamSelesaiTimestamp < $startLimit || $jamSelesaiTimestamp > $endLimit) {
            $session->setFlashdata('message', [
                'title' => 'Validation Error',
                'description' => '<ul><li>Jam selesai harus berada di antara 07:00 dan 17:00.</li></ul>',
                'type' => 'danger'
            ]);

            return redirect()->to('/dashboard/waktu-kuliah/edit/' . $id)->withInput();
        }

        // Ambil jadwal yang sudah ada di hari yang dipilih, kecuali jadwal yang sedang diupdate
        $existingSchedules = $this->waktuKuliahModel->where('hari', $hari)
            ->where('id !=', $id)
            ->findAll();

        // Cek apakah ada tabrakan dengan jadwal yang sudah ada
        $errorList = '<ul>';
        $jadwalInfo = '<p>Jadwal yang sudah ada pada hari ' . esc($hari) . ':</p><ul>';

        foreach ($existingSchedules as $schedule) {
            $existingMulai = $schedule['jam_mulai'];
            $existingSelesai = $schedule['jam_selesai'];

            // Tampilkan jadwal yang sudah ada dengan format HH:MM
            $jadwalInfo .= '<li>' . date('H:i', strtotime($existingMulai)) . ' - ' . date('H:i', strtotime($existingSelesai)) . '</li>';

            // Validasi untuk tabrakan waktu
            if ($jamMulai >= $existingMulai && $jamMulai < $existingSelesai) {
                $errorList .= '<li>Jam mulai sudah digunakan oleh jadwal lain, gunakan waktu lain.</li>';
            }
            if ($jamSelesai > $existingMulai && $jamSelesai <= $existingSelesai) {
                $errorList .= '<li>Jam selesai sudah digunakan oleh jadwal lain, gunakan waktu lain.</li>';
            }
            if ($jamMulai <= $existingMulai && $jamSelesai >= $existingSelesai) {
                $errorList .= '<li>Rentang waktu bertabrakan dengan jadwal lain, pilih rentang waktu lain.</li>';
            }
        }
        $jadwalInfo .= '</ul>';

        if ($errorList !== '<ul>') {
            $errorList .= '</ul>';
            $session->setFlashdata('message', [
                'title' => 'Validation Error',
                'description' => $errorList . $jadwalInfo, // Gabungkan pesan error dan jadwal yang sudah ada
                'type' => 'danger'
            ]);

            return redirect()->to('/dashboard/waktu-kuliah/edit/' . $id)->withInput();
        }

        // Jika tidak ada tabrakan, simpan data
        $data = [
            'hari' => $hari,
            'jam_mulai' => $jamMulai,
            'jam_selesai' => $jamSelesai,
        ];

        $this->waktuKuliahModel->update($id, $data);

        $session->setFlashdata('message', [
            'title' => 'Success',
            'description' => 'Waktu kuliah berhasil diupdate.',
            'type' => 'success'
        ]);

        return redirect()->to('/dashboard/waktu-kuliah');
    }


    public function delete($id)
    {
        try {
            $existingData = $this->waktuKuliahModel->find($id);
            if (!$existingData) {
                return $this->response->setJSON([
                    'code' => 404,
                    'message' => [
                        'title' => 'Not Found',
                        'description' => 'Waktu kuliah tidak ditemukan',
                        'type' => 'error'
                    ],
                    'data' => null
                ])->setStatusCode(404);
            }

            $this->waktuKuliahModel->delete($id);

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
}
