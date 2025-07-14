<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AlreadyLoggedInFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Jika sudah ada token di session, redirect ke halaman profile
        if (session()->get('token')) {
            return redirect()->to('/profile');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        
    }
}
