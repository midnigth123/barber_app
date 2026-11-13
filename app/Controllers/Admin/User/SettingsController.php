<?php

namespace App\Controllers\Admin\User;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\SettingModel;

class SettingsController extends BaseController
{
    protected $userModel;
    protected $settingModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->settingModel = new SettingModel();
    }

    // --- BAGIAN 1: PENGATURAN AKUN USER / ADMIN ---
    public function index()
    {
        $userId = session()->get('id') ?? 1;

        $data = [
            'title' => 'User Settings',
            'user'  => $this->userModel->find($userId)
        ];

        return view('admin/users/settings', $data);
    }

    public function update()
    {
        $userId = session()->get('id') ?? 1;

        $rules = [
            'name'     => 'required|min_length[3]',
            'username' => "required|min_length[3]|is_unique[users.username,id,{$userId}]",
        ];

        if ($this->request->getPost('password')) {
            $rules['password'] = 'required|min_length[6]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $updateData = [
            'name'     => $this->request->getPost('name'),
            'username' => $this->request->getPost('username'),
        ];

        if ($this->request->getPost('password')) {
            $updateData['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
        }

        $this->userModel->update($userId, $updateData);

        return redirect()->to(base_url('admin/users/settings'))->with('success', 'Pengaturan akun berhasil diperbarui!');
    }

    // --- BAGIAN 2: PENGATURAN TOKO, LOGO STRUK, & PPN ---
    public function shop()
    {
        $data = [
            'title'   => 'Pengaturan Toko & Struk',
            'setting' => $this->settingModel->find(1)
        ];

        return view('admin/users/settings', $data);
    }

    public function updateShop()
    {
        $setting = $this->settingModel->find(1);

        $data = [
            'shop_name'      => $this->request->getPost('shop_name'),
            'address'        => $this->request->getPost('address'),
            'phone'          => $this->request->getPost('phone'),
            'social_media'   => $this->request->getPost('social_media'),
            'receipt_footer' => $this->request->getPost('receipt_footer'),
            'ppn_percentage' => $this->request->getPost('ppn_percentage'),
        ];

        // Handle Upload Logo Struk / Toko
        $fileLogo = $this->request->getFile('logo');
        if ($fileLogo && $fileLogo->isValid() && !$fileLogo->hasMoved()) {
            $newName = $fileLogo->getRandomName();
            
            $uploadPath = FCPATH . 'uploads/logo';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            $fileLogo->move($uploadPath, $newName);
            
            if (!empty($setting['logo']) && file_exists($uploadPath . '/' . $setting['logo'])) {
                unlink($uploadPath . '/' . $setting['logo']);
            }
            
            $data['logo'] = $newName;
        }

        $this->settingModel->update(1, $data);

        return redirect()->to(base_url('admin/settings/shop'))->with('success', 'Pengaturan toko dan struk berhasil diperbarui!');
    }
}