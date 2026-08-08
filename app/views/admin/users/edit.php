<?php 
$title = "Sửa Thành Viên - T-Bookstore";
include_once __DIR__ . '/../layouts/header.php'; 
?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <!-- Breadcrumb điều hướng -->
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="/admin/dashboard" class="breadcrumb-link">
                            <i class="fas fa-tachometer-alt me-1"></i>Bảng điều khiển
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="/admin/users" class="breadcrumb-link">Thành viên</a>
                    </li>
                    <li class="breadcrumb-item active text-muted" aria-current="page">Chỉnh sửa</li>
                </ol>
            </nav>

            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    <h3 class="fw-bold text-primary mb-4">
                        <i class="fa-solid fa-user-pen me-2"></i>Sửa Thành Viên
                    </h3>

                    <form action="/admin/users/edit/<?= $user['id'] ?? '' ?>" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Họ và Tên</label>
                            <input type="text" name="name" class="form-control py-2" value="<?= htmlspecialchars($user['name'] ?? '') ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Địa chỉ Email</label>
                            <input type="email" name="email" class="form-control py-2" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Mật khẩu mới</label>
                            <input type="password" name="password" class="form-control py-2" placeholder="Để trống nếu không muốn đổi mật khẩu">
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Vai trò</label>
                            <select name="role" class="form-select py-2">
                                <option value="user" <?= (($user['role'] ?? '') === 'user') ? 'selected' : '' ?>>Khách hàng (User)</option>
                                <option value="admin" <?= (($user['role'] ?? '') === 'admin') ? 'selected' : '' ?>>Quản trị viên (Admin)</option>
                            </select>
                        </div>

                        <!-- Cụm nút bấm đặt cạnh nhau ở cuối form -->
                        <div class="d-flex gap-2 justify-content-end pt-2">
                            <a href="/admin/users" class="btn btn-outline-secondary px-4 fw-medium">
                                <i class="fa-solid fa-arrow-left me-1"></i> Quay lại
                            </a>

                            <button type="submit" class="btn btn-warning text-dark px-4 fw-bold">
                                <i class="fa-solid fa-rotate me-1"></i> Cập nhật thông tin
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/../layouts/footer.php'; ?>