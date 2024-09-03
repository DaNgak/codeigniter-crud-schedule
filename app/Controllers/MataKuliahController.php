<?php namespace App\Controllers;

use App\Models\MataKuliahModel;

class MataKuliahController extends BaseController
{
    private $model;

    public function __construct()
    {
        $this->model = new MataKuliahModel();
    }

    public function index()
    {
        $data['mata_kuliah'] = $this->model->findAll();
        return view('dashboard/mata-kuliah/index', $data);
    }

    public function create()
    {
        return view('dashboard/mata-kuliah/create');
    }

    public function store()
    {
        $session = session();

        // Validasi input
        $validation = \Config\Services::validation();
        $validation->setRules([
            'nama'       => 'required|min_length[3]',
            'kode'       => 'required|min_length[3]|is_unique[mata_kuliah.kode]',
            'deskripsi'  => 'permit_empty'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            $errors = $validation->getErrors();
            $session->setFlashdata('message', 'Validation error: ' . implode(', ', $errors));
            return redirect()->to('/dashboard/mata-kuliah/create')->withInput();
        }

        $data = [
            'nama'       => $this->request->getPost('nama'),
            'kode'       => $this->request->getPost('kode'),
            'deskripsi'  => $this->request->getPost('deskripsi'),
        ];

        $this->model->save($data);
        $session->setFlashdata('message', 'Data berhasil ditambahkan');
        return redirect()->to('/dashboard/mata-kuliah');
    }

    public function edit($id)
    {
        $data['mata_kuliah'] = $this->model->find($id);
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
            'deskripsi'  => [
                'rules' => 'permit_empty',
                'errors' => [
                    'permit_empty' => 'Deskripsi tidak wajib diisi.'
                ],
            ]
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            $errors = $validation->getErrors();
            $session->setFlashdata('message', 'Validation error: ' . implode(', ', $errors));
            return redirect()->to('/dashboard/mata-kuliah/edit/' . $id)->withInput();
        }

        // Ambil data input
        $data = [
            'nama'       => $this->request->getPost('nama'),
            'kode'       => $this->request->getPost('kode'),
            'deskripsi'  => $this->request->getPost('deskripsi'),
        ];

        // return var_dump($data);

        // Perbarui data
        $this->model->update($id, $data);
        $session->setFlashdata('message', 'Data berhasil di edit');
        return redirect()->to('/dashboard/mata-kuliah');
    }

    public function delete($id)
    {
        $this->model->delete($id);
        return redirect()->to('/dashboard/mata-kuliah');
    }
}