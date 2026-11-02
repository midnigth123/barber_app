<?php

namespace App\Controllers\Admin\User; // Harus sesuai dengan foldernya

use App\Controllers\BaseController;
use App\Models\ActivityLogModel;

class LogController extends BaseController // Nama class cukup LogController
{
    public function index()
    {
        $logModel = new ActivityLogModel();

        $data['logs'] = $logModel->select('activity_logs.*, users.name as user_name, users.role')
                                 ->join('users', 'users.id = activity_logs.user_id', 'left')
                                 ->orderBy('activity_logs.created_at', 'DESC')
                                 ->findAll();

        return view('admin/users/logs', $data);
    }
}