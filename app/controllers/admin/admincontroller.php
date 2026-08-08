<?php
namespace App\Controllers\Admin;

use App\Controllers\Admin\AdminBaseController;

class AdminController extends AdminBaseController
{
    // Hàm hien thi trang chu quan tri
    public function dashboard()
    {
        // 1. Kết nối Database PostgreSQL
        $db = \App\Core\Database::getConnection();

        // 2. Thực hiện các câu lệnh thống kê số liệu thực tế
        $stmtBook = $db->query("SELECT COUNT(*) AS total FROM books");
        $totalBooks = $stmtBook->fetch(\PDO::FETCH_ASSOC)['total'];

        $stmtCat = $db->query("SELECT COUNT(*) AS total FROM categories");
        $totalCategories = $stmtCat->fetch(\PDO::FETCH_ASSOC)['total'];

        $stmtUser = $db->query("SELECT COUNT(*) AS total FROM users");
        $totalUsers = $stmtUser->fetch(\PDO::FETCH_ASSOC)['total'];

        // BỔ SUNG: Truy vấn đếm tổng số đơn hàng trong hệ thống
        $stmtOrder = $db->query("SELECT COUNT(*) AS total FROM orders");
        $totalOrders = $stmtOrder->fetch(\PDO::FETCH_ASSOC)['total'];

        // 3. Lấy tên Admin từ Session
        $adminName = $_SESSION['user']['name'] ?? $_SESSION['admin_name'] ?? $_SESSION['user_name'] ?? 'Quản Trị Viên';

        // BỔ SUNG: Tính tổng doanh thu từ các đơn hàng Đã hoàn thành (completed)
        $stmtRevenue = $db->query("SELECT SUM(total_money) AS total FROM orders WHERE LOWER(status) IN ('completed', 'đã hoàn thành')");
        $totalRevenue = $stmtRevenue->fetch(\PDO::FETCH_ASSOC)['total'] ?? 0;

        // 4. Gom tất cả các biến vào mảng dữ liệu duy nhất
        $data = [
            'title'           => 'Trang Quản Trị - T-Bookstore',
            'totalBooks'      => $totalBooks,
            'totalCategories' => $totalCategories,
            'totalUsers'      => $totalUsers,
            'totalOrders'     => $totalOrders, // BỔ SUNG: Truyền tổng đơn hàng ra view
            'totalRevenue'    => $totalRevenue, // BỔ SUNG: Biến tổng doanh thu
            'admin_name'      => $adminName 
        ];

        // 5. Đổ dữ liệu ra view
        return $this->render('admin.dashboard', $data);
    }
}