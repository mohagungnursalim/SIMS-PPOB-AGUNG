<?php

namespace App\Controllers;

use CodeIgniter\HTTP\Client;
use Config\Services;

class Home extends BaseController
{

    public function index()
    {
        $user = $this->getUserProfile();
        $balance = $this->getUserBalance(); 
        $services = $this->getServices();

        $client = Services::curlrequest();

        // Kirim request Get ke API 
        $response = $client->get('https://take-home-test-api.nutech-integrasi.com/banner');

        // Parse response JSON
        $body = json_decode($response->getBody(), true);

        // Cek apakah status-nya sukses
        $banners = [];
        if ($response->getStatusCode() === 200 && $body['status'] === 0) {
            $banners = $body['data'];
        }

        // Kirim ke view
        return view('welcome/welcome_message', [
            'banners' => $banners,
            'user' => $user,
            'balance' => $balance,
            'services' => $services,
            'title' => 'Home | HIS PPOB-MOH. AGUNG NURSALIM',
            'description' => 'Selamat datang di aplikasi PPOB HIS, solusi pembayaran online yang mudah dan cepat.',
        ]);
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


    private function getUserBalance()
    {
        $token = session('token');

        if (!$token) {
            return null;
        }

        try {
            $client = Services::curlrequest();

            $response = $client->get('https://take-home-test-api.nutech-integrasi.com/balance', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Accept'        => 'application/json',
                ],
                'http_errors' => false
            ]);

            $body = json_decode($response->getBody(), true);

            // Handle token kadaluarsa
            if ($response->getStatusCode() === 401 || $body['status'] === 108) {
                session()->remove(['token', 'email']);
                return redirect()->to('/login')->with('error', 'Sesi Anda telah habis. Silakan login ulang.');
            }

            if ($response->getStatusCode() === 200 && $body['status'] === 0) {
                return $body['data']['balance'];
            }

        } catch (\Exception $e) {
            log_message('error', 'Gagal ambil saldo: ' . $e->getMessage());
        }

        return null;
    }

    private function getServices()
    {
        $token = session('token');

        if (!$token) {
            return []; // user belum login
        }

        try {
            $client = \Config\Services::curlrequest();

            $response = $client->get('https://take-home-test-api.nutech-integrasi.com/services', [
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
            log_message('error', 'Gagal ambil services: ' . $e->getMessage());
        }

        return [];
    }


}
