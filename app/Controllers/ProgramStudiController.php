<?php namespace App\Controllers;

use App\Models\ProgramStudiModel;

class ProgramStudiController extends BaseController
{
    private $model;

    public function __construct()
    {
        $this->model = new ProgramStudiModel();
    }

    public function index()
    {
        $data['programStudi'] = $this->model->findAll();
        return view('dashboard/program-studi/index', $data);
    }

    public function create()
    {
        return view('dashboard/program-studi/create');
    }

    public function store()
    {
        $session = session();

        $validation = \Config\Services::validation();
        $validation->setRules([
            'nama' => [
                'label' => 'Nama',
                'rules' => 'required|min_length[3]',
                'errors' => [
                    'required' => 'Nama wajib diisi.',
                    'min_length' => 'Nama harus memiliki panjang minimal 3 karakter.'
                ]
            ],
            'kode' => [
                'label' => 'Kode',
                'rules' => 'required|min_length[2]|is_unique[program_studi.kode]',
                'errors' => [
                    'required' => 'Kode wajib diisi.',
                    'min_length' => 'Kode harus memiliki panjang minimal 2 karakter.',
                    'is_unique' => 'Kode sudah terdaftar.'
                ]
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
    
            return redirect()->to('/dashboard/program-studi/create')->withInput();
        }

        $data = [
            'nama' => $this->request->getPost('nama'),
            'kode' => $this->request->getPost('kode'),
        ];

        $this->model->save($data);

        $session->setFlashdata('message', [
            'title' => 'Success',
            'description' => 'Data berhasil ditambahkan.',
            'type' => 'success'
        ]);

        return redirect()->to('/dashboard/program-studi');
    }

    public function edit($id)
    {
        $existingData = $this->model->find($id);
        if (!$existingData) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Program Studi tidak ditemukan');
        }
        $data['programStudi'] = $existingData;
        return view('dashboard/program-studi/edit', $data);
    }

    public function update($id)
    {
        $session = session();

        $existingData = $this->model->find($id);
        if (!$existingData) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Program Studi tidak ditemukan');
        }

        $validation = \Config\Services::validation();
        $validation->setRules([
            'nama' => [
                'label' => 'Nama',
                'rules' => 'required|min_length[3]',
                'errors' => [
                    'required' => 'Nama wajib diisi.',
                    'min_length' => 'Nama harus memiliki panjang minimal 3 karakter.'
                ]
            ],
            'kode' => [
                'label' => 'Kode',
                'rules' => 'required|min_length[2]|is_unique[program_studi.kode,id,' . $id . ']',
                'errors' => [
                    'required' => 'Kode wajib diisi.',
                    'min_length' => 'Kode harus memiliki panjang minimal 2 karakter.',
                    'is_unique' => 'Kode sudah terdaftar.'
                ]
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

            return redirect()->to('/dashboard/program-studi/edit/' . $id)->withInput();
        }

        $data = [
            'nama' => $this->request->getPost('nama'),
            'kode' => $this->request->getPost('kode'),
        ];

        $this->model->update($id, $data);

        $session->setFlashdata('message', [
            'title' => 'Success',
            'description' => 'Data berhasil diedit.',
            'type' => 'success'
        ]);

        return redirect()->to('/dashboard/program-studi');
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
                        'description' => 'Program Studi tidak ditemukan',
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
