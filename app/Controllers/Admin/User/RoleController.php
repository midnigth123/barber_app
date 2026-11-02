<?php
namespace App\Controllers\Admin\User;

use App\Controllers\BaseController;
use App\Models\RoleModel;
use App\Models\UserModel;

class RoleController extends BaseController
{
    public function index()
    {
        $roleModel = new RoleModel();
        $userModel = new UserModel();

        $roles = $roleModel->findAll();

        // Hitung jumlah user per role secara dinamis
        foreach ($roles as &$role) {
            $role['users_count'] = $userModel->where('role', $role['name'])->countAllResults();
        }

        $data['roles'] = $roles;

        return view('admin/users/roles', $data);
    }
}