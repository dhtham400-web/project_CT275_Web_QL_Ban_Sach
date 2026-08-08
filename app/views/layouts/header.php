<?php
// 1. Đảm bảo session luôn hoạt động trên header
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Kiểm tra vai trò Admin (hỗ trợ các kiểu lưu session phổ biến)
$isAdmin = false;
if (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin') {
    $isAdmin = true;
} elseif (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
    $isAdmin = true;
} elseif (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    $isAdmin = true;
}

// 3. Tính tổng số lượng sách trong giỏ hàng
$cartBadgeCount = 0;
if (!empty($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $cartBadgeCount += ($item['quantity'] ?? 0);
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? $pageTitle : 'T-Bookstore'; ?></title>
    
    <!-- BOOTSTRAP 5 VÀ FONT AWESOME -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="/css/auth.css" rel="stylesheet">
    
    <style>
        html, body {
            margin: 0 !important;
            padding: 0 !important;
        }
        body {
            background-color: #f4f6f9;
        }
        .product-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border-radius: 12px;
            overflow: hidden;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.12) !important;
        }
        .btn-buy-now {
            background: linear-gradient(135deg, #ff9900 0%, #ff5500 100%);
            border: none;
            color: white !important;
            transition: all 0.2s ease;
        }
        .btn-buy-now:hover {
            background: linear-gradient(135deg, #ff5500 0%, #cc3300 100%);
            transform: scale(1.02);
        }
        .hover-warning:hover { color: #ffc107 !important; }
        .hover-underline:hover { text-decoration: underline !important; }
    </style>
</head>
<body class="bg-light d-flex flex-column min-vh-100">

<!-- Thanh điều hướng navbar -->
<nav class="navbar navbar-expand-lg navbar-dark shadow-sm py-3" style="background-color: #0056b3;">
    <div class="container-fluid px-4">
        
        <!-- LOGO HỆ THỐNG -->
        <a class="navbar-brand fw-bold text-white d-flex align-items-center gap-2 m-0 fs-4 me-4" href="/">
            <i class="fa-solid fa-book-open text-warning fs-4"></i>
            <span>T-Bookstore</span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarResponsive">
            
            <!-- Ô TÌM KIẾM -->
            <form action="/search" method="GET" class="d-flex flex-grow-1 mx-4">
                <div class="input-group">
                    <input type="text" 
                        name="keyword" 
                        class="form-control" 
                        placeholder="Nhập tên sách, tác giả hoặc danh mục cần tìm..." 
                        value="<?= htmlspecialchars($_GET['keyword'] ?? '') ?>" 
                        required>
                    <button class="btn btn-warning" type="submit">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </div>
            </form>

            <!-- TÀI KHOẢN VÀ GIỎ HÀNG -->
            <div class="d-flex align-items-center gap-4 ms-auto mt-2 mt-lg-0">
                
                <div class="d-flex align-items-center gap-2 text-white">
                    <?php if (isset($_SESSION['user_name']) && !empty($_SESSION['user_name'])): ?>
                        <div class="d-flex align-items-center gap-2">
                            <i class="fa-regular fa-user-circle fs-3 text-warning"></i>
                            <div class="dropdown me-2">
                                <a class="nav-link dropdown-toggle fw-bold text-white d-inline-block p-0 align-middle" 
                                   href="#" 
                                   role="button" 
                                   id="userMenuDropdown" 
                                   data-bs-toggle="dropdown" 
                                   aria-expanded="false">
                                    <span class="small text-white-50 d-block fw-normal" style="font-size: 0.75rem; line-height: 1;">
                                        <?= $isAdmin ? 'Quản trị viên' : 'Xin chào,'; ?>
                                    </span>
                                    <?= htmlspecialchars($_SESSION['user_name']); ?>
                                </a>

                                <ul class="dropdown-menu dropdown-menu-end shadow">
                                    <?php if ($isAdmin): ?>
                                        <!-- MENU ADMIN -->
                                        <li>
                                            <a class="dropdown-item fw-semibold text-primary" href="/admin/orders">
                                                <i class="fa-solid fa-list-check me-2"></i>Quản lý đơn hàng
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item fw-semibold" href="/admin/books">
                                                <i class="fa-solid fa-book-bookmark me-2 text-secondary"></i>Quản lý kho sách
                                            </a>
                                        </li>
                                    <?php else: ?>
                                        <!-- MENU KHÁCH HÀNG -->
                                        <li>
                                            <a class="dropdown-item" href="/orders">
                                                <i class="fa-solid fa-receipt me-2 text-primary"></i>Đơn hàng của tôi
                                            </a>
                                        </li>
                                    <?php endif; ?>

                                    <li>
                                        <a class="dropdown-item" href="/change-password">
                                            <i class="fa-solid fa-key me-2 text-secondary"></i>Đổi mật khẩu
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item text-danger fw-bold" href="/logout">
                                            <i class="fa-solid fa-right-from-bracket me-2"></i>Đăng xuất
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- CHƯA ĐĂNG NHẬP -->
                        <div class="d-flex align-items-center gap-3">
                            <a href="/login" class="text-white text-decoration-none d-flex align-items-center gap-1 hover-warning">
                                <i class="fa-regular fa-user fs-5"></i>
                                <span>Đăng nhập</span>
                            </a>
                            <span class="text-white-50">/</span>
                            <a href="/register" class="text-warning text-decoration-none fw-semibold hover-underline">
                                <span>Đăng Ký</span>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Nút Giỏ hàng -->
                <a href="/cart" class="btn btn-warning fw-bold position-relative">
                    <i class="fa-solid fa-cart-shopping me-1"></i> Giỏ hàng
                    <?php if ($cartBadgeCount > 0): ?>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            <?= $cartBadgeCount ?>
                        </span>
                    <?php endif; ?>
                </a>

            </div>
            
        </div>
    </div>
</nav>

<!-- THÔNG BÁO FLASHDATA HỆ THỐNG -->
<div class="container mt-3">
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm d-flex align-items-center" role="alert">
            <i class="fa-solid fa-circle-check fs-5 me-2"></i>
            <div><?= $_SESSION['success']; ?></div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show shadow-sm d-flex align-items-center" role="alert">
            <i class="fa-solid fa-circle-exclamation fs-5 me-2"></i>
            <div><?= $_SESSION['error']; ?></div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
</div>