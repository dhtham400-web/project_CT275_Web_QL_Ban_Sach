<?php 
$title = "Quản Lý Thành Viên - T-Bookstore";
include_once __DIR__ . '/../layouts/header.php'; 
?>

<div class="container py-4">
    <!-- Breadcrumb điều hướng -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item">
                <a href="/admin/dashboard" class="breadcrumb-link">
                    <i class="fas fa-tachometer-alt me-1"></i>Bảng điều khiển
                </a>
            </li>
            <li class="breadcrumb-item active text-muted" aria-current="page">Thành viên</li>
        </ol>
    </nav>

    <!-- Tiêu đề & Nút thêm mới -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark mb-0">Quản Lý Thành Viên</h2>
        <a href="/admin/users/create" class="btn btn-primary fw-bold px-3 py-2 shadow-sm">
            <i class="fa-solid fa-plus me-1"></i> Thêm Thành Viên Mới
        </a>
    </div>

    <!-- Bảng danh sách thành viên -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-center" style="width: 80px;">STT</th>
                            <th>Họ và Tên</th>
                            <th>Email</th>
                            <th class="text-center">Vai trò</th>
                            <th class="text-center" style="width: 200px;">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($users)): ?>
                            <?php $stt = 1; foreach ($users as $u): ?>
                                <tr>
                                    <td class="text-center fw-bold"><?= $stt++ ?></td>
                                    <td class="fw-semibold"><?= htmlspecialchars($u['name']) ?></td>
                                    <td><?= htmlspecialchars($u['email']) ?></td>
                                    <td class="text-center">
                                        <?php if (($u['role'] ?? 'user') === 'admin'): ?>
                                            <span class="badge bg-danger fs-6 px-3">Quản trị viên</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary fs-6 px-3">Khách hàng</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <a href="/admin/users/edit/<?= $u['id'] ?>" class="btn btn-warning btn-sm fw-medium me-1">
                                            <i class="fa-solid fa-pen-to-square me-1"></i> Sửa
                                        </a>
                                        <a href="/admin/users/delete/<?= $u['id'] ?>" 
                                           class="btn btn-danger btn-sm fw-medium" 
                                           onclick="return confirm('Bạn có chắc chắn muốn xóa thành viên này?');">
                                            <i class="fa-solid fa-trash me-1"></i> Xóa
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">Chưa có thành viên nào trong hệ thống.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php 
include_once __DIR__ . '/../layouts/footer.php'; 
?>