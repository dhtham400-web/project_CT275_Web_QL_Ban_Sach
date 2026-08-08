<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập Hệ Thống</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="/css/auth.css" rel="stylesheet">
</head>
<body class="login-page">

<div class="login-container">
    <!-- Thêm đoạn này ngay phía trên tiêu đề ĐĂNG NHẬP / ĐĂNG KÝ trong file View -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="/" class="text-decoration-none text-secondary small hover-primary">
            <i class="fa-solid fa-arrow-left me-1"></i> Trang chủ
        </a>
        <a href="/" class="fw-bold text-primary text-decoration-none small">
            <i class="fa-solid fa-book-open text-warning me-1"></i>T-Bookstore
        </a>
    </div>
    <h3 class="login-title text-center text-primary fw-bold mb-4">ĐĂNG NHẬP</h3>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= $error; ?></div>
    <?php endif; ?>

    <form action="/login" method="POST">
        <div class="mb-3">
            <label for="email" class="form-label fw-bold">Địa chỉ Email</label>
            <input type="email" name="email" class="form-control" id="email" placeholder="Nhập email của bạn..." required>
        </div>
        
        <div class="mb-3">
            <label for="password" class="form-label fw-bold">Mật khẩu</label>
            <div class="input-group">
                <input type="password" name="password" class="form-control" id="password" placeholder="Nhập mật khẩu..." required>
                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword()">
                    <i class="fa-solid fa-eye" id="eye-icon"></i>
                </button>
            </div>
        </div>

        <button type="submit" class="btn btn-login mt-3">Đăng Nhập</button>
        
        <div class="text-center mt-3">
            <a href="/register" class="text-decoration-none small text-primary">Chưa có tài khoản? Đăng ký ngay</a>
        </div>
    </form>
</div>

<script>
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eye-icon');
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        eyeIcon.classList.remove('fa-eye');
        eyeIcon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        eyeIcon.classList.remove('fa-eye-slash');
        eyeIcon.classList.add('fa-eye');
    }
}
</script>
</body>
</html>