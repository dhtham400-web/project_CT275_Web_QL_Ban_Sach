<?php include __DIR__ . '/../layouts/header.php'; ?>

<!-- Khung chứa nội dung bảng dữ liệu -->
<div class="container-fluid px-4 mt-4 flex-grow-1">
    
    <!-- Đặt thông báo lỗi/thành công ở đây để nằm độc lập, hiển thị đẹp và thoáng hơn -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-3" role="alert">
            <i class="fas fa-check-circle me-2"></i><?= $_SESSION['success']; unset($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-3" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i><?= $_SESSION['error']; unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm border-0">
        <!-- Card Header: Chứa Breadcrumb và Tiêu đề giống trang sách -->
        <div class="card-header bg-white pt-3 pb-2 border-0">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="/admin/dashboard" class="breadcrumb-link">
                            <i class="fas fa-tachometer-alt me-1"></i>Bảng điều khiển
                        </a>
                    </td>
                    <li class="breadcrumb-item active current-page" aria-current="page">
                        Danh mục
                    </li>
                </ol>
            </nav>
            
            <!-- Tiêu đề + Nút thêm danh mục (đưa lên cùng hàng) -->
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="fw-bold mb-0 text-dark">Quản Lý Danh Mục</h2>
                <a href="/admin/categories/create" class="btn btn-primary px-3 shadow-sm">
                    <i class="fas fa-plus me-1"></i> Thêm Danh Mục Mới
                </a>
            </div>
        </div>

        <!-- Card Body: Chứa bảng dữ liệu -->
        <div class="card-body p-0 mt-2">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle mb-0 text-center">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col" style="width: 80px;">STT</th>
                            <th scope="col" class="text-start">Tên danh mục</th>
                            <th scope="col" style="width: 200px;">Số lượng sách</th>
                            <th scope="col" style="width: 180px;">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($categories)): ?>
                            <?php foreach ($categories as $index => $cat): ?>
                                <tr>
                                    <td class="fw-bold text-secondary"><?= $index + 1; ?></td>
                                    <td class="text-start fw-bold text-primary"><?= htmlspecialchars($cat['name']); ?></td>
                                    <td>
                                        <span class="badge bg-info text-dark fw-bold px-3 py-2 rounded-pill">
                                            <?= $cat['total_books']; ?> cuốn sách
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            <!-- Icon Sửa -->
                                            <a href="/admin/categories/edit/<?= $cat['id']; ?>" class="btn btn-warning btn-sm px-2 text-dark fw-semibold shadow-sm">
                                                <i class="fa-solid fa-pen-to-square me-1"></i> Sửa
                                            </a>
                                            <!-- Icon Xóa kèm ràng buộc xác nhận -->
                                            <a href="/admin/categories/delete/<?= $cat['id']; ?>" 
                                               class="btn btn-danger btn-sm px-2 fw-semibold shadow-sm" 
                                               onclick="return confirm('Bạn có chắc chắn muốn xóa danh mục này?');">
                                                <i class="fa-solid fa-trash me-1"></i> Xóa
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <!-- TĂNG COLSPAN THÀNH 5 ĐỂ VỪA VỚI CỘT MỚI THÊM -->
                                <td colspan="5" class="text-center py-4 text-muted">Chưa có danh mục nào trong hệ thống.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- ĐIỂM MỚI 3: ĐOẠN JAVASCRIPT GỬI DỮ LIỆU ĐỂ LƯU KHÔNG CẦN TẢI LẠI TRANG -->
<script>
document.querySelectorAll('.status-switch').forEach(function(checkbox) {
    checkbox.addEventListener('change', function() {
        const categoryId = this.getAttribute('data-id');
        // Checkbox được tích chọn gửi lên số 1, ngược lại gửi số 0
        const isChecked = this.checked ? 1 : 0;

        fetch('/admin/categories/updateHomeStatus', { 
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                id: categoryId,
                status: isChecked
            })
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                alert("Có lỗi xảy ra khi lưu trạng thái!");
                this.checked = !this.checked; // Quay lại trạng thái cũ nếu lưu thất bại
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert("Lỗi kết nối với máy chủ!");
            this.checked = !this.checked;
        });
    });
});
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>