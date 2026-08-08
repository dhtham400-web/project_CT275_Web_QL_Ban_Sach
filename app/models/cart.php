<?php
namespace App\Models;

class Cart {
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }

    // Thêm sản phẩm vào giỏ hoặc cập nhật số lượng
    public function add($product, $quantity = 1) {
        if (!is_array($product) || empty($product['id'])) {
            return;
        }

        $id = $product['id'];

        // Nếu đã có trong giỏ -> tăng số lượng
        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['quantity'] += $quantity;
        } else {
            // Tạo mới mảng sản phẩm với đầy đủ thông tin
            $_SESSION['cart'][$id] = [
                'id'       => $product['id'],
                'title'    => $product['title'] ?? $product['name'] ?? 'Chưa có tên',
                'price'    => $product['price'] ?? 0,
                'image'    => $product['image'] ?? '',
                'quantity' => $quantity
            ];
        }
    }

    // Lấy danh sách sản phẩm trong giỏ
    public function getItems() {
        return $_SESSION['cart'] ?? [];
    }

    // Đếm tổng số lượng sản phẩm để hiển thị lên icon giỏ hàng
   public function getTotalQuantity() {
        $totalQuantity = 0;
        if (!empty($_SESSION['cart']) && is_array($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $item) {
                $totalQuantity += $item['quantity'] ?? 1;
            }
        }
        return $totalQuantity;
    }
    public function getTotalPrice() {
        $total = 0;
        foreach ($_SESSION['cart'] as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }

    // CẬP NHẬT SỐ LƯỢNG
    public function update($id, $quantity) {
        if (isset($_SESSION['cart'][$id])) {
            if ($quantity > 0) {
                $_SESSION['cart'][$id]['quantity'] = $quantity;
            } else {
                $this->remove($id);
            }
        }
    }
    public function remove($id) {
        if (isset($_SESSION['cart'][$id])) {
            unset($_SESSION['cart'][$id]);
        }
        
        // Nếu giỏ hàng không còn sản phẩm nào, xóa hẳn session cart
        if (empty($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }
}