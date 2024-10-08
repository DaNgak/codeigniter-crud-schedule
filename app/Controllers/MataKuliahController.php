<?php namespace App\Controllers;

use App\Models\MataKuliahModel;
use App\Models\ProgramStudiModel;

class MataKuliahController extends BaseController
{
    private $model, $programStudiModel;

    public function __construct()
    {
        $this->model = new MataKuliahModel();
        $this->programStudiModel = new ProgramStudiModel();
    }

    public function index()
    {
        $data['mataKuliah'] = $this->model->findAllWithProgramStudi();
        return view('dashboard/mata-kuliah/index', $data);
    }

    public function create()
    {
        $data['programStudi'] = $this->programStudiModel->findAll();
        return view('dashboard/mata-kuliah/create', $data);
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
                    'required'    => 'Nama mata kuliah harus diisi.',
                    'min_length'  => 'Nama mata kuliah harus terdiri dari minimal {param} karakter.'
                ],
            ],
            'kode'       => [
                'rules' => 'required|min_length[3]|is_unique[mata_kuliah.kode]',
                'errors' => [
                    'required'    => 'Kode mata kuliah harus diisi.',
                    'min_length'  => 'Kode mata kuliah harus terdiri dari minimal {param} karakter.',
                    'is_unique'   => 'Kode sudah digunakan, silahkan gunakan kode lainnya.'
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

            // Membuat error list dalam bentuk ul li
            $errorList = '<ul>';
            foreach ($errors as $error) {
                $errorList .= '<li>' . esc($error) . '</li>';
            }
            $errorList .= '</ul>';

            // Menyimpan pesan flash dengan title, data, dan type
            $session->setFlashdata('message', [
                'title' => 'Validation Error',
                'description'  => $errorList,
                'type'  => 'danger'
            ]);

            return redirect()->to('/dashboard/mata-kuliah/create')->withInput();
        }

        $data = [
            'nama'       => $this->request->getPost('nama'),
            'kode'       => $this->request->getPost('kode'),
            'program_studi_id'  => $this->request->getPost('program_studi_id'),
        ];

        $this->model->save($data);

        // Menyimpan pesan flash untuk sukses
        $session->setFlashdata('message', [
            'title' => 'Success',
            'description'  => 'Data berhasil ditambahkan.',
            'type'  => 'success'
        ]);

        return redirect()->to('/dashboard/mata-kuliah');
    }

    public function edit($id)
    {
        // Cek apakah ID ada dalam database
        $existingData = $this->model->find($id);
        if (!$existingData) {
            // Redirect ke halaman 404 jika data tidak ditemukan
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Mata kuliah tidak ditemukan');
        }
        $data['mataKuliah'] = $existingData;
        $data['programStudi'] = $this->programStudiModel->findAll();
        return view('dashboard/mata-kuliah/edit', $data);
    }

    public function update($id)
    {
        $session = session();

        // Cek apakah ID ada dalam database
        $existingData = $this->model->find($id);
        if (!$existingData) {
            // Redirect ke halaman 404 jika data tidak ditemukan
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Mata kuliah tidak ditemukan');
        }

        // Validasi input
        $validation = \Config\Services::validation();
        $validation->setRules([
            'nama'       => [
                'rules' => 'required|min_length[3]',
                'errors' => [
                    'required'    => 'Nama mata kuliah harus diisi.',
                    'min_length'  => 'Nama mata kuliah harus terdiri dari minimal {param} karakter.'
                ],
            ],
            'kode'       => [
                'rules' => 'required|min_length[3]|is_unique[mata_kuliah.kode,id,' . $id . ']',
                'errors' => [
                    'required'    => 'Kode mata kuliah harus diisi.',
                    'min_length'  => 'Kode mata kuliah harus terdiri dari minimal {param} karakter.',
                    'is_unique'   => 'Kode sudah digunakan, silahkan gunakan kode lainnya.'
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

            // Membuat error list dalam bentuk ul li
            $errorList = '<ul>';
            foreach ($errors as $error) {
                $errorList .= '<li>' . esc($error) . '</li>';
            }
            $errorList .= '</ul>';

            // Menyimpan pesan flash dengan title, data, dan type
            $session->setFlashdata('message', [
                'title' => 'Validation Error',
                'description'  => $errorList,
                'type'  => 'danger'
            ]);

            return redirect()->to('/dashboard/mata-kuliah/edit/' . $id)->withInput();
        }

        // Ambil data input
        $data = [
            'nama'       => $this->request->getPost('nama'),
            'kode'       => $this->request->getPost('kode'),
            'program_studi_id'  => $this->request->getPost('program_studi_id'),
        ];

        // Perbarui data
        $this->model->update($id, $data);

        // Menyimpan pesan flash untuk sukses
        $session->setFlashdata('message', [
            'title' => 'Success',
            'description'  => 'Data berhasil di edit.',
            'type'  => 'success'
        ]);

        return redirect()->to('/dashboard/mata-kuliah');
    }

    // public function delete($id)
    // {
    //     $this->model->delete($id);

    //     // Menyimpan pesan flash untuk sukses
    //     session()->setFlashdata('message', [
    //         'title' => 'Success',
    //         'description'  => 'Data berhasil dihapus.',
    //         'type'  => 'success'
    //     ]);

    //     return redirect()->to('/dashboard/mata-kuliah');
    // }

    public function delete($id)
    {
        try {
            // Cek apakah ID ada dalam database
            $existingData = $this->model->find($id);
            if (!$existingData) {
                return $this->response->setJSON([
                    'code' => 404,
                    'message' => [
                        'title' => 'Not Found',
                        'description' => 'Mata kuliah tidak ditemukan',
                        'type' => 'error'
                    ],
                    'data' => null
                ])->setStatusCode(404);
            }

            // Hapus data dari database
            $this->model->delete($id);

            // Mengembalikan respons sukses
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
            // Mengembalikan respons error jika terjadi exception
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
