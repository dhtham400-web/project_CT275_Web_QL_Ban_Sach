<?php
namespace App\Controllers\Admin;

use App\Models\User;

class UserController extends AdminBaseController
{
    // Hiển thị danh sách thành viên
    public function index()
    {
        $userModel = new User();
        $users = $userModel->getAllUsers();
        $pageTitle = "Quản lý Thành viên";

        require_once __DIR__ . '/../../views/admin/users/index.php';
    }

    // Hiển thị form và xử lý thêm thành viên
    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userModel = new User();
            $userModel->create($_POST);
            header('Location: /admin/users');
            exit;
        }

        $pageTitle = "Thêm Thành viên Mới";
        require_once __DIR__ . '/../../views/admin/users/create.php';
    }

    // Hiển thị form và xử lý sửa thành viên
    public function edit($id)
    {
        $userModel = new User();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userModel->update($id, $_POST);
            header('Location: /admin/users');
            exit;
        }

        $user = $userModel->getUserById($id);
        $pageTitle = "Chỉnh sửa Thành viên";
        require_once __DIR__ . '/../../views/admin/users/edit.php';
    }

    // Xóa thành viên
    public function delete($id)
    {
        $userModel = new User();
        $userModel->delete($id);
        header('Location: /admin/users');
        exit;
    }
}