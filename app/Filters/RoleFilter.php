<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        $userRole = $session->get('role'); // Ambil role dari session saat login

        // Jika belum login, lempar ke halaman login
        if (!$session->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Jika admin, bebaskan akses apa saja
        if ($userRole === 'admin') {
            return;
        }

        // Definisikan batasan akses berdasarkan segment URL
        $uri = service('uri');
        $segment = $uri->getSegment(2); // contoh: /admin/services -> segment 2 adalah 'services'

        // Batasan untuk Kasir (Hanya boleh akses POS)
        if ($userRole === 'kasir') {
            $allowedForCashier = ['pos', 'dashboard'];
            if (!in_array($segment, $allowedForCashier)) {
                return redirect()->to('/admin/dashboard')->with('error', 'Anda tidak memiliki hak akses ke menu tersebut!');
            }
        }

        // Batasan untuk Barber
        if ($userRole === 'barber') {
            $allowedForBarber = ['dashboard', 'reports'];
            if (!in_array($segment, $allowedForBarber)) {
                return redirect()->to('/admin/dashboard')->with('error', 'Akses ditolak untuk akun Barber.');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here if needed
    }
}