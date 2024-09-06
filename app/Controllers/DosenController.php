<?php namespace App\Controllers;

use App\Models\DosenModel;
use App\Models\ProgramStudiModel;

class DosenController extends BaseController
{
    private $model, $programStudiModel;

    public function __construct()
    {
        $this->model = new DosenModel();
        $this->programStudiModel = new ProgramStudiModel();

    }

    public function index()
    {
        $data['dosen'] = $this->model->findAllWithProgramStudi();
        return view('dashboard/dosen/index', $data);
    }

    public function create()
    {
        $data['programStudi'] = $this->programStudiModel->findAll();
        return view('dashboard/dosen/create', $data);
    }

    public function store()
    {
        $session = session();

        $validation = \Config\Services::validation();
        $validation->setRules([
            'nama' => [
                'rules' => 'required|min_length[3]',
                'errors' => [
                    'required' => 'Nama wajib diisi.',
                    'min_length' => 'Nama harus memiliki panjang minimal 3 karakter.'
                ]
            ],
            'nomer_pegawai' => [
                'rules' => 'required|exact_length[10]|is_unique[dosen.nomer_pegawai]',
                'errors' => [
                    'required' => 'Nomer Pegawai wajib diisi.',
                    'exact_length' => 'Nomer Pegawai harus terdiri dari tepat 10 digit.',
                    'is_unique' => 'Nomer Pegawai sudah terdaftar.'
                ]
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
                'title' => 'Kesalahan Validasi',
                'description' => $errorList,
                'type' => 'danger'
            ]);

            return redirect()->to('/dashboard/dosen/create')->withInput();
        }

        $data = [
            'nama' => $this->request->getPost('nama'),
            'nomer_pegawai' => $this->request->getPost('nomer_pegawai'),
            'program_studi_id' => $this->request->getPost('program_studi_id'),
        ];

        $this->model->save($data);

        $session->setFlashdata('message', [
            'title' => 'Success',
            'description' => 'Data berhasil ditambahkan.',
            'type' => 'success'
        ]);

        return redirect()->to('/dashboard/dosen');
    }

    public function edit($id)
    {
        $existingData = $this->model->find($id);
        if (!$existingData) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Dosen tidak ditemukan');
        }
        $data['dosen'] = $existingData;
        $data['programStudi'] = $this->programStudiModel->findAll();
        return view('dashboard/dosen/edit', $data);
    }

    public function update($id)
    {
        $session = session();

        $existingData = $this->model->find($id);
        if (!$existingData) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Dosen tidak ditemukan');
        }

        $validation = \Config\Services::validation();
        $validation->setRules([
            'nama' => [
                'rules' => 'required|min_length[3]',
                'errors' => [
                    'required' => 'Nama wajib diisi.',
                    'min_length' => 'Nama harus memiliki panjang minimal 3 karakter.'
                ]
            ],
            'nomer_pegawai' => [
                'rules' => 'required|exact_length[10]|is_unique[dosen.nomer_pegawai,id,' . $id . ']',
                'errors' => [
                    'required' => 'Nomer Pegawai wajib diisi.',
                    'exact_length' => 'Nomer Pegawai harus terdiri dari tepat 10 digit.',
                    'is_unique' => 'Nomer Pegawai sudah terdaftar.'
                ]
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
                'description' => $errorList,
                'type' => 'danger'
            ]);

            return redirect()->to('/dashboard/dosen/edit/' . $id)->withInput();
        }

        $data = [
            'nama' => $this->request->getPost('nama'),
            'nomer_pegawai' => $this->request->getPost('nomer_pegawai'),
            'program_studi_id' => $this->request->getPost('program_studi_id'),
        ];

        $this->model->update($id, $data);

        $session->setFlashdata('message', [
            'title' => 'Success',
            'description' => 'Data berhasil di edit.',
            'type' => 'success'
        ]);

        return redirect()->to('/dashboard/dosen');
    }

    public function delete($id)
    {
        try {
            $existingData = $this->model->find($id);
            if (!$existingData) {
                return $this->response->setJSON([
                    'code' => 404,
                    'message' => [
                        'title' => 'Not Found',
                        'description' => 'Dosen tidak ditemukan',
                        'type' => 'error'
                    ],
                    'data' => null
                ])->setStatusCode(404);
            }

            $this->model->delete($id);

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
