<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\ActivityLogModel; // <-- 1. Tambahkan use model log di bagian atas (jika belum ada)

class Auth extends BaseController
{
    public function login()
    {
        // Jika sudah login, arahkan langsung ke halaman utama/admin
        if (session()->get('logged_in')) {
            return redirect()->to('/admin/services');
        }

        return view('auth/login');
    }

    public function process()
    {
        $session = session();
        $userModel = new UserModel();
        $logModel  = new ActivityLogModel(); // <-- Inisialisasi model log

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $user = $userModel->where('username', $username)->first();

        if ($user) {
            // Cek status aktif
            if ($user['is_active'] == 1) {
                if ($password == $user['password'] || password_verify($password, $user['password'])) {
                    $sesData = [
                        'id'        => $user['id'],
                        'name'      => $user['name'],
                        'username'  => $user['username'],
                        'role'      => $user['role'],
                        'logged_in' => TRUE
                    ];
                    $session->set($sesData);

                    // --- 2. CATAT LOG AKTIVITAS LOGIN BERHASIL ---
                    $logModel->save([
                        'user_id'    => $user['id'],
                        'activity'   => 'Melakukan login ke sistem',
                        'ip_address' => $this->request->getIPAddress()
                    ]);
                    // ----------------------------------------------

                    // Arahkan berdasarkan role
                    if ($user['role'] == 'admin') {
                        return redirect()->to('/admin/services');
                    } else {
                        return redirect()->to('/'); // Kasir
                    }
                } else {
                    $session->setFlashdata('error', 'Password salah!');
                    return redirect()->to('/login');
                }
            } else {
                $session->setFlashdata('error', 'Akun Anda telah dinonaktifkan.');
                return redirect()->to('/login');
            }
        } else {
            $session->setFlashdata('error', 'Username tidak ditemukan.');
            return redirect()->to('/login');
        }
    }

    public function logout()
    {
        // (Opsional) Anda juga bisa mencatat log logout di sini sebelum session dihancurkan
        
        // Hancurkan session
        session()->destroy();

        // Redirect ke halaman login
        return redirect()->to('/login')->with('success', 'Anda telah berhasil logout.');
    }
}