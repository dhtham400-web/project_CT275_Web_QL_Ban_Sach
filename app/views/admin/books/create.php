<?php 
$title = "Thêm Sách Mới - T-Bookstore";
include_once __DIR__ . '/../layouts/header.php'; 
?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Thanh điều hướng Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="/admin/dashboard" class="text-dark text-decoration-none nav-breadcrumb-link">
                            <i class="fas fa-tachometer-alt me-1"></i>Bảng điều khiển
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="/admin/books" class="text-dark text-decoration-none nav-breadcrumb-link">Quản lý sách</a>
                    </li>
                    <li class="breadcrumb-item active text-muted" aria-current="page">Thêm mới</li>
                </ol>
            </nav>

            <style>
                .nav-breadcrumb-link:hover {
                    color: #0d6efd !important; /* Đổi sang màu xanh dương (Bootstrap primary) khi hover */
                    text-decoration: none !important;
                }
            </style>

            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="fa-solid fa-book-medical me-2"></i>Thêm Sách Mới Vào Hệ Thống</h5>
                </div>
                <div class="card-body p-4">
                    <form action="/admin/books/store" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                        <!-- Tiêu đề sách -->
                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark">Tiêu đề sách <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" placeholder="Nhập tên cuốn sách..." required>
                            <div class="invalid-feedback">Vui lòng nhập tiêu đề sách.</div>
                        </div>

                        <!-- Mô tả sách -->
                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark">Mô tả sách <span class="text-danger">*</span></label>
                            <textarea name="description" class="form-control" rows="4" placeholder="Nội dung chính, điểm nổi bật..." required></textarea>
                            <div class="invalid-feedback">Vui lòng điền mô tả tóm tắt của sách.</div>
                        </div>

                        <div class="row">
                            <!-- Tác giả -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-dark">Tác giả <span class="text-danger">*</span></label>
                                <input type="text" name="author" class="form-control" placeholder="Tên tác giả..." required>
                                <div class="invalid-feedback">Vui lòng nhập tên tác giả.</div>
                            </div>
                            
                            <!-- Danh mục -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-dark">Danh mục <span class="text-danger">*</span></label>
                                <select name="category_id" class="form-select" required>
                                    <option value="">-- Chọn danh mục --</option>
                                    <?php if (!empty($categories)): ?>
                                        <?php foreach ($categories as $cat): ?>
                                            <option value="<?= $cat['id']; ?>"><?= htmlspecialchars($cat['name']); ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <div class="invalid-feedback">Vui lòng chọn một danh mục cụ thể.</div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Giá bán -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-dark">Giá bán (VNĐ) <span class="text-danger">*</span></label>
                                <input type="number" name="price" min="0" class="form-control" placeholder="Ví dụ: 150000" required>
                                <div class="invalid-feedback">Vui lòng nhập giá bán hợp lệ (lớn hơn hoặc bằng 0).</div>
                            </div>
                            
                            <!-- Số lượng -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-dark">Số lượng <span class="text-danger">*</span></label>
                                <input type="number" name="quantity" min="1" value="1" class="form-control" required>
                                <div class="invalid-feedback">Số lượng nhập kho tối thiểu phải từ 1 cuốn.</div>
                            </div>
                        </div>

                        <!-- Ảnh bìa sách -->
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">Ảnh bìa sách <span class="text-danger">*</span></label>
                            <input type="file" name="image" class="form-control" accept="image/*" required>
                            <div class="form-text small text-muted">Định dạng hợp lệ: JPG, PNG, JPEG. Kích thước dưới 2MB.</div>
                            <div class="invalid-feedback">Vui lòng chọn một tệp ảnh bìa cho sách.</div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="/admin/books" class="btn btn-outline-secondary px-4 fw-medium">
                                <i class="fa-solid fa-arrow-left me-1"></i> Quay lại
                            </a>
                            <button type="submit" class="btn btn-primary px-4 fw-bold shadow-sm">
                                <i class="fa-solid fa-floppy-disk me-1"></i> Lưu sản phẩm
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(() => {
    'use strict'
    const forms = document.querySelectorAll('.needs-validation')
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
            }
            form.classList.add('was-validated')
        }, false)
    })
})()
</script>

<?php include_once __DIR__ . '/../layouts/footer.php'; ?>