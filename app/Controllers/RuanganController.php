<?php namespace App\Controllers;

use App\Models\ProgramStudiModel;
use App\Models\RuanganModel;

class RuanganController extends BaseController
{
    private $model, $programStudiModel;

    public function __construct()
    {
        $this->model = new RuanganModel();
        $this->programStudiModel = new ProgramStudiModel();
    }

    public function index()
    {
        $data['ruangan'] = $this->model->findAllWithProgramStudi();
        return view('dashboard/ruangan/index', $data);
    }

    public function create()
    {
        $data['programStudi'] = $this->programStudiModel->findAll();
        return view('dashboard/ruangan/create', $data);
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
                    'required'    => 'Nama ruangan harus diisi.',
                    'min_length'  => 'Nama ruangan harus terdiri dari minimal {param} karakter.'
                ],
            ],
            'kode'       => [
                'rules' => 'required|min_length[3]|is_unique[ruangan.kode]',
                'errors' => [
                    'required'    => 'Kode ruangan harus diisi.',
                    'min_length'  => 'Kode ruangan harus terdiri dari minimal {param} karakter.',
                    'is_unique'   => 'Kode sudah digunakan, silakan gunakan kode lainnya.'
                ],
            ],
            'keterangan' => [
                'rules' => 'permit_empty|min_length[3]',
                'errors' => [
                    'min_length' => 'Keterangan harus terdiri dari minimal {param} karakter.'
                ],
            ],
            'kapasitas'  => [
                'rules' => 'required|integer|greater_than_equal_to[10]|less_than_equal_to[50]',
                'errors' => [
                    'required' => 'Kapasitas ruangan harus diisi.',
                    'integer'  => 'Kapasitas ruangan harus berupa angka.',
                    'greater_than_equal_to' => 'Kapasitas ruangan minimal 10.',
                    'less_than_equal_to' => 'Kapasitas ruangan maksimal 50.'
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

            return redirect()->to('/dashboard/ruangan/create')->withInput();
        }

        $data = [
            'nama'       => $this->request->getPost('nama'),
            'kode'       => $this->request->getPost('kode'),
            'keterangan' => $this->request->getPost('keterangan'),
            'kapasitas'  => $this->request->getPost('kapasitas'),
            'program_studi_id'  => $this->request->getPost('program_studi_id'),
        ];

        $this->model->save($data);

        $session->setFlashdata('message', [
            'title' => 'Success',
            'description'  => 'Data berhasil ditambahkan.',
            'type'  => 'success'
        ]);

        return redirect()->to('/dashboard/ruangan');
    }

    public function edit($id)
    {
        $existingData = $this->model->find($id);
        if (!$existingData) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Ruangan tidak ditemukan');
        }
        $data['ruangan'] = $existingData;
        $data['programStudi'] = $this->programStudiModel->findAll();
        return view('dashboard/ruangan/edit', $data);
    }

    public function update($id)
    {
        $session = session();

        $existingData = $this->model->find($id);
        if (!$existingData) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Ruangan tidak ditemukan');
        }

        $validation = \Config\Services::validation();
        $validation->setRules([
            'nama'       => [
                'rules' => 'required|min_length[3]',
                'errors' => [
                    'required'    => 'Nama ruangan harus diisi.',
                    'min_length'  => 'Nama ruangan harus terdiri dari minimal {param} karakter.'
                ],
            ],
            'kode'       => [
                'rules' => 'required|min_length[3]|is_unique[ruangan.kode,id,' . $id . ']',
                'errors' => [
                    'required'    => 'Kode ruangan harus diisi.',
                    'min_length'  => 'Kode ruangan harus terdiri dari minimal {param} karakter.',
                    'is_unique'   => 'Kode sudah digunakan, silakan gunakan kode lainnya.'
                ],
            ],
            'keterangan' => [
                'rules' => 'permit_empty|min_length[3]',
                'errors' => [
                    'min_length' => 'Keterangan harus terdiri dari minimal {param} karakter.'
                ],
            ],
            'kapasitas'  => [
                'rules' => 'required|integer|greater_than_equal_to[10]|less_than_equal_to[50]',
                'errors' => [
                    'required' => 'Kapasitas ruangan harus diisi.',
                    'integer'  => 'Kapasitas ruangan harus berupa angka.',
                    'greater_than_equal_to' => 'Kapasitas ruangan minimal 10.',
                    'less_than_equal_to' => 'Kapasitas ruangan maksimal 50.'
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

            return redirect()->to('/dashboard/ruangan/edit/' . $id)->withInput();
        }

        $data = [
            'nama'       => $this->request->getPost('nama'),
            'kode'       => $this->request->getPost('kode'),
            'keterangan' => $this->request->getPost('keterangan'),
            'kapasitas'  => $this->request->getPost('kapasitas'),
            'program_studi_id'  => $this->request->getPost('program_studi_id'),
        ];

        $this->model->update($id, $data);

        $session->setFlashdata('message', [
            'title' => 'Success',
            'description'  => 'Data berhasil di edit.',
            'type'  => 'success'
        ]);

        return redirect()->to('/dashboard/ruangan');
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
                        'description' => 'Ruangan tidak ditemukan',
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
