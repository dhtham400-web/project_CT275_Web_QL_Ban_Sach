<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký Tài Khoản Mới</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="/css/auth.css" rel="stylesheet">
</head>
<body class="bg-light"> <!-- Thêm nền xám nhạt cho đồng bộ -->

<!-- Khối bao sử dụng Flexbox của Bootstrap để căn giữa tuyệt đối -->
<div class="d-flex justify-content-center align-items-center min-vh-100 py-4">
    
    <!-- Thêm class w-100 và giới hạn độ rộng tối đa bằng style để form không bị co quá bé hoặc giãn quá to -->
    <div class="login-container w-100" style="max-width: 450px;">
        <!-- Thêm đoạn này ngay phía trên tiêu đề ĐĂNG NHẬP / ĐĂNG KÝ trong file View -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="/" class="text-decoration-none text-secondary small hover-primary">
                <i class="fa-solid fa-arrow-left me-1"></i> Trang chủ
            </a>
            <a href="/" class="fw-bold text-primary text-decoration-none small">
                <i class="fa-solid fa-book-open text-warning me-1"></i>T-Bookstore
            </a>
        </div>
        <h3 class="login-title text-center text-primary fw-bold mb-4">ĐĂNG KÝ</h3>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger text-center mb-3">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form action="/register" method="POST" onsubmit="return validatePassword()">
            <!-- Họ và Tên -->
            <div class="mb-3">
                <label for="name" class="form-label fw-bold">Họ và Tên</label>
                <input type="text" name="name" class="form-control" id="name" placeholder="Nhập họ và tên của bạn..." required>
            </div>

            <!-- Email -->
            <div class="mb-3">
                <label for="email" class="form-label fw-bold">Địa chỉ Email</label>
                <input type="email" name="email" class="form-control" id="email" placeholder="Nhập địa chỉ email..." required>
            </div>
            
            <!-- Mật khẩu -->
            <div class="mb-3">
                <label for="password" class="form-label fw-bold">Mật khẩu</label>
                <div class="input-group">
                    <input type="password" name="password" class="form-control" id="password" placeholder="Nhập mật khẩu từ 6 ký tự..." required>
                    <button class="btn btn-outline-secondary toggle-password" type="button" onclick="togglePasswordVisibility('password', this)">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </div>
            </div>

            <!-- Nhập lại mật khẩu -->
            <div class="mb-3">
                <label for="confirm_password" class="form-label fw-bold">Nhập lại mật khẩu</label>
                <div class="input-group">
                    <input type="password" name="confirm_password" class="form-control" id="confirm_password" placeholder="Nhập lại mật khẩu chính xác..." required>
                    <button class="btn btn-outline-secondary toggle-password" type="button" onclick="togglePasswordVisibility('confirm_password', this)">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </div>
                <div id="password-error" class="text-danger small mt-1" style="display: none;">Mật khẩu nhập lại không khớp!</div>
            </div>

            <button type="submit" class="btn btn-login w-100 mt-3">Đăng Ký Ngay</button> <!-- Thêm w-100 cho nút dài ra bằng form sẽ đẹp hơn -->
            
            <div class="text-center mt-3">
                <a href="/login" class="text-decoration-none small text-primary">Đã có tài khoản? Đăng nhập</a>
            </div>
        </form>
    </div>
</div>

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

function validatePassword() {
    const password = document.getElementById("password").value;
    const confirmPassword = document.getElementById("confirm_password").value;
    const errorDiv = document.getElementById("password-error");

    if (password !== confirmPassword) {
        errorDiv.style.display = "block";
        return false;
    }
    errorDiv.style.display = "none";
    return true;
}
</script>
</body>
</html>