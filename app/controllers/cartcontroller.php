<?php
namespace App\Controllers;

use App\Models\Book;
use App\Models\Cart;

class CartController
{
    // 1. Hiển thị trang giỏ hàng
    public function index()
    {
        $cart = new Cart();
        $cartItems = $cart->getItems();
        $totalPrice = $cart->getTotalPrice();
        $pageTitle = "Giỏ hàng của bạn";

        require_once __DIR__ . '/../views/cart/index.php';
    }

    // 2. Thêm sản phẩm vào giỏ hàng / Mua ngay
    public function add($id) 
    {
        // Lấy số lượng từ $_REQUEST (chấp nhận cả POST và GET)
        $quantity = isset($_REQUEST['quantity']) ? max(1, (int)$_REQUEST['quantity']) : 1;
        
        // Lấy thông tin sách từ Model Book
        $bookModel = new \App\Models\Book();
        $product = $bookModel->getBookById($id);

        if ($product) {
            // Thêm sản phẩm vào giỏ
            $cartModel = new \App\Models\Cart();
            $cartModel->add($product, $quantity);

            // Lấy loại hành động (add hoặc buy_now)
            $action = $_REQUEST['action'] ?? 'add';

            // Nếu bấm "MUA NGAY" -> Chuyển thẳng tới trang Thanh toán /checkout
            if ($action === 'buy_now') {
                header('Location: /checkout');
                exit;
            }
        }

        // Nếu bấm "Thêm vào giỏ" -> Quay lại trang trước đó hoặc trang giỏ hàng
        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/cart'));
        exit;
    }

    // 3. Cập nhật số lượng sản phẩm trong giỏ hàng
    public function update($id)
    {
        $quantity = isset($_POST['quantity']) ? max(1, (int)$_POST['quantity']) : 1;
        
        $cart = new Cart();
        $cart->update($id, $quantity);

        header('Location: /cart');
        exit;
    }

    // 4. Xóa sản phẩm khỏi giỏ hàng
    public function delete($id)
    {
        // Logic xóa sản phẩm khỏi Session/CSDL theo $id
        unset($_SESSION['cart'][$id]);

        // Chuyển hướng quay lại trang giỏ hàng
        header('Location: /cart');
        exit();
    }
}