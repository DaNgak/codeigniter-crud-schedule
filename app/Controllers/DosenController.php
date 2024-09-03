<?php namespace App\Controllers;

use App\Models\DosenModel;

class DosenController extends BaseController
{
    private $model;

    public function __construct()
    {
        $this->model = new DosenModel();
    }

    public function index()
    {
        $data['dosen'] = $this->model->findAll();
        return view('dashboard/dosen/index', $data);
    }

    public function create()
    {
        return view('dashboard/dosen/create');
    }

    public function store()
    {
        $session = session();

        $validation = \Config\Services::validation();
        $validation->setRules([
            'nama' => 'required|min_length[3]',
            'nomer_pegawai' => 'required|min_length[3]|is_unique[dosen.nomer_pegawai]',
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

            return redirect()->to('/dashboard/dosen/create')->withInput();
        }

        $data = [
            'nama' => $this->request->getPost('nama'),
            'nomer_pegawai' => $this->request->getPost('nomer_pegawai'),
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
        $data['dosen'] = $this->model->find($id);
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
            'nama' => 'required|min_length[3]',
            'nomer_pegawai' => 'required|min_length[3]|is_unique[dosen.nomer_pegawai,id,' . $id . ']',
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
                ]);
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
