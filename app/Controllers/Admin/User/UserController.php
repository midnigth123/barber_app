<?php

namespace App\Controllers\Admin\User;

use App\Controllers\BaseController;
use App\Models\UserModel;

class UserController extends BaseController
{
    public function index()
    {
        $userModel = new UserModel();
        // Ambil semua data user (baik aktif maupun nonaktif)
        $data['users'] = $userModel->findAll(); 
        
        return view('admin/users/users', $data);
    }

    public function store()
    {
        $userModel = new UserModel();
        
        $userModel->save([
            'name'      => $this->request->getPost('name'),
            'username'  => $this->request->getPost('username'),
            'email'     => $this->request->getPost('email'),
            'role'      => $this->request->getPost('role'),
            'password'  => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'is_active' => 1
        ]);

        // Mengarahkan kembali dengan flashdata 'success'
        return redirect()->to('admin/users')->with('success', 'User baru berhasil ditambahkan!');
    }
    public function delete($id)
    {
        $userModel = new UserModel();
        $user = $userModel->find($id);

        if ($user) {
            // Jika saat ini nonaktif (0), ubah jadi aktif (1). Jika aktif (1), ubah jadi nonaktif (0).
            $newStatus = ($user['is_active'] == 0) ? 1 : 0;
            
            $userModel->update($id, ['is_active' => $newStatus]);

            $pesan = ($newStatus == 1) ? 'User berhasil diaktifkan kembali!' : 'Status user berhasil dinonaktifkan.';
            return redirect()->to('admin/users')->with('success', $pesan);
        }

        return redirect()->to('admin/users')->with('error', 'User tidak ditemukan.');
    }
    public function update($id)
    {
        $userModel = new UserModel();
        
        $data = [
            'name'      => $this->request->getPost('name'),
            'username'  => $this->request->getPost('username'),
            'email'     => $this->request->getPost('email'),
            'role'      => $this->request->getPost('role'),
        ];

        // Jika password diisi, perbarui passwordnya
        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $userModel->update($id, $data);

        return redirect()->to('admin/users')->with('success', 'Data user berhasil diperbarui!');
    }
    public function toggleStatus($id)
    {
        $userModel = new UserModel();
        $user = $userModel->find($id);

        if ($user) {
            // Balikkan status aktif/nonaktif
            $newStatus = ($user['is_active'] == 1) ? 0 : 1;
            $msg = ($newStatus == 1) ? 'User berhasil diaktifkan kembali.' : 'Status user berhasil dinonaktifkan.';

            $userModel->update($id, ['is_active' => $newStatus]);
            return redirect()->to('admin/users')->with('success', $msg);
        }

        return redirect()->to('admin/users')->with('error', 'User tidak ditemukan.');
    }
}