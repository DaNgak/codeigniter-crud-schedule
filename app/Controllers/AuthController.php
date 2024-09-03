<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\UserModel;

class AuthController extends Controller
{
    public function login()
    {
        $data['title'] = "Login";
        return view('auth/login', $data);
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
            $errorList = '<ul>';
            foreach ($errors as $error) {
                $errorList .= '<li>' . esc($error) . '</li>';
            }
            $errorList .= '</ul>';
            $session->setFlashdata('message', [
                'type'  => 'danger',
                'title' => 'Error Validation',
                'description'  => $errorList
            ]);
            return redirect()->to('/login')->withInput();
        }
        
        // Ambil data input
        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');
        $data = $model->where('email', $email)->first();
    
        if ($data && password_verify($password, $data['password'])) {
            $ses_data = [
                'id'        => $data['id'],
                'email'     => $data['email'],
                'isLoggedIn' => true
            ];
            $session->set($ses_data);
            $session->setFlashdata('message', [
                'type'  => 'success',
                'title' => 'Login Successful!',
                'description'  => 'Welcome back, ' . $data['email']
            ]);
            return redirect()->to('/dashboard');
        } else {
            $session->setFlashdata('message', [
                'type'  => 'danger',
                'title' => 'Login Failed!',
                'description'  => 'Kredential not found'
            ]);
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