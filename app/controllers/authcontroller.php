<?php

namespace App\Controllers;

use App\Core\Database;
use PDO;

class AuthController extends BaseController
{
    // Hàm hiển thị trang đăng nhập
    public function login()
    {
        $this->render('auth.login');
    }

    // Hàm xử lý logic đăng nhập khi submit form
    public function postLogin()
    {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $data = ['error' => 'Vui lòng điền đầy đủ thông tin!'];
            return $this->render('auth.login', $data);
        }

        $db = Database::getConnection();

        // 1. Tìm tài khoản dựa trên email
        $stmt = $db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);

        // 2. Xác thực mật khẩu và lưu Session đồng bộ
        if ($user && password_verify($password, $user['password'])) {
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            
            // Lưu Session theo cả 2 dạng mảng và biến lẻ để tương thích toàn hệ thống
            $_SESSION['user'] = [
                'id'   => $user['id'],
                'name' => $user['name'],
                'role' => $user['role']
            ];
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role'];

            // 3. Điều hướng người dùng
            if ($user['role'] === 'admin') {
                header("Location: /admin/dashboard");
            } else {
                header("Location: /");
            }
            exit();
        } else {
            $data = ['error' => 'Email hoặc mật khẩu không chính xác!'];
            return $this->render('auth.login', $data);
        }
    }

    // Hàm đăng xuất khỏi hệ thống
    public function logout()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        // Xóa sạch các biến Session tài khoản
        unset($_SESSION['user']);
        unset($_SESSION['user_id']);
        unset($_SESSION['user_name']);
        unset($_SESSION['user_role']);

        header("Location: /");
        exit();
    }

    // Hiển thị trang đăng ký
    public function register()
    {
        $this->render('auth.register'); 
    }

    // Xử lý đăng ký
    public function postRegister()
    {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($name) || empty($email) || empty($password)) {
            $data = ['error' => 'Vui lòng điền đầy đủ tất cả các trường!'];
            return $this->render('auth.register', $data);
        }

        $db = Database::getConnection();

        $stmt = $db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        if ($stmt->fetch()) {
            $data = ['error' => 'Email này đã được sử dụng!'];
            return $this->render('auth.register', $data);
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $db->prepare("INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, 'customer')");
        $result = $stmt->execute([
            'name' => $name,
            'email' => $email,
            'password' => $hashedPassword
        ]);

        if ($result) {
            header("Location: /login");
            exit();
        } else {
            $data = ['error' => 'Có lỗi xảy ra trong quá trình đăng ký. Vui lòng thử lại!'];
            return $this->render('auth.register', $data);
        }
    }

    // Hiển thị trang đổi mật khẩu
    public function showChangePassword() 
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $userId = $_SESSION['user_id'] ?? $_SESSION['user']['id'] ?? null;
        if (!$userId) {
            header('Location: /login');
            exit();
        }
        
        require_once '../app/views/auth/change-password.php';
    }

    // Xử lý đổi mật khẩu
    public function handleChangePassword() 
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $userId = $_SESSION['user_id'] ?? $_SESSION['user']['id'] ?? null;
        if (!$userId) {
            header('Location: /login');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $currentPassword = $_POST['current_password'] ?? '';
            $newPassword     = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
                $_SESSION['error'] = 'Vui lòng điền đầy đủ thông tin!';
                header('Location: /change-password');
                exit();
            }

            if ($newPassword !== $confirmPassword) {
                $_SESSION['error'] = 'Mật khẩu mới và xác nhận mật khẩu không khớp!';
                header('Location: /change-password');
                exit();
            }

            try {
                $db = Database::getConnection(); 

                $stmt = $db->prepare("SELECT password FROM users WHERE id = ?");
                $stmt->execute([$userId]);
                $user = $stmt->fetch(\PDO::FETCH_ASSOC);

                if ($user && password_verify($currentPassword, $user['password'])) {
                    $newHash = password_hash($newPassword, PASSWORD_BCRYPT);
                    
                    $updateStmt = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
                    $updateStmt->execute([$newHash, $userId]);

                    $_SESSION['success'] = 'Đổi mật khẩu thành công!';
                    header('Location: /');
                    exit();
                } else {
                    $_SESSION['error'] = 'Mật khẩu hiện tại không chính xác!';
                    header('Location: /change-password');
                    exit();
                }

            } catch (\PDOException $e) {
                $_SESSION['error'] = 'Có lỗi xảy ra kết nối hệ thống: ' . $e->getMessage();
                header('Location: /change-password');
                exit();
            }
        }
    }
}