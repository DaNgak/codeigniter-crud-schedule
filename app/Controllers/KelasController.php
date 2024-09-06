<?php namespace App\Controllers;

use App\Models\KelasModel;
use App\Models\ProgramStudiModel;

class KelasController extends BaseController
{
    private $kelasModel;
    private $programStudiModel;

    public function __construct()
    {
        $this->kelasModel = new KelasModel();
        $this->programStudiModel = new ProgramStudiModel();
    }

    public function index()
    {
        $data['kelas'] = $this->kelasModel->findAllWithProgramStudi();;
        return view('dashboard/kelas/index', $data);
    }

    public function create()
    {
        $data['programStudi'] = $this->programStudiModel->findAll();
        return view('dashboard/kelas/create', $data);
    }

    public function store()
    {
        $session = session();

        // Validasi input
        $validation = \Config\Services::validation();
        $validation->setRules([
            'nama'       => [
                'rules' => 'required|min_length[3]',
                'errors' => [
                    'required'    => 'Nama kelas harus diisi.',
                    'min_length'  => 'Nama kelas harus terdiri dari minimal {param} karakter.'
                ],
            ],
            'kode'       => [
                'rules' => 'required|min_length[2]|is_unique[kelas.kode]',
                'errors' => [
                    'required'    => 'Kode kelas harus diisi.',
                    'min_length'  => 'Kode kelas harus terdiri dari minimal {param} karakter.',
                    'is_unique'   => 'Kode sudah digunakan, silakan gunakan kode lainnya.'
                ],
            ],
            'program_studi_id' => [
                // 'rules' => 'required|check_exists[program_studi,id]',  // Menggunakan nama validasi kustom
                'rules' => 'required',  // Menggunakan nama validasi kustom
                'errors' => [
                    'required' => 'Program studi harus dipilih.',
                    'check_exists' => 'Program studi yang dipilih tidak valid.',
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
                'description'  => $errorList,
                'type'  => 'danger'
            ]);

            return redirect()->to('/dashboard/kelas/create')->withInput();
        }

        $data = [
            'nama'             => $this->request->getPost('nama'),
            'kode'             => $this->request->getPost('kode'),
            'program_studi_id' => $this->request->getPost('program_studi_id'),
        ];

        $this->kelasModel->save($data);

        $session->setFlashdata('message', [
            'title' => 'Success',
            'description'  => 'Data berhasil ditambahkan.',
            'type'  => 'success'
        ]);

        return redirect()->to('/dashboard/kelas');
    }

    public function edit($id)
    {
        $existingData = $this->kelasModel->find($id);
        if (!$existingData) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Kelas tidak ditemukan');
        }
        $data['kelas'] = $existingData;
        $data['programStudi'] = $this->programStudiModel->findAll();
        return view('dashboard/kelas/edit', $data);
    }

    public function update($id)
    {
        $session = session();

        $existingData = $this->kelasModel->find($id);
        if (!$existingData) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Kelas tidak ditemukan');
        }

        $validation = \Config\Services::validation();
        $validation->setRules([
            'nama'       => [
                'rules' => 'required|min_length[3]',
                'errors' => [
                    'required'    => 'Nama kelas harus diisi.',
                    'min_length'  => 'Nama kelas harus terdiri dari minimal {param} karakter.'
                ],
            ],
            'kode'       => [
                'rules' => 'required|min_length[2]|is_unique[kelas.kode,id,' . $id . ']',
                'errors' => [
                    'required'    => 'Kode kelas harus diisi.',
                    'min_length'  => 'Kode kelas harus terdiri dari minimal {param} karakter.',
                    'is_unique'   => 'Kode sudah digunakan, silakan gunakan kode lainnya.'
                ],
            ],
            'program_studi_id' => [
                // 'rules' => 'required|check_exists[program_studi,id]',  // Menggunakan nama validasi kustom
                'rules' => 'required',  // Menggunakan nama validasi kustom
                'errors' => [
                    'required' => 'Program studi harus dipilih.',
                    'check_exists' => 'Program studi yang dipilih tidak valid.',
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
                'description'  => $errorList,
                'type'  => 'danger'
            ]);

            return redirect()->to('/dashboard/kelas/edit/' . $id)->withInput();
        }

        $data = [
            'nama'             => $this->request->getPost('nama'),
            'kode'             => $this->request->getPost('kode'),
            'program_studi_id' => $this->request->getPost('program_studi_id'),
        ];

        $this->kelasModel->update($id, $data);

        $session->setFlashdata('message', [
            'title' => 'Success',
            'description'  => 'Data berhasil di edit.',
            'type'  => 'success'
        ]);

        return redirect()->to('/dashboard/kelas');
    }

    public function delete($id)
    {
        try {
            $existingData = $this->kelasModel->find($id);
            if (!$existingData) {
                return $this->response->setJSON([
                    'code' => 404,
                    'message' => [
                        'title' => 'Not Found',
                        'description' => 'Kelas tidak ditemukan',
                        'type' => 'error'
                    ],
                    'data' => null
                ])->setStatusCode(404);
            }

            $this->kelasModel->delete($id);

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
