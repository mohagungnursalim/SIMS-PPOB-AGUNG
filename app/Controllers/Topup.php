<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use Config\Services;

class Topup extends BaseController
{
    protected $session;
    protected $client;

    public function __construct()
    {
        $this->session = session();
        $this->client = Services::curlrequest();
    }

    public function showForm()
    {
        return view('topup/form', [
            'title' => 'Top Up | HIS PPOB-MOH. AGUNG NURSALIM',
            'user' => $this->getUserProfile(),
            'balance' => $this->getUserBalance(),
        ]);
    }

    public function process()
    {
        $amount = $this->request->getPost('top_up_amount');

        // Validasi input
        if (!is_numeric($amount) || $amount <= 0) {
            return redirect()->back()->withInput()->with('error', 'Jumlah top up harus berupa angka dan lebih dari 0');
        }

        $token = $this->session->get('token');

        if (!$token) {
            return redirect()->to('/login')->with('error', 'Sesi tidak valid. Silakan login ulang.');
        }

        try {
            $response = $this->client->post('https://take-home-test-api.nutech-integrasi.com/topup', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Accept'        => 'application/json',
                    'Content-Type'  => 'application/json',
                ],
                'json' => [
                    'top_up_amount' => (int) $amount
                ],
                'http_errors' => false
            ]);

            $body = json_decode($response->getBody(), true);

            if ($response->getStatusCode() === 200 && $body['status'] === 0) {
                 // Jika transaksi berhasil,kirim flashdata sukses
                 session()->setFlashdata('success', number_format($body['data']['balance'], 0, ',', '.') ?? 'Transaksi berhasil!');
                 return redirect()->back();
              
            }

            return redirect()->back()->withInput()->with('error', $body['message'] ?? 'Gagal melakukan top up.');

        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan server: ' . $e->getMessage());
        }

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
}
