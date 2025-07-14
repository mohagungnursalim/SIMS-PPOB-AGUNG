<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use Config\Services;

class Profile extends BaseController
{
    public function index()
    {
        $user = $this->getUserProfile();
        return view('profile/profile', [
            'title' => 'Profile | HIS PPOB-MOH. AGUNG NURSALIM',
            'description' => 'Halaman profil pengguna untuk mengelola informasi akun.',
            'user' => $user ?: [],
        ]);
    }

    public function update()
    {
        $token = session('token');
        if (!$token) return redirect()->to('/login');

        $firstName = $this->request->getPost('first_name');
        $lastName  = $this->request->getPost('last_name');

        $client = Services::curlrequest();
        $response = $client->request('POST', 'https://take-home-test-api.nutech-integrasi.com/profile/update', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
            'body' => json_encode([
                'first_name' => $firstName,
                'last_name'  => $lastName,
            ]),
        ]);

        $result = json_decode($response->getBody(), true);
        if ($result['status'] === 0) {
            return redirect()->back()->with('success', $result['message']);
        }

        return redirect()->back()->with('error', $result['message'] ?? 'Gagal mengupdate profil.');
    }

    public function updateImage()
    {
        $token = session('token');
        if (!$token) return redirect()->to('/login');

        $file = $this->request->getFile('profile_image');
        if (!$file->isValid() || $file->getSize() > 100 * 1024) {
            return redirect()->back()->with('error', 'Ukuran gambar harus kurang dari 100KB dan valid.');
        }

        $base64Image = base64_encode(file_get_contents($file->getTempName()));

        $client = Services::curlrequest();
        $response = $client->request('POST', 'https://take-home-test-api.nutech-integrasi.com/profile/image', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
            'body' => json_encode([
                'file' => $base64Image
            ])
        ]);

        $result = json_decode($response->getBody(), true);
        if ($result['status'] === 0) {
            return redirect()->back()->with('success', $result['message']);
        }

        return redirect()->back()->with('error', $result['message'] ?? 'Gagal upload gambar.');
    }

    private function getUserProfile()
    {
        $token = session('token'); // Ambil token dari session

        if (!$token) {
            return null;
        }

        try {
            $client = \Config\Services::curlrequest();

            $response = $client->get('https://take-home-test-api.nutech-integrasi.com/profile', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Accept'        => 'application/json',
                ],
                'http_errors' => false
            ]);

            $body = json_decode($response->getBody(), true);

            if ($response->getStatusCode() === 200 && $body['status'] === 0) {
                return $body['data'];
            }

        } catch (\Exception $e) {
            log_message('error', 'Gagal ambil profil user: ' . $e->getMessage());
        }

        return null;
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }

}
