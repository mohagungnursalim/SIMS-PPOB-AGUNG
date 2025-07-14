<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use Config\Services;

class Auth extends Controller
{
    protected $session;
    protected $client;

    public function __construct()
    {
        $this->session = session();
        $this->client = Services::curlrequest();
    }

    public function showLogin()
    {
        return view('auth/login',[
            'title' => 'Login | HIS PPOB-MOH. AGUNG NURSALIM'
        ]);
    }

    public function login()
    {
        // Ambil semua input POST dari form login
        $data = $this->request->getPost();
    
        // Validasi data input
        if (!$this->validate([
            'email'    => 'required|valid_email',
            'password' => 'required|min_length[8]',
        ],[
            'email' => [
                'required'    => 'Email wajib diisi.',
                'valid_email' => 'Format email tidak valid.',
            ],
            'password' => [
                'required'   => 'Password wajib diisi.',
                'min_length' => 'Password minimal 8 karakter.',
            ],
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
    
        try {
            // Kirim request ke API login
            $response = $this->client->post('https://take-home-test-api.nutech-integrasi.com/login', [
                'headers' => ['Accept' => 'application/json'],
                'json' => [
                    'email'    => $data['email'],
                    'password' => $data['password'],
                ],
                'http_errors' => false // Agar error response tidak dilempar sebagai exception
            ]);
    
            $result = json_decode($response->getBody(), true);
            $httpStatus = $response->getStatusCode();
    
            // Cek jika login sukses
            if ($httpStatus === 200 && $result['status'] === 0) {
                // Simpan token ke session
                $this->session->set('token', $result['data']['token']);
                $this->session->set('email', $data['email']);
    
                return redirect()->to('/')->with('success', 'Login berhasil!');
            }
    
            // Tampilkan pesan error dari API atau fallback "Login gagal"
            return redirect()->back()->withInput()->with('error', $result['message'] ?? 'Login gagal.');
    
        } catch (\Exception $e) {
            // Jika terjadi exception selain error dari API misal koneksi error, tampilkan pesan error
            return redirect()->back()->withInput()->with('error', 'Kesalahan server: ' . $e->getMessage());
        }
    }
    


    public function showRegister()
    {
        return view('auth/register',[
            'title' => 'Register | HIS PPOB-MOH. AGUNG NURSALIM'
        ]);
    }

    public function register()
    {
        // Ambil semua input POST dari form registrasi
        $data = $this->request->getPost();

        if (!$this->validate([
            'first_name' => 'required',
            'last_name'  => 'required',
            'email'      => 'required|valid_email',
            'password'   => 'required|min_length[8]',
            'confirm'    => 'required|matches[password]',
        ], [
            'first_name' => [
                'required' => 'Nama depan wajib diisi.',
            ],
            'last_name' => [
                'required' => 'Nama belakang wajib diisi.',
            ],
            'email' => [
                'required'    => 'Email wajib diisi.',
                'valid_email' => 'Format email tidak valid.',
            ],
            'password' => [
                'required'   => 'Password wajib diisi.',
                'min_length' => 'Password minimal 8 karakter.',
            ],
            'confirm' => [
                'required' => 'Konfirmasi password wajib diisi.',
                'matches'  => 'Konfirmasi password tidak cocok.',
            ],
        ])) {
            // Jika validasi gagal, kembalikan ke halaman sebelumnya dengan input dan error
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        

        try {
            // Kirim request ke endpoint API untuk melakukan registrasi user
            $response = $this->client->post('https://take-home-test-api.nutech-integrasi.com/registration', [
                'form_params' => [ // Format data yang dikirim ke API
                    'email'      => $data['email'],
                    'first_name' => $data['first_name'],
                    'last_name'  => $data['last_name'],
                    'password'   => $data['password'],
                ],
                'http_errors' => false // Agar error response tidak dilempar sebagai exception
            ]);

            // Decode response JSON dari API jadi array PHP
            $result = json_decode($response->getBody(), true);

            // Ambil status HTTP-nya (200 = OK, 400 = Bad Request)
            $httpStatus = $response->getStatusCode();

            // Jika status HTTP-nya 200 dan status response = 0, artinya registrasi sukses
            if ($httpStatus === 200 && $result['status'] === 0) {
                // Arahkan user ke halaman login dengan pesan sukses
                return redirect()->to('/login')->with('success', $result['message']);
            }

            // Tampilkan pesan error dari API atau fallback "Registrasi gagal"
            return redirect()->back()->withInput()->with('error', $result['message'] ?? 'Registrasi gagal.');
            
        } catch (\Exception $e) {
            // Jika terjadi exception selain error dari API misal koneksi error, tampilkan pesan error
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan server: ' . $e->getMessage());
        }
    }


    public function logout()
    {
        $this->session->destroy();
        return redirect()->to('/login')->with('success', 'Kamu berhasil logout.');
    }


}
