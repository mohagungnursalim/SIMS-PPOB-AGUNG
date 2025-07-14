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

        $payload = json_encode([
            'first_name' => $firstName,
            'last_name'  => $lastName
        ]);

        $response = $client->put('https://take-home-test-api.nutech-integrasi.com/profile/update', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Accept'        => 'application/json',
                'Content-Type'  => 'application/json'
            ],
            'body' => $payload,
            'http_errors' => false
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

        $tmpFilePath = $file->getTempName();
        $fileName = $file->getName();
        $mimeType = $file->getMimeType();

        $curlFile = new \CURLFile($tmpFilePath, $mimeType, $fileName);

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => 'https://take-home-test-api.nutech-integrasi.com/profile/image',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'PUT',
            CURLOPT_POSTFIELDS => ['file' => $curlFile],
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $token,
                'Accept: application/json'
            ],
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            return redirect()->back()->with('error', 'Curl error: ' . $error);
        }

        $result = json_decode($response, true);

        if ($httpCode === 200 && $result['status'] === 0) {
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
