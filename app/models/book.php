<?php

namespace App\Models;

use App\Core\Database; // Thay bằng namespace chứa class Database của bạn

class Book {
    private $db;

    public function __construct() {
        // Gọi trực tiếp hàm kết nối từ class Database core
        $this->db = Database::getConnection(); // hoặc Database::getInstance()->getConnection();
    }

    public function getAllBooks() {
        $sql = "SELECT books.*, categories.name AS category_name 
                FROM books 
                LEFT JOIN categories ON books.category_id = categories.id 
                ORDER BY books.id DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function getTopSelling($limit = 5) {
        // Truy vấn lấy các cuốn sách có lượt mua cao nhất kèm tên danh mục của nó
        $sql = "SELECT b.*, c.name as category_name 
                FROM books b 
                LEFT JOIN categories c ON b.category_id = c.id 
                ORDER BY b.sold DESC, b.id DESC 
                LIMIT :limit";
                
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, \PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function getBooksByCategory($categoryId) {
        // Lấy toàn bộ sách có category_id tương ứng
        $stmt = $this->db->prepare("SELECT * FROM books WHERE category_id = :category_id ORDER BY id DESC");
        $stmt->bindValue(':category_id', $categoryId, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function delete($id) {
        // Câu lệnh SQL xóa sách theo ID sử dụng Prepared Statement để bảo mật
        $sql = "DELETE FROM books WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        
        // Bind giá trị ID và thực thi câu lệnh
        return $stmt->execute([
            ':id' => $id
        ]);
    }
   public function update($id, $data) {
        $sql = "UPDATE books SET 
                    title = :title, 
                    description = :description, 
                    author = :author, 
                    category_id = :category_id, 
                    price = :price, 
                    quantity = :quantity, 
                    image = :image 
                WHERE id = :id";
                
        $stmt = $this->db->prepare($sql);
        
        // Thêm ID vào mảng dữ liệu để bind param
        $data['id'] = $id;
        
        return $stmt->execute($data);
    }
    public function getAdminBooks($search = '', $categoryId = '') {
        // Câu lệnh SQL gốc kết hợp với bảng danh mục để lấy tên danh mục
        $sql = "SELECT b.*, c.name as category_name 
                FROM books b 
                LEFT JOIN categories c ON b.category_id = c.id 
                WHERE 1=1";
        $params = [];

        // Tình huống 1: Người dùng nhập từ khóa tìm kiếm (Tên sách hoặc Tác giả)
        if (!empty($search)) {
            $sql .= " AND (b.title ILIKE :search OR b.author ILIKE :search)";
            $params[':search'] = '%' . $search . '%';
        }

        // Tình huống 2: Người dùng chọn một danh mục cụ thể để lọc
        if (!empty($categoryId)) {
            $sql .= " AND b.category_id = :category_id";
            $params[':category_id'] = (int)$categoryId;
        }

        // Sắp xếp theo ID giảm dần để hiển thị sách mới thêm lên đầu bảng
        $sql .= " ORDER BY b.id DESC";

        // Thực thi câu lệnh an toàn qua PDO binding để tránh lỗi SQL Injection
        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function getBookById($id) {
        $sql = "SELECT b.*, c.name as category_name 
                FROM books b 
                LEFT JOIN categories c ON b.category_id = c.id 
                WHERE b.id = :id";
                
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', (int)$id, \PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
    public function find($id) {
        $stmt = $this->db->prepare("SELECT * FROM books WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}