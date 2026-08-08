<?php

namespace App\Controllers;

use PDO;
use Exception;

class OrderController
{
    private $db;

    public function __construct()
    {
        $this->db = \App\Core\Database::getConnection();
    }

    // Danh sách đơn hàng của tôi
    public function index()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit();
        }

        $userId = $_SESSION['user']['id'];

        $stmt = $this->db->prepare("SELECT * FROM orders WHERE user_id = :user_id ORDER BY id DESC");
        $stmt->execute([':user_id' => $userId]);
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        include __DIR__ . '/../views/orders/index.php';
    }

    // Xem chi tiết đơn hàng
    public function detail($id)
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit();
        }

        $userId = $_SESSION['user']['id'];

        $stmtOrder = $this->db->prepare("
            SELECT o.*, 
                   COALESCE(NULLIF(o.customer_name, ''), u.name) AS customer_name,
                   COALESCE(NULLIF(o.customer_phone, ''), u.phone) AS customer_phone,
                   COALESCE(NULLIF(o.customer_address, ''), u.address) AS customer_address
            FROM orders o
            LEFT JOIN users u ON o.user_id = u.id
            WHERE o.id = :id AND o.user_id = :user_id
        ");
        $stmtOrder->execute([':id' => $id, ':user_id' => $userId]);
        $order = $stmtOrder->fetch(PDO::FETCH_ASSOC);

        if (!$order) {
            $_SESSION['error'] = "Không tìm thấy đơn hàng.";
            header('Location: /orders');
            exit();
        }

        $stmtDetails = $this->db->prepare("
            SELECT od.*, b.title AS book_title 
            FROM order_details od 
            LEFT JOIN books b ON od.book_id = b.id 
            WHERE od.order_id = :order_id
        ");
        $stmtDetails->execute([':order_id' => $id]);
        $orderDetails = $stmtDetails->fetchAll(PDO::FETCH_ASSOC);

        include __DIR__ . '/../views/orders/detail.php';
    }

    // Hủy đơn hàng
    public function cancel($id)
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit();
        }

        $userId = $_SESSION['user']['id'];

        $stmt = $this->db->prepare("SELECT status FROM orders WHERE id = :id AND user_id = :user_id");
        $stmt->execute([':id' => $id, ':user_id' => $userId]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$order) {
            $_SESSION['error'] = "Không tìm thấy đơn hàng.";
            header('Location: /orders');
            exit();
        }

        $status = mb_strtolower(trim($order['status'] ?? ''), 'UTF-8');

        if ($status === 'pending' || $status === 'chờ xử lý') {
            try {
                $this->db->beginTransaction();

                // Lấy các sản phẩm trong đơn để cộng trả lại kho
                $stmtItems = $this->db->prepare("SELECT book_id, quantity FROM order_details WHERE order_id = :order_id");
                $stmtItems->execute([':order_id' => $id]);
                $items = $stmtItems->fetchAll(PDO::FETCH_ASSOC);

                $stmtStock = $this->db->prepare("UPDATE books SET quantity = quantity + :qty WHERE id = :book_id");
                foreach ($items as $item) {
                    $stmtStock->execute([
                        ':qty' => $item['quantity'],
                        ':book_id' => $item['book_id']
                    ]);
                }

                // Cập nhật trạng thái đơn thành 'cancelled'
                $stmtCancel = $this->db->prepare("UPDATE orders SET status = 'cancelled' WHERE id = :id");
                $stmtCancel->execute([':id' => $id]);

                $this->db->commit();
                $_SESSION['success'] = "Hủy đơn hàng thành công và đã hoàn lại tồn kho!";
            } catch (Exception $e) {
                if ($this->db->inTransaction()) {
                    $this->db->rollBack();
                }
                $_SESSION['error'] = "Có lỗi xảy ra khi hủy đơn hàng: " . $e->getMessage();
            }
        } else {
            $_SESSION['error'] = "Đơn hàng đã được xử lý hoặc đã hủy, không thể hủy nữa.";
        }

        header("Location: /orders/detail/{$id}");
        exit();
    }
}