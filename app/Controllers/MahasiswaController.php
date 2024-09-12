<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\KelasModel;
use App\Models\MahasiswaModel;
use App\Models\ProgramStudiModel;

class MahasiswaController extends BaseController
{
    private $mahasiswaModel, $programStudiModel, $kelasModel;

    public function __construct()
    {
        $this->mahasiswaModel = new MahasiswaModel();
        $this->programStudiModel = new ProgramStudiModel();
        $this->kelasModel = new KelasModel();
    }

    public function index()
    {
        $data['mahasiswa'] = $this->mahasiswaModel->findAllWithAllRelation();
        return view('dashboard/mahasiswa/index', $data);
    }

    public function create()
    {
        $data['programStudi'] = $this->programStudiModel->findAll();
        return view('dashboard/mahasiswa/create', $data);
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
            'nomer_identitas' => [
                'rules' => 'required|exact_length[10]|is_unique[mahasiswa.nomer_identitas]',
                'errors' => [
                    'required' => 'Nomer Identitas wajib diisi.',
                    'exact_length' => 'Nomer Identitas harus terdiri dari tepat 10 digit.',
                    'is_unique' => 'Nomer Identitas sudah terdaftar.'
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

            return redirect()->to('/dashboard/mahasiswa/create')->withInput();
        }

        $data = [
            'nama' => $this->request->getPost('nama'),
            'nomer_identitas' => $this->request->getPost('nomer_identitas'),
            'program_studi_id' => $this->request->getPost('program_studi_id'),
            'kelas_id' => $this->request->getPost('kelas_id'),
        ];

        $this->mahasiswaModel->save($data);

        $session->setFlashdata('message', [
            'title' => 'Success',
            'description' => 'Data mahasiswa berhasil ditambahkan.',
            'type' => 'success'
        ]);

        return redirect()->to('/dashboard/mahasiswa');
    }

    public function edit($id)
    {
        $existingData = $this->mahasiswaModel->find($id);
        if (!$existingData) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Mahasiswa tidak ditemukan');
        }

        $data['mahasiswa'] = $existingData;
        $data['programStudi'] = $this->programStudiModel->findAll();
        $data['kelas'] = $this->kelasModel->where('program_studi_id', $existingData['program_studi_id'])->findAll();

        return view('dashboard/mahasiswa/edit', $data);
    }

    public function update($id)
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
            'nomer_identitas' => [
                'rules' => 'required|exact_length[10]|is_unique[mahasiswa.nomer_identitas,id,' . $id . ']',
                'errors' => [
                    'required' => 'Nomer Identitas wajib diisi.',
                    'exact_length' => 'Nomer Identitas harus terdiri dari tepat 10 digit.',
                    'is_unique' => 'Nomer Identitas sudah terdaftar.'
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

            return redirect()->to('/dashboard/mahasiswa/edit/' . $id)->withInput();
        }

        $data = [
            'nama' => $this->request->getPost('nama'),
            'nomer_identitas' => $this->request->getPost('nomer_identitas'),
            'program_studi_id' => $this->request->getPost('program_studi_id'),
            'kelas_id' => $this->request->getPost('kelas_id'),
        ];

        $this->mahasiswaModel->update($id, $data);

        $session->setFlashdata('message', [
            'title' => 'Success',
            'description' => 'Data mahasiswa berhasil diperbarui.',
            'type' => 'success'
        ]);

        return redirect()->to('/dashboard/mahasiswa');
    }

    public function delete($id)
    {
        try {
            $existingData = $this->mahasiswaModel->find($id);
            if (!$existingData) {
                return $this->response->setJSON([
                    'code' => 404,
                    'message' => [
                        'title' => 'Not Found',
                        'description' => 'Mahasiswa tidak ditemukan',
                        'type' => 'error'
                    ],
                    'data' => null
                ])->setStatusCode(404);
            }

            $this->mahasiswaModel->delete($id);

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

    // Dropdown 
    public function getKelasByProgramStudi($programStudiId)
    {
        try {
            // Attempt to retrieve kelas data based on program_studi_id
            $kelas = $this->kelasModel->where('program_studi_id', $programStudiId)->findAll();
    
            // Return success response
            return $this->response->setJSON([
                'code' => 200,
                'message' => 'Success',
                'data' => $kelas
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
