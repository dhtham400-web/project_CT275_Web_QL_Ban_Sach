<?php
namespace App\Controllers\Admin;

// THÀNH DÒNG NÀY: Trỏ đúng vào thư mục Admin
use App\Controllers\Admin\AdminBaseController; 
use App\Models\Book;

class BookController extends AdminBaseController
{
    protected $bookModel;
    public function __construct() {
        // RẤT QUAN TRỌNG: Phải gọi parent::__construct() để kích hoạt đoạn code chặn phân quyền
        parent::__construct();
        // Tùy thuộc vào cách dự án của bạn khởi tạo Model:
        // Cách thông thường:
        $this->bookModel = new Book();
        
        // Hoặc nếu dự án dùng kết nối Database truyền vào:
        // global $db;
        // $this->bookModel = new Book($db);
    }
    // Ham hien thi form them sach moi (Giu nguyen cua ban)
    public function create()
    {
        $db = \App\Core\Database::getConnection();
        $stmt = $db->query("SELECT * FROM categories ORDER BY name ASC");
        $categories = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // ĐÃ XÓA dòng $description thừa ở đây vì hàm này chỉ render giao diện form

        $data = [
            'title' => 'Them Sach Moi - Admin',
            'categories' => $categories
        ];

        return $this->render('admin.books.create', $data);
    }

    // HAM BO SUNG: Xu ly nhan du lieu tu Form va luu vao Database
    public function store()
    {
        // 1. Kiem tra xem co phai du lieu gui len bang POST khong
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            // 2. Lay du lieu tu cac o nhap lieu cua Form va loai bo khoang trang thua
            $title = trim($_POST['title'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $author = trim($_POST['author'] ?? '');
            $categoryId = $_POST['category_id'] ?? null;
            $price = trim($_POST['price'] ?? '');
            $quantity = trim($_POST['quantity'] ?? '');
            
            // --- BỔ SUNG: RÀNG BUỘC KHÔNG ĐỂ TRỐNG DỮ LIỆU ---
            if (empty($title) || empty($description) || empty("{$author}") || empty($categoryId) || $price === '' || $quantity === '') {
                die("Lỗi: Vui lòng điền đầy đủ tất cả các trường dữ liệu bắt buộc!");
            }

            // --- BỔ SUNG: KIỂM TRA TẬP TIN ẢNH BẮT BUỘC ---
            if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
                die("Lỗi: Vui lòng chọn một tệp ảnh bìa hợp lệ cho sách!");
            }
            
            // Mặc dinh ten anh la trong
            $imageName = '';

            // 3. Xu ly upload file anh bia sach
            $fileTmpPath = $_FILES['image']['tmp_name'];
            $originalFileName = $_FILES['image']['name'];
            
            // Tach phan mo rong cua file (jpg, png...)
            $fileExtension = strtolower(pathinfo($originalFileName, PATHINFO_EXTENSION));
            
            // Tao ten file ngau nhien de khong bi trung lap anh tren he thong
            $imageName = time() . '_' . md5($originalFileName) . '.' . $fileExtension;
            
            // Duong dan tuyet doi de luu vao thu muc public/uploads
            $uploadFileDir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/';
            $dest_path = $uploadFileDir . $imageName;

            // Tao thu muc uploads neu chua ton tai
            if (!is_dir($uploadFileDir)) {
                mkdir($uploadFileDir, 0755, true);
            }

            // Di chuyen file tam thoi vao thu muc uploads
            move_uploaded_file($fileTmpPath, $dest_path);

            // 4. Ket noi Database de chen du lieu vao PostgreSQL
            $db = \App\Core\Database::getConnection();
            
            $sql = "INSERT INTO books (title, author, category_id, price, quantity, image, description) 
                    VALUES (:title, :author, :category_id, :price, :quantity, :image, :description)";
            
            $stmt = $db->prepare($sql);
            
            $stmt->execute([
                ':title' => $title,
                ':author' => $author,
                ':category_id' => $categoryId,
                ':price' => $price,
                ':quantity' => $quantity,
                ':image' => $imageName,
                ':description' => $description
            ]);
            $_SESSION['success'] = "Thêm sách mới '{$title}' thành công!";

            // 5. Luu thanh cong thi chuyen huong ve trang danh sach sach
            header('Location: /admin/books');
            exit();
        }
    }
    // Hàm hiển thị danh sách sách trong trang quản trị
    public function index() {
        // Khởi tạo các Model cần thiết
        $bookModel = new \App\Models\Book();
        $categoryModel = new \App\Models\Category();

        // 1. Lấy toàn bộ danh mục để hiển thị vào thẻ <select> của bộ lọc
        $allCategories = $categoryModel->getAll(); 

        // 2. Tiếp nhận từ khóa tìm kiếm và ID danh mục từ URL (nếu có)
        $search = $_GET['search'] ?? '';
        $categoryId = $_GET['category_id'] ?? '';

        // 3. Gọi hàm trong Model xử lý tìm kiếm và lọc dữ liệu
        $books = $bookModel->getAdminBooks($search, $categoryId);

        // 4. Truyền các biến dữ liệu sang file giao diện View index.php đã tạo trước đó
        include __DIR__ . '/../../views/admin/books/index.php';
    }
    // 1. Ham xu ly xoa sach
    public function delete($id) {
        try {
            // 1. Gọi đến Model để thực hiện lệnh xóa sách trong Database
            // Lưu ý: Thay đổi '$this->bookModel' thành tên biến Model tương ứng trong dự án của bạn (nếu có khác biệt)
            $result = $this->bookModel->delete($id); 
            
            // 2. Kiểm tra kết quả trả về từ Model để bắn thông báo
            if ($result) {
                $_SESSION['success'] = "Xóa cuốn sách thành công khỏi hệ thống!";
            } else {
                $_SESSION['error'] = "Không tìm thấy cuốn sách dữ liệu để xóa hoặc xóa thất bại.";
            }
            
        } catch (\Exception $e) {
            // Trường hợp lỗi hệ thống (ví dụ: ràng buộc khóa ngoại nếu sách đã có trong đơn hàng)
            $_SESSION['error'] = "Lỗi: Không thể xóa cuốn sách này! (Chi tiết: " . $e->getMessage() . ")";
        }

        // 3. Chuyển hướng quay trở lại trang danh sách sách
        header('Location: /admin/books');
        exit();
    }
    // 2. Ham hien thi form sua thong tin sach
    public function edit($id)
    {
        $db = \App\Core\Database::getConnection();

        // 1. Lấy thông tin cuốn sách cần sửa
        $stmtBook = $db->prepare("SELECT * FROM books WHERE id = :id");
        $stmtBook->execute([':id' => $id]);
        $book = $stmtBook->fetch(\PDO::FETCH_ASSOC);

        // Nếu không tìm thấy sách, chuyển hướng về trang danh sách
        if (!$book) {
            header('Location: /admin/books');
            exit();
        }

        // 2. Lấy danh sách danh mục để chọn lại
        $stmtCat = $db->query("SELECT * FROM categories ORDER BY name ASC");
        $categories = $stmtCat->fetchAll(\PDO::FETCH_ASSOC);

        $data = [
            'title' => 'Chỉnh Sửa Sách - Admin',
            'book' => $book,
            'categories' => $categories
        ];

        return $this->render('admin.books.edit', $data);
    }
    public function update($id) {
        try {
            // 1. Thu thập và chuẩn bị đầy đủ dữ liệu từ form $_POST
            // Đảm bảo các key (title, author...) khớp chính xác với các placeholder trong SQL
            $data = [
                'title'        => $_POST['title'] ?? '',
                'description'  => $_POST['description'] ?? '',
                'author'       => $_POST['author'] ?? '',
                'category_id'  => $_POST['category_id'] ?? null,
                'price'        => $_POST['price'] ?? 0,
                'quantity'     => $_POST['quantity'] ?? 0,
                'image'        => $_POST['old_image'] ?? '' // Mặc định giữ ảnh cũ
            ];

            // 2. Xử lý logic upload ảnh mới nếu người dùng chọn file mới
            if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
                $targetDir = "uploads/";
                $fileName = time() . '_' . basename($_FILES['image']['name']);
                $targetFilePath = $targetDir . $fileName;

                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
                    // Cập nhật tên ảnh mới vào mảng dữ liệu
                    $data['image'] = $fileName;

                    // (Tùy chọn) Xóa file ảnh cũ khỏi thư mục để tránh rác bộ nhớ
                    if (!empty($_POST['old_image']) && file_exists($targetDir . $_POST['old_image'])) {
                        unlink($targetDir . $_POST['old_image']);
                    }
                }
            }

            // 3. Gọi Model xử lý với đầy đủ tham số trong mảng $data
            $result = $this->bookModel->update($id, $data); 

            if ($result) {
                $_SESSION['success'] = "Cập nhật thông tin sách thành công!";
                header('Location: /admin/books');
                exit();
            } else {
                $_SESSION['error'] = "Không có thay đổi nào được ghi nhận hoặc cập nhật thất bại.";
                header("Location: /admin/books/edit/$id");
                exit();
            }
        } catch (\Exception $e) {
            $_SESSION['error'] = "Lỗi hệ thống: " . $e->getMessage();
            header("Location: /admin/books/edit/$id");
            exit();
        }
    }  
   public function show($id) {
        $bookModel = new Book();
        $book = $bookModel->getBookById($id);
        
        if (!$book) {
            header('Location: /');
            exit();
        }
        
        $pageTitle = $book['title'] . " - T-Bookstore";
        // Cập nhật đường dẫn đi vào thư mục auth/books/detail.php
        include __DIR__ . '/../../views/books/detail.php';
    }
   
   public function search()
    {
        // Lấy và làm sạch từ khóa tìm kiếm
        $keyword = trim($_GET['keyword'] ?? '');

        $books = [];
        if (!empty($keyword)) {
            // Lấy kết nối CSDL chuẩn theo class Database của bạn
            $db = \App\Core\Database::getConnection();

            $sql = "SELECT b.*, c.name AS category_name
                    FROM books b
                    LEFT JOIN categories c ON b.category_id = c.id
                    WHERE b.title ILIKE :keyword 
                    OR b.author ILIKE :keyword 
                    OR c.name ILIKE :keyword
                    ORDER BY b.id DESC";

            $stmt = $db->prepare($sql);
            $stmt->execute([':keyword' => "%{$keyword}%"]);
            $books = $stmt->fetchAll();
        }

        // Truyền dữ liệu sang View kết quả
        require_once __DIR__ . '/../../views/books/search.php';
    }
}