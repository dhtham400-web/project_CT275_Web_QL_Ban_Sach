<?php
namespace App\Controllers\Admin;

// Import Model Category vào Controller
use App\Models\Category; 

class CategoryController extends AdminBaseController {
    
    private $categoryModel;

    // Hàm khởi tạo để nạp Model tự động
    public function __construct() {
        // RẤT QUAN TRỌNG: Phải gọi parent::__construct() để kích hoạt đoạn code chặn phân quyền
        parent::__construct();
        $this->categoryModel = new Category(); 
    }

    // Hiển thị danh sách danh mục (View index.php)
    public function index() {
        $categories = $this->categoryModel->getAll();
        // Nạp view danh sách danh mục
        include __DIR__ . '/../../views/admin/categories/index.php';
    }

    // Hiển thị form thêm mới (View create.php)
    public function create() {
        include __DIR__ . '/../../views/admin/categories/create.php';
    }

    // Xử lý lưu dữ liệu khi thêm mới
    public function store() {
        if (session_status() === PHP_SESSION_NONE) { session_start(); }
        $name = isset($_POST['name']) ? trim($_POST['name']) : '';

        if (empty($name)) {
            $_SESSION['error'] = "Tên danh mục không được để trống!";
            header("Location: /admin/categories/create");
            exit();
        }

        try {
            $this->categoryModel->insert($name); 
            $_SESSION['success'] = "Thêm danh mục [<strong>" . htmlspecialchars($name) . "</strong>] thành công!";
            header("Location: /admin/categories");
            exit();
        } catch (\PDOException $e) {
            if ($e->getCode() == '23505' || strpos($e->getMessage(), 'duplicate key') !== false) {
                $_SESSION['error'] = "Tên danh mục [<strong>" . htmlspecialchars($name) . "</strong>] đã tồn tại!";
            } else {
                $_SESSION['error'] = "Lỗi hệ thống: " . $e->getMessage();
            }
            header("Location: /admin/categories/create");
            exit();
        }
    }

    // Hiển thị form chỉnh sửa (View edit.php)
    public function edit($id) {
        $category = $this->categoryModel->find($id);
        if (!$category) {
            if (session_status() === PHP_SESSION_NONE) { session_start(); }
            $_SESSION['error'] = "Danh mục không tồn tại!";
            header("Location: /admin/categories");
            exit();
        }
        include __DIR__ . '/../../views/admin/categories/edit.php';
    }

    // Xử lý cập nhật dữ liệu
   public function update($id) {
    // 1. Lấy dữ liệu tên từ form gửi lên và loại bỏ khoảng trắng thừa
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';

    if (empty($name)) {
        $_SESSION['error'] = "Tên danh mục không được để trống!";
        header("Location: /admin/categories/edit/" . $id);
        exit();
    }

    // 2. Kiểm tra xem tên mới có bị trùng với danh mục KHÁC hay không
    // Câu lệnh SQL kiểm tra sẽ có dạng: SELECT * FROM categories WHERE name = :name AND id != :id
    $categoryModel = new Category(); // Hoặc cách gọi Model của dự án bạn
    $isExisted = $categoryModel->checkNameExistForUpdate($name, $id);

    if ($isExisted) {
        // Nếu trùng tên với một danh mục khác, thông báo lỗi và giữ lại trang sửa
        $_SESSION['error'] = "Tên danh mục này đã tồn tại trong hệ thống!";
        header("Location: /admin/categories/edit/" . $id);
        exit();
    }

    // 3. Nếu không trùng, tiến hành cập nhật dữ liệu
    $result = $categoryModel->updateCategory($id, $name);

    if ($result) {
        $_SESSION['success'] = "Cập nhật danh mục thành công!";
        header("Location: /admin/categories");
    } else {
        $_SESSION['error'] = "Có lỗi xảy ra trong quá trình cập nhật!";
        header("Location: /admin/categories/edit/" . $id);
    }
    exit();
}

    // Xử lý xóa danh mục
    public function delete($id) {
        $categoryModel = new Category();

        // 1. Kiểm tra xem danh mục có đang chứa sách hay không
        $bookCount = $categoryModel->countBooks($id);

        if ($bookCount > 0) {
            // Nếu còn sách, lưu thông báo lỗi vào session và quay lại trang danh sách
            $_SESSION['error'] = "Không thể xóa! Danh mục này đang chứa " . $bookCount . " cuốn sách.";
            header("Location: /admin/categories");
            exit();
        }

        // 2. Nếu không có sách ràng buộc, tiến hành xóa
        // Thay 'deleteCategory' bằng tên hàm xóa thực tế trong Model của bạn nếu khác tên
        $result = $categoryModel->deleteCategory($id); 

        if ($result) {
            $_SESSION['success'] = "Xóa danh mục thành công!";
        } else {
            $_SESSION['error'] = "Có lỗi xảy ra trong quá trình xóa danh mục.";
        }

        header("Location: /admin/categories");
        exit();
    }
    public function updateHomeStatus() {
        header('Content-Type: application/json');
        $input = json_decode(file_get_contents('php://input'), true);
        
        $id = isset($input['id']) ? intval($input['id']) : 0;
        $status = (isset($input['status']) && $input['status'] == 1) ? true : false; 

        if ($id > 0) {
            try {
                // Gọi hàm xử lý cập nhật từ Model đã được nạp sẵn
                $result = $this->categoryModel->updateHomeStatusModel($id, $status);
                
                if ($result) {
                    echo json_encode(['success' => true]);
                    exit;
                }
            } catch (\PDOException $e) {
                echo json_encode(['success' => false, 'error' => $e->getMessage()]);
                exit;
            }
        }

        echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ.']);
        exit;
    }
}