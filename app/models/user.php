<?php
namespace App\Models;

use PDO;

class User
{
    private $db;

    public function __construct()
    {
        // Cấu hình kết nối dành cho PostgreSQL (pgAdmin)
        $host = '127.0.0.1';
        $port = '5432'; // Port mặc định của PostgreSQL
        $dbname = 'CT275_QLBSach';
        $user = 'postgres'; // Username mặc định của PostgreSQL
        $password = '0967196400'; // Mật khẩu bạn đặt khi cài đặt PostgreSQL/pgAdmin

        $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
        
        $this->db = new PDO($dsn, $user, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
    }

    // Lấy tất cả thành viên
    public function getAllUsers()
    {
        $stmt = $this->db->prepare("SELECT * FROM users ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy thông tin 1 thành viên theo ID
    public function getUserById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Thêm thành viên mới
    public function create($data)
    {
        $stmt = $this->db->prepare("INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, :role)");
        return $stmt->execute([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
            'role' => $data['role'] ?? 'user'
        ]);
    }

    // Cập nhật thông tin thành viên
    public function update($id, $data)
    {
        if (!empty($data['password'])) {
            $stmt = $this->db->prepare("UPDATE users SET name = :name, email = :email, password = :password, role = :role WHERE id = :id");
            return $stmt->execute([
                'id' => $id,
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => password_hash($data['password'], PASSWORD_DEFAULT),
                'role' => $data['role']
            ]);
        } else {
            $stmt = $this->db->prepare("UPDATE users SET name = :name, email = :email, role = :role WHERE id = :id");
            return $stmt->execute([
                'id' => $id,
                'name' => $data['name'],
                'email' => $data['email'],
                'role' => $data['role']
            ]);
        }
    }

    // Xóa thành viên
    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}