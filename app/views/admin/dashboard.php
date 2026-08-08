<?php 
$title = $title ?? "Bảng Điều Khiển - T-Bookstore";
include_once __DIR__ . '/layouts/header.php'; 
?>

<!-- Nội dung chính: Tự động giãn để đẩy footer xuống -->
<div class="container-fluid px-4 mt-4 flex-grow-1">
    <h3 class="fw-bold text-dark mb-4">Bảng Điều Khiển Hệ Thống</h3>

    <!-- 1. HÀNG THỐNG KÊ SỐ LIỆU NHANH (CARDS) -->
    <div class="row g-3 mb-4">
        <!-- Tổng số sách -->
        <div class="col-md-6 col-lg-3">
            <div class="card bg-primary text-white border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center justify-content-between p-4">
                    <div>
                        <h6 class="text-uppercase text-white-50 small mb-1">Tổng Số Sách</h6>
                        <h2 class="fw-bold mb-0"><?= $totalBooks ?? 0; ?></h2>
                    </div>
                    <div class="fs-1 opacity-50">
                        <i class="fa-solid fa-book"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tổng số danh mục -->
        <div class="col-md-6 col-lg-3">
            <div class="card bg-success text-white border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center justify-content-between p-4">
                    <div>
                        <h6 class="text-uppercase text-white-50 small mb-1">Danh Mục</h6>
                        <h2 class="fw-bold mb-0"><?= $totalCategories ?? 0; ?></h2>
                    </div>
                    <div class="fs-1 opacity-50">
                        <i class="fa-solid fa-list"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tổng số người dùng -->
        <div class="col-md-6 col-lg-3">
            <div class="card bg-warning text-white border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center justify-content-between p-4">
                    <div>
                        <h6 class="text-uppercase text-white-50 small mb-1">Người Dùng</h6>
                        <h2 class="fw-bold mb-0"><?= $totalUsers ?? 0; ?></h2>
                    </div>
                    <div class="fs-1 opacity-50">
                        <i class="fa-solid fa-users"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tổng đơn hàng -->
        <div class="col-md-6 col-lg-3">
            <div class="card bg-danger text-white border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center justify-content-between p-4">
                    <div>
                        <h6 class="text-uppercase text-white-50 small mb-1">Tổng Đơn Hàng</h6>
                        <h2 class="fw-bold mb-0"><?= $totalOrders ?? 0; ?></h2>
                    </div>
                    <div class="fs-1 opacity-50">
                        <i class="fa-solid fa-cart-shopping"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. HÀNG MENU ĐIỀU HƯỚNG NHANH ĐẾN CÁC TRANG QUẢN LÝ -->
    <div class="row g-3">
        <div class="col-md-6 col-lg-3">
            <a href="/admin/books" class="btn btn-outline-primary w-100 py-3 fw-bold shadow-sm">
                <i class="fa-solid fa-gear me-2"></i> Quản Lý Sách
            </a>
        </div>
        <div class="col-md-6 col-lg-3">
            <a href="/admin/categories" class="btn btn-outline-success w-100 py-3 fw-bold shadow-sm">
                <i class="fa-solid fa-list-check me-2"></i> Quản Lý Danh Mục
            </a>
        </div>
        <div class="col-md-6 col-lg-3">
            <a href="/admin/users" class="btn btn-outline-warning w-100 py-3 fw-bold shadow-sm">
                <i class="fa-solid fa-user-shield me-2"></i> Quản Lý Thành Viên
            </a>
        </div>
        <div class="col-md-6 col-lg-3">
            <a href="/admin/orders" class="btn btn-outline-danger w-100 py-3 fw-bold shadow-sm">
                <i class="fa-solid fa-cart-shopping me-2"></i> Quản Lý Đơn Hàng
            </a>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/layouts/footer.php'; ?>