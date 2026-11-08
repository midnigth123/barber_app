<?php

namespace App\Controllers\Admin\User;

use App\Controllers\BaseController;
use App\Models\ActivityLogModel;

class LogController extends BaseController
{
    public function index()
    {
        $logModel = new ActivityLogModel();

        // Ambil data log digabungkan dengan tabel users
        $data['logs'] = $logModel->select('activity_logs.*, users.name as user_name, users.role')
                                 ->join('users', 'users.id = activity_logs.user_id', 'left')
                                 ->orderBy('activity_logs.created_at', 'DESC')
                                 ->findAll();

        return view('admin/users/logs', $data);
    }
}