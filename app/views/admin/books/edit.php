<?php 
$title = "Chỉnh Sửa Sách - T-Bookstore";
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
                    <li class="breadcrumb-item active text-muted" aria-current="page">Chỉnh sửa</li>
                </ol>
            </nav>

            <style>
                .nav-breadcrumb-link:hover {
                    color: #0d6efd !important; /* Đổi sang màu xanh dương (Bootstrap primary) khi hover */
                    text-decoration: none !important;
                }
            </style>

            <?php if (isset($_SESSION['success'])): ?>
                <div id="flash-alert" class="alert alert-success alert-dismissible fade show shadow-sm mb-3" role="alert">
                    <i class="fas fa-check-circle me-2"></i><?= $_SESSION['success']; unset($_SESSION['success']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div id="flash-alert" class="alert alert-danger alert-dismissible fade show shadow-sm mb-3" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i><?= $_SESSION['error']; unset($_SESSION['error']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-warning text-dark py-3">
                    <h5 class="mb-0 fw-bold"><i class="fa-solid fa-pen-to-square me-2"></i>Chỉnh Sửa Thông Tin Sách</h5>
                </div>
                <div class="card-body p-4">
                    <form action="/admin/books/update/<?= $book['id'] ?>" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="old_image" value="<?= htmlspecialchars($book['image'] ?? '') ?>">

                        <!-- Tiêu đề sách -->
                        <div class="mb-3">
                            <label for="title" class="form-label fw-bold">Tiêu đề sách</label>
                            <input type="text" class="form-control" id="title" name="title" value="<?= htmlspecialchars($book['title']); ?>" required>
                        </div>

                        <!-- Mô tả sách -->
                        <div class="mb-3">
                            <label for="description" class="form-label fw-bold">Mô tả sách</label>
                            <textarea class="form-control" name="description" rows="4"><?= htmlspecialchars($book['description'] ?? '') ?></textarea>
                        </div>

                        <div class="row">
                            <!-- Tác giả -->
                            <div class="col-md-6 mb-3">
                                <label for="author" class="form-label fw-bold">Tác giả</label>
                                <input type="text" class="form-control" id="author" name="author" value="<?= htmlspecialchars($book['author']); ?>" required>
                            </div>
                            <!-- Danh mục -->
                            <div class="col-md-6 mb-3">
                                <label for="category_id" class="form-label fw-bold">Danh mục</label>
                                <select class="form-select" id="category_id" name="category_id" required>
                                    <option value="">-- Chọn danh mục --</option>
                                    <?php if(!empty($categories)): ?>
                                        <?php foreach($categories as $cat): ?>
                                            <option value="<?= $cat['id']; ?>" <?= $cat['id'] == $book['category_id'] ? 'selected' : ''; ?>>
                                                <?= htmlspecialchars($cat['name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Giá bán -->
                            <div class="col-md-6 mb-3">
                                <label for="price" class="form-label fw-bold">Giá bán (VNĐ)</label>
                                <input type="number" class="form-control" id="price" name="price" value="<?= $book['price']; ?>" min="0" required>
                            </div>
                            <!-- Số lượng -->
                            <div class="col-md-6 mb-3">
                                <label for="quantity" class="form-label fw-bold">Số lượng</label>
                                <input type="number" class="form-control" id="quantity" name="quantity" value="<?= $book['quantity']; ?>" min="0" required>
                            </div>
                        </div>

                        <!-- Ảnh bìa sách -->
                        <div class="mb-4">
                            <label for="image" class="form-label fw-bold">Ảnh bìa sách</label>
                            <input class="form-control mb-2" type="file" id="image" name="image" accept="image/*">
                            <div class="form-text text-muted mb-2">Định dạng hợp lệ: JPG, PNG, JPEG. Để trống nếu muốn giữ nguyên ảnh cũ.</div>
                            
                            <?php if(!empty($book['image'])): ?>
                                <div class="mt-2">
                                    <span class="d-block text-secondary small mb-1">Ảnh hiện tại:</span>
                                    <img src="/uploads/<?= htmlspecialchars($book['image']); ?>" class="img-thumbnail" style="max-height: 120px;" alt="Bìa sách hiện tại">
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="/admin/books" class="btn btn-outline-secondary px-4 fw-medium">
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

<script>
document.addEventListener("DOMContentLoaded", function() {
    const alertEl = document.getElementById("flash-alert");
    if (alertEl) {
        setTimeout(function() {
            alertEl.style.transition = "opacity 0.5s ease";
            alertEl.style.opacity = "0";
            setTimeout(function() { alertEl.remove(); }, 500);
        }, 5000); 
    }
});
</script>

<?php include_once __DIR__ . '/../layouts/footer.php'; ?>