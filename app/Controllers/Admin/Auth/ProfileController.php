<?php

namespace App\Controllers\Admin\Auth;

use App\Controllers\BaseController;

class ProfileController extends BaseController
{
    public function updateProfile()
    {
        $name     = $this->request->getPost('name');
        $username = $this->request->getPost('username');

        $userId = session()->get('id');
        if (!$userId) {
            return redirect()->to('/login');
        }

        $db = \Config\Database::connect();
        $db->table('users')->where('id', $userId)->update([
            'name'       => $name,
            'username'   => $username,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        session()->set([
            'name'     => $name,
            'username' => $username
        ]);

        // Berikan flashdata sukses profil
        return redirect()->back()->with('success_profile', 'Profil berhasil diperbarui!');
    }

    public function updatePassword()
    {
        $oldPassword     = $this->request->getPost('old_password');
        $newPassword     = $this->request->getPost('new_password');
        $confirmPassword = $this->request->getPost('confirm_password');

        if ($newPassword !== $confirmPassword) {
            return redirect()->back()->with('error', 'Konfirmasi password baru tidak cocok.');
        }

        $userId = session()->get('id');
        if (!$userId) {
            return redirect()->to('/login');
        }

        $db = \Config\Database::connect();
        $builder = $db->table('users');
        $user = $builder->where('id', $userId)->get()->getRow();

        if (!$user) {
            return redirect()->back()->with('error', 'Data pengguna tidak ditemukan.');
        }

        $isPasswordCorrect = false;
        if (password_get_info($user->password)['algo']) {
            $isPasswordCorrect = password_verify($oldPassword, $user->password);
        } else {
            $isPasswordCorrect = ($oldPassword === $user->password);
        }

        if (!$isPasswordCorrect) {
            return redirect()->back()->with('error', 'Password lama yang Anda masukkan salah.');
        }

        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        $builder->where('id', $userId)->update([
            'password'   => $hashedPassword,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        // Berikan flashdata sukses password
        return redirect()->back()->with('success_password', 'Kata sandi berhasil diperbarui dan dienkripsi!');
    }
}