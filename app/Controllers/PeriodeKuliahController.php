<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PeriodeKuliahModel;

class PeriodeKuliahController extends BaseController
{
    private $periodeKuliahModel;

    public function __construct()
    {
        $this->periodeKuliahModel = new PeriodeKuliahModel();
    }

    public function index()
    {
        $data['periodeKuliah'] = $this->periodeKuliahModel->findAll();
        return view('dashboard/periode-kuliah/index', $data);
    }

    public function create()
    {
        return view('dashboard/periode-kuliah/create');
    }

    public function store()
    {
        $session = session();
        // return var_dump([
        //     'tahunAwal' => $this->request->getPost('tahun_awal'),
        //     'tahunAkhir' => $this->request->getPost('tahun_akhir'),
        //     'semester' => $this->request->getPost('semester'),
        // ]);
        // Validasi input
        $validation = \Config\Services::validation();
        $validation->setRules([
            'tahun_awal' => [
                'rules' => 'required|integer',
                'errors' => [
                    'required' => 'Tahun awal harus diisi.',
                    'integer'  => 'Tahun awal harus berupa angka.',
                ],
            ],
            'tahun_akhir' => [
                'rules' => 'required|integer',
                'errors' => [
                    'required' => 'Tahun akhir harus diisi.',
                    'integer'  => 'Tahun akhir harus berupa angka.',
                ],
            ],
            'semester' => [
                'rules' => 'required|in_list[Ganjil,Genap]',
                'errors' => [
                    'required' => 'Semester harus dipilih.',
                    'in_list'  => 'Semester harus salah satu dari Ganjil atau Genap.',
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

            return redirect()->to('/dashboard/periode-kuliah/create')->withInput();
        }

        $tahunAwal = $this->request->getPost('tahun_awal');
        $tahunAkhir = $this->request->getPost('tahun_akhir');
        $semester = $this->request->getPost('semester');
    
        // Manual validation for tahun_akhir
        if ($tahunAkhir <= $tahunAwal) {
            $session->setFlashdata('message', [
                'title' => 'Validation Error',
                'description' => '<ul><li>Tahun akhir harus lebih besar dari tahun awal.</li></ul>',
                'type' => 'danger'
            ]);

            return redirect()->to('/dashboard/periode-kuliah/create')->withInput();
        }

        // Check if tahun_akhir is exactly one year greater than tahun_awal
        if ($tahunAkhir != $tahunAwal + 1) {
            $session->setFlashdata('message', [
                'title' => 'Validation Error',
                'description' => '<ul><li>Tahun akhir harus tepat satu tahun lebih besar dari tahun awal.</li></ul>',
                'type' => 'danger'
            ]);

            return redirect()->to('/dashboard/periode-kuliah/create')->withInput();
        }

        // Cek jika data sudah ada di database
        $existing = $this->periodeKuliahModel->where([
            'tahun_awal' => $tahunAwal,
            'tahun_akhir' => $tahunAkhir,
            'semester' => $semester,
        ])->first();
    
        if ($existing) {
            $errorList = '<ul>';
            $errorList .= '<li>Data ini sudah ada di database. Harap input data lainnya.</li>';
            $errorList .= '</ul>';
    
            $session->setFlashdata('message', [
                'title' => 'Validation Error',
                'description' => $errorList,
                'type' => 'danger'
            ]);
    
            return redirect()->to('/dashboard/periode-kuliah/create')->withInput();
        }

        $data = [
            'tahun_awal' => $tahunAwal,
            'tahun_akhir' => $tahunAkhir,
            'semester' => $semester,
        ];

        $this->periodeKuliahModel->save($data);

        $session->setFlashdata('message', [
            'title' => 'Success',
            'description' => 'Periode kuliah berhasil ditambahkan.',
            'type' => 'success'
        ]);

        return redirect()->to('/dashboard/periode-kuliah');
    }

    public function edit($id)
    {
        $existingData = $this->periodeKuliahModel->find($id);
        if (!$existingData) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Periode kuliah tidak ditemukan');
        }

        $data['periodeKuliah'] = $existingData;
        return view('dashboard/periode-kuliah/edit', $data);
    }

    public function update($id)
    {
        $session = session();

        // Ambil data yang ada
        $existingData = $this->periodeKuliahModel->find($id);
        if (!$existingData) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Periode kuliah tidak ditemukan');
        }

        // Validasi input
        $validation = \Config\Services::validation();
        $validation->setRules([
            'tahun_awal' => [
                'rules' => 'required|integer',
                'errors' => [
                    'required' => 'Tahun awal harus diisi.',
                    'integer'  => 'Tahun awal harus berupa angka.',
                ],
            ],
            'tahun_akhir' => [
                'rules' => 'required|integer',
                'errors' => [
                    'required' => 'Tahun akhir harus diisi.',
                    'integer'  => 'Tahun akhir harus berupa angka.',
                ],
            ],
            'semester' => [
                'rules' => 'required|in_list[Ganjil,Genap]',
                'errors' => [
                    'required' => 'Semester harus dipilih.',
                    'in_list'  => 'Semester harus salah satu dari Ganjil atau Genap.',
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

            return redirect()->to('/dashboard/periode-kuliah/edit/' . $id)->withInput();
        }

        $data = [
            'id' => $id,
            'tahun_awal' => $this->request->getPost('tahun_awal'),
            'tahun_akhir' => $this->request->getPost('tahun_akhir'),
            'semester' => $this->request->getPost('semester'),
        ];

        $this->periodeKuliahModel->save($data);

        $session->setFlashdata('message', [
            'title' => 'Success',
            'description' => 'Periode kuliah berhasil diperbarui.',
            'type' => 'success'
        ]);

        return redirect()->to('/dashboard/periode-kuliah');
    }

    public function delete($id)
    {
        try {
            $existingData = $this->periodeKuliahModel->find($id);
            if (!$existingData) {
                return $this->response->setJSON([
                    'code' => 404,
                    'message' => [
                        'title' => 'Not Found',
                        'description' => 'Periode kuliah tidak ditemukan',
                        'type' => 'error'
                    ],
                    'data' => null
                ])->setStatusCode(404);
            }

            $this->periodeKuliahModel->delete($id);

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

    // Callback method for custom validation
    public function valid_tahun_akhir(string $tahun_akhir): bool
    {
        $tahun_awal = $this->request->getPost('tahun_awal');
        if ((int) $tahun_akhir < (int) $tahun_awal) {
            return false; // Validation failed
        }
        return true; // Validation passed
    }
}
