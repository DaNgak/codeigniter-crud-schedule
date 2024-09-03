<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\UserModel;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth/login');
    }

    public function loginPost()
    {
        $session = session();
        $model = new UserModel();
    
        // Validasi input
        $validation = \Config\Services::validation();
        $validation->setRules([
            'email'    => 'required|valid_email',
            'password' => 'required|min_length[8]'
        ]);
    
        if (!$validation->withRequest($this->request)->run()) {
            // Jika validasi gagal
            $errors = $validation->getErrors();
            $session->setFlashdata('message', 'Validation error: ' . implode(', ', $errors));
            return redirect()->to('/login');
        }
        
        // Ambil data input
        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');
        $data = $model->where('email', $email)->first();

        if ($data && password_verify($password, $data['password'])) {
            $ses_data = [
                'id'        => $data['id'],
                'email'     => $data['email'],
                'logged_in' => TRUE
            ];
            $session->set($ses_data);
            return redirect()->to('/dashboard');
        } else {
            $session->setFlashdata('message', 'Kredential not found');
            return redirect()->to('/login');
        }
    }

    public function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to('/login');
    }
}