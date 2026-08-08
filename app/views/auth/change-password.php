<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đổi Mật Khẩu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="/css/auth.css" rel="stylesheet">
</head>
<script>
function togglePasswordVisibility(inputId, button) {
    const input = document.getElementById(inputId);
    const icon = button.querySelector('i');
    
    if (input.type === "password") {
        input.type = "text";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
    } else {
        input.type = "password";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
    }
}

// Bổ sung hàm kiểm tra khớp mật khẩu khi nhấn Cập nhật
document.querySelector('form').addEventListener('submit', function(e) {
    const newPass = document.getElementById('new_password').value;
    const confirmPass = document.getElementById('confirm_password').value;
    const errorDiv = document.getElementById('password-error');

    if (newPass !== confirmPass) {
        e.preventDefault(); // Chặn gửi form
        errorDiv.style.display = 'block';
    } else {
        errorDiv.style.display = 'none';
    }
});
</script>
<body class="login-page">

<div class="login-container">
    <div class="text-center mb-4">
        <!-- Thêm đoạn này ngay phía trên tiêu đề ĐĂNG NHẬP / ĐĂNG KÝ trong file View -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="/" class="text-decoration-none text-secondary small hover-primary">
                <i class="fa-solid fa-arrow-left me-1"></i> Trang chủ
            </a>
            <a href="/" class="fw-bold text-primary text-decoration-none small">
                <i class="fa-solid fa-book-open text-warning me-1"></i>T-Bookstore
            </a>
        </div>
        <h3 class="fw-bold text-primary"><i class="fa-solid fa-key me-2"></i>Đổi Mật Khẩu</h3>
        <p class="text-muted small">Vui lòng nhập mật khẩu hiện tại và mật khẩu mới của bạn</p>
    </div>

    <!-- Hiển thị thông báo lỗi nếu có từ Session -->
    <?php 
    if (session_status() == PHP_SESSION_NONE) { session_start(); }
    if (isset($_SESSION['error'])): 
    ?>
        <div class="alert alert-danger alert-dismissible fade show small" role="alert">
            <i class="fa-solid fa-circle-exclamation me-2"></i><?= $_SESSION['error']; unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <form action="/change-password" method="POST">
       <!-- Mật khẩu hiện tại -->
        <div class="mb-3">
            <label for="current_password" class="form-label fw-bold">Mật khẩu hiện tại</label>
            <div class="input-group">
                <input type="password" name="current_password" class="form-control" id="current_password" placeholder="••••••••" required>
                <button class="btn btn-outline-secondary toggle-password" type="button" onclick="togglePasswordVisibility('current_password', this)">
                    <i class="fa-solid fa-eye"></i>
                </button>
            </div>
        </div>

        <!-- Mật khẩu mới -->
        <div class="mb-3">
            <label for="new_password" class="form-label fw-bold">Mật khẩu mới</label>
            <div class="input-group">
                <input type="password" name="new_password" class="form-control" id="new_password" placeholder="Tối thiểu 6 ký tự" required>
                <button class="btn btn-outline-secondary toggle-password" type="button" onclick="togglePasswordVisibility('new_password', this)">
                    <i class="fa-solid fa-eye"></i>
                </button>
            </div>
        </div>

        <!-- Xác nhận mật khẩu mới -->
        <div class="mb-3">
            <label for="confirm_password" class="form-label fw-bold">Xác nhận mật khẩu mới</label>
            <div class="input-group">
                <input type="password" name="confirm_password" class="form-control" id="confirm_password" placeholder="Nhập lại mật khẩu mới" required>
                <button class="btn btn-outline-secondary toggle-password" type="button" onclick="togglePasswordVisibility('confirm_password', this)">
                    <i class="fa-solid fa-eye"></i>
                </button>
            </div>
            <div id="password-error" class="text-danger small mt-1" style="display: none;">Mật khẩu mới nhập lại không khớp!</div>
        </div>

        <!-- Nút bấm hành động -->
        <button type="submit" class="btn btn-login mb-2 shadow-sm">Cập nhật mật khẩu</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>