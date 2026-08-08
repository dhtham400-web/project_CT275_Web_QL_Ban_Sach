<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Lấy đường dẫn URL hiện tại
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Kiểm tra nếu người dùng truy cập bất kỳ đường dẫn nào bắt đầu bằng /admin
if (strpos($requestUri, '/admin') === 0) {
    $currentUser = $_SESSION['user'] ?? null;
    $role = strtolower(trim($currentUser['role'] ?? ''));

    // Chưa đăng nhập HOẶC role không phải 'admin' -> Đẩy ngay về trang đăng nhập
    if (!$currentUser || $role !== 'admin') {
        $_SESSION['error'] = "Tài khoản của bạn không có quyền truy cập khu vực Quản trị!";
        header('Location: /login');
        exit();
    }
}

// Nhung file tu dong nap cua Composer
require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Router;
use App\Controllers\CartController;
use App\Controllers\CheckoutController;

// Khoi tao doi tuong Router
$router = new Router();

// --- DANG KY CAC DUONG DAN (ROUTES) TAI DAY ---

// Trang chu nguoi dung
$router->get('/', 'homecontroller@index');

// Cac route xu ly dang nhap va dang xuat
$router->get('/login', 'authcontroller@login');       // Hien thi form login
$router->post('/login', 'authcontroller@postLogin');   // Xu ly khi bam nut dang nhap
$router->get('/logout', 'authcontroller@logout');     // Xu ly dang xuat
$router->get('/admin/dashboard', 'admin\adminController@dashboard');

// Cac route xu ly dang ky tai khoan
$router->get('/register', 'authcontroller@register');       // Hien thi form dang ky
$router->post('/register', 'authcontroller@postRegister');   // Xu ly luu tai khoan moi

// Thêm vào cùng nhóm với các route Auth/User hiện tại 
$router->get('/change-password', 'authcontroller@showChangePassword');
$router->post('/change-password', 'authcontroller@handleChangePassword');

// Đăng ký route cho trang chi tiết sách
$router->get('/books/detail/(\d+)', 'admin\bookcontroller@show');

// Route cho Giỏ hàng (Cart)
$router->get('/cart', 'cartcontroller@index');
$router->post('/cart/add/(\d+)', 'cartcontroller@add');
$router->post('/cart/update/(\d+)', 'cartcontroller@update');
$router->get('/cart/delete/(\d+)', 'cartcontroller@delete');

// Route cho Thanh toán (Checkout)
$router->get('/checkout', 'checkoutcontroller@index');
$router->post('/checkout/process', 'checkoutcontroller@process');
$router->get('/checkout/success', 'checkoutcontroller@success');

// Đã sửa: Bỏ prefix App\Controllers\ bị lặp
$router->get('/orders/detail/(\d+)', 'ordercontroller@detail');

// Route xem Lịch sử đơn hàng của người dùng
$router->get('/orders', 'ordercontroller@index');

// Route cho Khách hàng xem & hủy đơn hàng
$router->get('/orders/detail/(\d+)', 'ordercontroller@detail');
$router->get('/orders/cancel/(\d+)', 'ordercontroller@cancel');
$router->post('/orders/cancel/(\d+)', 'ordercontroller@cancel');

// Route Tìm kiếm
$router->get('/search', 'admin\bookcontroller@search');

// Route Quản lý sách (Admin)
$router->get('/admin/books/create', 'admin\bookcontroller@create');
$router->post('/admin/books/store', 'admin\bookcontroller@store');
$router->get('/admin/books', 'admin\bookcontroller@index');
$router->get('/admin/books/delete/(\d+)', 'admin\bookcontroller@delete');
$router->get('/admin/books/edit/(\d+)', 'admin\bookcontroller@edit');
$router->post('/admin/books/update/(\d+)', 'admin\bookcontroller@update');

// Route Quản lý danh mục (Admin)
$router->get('/admin/categories', 'Admin\CategoryController@index');
$router->get('/admin/categories/create', 'Admin\CategoryController@create');
$router->post('/admin/categories/store', 'Admin\CategoryController@store');
$router->get('/admin/categories/edit/(\d+)', 'Admin\CategoryController@edit');
$router->post('/admin/categories/update/(\d+)', 'Admin\CategoryController@update');
$router->get('/admin/categories/delete/(\d+)', 'Admin\CategoryController@delete');
$router->post('/admin/categories/updateHomeStatus', 'Admin\CategoryController@updateHomeStatus');

// Route Quản lý thành viên (Admin)
$router->get('/admin/users', 'Admin\UserController@index');
$router->get('/admin/users/create', 'Admin\UserController@create');
$router->post('/admin/users/create', 'Admin\UserController@create');
$router->get('/admin/users/edit/(\d+)', 'Admin\UserController@edit');
$router->post('/admin/users/edit/(\d+)', 'Admin\UserController@edit');
$router->get('/admin/users/delete/(\d+)', 'Admin\UserController@delete');

// Route Quản lý đơn hàng (Admin)
$router->get('/admin/orders', 'Admin\OrderController@index');
$router->get('/admin/orders/detail/(\d+)', 'Admin\OrderController@detail');
$router->post('/admin/orders/update/(\d+)', 'Admin\OrderController@updateStatus');

// --- KICH HOAT BO DINH TUYEN ---
$router->dispatch();