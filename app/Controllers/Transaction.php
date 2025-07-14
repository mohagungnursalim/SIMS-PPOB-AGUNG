<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;

class Transaction extends BaseController
{
    protected $client;

    public function __construct()
    {
        $this->client = \Config\Services::curlrequest();
    }

    public function index()
    {
        // Halaman view awal
        return view('transactions/transactions', [
            'title' => 'Semua Transaksi | HIS PPOB-MOH. AGUNG NURSALIM',
            'user' => $this->getUserProfile(),
            'balance' => $this->getUserBalance(),
        ]);
    }
    
    public function loadMore()
    {
        $token = session('token');
        if (!$token) {
            return $this->response->setStatusCode(401)->setJSON(['success' => false, 'message' => 'Sesi tidak valid.']);
        }
    
        $limit = 5;
        $offset = (int) $this->request->getGet('offset');
        $month = $this->request->getGet('month');
    
        try {
            $response = Services::curlrequest()->get('https://take-home-test-api.nutech-integrasi.com/transaction/history', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Accept'        => 'application/json',
                ],
                'query' => [
                    'limit' => $limit,
                    'offset' => $offset,
                ],
                'http_errors' => false,
            ]);
    
            $body = json_decode($response->getBody(), true);
            $records = $body['data']['records'] ?? [];
    
            // Filter dan group by bulan
            $grouped = [];
            foreach ($records as $tx) {
                if ($month && date('m', strtotime($tx['created_on'])) !== $month) {
                    continue;
                }
    
                $bulan = date('F Y', strtotime($tx['created_on']));
                $grouped[$bulan][] = $tx;
            }
    
            return $this->response->setJSON([
                'success' => true,
                'data' => $grouped,
                'nextOffset' => $offset + $limit,
                'hasMore' => count($records) === $limit,
            ]);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ]);
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