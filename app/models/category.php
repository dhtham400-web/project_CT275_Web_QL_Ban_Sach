<?php
namespace App\Models;

use PDO;
use App\Core\Database; 

class Category {
    private $db;

    public function __construct() {
        // Khởi tạo kết nối PDO thông qua lớp Database core của bạn
        // Bạn hãy chỉnh sửa dòng này cho khớp với cách viết trong core/database.php của bạn
        $this->db = Database::getConnection(); 
    }

    // 1. Lấy danh sách toàn bộ danh mục kèm số lượng sách
    public function getAll() {
        $sql = "SELECT c.*, COUNT(b.id) as total_books 
                FROM categories c 
                LEFT JOIN books b ON c.id = b.category_id 
                GROUP BY c.id 
                ORDER BY c.id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 2. Lấy thông tin chi tiết một danh mục theo ID (dùng cho trang Edit)
    public function find($id) {
        $sql = "SELECT * FROM categories WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 3. Hàm thêm mới danh mục
    public function insert($name) {
        $sql = "INSERT INTO categories (name) VALUES (:name)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['name' => $name]);
    }

    // 4. Hàm cập nhật danh mục
    public function updateCategory($id, $name) {
        $sql = "UPDATE categories SET name = :name WHERE id = :id";
        $stmt = $this->db->prepare($sql); // Thay $this->db bằng thuộc tính kết nối PDO trong model của bạn
        return $stmt->execute([
            ':name' => $name,
            ':id' => $id
        ]);
    }

    // 5. Hàm xóa danh mục
    public function deleteCategory($id) {
        $sql = "DELETE FROM categories WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
    public function checkNameExistForUpdate($name, $id) {
        // Sử dụng BINARY hoặc LOWER tùy cấu hình DB để kiểm tra không phân biệt chữ hoa chữ thường
        $sql = "SELECT COUNT(*) FROM categories WHERE LOWER(name) = LOWER(:name) AND id != :id";
        $stmt = $this->db->prepare($sql); // Thay $this->db bằng biến kết nối PDO của bạn
        $stmt->execute([
            ':name' => $name,
            ':id' => $id
        ]);
        return $stmt->fetchColumn() > 0;
    }
    public function countBooks($categoryId) {
        // Thay đổi tên bảng 'books' và khóa ngoại 'category_id' cho đúng với DB của bạn
        $sql = "SELECT COUNT(*) FROM books WHERE category_id = :category_id";
        $stmt = $this->db->prepare($sql); // Sử dụng biến kết nối PDO của bạn
        $stmt->execute([':category_id' => $categoryId]);
        return (int) $stmt->fetchColumn();
    }
    public function updateHomeStatusModel($id, $status) {
        // Sử dụng thuộc tính kết nối PDO nội bộ của Model
        // (Hãy đổi $this->db thành $this->conn nếu Model của bạn quy định vậy)
        $stmt = $this->db->prepare("SELECT id, name FROM categories WHERE show_on_home = TRUE ORDER BY id ASC;");
        return $stmt->execute([
            'status' => $status ? 'true' : 'false',
            'id' => $id
        ]);
    }
   public function getHomeCategories() {
        // Lấy toàn bộ danh mục hiện có trong hệ thống
        $stmt = $this->db->prepare("SELECT id, name FROM categories ORDER BY id ASC");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}