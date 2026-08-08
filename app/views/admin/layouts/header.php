<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Quản Lý Hệ Thống'; ?></title>
    <!-- Các thư viện CDN giữ nguyên -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    
    <!-- THAY ĐỔI: Thêm dấu / vào đầu đường dẫn để không bị sai lệch cấp thư mục -->
    <link href="/css/auth.css" rel="stylesheet">
    
    <style>
        .navbar-admin { background-color: #0056b3; }
    </style>
</head>
<body class="bg-light d-flex flex-column min-vh-100">
<nav class="navbar navbar-expand-lg navbar-dark shadow-sm py-3" style="background-color: #0056b3;">
    <div class="container-fluid px-4">
        
        <!-- BÊN TRÁI: LOGO HỆ THỐNG DÀNH RIÊNG CHO ADMIN -->
        <a class="navbar-brand fw-bold text-white d-flex align-items-center gap-2 m-0 fs-4" href="/admin/dashboard">
            <i class="fa-solid fa-book-open text-warning fs-4"></i>
            <span>T-Bookstore <span class="fw-normal text-warning-50 fs-6">Trang quản trị</span></span>
        </a>

        <!-- BÊN PHẢI: KHỐI THÔNG TIN TÀI KHOẢN ADMIN ĐỒNG BỘ -->
        <div class="d-flex align-items-center gap-2 text-white ms-auto">
            <i class="fa-regular fa-user-circle fs-3 text-warning"></i>
            
            <!-- DROPDOWN MENU CHO ADMIN -->
            <div class="dropdown me-2">
                <a class="nav-link dropdown-toggle fw-bold text-white d-inline-block p-0 align-middle" 
                   href="#" 
                   role="button" 
                   id="adminMenuDropdown" 
                   data-bs-toggle="dropdown" 
                   aria-expanded="false"
                   style="cursor: pointer;">
                    <span class="small text-white-50 d-block fw-normal" style="font-size: 0.75rem; line-height: 1;">Xin chào,</span>
                    <?= htmlspecialchars($_SESSION['user_name'] ?? 'Quản Trị Viên'); ?>
                </a>

                <!-- Khối menu con xổ xuống của Admin -->
                <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2" aria-labelledby="adminMenuDropdown">
                    <li>
                        <a class="dropdown-item py-2" href="/">
                            <i class="fa-solid fa-house text-muted me-2" style="width: 18px;"></i>Xem trang chủ
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item py-2" href="/admin/change-password">
                            <i class="fa-solid fa-key text-muted me-2" style="width: 18px;"></i>Đổi mật khẩu
                        </a>
                    </li>
                    <li><hr class="dropdown-divider my-1"></li>
                    <li>
                        <a class="dropdown-item py-2 text-danger" href="/logout">
                            <i class="fa-solid fa-sign-out-alt me-2" style="width: 18px;"></i>Đăng xuất
                        </a>
                    </li>
                </ul>
            </div>
        </div>

    </div>
</nav>