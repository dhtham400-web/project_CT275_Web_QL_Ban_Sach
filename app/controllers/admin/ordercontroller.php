<?php

namespace App\Controllers\Admin;

use App\Controllers\Admin\AdminBaseController;
use PDO;

class OrderController extends AdminBaseController
{
    private $db;

    public function __construct()
    {
        // RẤT QUAN TRỌNG: Phải gọi parent::__construct() để kích hoạt đoạn code chặn phân quyền
        parent::__construct();
        $this->db = \App\Core\Database::getConnection();
    }

    // 1. Hiển thị danh sách đơn hàng phía Admin
    public function index()
    {
        $stmt = $this->db->query("
            SELECT 
                o.*, 
                COALESCE(NULLIF(o.customer_name, ''), u.name, 'Khách lẻ') AS customer_name,
                COALESCE(NULLIF(o.customer_phone, ''), u.phone, 'Chưa cập nhật') AS customer_phone,
                COALESCE(NULLIF(o.customer_address, ''), u.address, 'Chưa cập nhật') AS customer_address
            FROM orders o 
            LEFT JOIN users u ON o.user_id = u.id 
            ORDER BY o.id DESC
        ");
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $data = [
            'title'  => 'Quản Lý Đơn Hàng - Admin',
            'orders' => $orders
        ];

        if (method_exists($this, 'render')) {
            return $this->render('admin.orders.index', $data);
        }

        include __DIR__ . '/../../views/admin/orders/index.php';
    }

    // 2. Hiển thị chi tiết đơn hàng phía Admin (Đã sửa lỗi)
    public function detail($id)
    {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit();
        }

        // Lấy thông tin đơn hàng và thông tin khách hàng (Không lọc theo user_id)
        $stmtOrder = $this->db->prepare("
            SELECT 
                o.*, 
                COALESCE(NULLIF(o.customer_name, ''), u.name, 'Khách lẻ') AS customer_name,
                COALESCE(NULLIF(o.customer_phone, ''), u.phone, 'Chưa cập nhật') AS customer_phone,
                COALESCE(NULLIF(o.customer_address, ''), u.address, 'Chưa cập nhật') AS customer_address
            FROM orders o
            LEFT JOIN users u ON o.user_id = u.id
            WHERE o.id = :id
        ");
        $stmtOrder->execute([':id' => $id]);
        $order = $stmtOrder->fetch(PDO::FETCH_ASSOC);

        // Nếu không tồn tại đơn hàng -> Báo lỗi và chuyển hướng về danh sách đơn Admin
        if (!$order) {
            $_SESSION['error'] = "Không tìm thấy đơn hàng #{$id}.";
            header('Location: /admin/orders');
            exit();
        }

        // Lấy chi tiết các sản phẩm trong đơn
        $stmtDetails = $this->db->prepare("
            SELECT od.*, b.title AS book_title, b.image 
            FROM order_details od 
            LEFT JOIN books b ON od.book_id = b.id 
            WHERE od.order_id = :order_id
        ");
        $stmtDetails->execute([':order_id' => $id]);
        $orderDetails = $stmtDetails->fetchAll(PDO::FETCH_ASSOC);

        // Nhúng đúng view Admin: admin/orders/detail.php
        include __DIR__ . '/../../views/admin/orders/detail.php';
    }

    // 3. Khách hàng/Admin bấm Hủy đơn
    public function cancel($id)
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit();
        }

        $stmt = $this->db->prepare("SELECT status FROM orders WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $status = mb_strtolower(trim($stmt->fetchColumn() ?? ''), 'UTF-8');

        if ($status === 'pending' || $status === 'chờ xử lý') {
            // Hoàn lại số lượng sách vào kho
            $stmtItems = $this->db->prepare("SELECT book_id, quantity FROM order_details WHERE order_id = :order_id");
            $stmtItems->execute([':order_id' => $id]);
            $items = $stmtItems->fetchAll(PDO::FETCH_ASSOC);

            foreach ($items as $item) {
                $stmtUpdateStock = $this->db->prepare("UPDATE books SET quantity = quantity + :qty WHERE id = :book_id");
                $stmtUpdateStock->execute([
                    ':qty'     => $item['quantity'],
                    ':book_id' => $item['book_id']
                ]);
            }

            // Cập nhật trạng thái đơn thành "Hủy đơn hàng"
            $stmtCancel = $this->db->prepare("UPDATE orders SET status = 'Hủy đơn hàng' WHERE id = :id");
            $stmtCancel->execute([':id' => $id]);

            $_SESSION['success'] = "Hủy đơn hàng thành công!";
        } else {
            $_SESSION['error'] = "Không thể hủy đơn hàng do đơn đã được xác nhận hoặc xử lý.";
        }

        header("Location: /admin/orders/detail/{$id}");
        exit();
    }

    // 4. Cập nhật trạng thái đơn hàng + Tự động hoàn kho khi Hủy
    // Cập nhật trạng thái đơn hàng + Tự động hoàn kho khi Hủy
    public function updateStatus($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $newStatus = trim($_POST['status'] ?? '');

            if (!empty($newStatus)) {
                // Lấy trạng thái hiện tại
                $stmt = $this->db->prepare("SELECT status FROM orders WHERE id = :id");
                $stmt->execute([':id' => $id]);
                $oldStatus = trim($stmt->fetchColumn() ?? '');

                $newLower = mb_strtolower($newStatus, 'UTF-8');
                $oldLower = mb_strtolower($oldStatus, 'UTF-8');

                $cancelKeys = ['cancelled', 'hủy đơn hàng', 'hủy đơn', 'canceled'];

                // Nếu chuyển sang trạng thái Hủy mà trước đó chưa hủy -> Cộng lại số lượng vào kho
                if (in_array($newLower, $cancelKeys) && !in_array($oldLower, $cancelKeys)) {
                    $stmtItems = $this->db->prepare("SELECT book_id, quantity FROM order_details WHERE order_id = :order_id");
                    $stmtItems->execute([':order_id' => $id]);
                    $items = $stmtItems->fetchAll(\PDO::FETCH_ASSOC);

                    foreach ($items as $item) {
                        $stmtUpdateStock = $this->db->prepare("UPDATE books SET quantity = quantity + :qty WHERE id = :book_id");
                        $stmtUpdateStock->execute([
                            ':qty'     => $item['quantity'],
                            ':book_id' => $item['book_id']
                        ]);
                    }
                }

                // Cập nhật trạng thái mới
                $stmtUpdate = $this->db->prepare("UPDATE orders SET status = :status WHERE id = :id");
                $result = $stmtUpdate->execute([':status' => $newStatus, ':id' => $id]);

                if ($result) {
                    $_SESSION['success'] = "Cập nhật trạng thái đơn hàng thành công!";
                } else {
                    $_SESSION['error'] = "Cập nhật trạng thái thất bại.";
                }
            }

            header("Location: /admin/orders/detail/{$id}");
            exit();
        }
    }

    // Giữ hàm update() để tránh lỗi nếu có nơi khác gọi
    public function update($id)
    {
        return $this->updateStatus($id);
    }
}   