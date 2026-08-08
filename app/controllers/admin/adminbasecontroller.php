<?php
namespace App\Controllers\Admin;

class AdminBaseController extends \App\Controllers\BaseController 
{
    public function __construct()
    {
        // 1. Kích hoạt session nếu chưa có
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $user = $_SESSION['user'] ?? null;
        $role = strtolower(trim($user['role'] ?? ''));

        // 2. Chặn nếu chưa đăng nhập HOẶC role không phải 'admin'
        if (!$user || $role !== 'admin') {
            $_SESSION['error'] = "Bạn không có quyền truy cập khu vực Quản trị!";
            header('Location: /login');
            exit();
        }
    }
}
