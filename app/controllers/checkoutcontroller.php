<?php
namespace App\Controllers;

use App\Models\Cart;
use PDO;
use PDOException;

class CheckoutController 
{
    private $cart;
    private $db;

    public function __construct() 
    {
        $this->cart = new Cart();
        $this->db = $this->getPDO();
    }

    private function getPDO() 
    {
        $host = '127.0.0.1';
        $port = '5432';
        $dbname = 'CT275_QLBSach';
        $username = 'postgres';
        $password = '0967196400';

        try {
            $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
            $pdo = new PDO($dsn, $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
            return $pdo;
        } catch (PDOException $e) {
            die("Lỗi kết nối CSDL PostgreSQL: " . $e->getMessage());
        }
    }

    public function index() 
    {
        $cartItems = $_SESSION['cart'] ?? [];
        $totalPrice = $this->cart->getTotalPrice();

        if (empty($cartItems)) {
            header('Location: /cart');
            exit;
        }

        require_once __DIR__ . '/../views/checkout/index.php';
    }

    public function process() 
    {
        if ($this->db === null) {
            $this->db = $this->getPDO();
        }

        $cartItems = $_SESSION['cart'] ?? [];
        if (empty($cartItems)) {
            $_SESSION['error'] = "Giỏ hàng của bạn đang trống!";
            header('Location: /cart');
            exit;
        }

        // 1. KIỂM TRA TỒN KHO TRƯỚC KHI TẠO ĐƠN
        foreach ($cartItems as $item) {
            $stmtCheck = $this->db->prepare("SELECT title, quantity FROM books WHERE id = :id");
            $stmtCheck->execute([':id' => $item['id']]);
            $book = $stmtCheck->fetch(PDO::FETCH_ASSOC);

            if (!$book) {
                $_SESSION['error'] = "Sách '" . htmlspecialchars($item['title'] ?? '') . "' không tồn tại trong hệ thống!";
                header('Location: /checkout');
                exit;
            }

            if ($item['quantity'] > $book['quantity']) {
                if ($book['quantity'] <= 0) {
                    $_SESSION['error'] = "Rất tiếc! Sách <strong>'" . htmlspecialchars($book['title']) . "'</strong> hiện đã hết hàng.";
                } else {
                    $_SESSION['error'] = "Sách <strong>'" . htmlspecialchars($book['title']) . "'</strong> chỉ còn <strong>" . $book['quantity'] . "</strong> cuốn trong kho, không đủ số lượng bạn đặt (" . $item['quantity'] . " cuốn).";
                }
                header('Location: /checkout');
                exit;
            }
        }

        // 2. THỰC HIỆN TẠO ĐƠN & TRỪ KHO BẰNG TRANSACTION
        try {
            $this->db->beginTransaction();

            $userId = $_SESSION['user']['id'] ?? null;

            // Lưu thông tin đơn hàng
            $stmt = $this->db->prepare("
                INSERT INTO orders (user_id, customer_name, customer_phone, customer_address, email, total_money, status, created_at) 
                VALUES (:user_id, :name, :phone, :address, :email, :total, :status, NOW())
            ");
            
            $stmt->execute([
                ':user_id' => $userId,
                ':name'    => trim($_POST['customer_name'] ?? ''),
                ':phone'   => trim($_POST['customer_phone'] ?? ''),
                ':address' => trim($_POST['address'] ?? ''),
                ':email'   => trim($_POST['customer_email'] ?? ''),
                ':total'   => $this->cart->getTotalPrice(),
                ':status'  => 'pending'
            ]);

            $orderId = $this->db->lastInsertId('orders_id_seq');

            // Câu lệnh thêm chi tiết đơn
            $stmtDetail = $this->db->prepare("
                INSERT INTO order_details (order_id, book_id, quantity, price) 
                VALUES (:order_id, :book_id, :quantity, :price)
            ");

            // Câu lệnh trừ số lượng tồn kho trực tiếp trong PostgreSQL
            $stmtUpdateStock = $this->db->prepare("
                UPDATE books 
                SET quantity = quantity - :quantity 
                WHERE id = :book_id AND quantity >= :quantity
            ");

            foreach ($cartItems as $item) {
                // Lưu chi tiết
                $stmtDetail->execute([
                    ':order_id' => $orderId,
                    ':book_id'  => $item['id'],
                    ':quantity' => $item['quantity'],
                    ':price'    => $item['price']
                ]);

                // Trừ kho
                $stmtUpdateStock->execute([
                    ':quantity' => $item['quantity'],
                    ':book_id'  => $item['id']
                ]);

                // Nếu không dòng nào bị tác động nghĩa là tồn kho không đủ
                if ($stmtUpdateStock->rowCount() === 0) {
                    throw new \Exception("Sách '" . htmlspecialchars($item['title'] ?? '') . "' không đủ số lượng tồn kho!");
                }
            }

            $this->db->commit();
            unset($_SESSION['cart']);

            header("Location: /checkout/success?order_id=" . $orderId);
            exit;

        } catch (\Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            $_SESSION['error'] = "Lỗi lưu đơn hàng: " . $e->getMessage();
            header('Location: /checkout');
            exit;
        }
    }

    public function success($id = null) 
    {
        $orderId = $_GET['order_id'] ?? $id;
        
        if (!$orderId) {
            header('Location: /');
            exit;
        }

        if ($this->db === null) {
            $this->db = $this->getPDO();
        }

        // Lấy thông tin đơn hàng
        $stmt = $this->db->prepare("SELECT * FROM orders WHERE id = :id");
        $stmt->execute([':id' => $orderId]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$order) {
            header('Location: /');
            exit;
        }

        // Lấy chi tiết các sản phẩm thuộc đơn hàng
        $stmtItems = $this->db->prepare("
            SELECT od.*, b.title, b.image 
            FROM order_details od
            JOIN books b ON od.book_id = b.id
            WHERE od.order_id = :order_id
        ");
        $stmtItems->execute([':order_id' => $orderId]);
        $orderItems = $stmtItems->fetchAll(PDO::FETCH_ASSOC);

        require_once __DIR__ . '/../views/checkout/success.php';
    }
}