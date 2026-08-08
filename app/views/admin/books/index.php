<?php include __DIR__ . '/../layouts/header.php'; ?>

<!-- Khung chứa nội dung bảng dữ liệu sách -->
<div class="container-fluid px-4 mt-4 flex-grow-1">
    
    <!-- Hệ thống hiển thị thông báo phản hồi từ Session -->
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

    <div class="card shadow-sm border-0">
        <!-- Card Header: Breadcrumb và Tiêu đề trang -->
        <div class="card-header bg-white pt-3 pb-2 border-0">
            <!-- Breadcrumb đường dẫn điều hướng -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="/admin/dashboard" class="breadcrumb-link">
                            <i class="fas fa-tachometer-alt me-1"></i>Bảng điều khiển
                        </a>
                    </li>
                    <li class="breadcrumb-item active current-page" aria-current="page">
                        Danh sách sách
                    </li>
                </ol>
            </nav>
            
            <!-- Tiêu đề & Nút thêm sách mới -->
            <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3">
                <h2 class="fw-bold mb-0 text-dark">Danh Sách Sách</h2>
                <a href="/admin/books/create" class="btn btn-primary px-3 shadow-sm">
                    <i class="fas fa-plus me-1"></i> Thêm Sách Mới
                </a>
            </div>

            <!-- THANH TÌM KIẾM VÀ BỘ LỌC DANH MỤC -->
            <form method="GET" action="/admin/books" class="row g-2 align-items-center mb-1">
                <!-- Ô tìm kiếm theo tên hoặc tác giả -->
                <div class="col-md-5">
                    <div class="input-group input-group-sm shadow-sm">
                        <span class="input-group-text bg-white"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="Tìm tên sách hoặc tác giả..." 
                               value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                    </div>
                </div>

                <!-- Bộ lọc theo Danh mục -->
                <div class="col-md-4">
                    <div class="shadow-sm">
                        <select name="category_id" class="form-select form-select-sm">
                            <option value="">-- Tất cả danh mục --</option>
                            <?php if (!empty($allCategories)): ?>
                                <?php foreach ($allCategories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>" <?= isset($_GET['category_id']) && $_GET['category_id'] == $cat['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($cat['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>

                <!-- Nút áp dụng và xóa bộ lọc nhanh -->
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-sm btn-dark px-3 shadow-sm flex-grow-1fw-semibold">
                        <i class="fa-solid fa-filter me-1"></i> Tìm lọc
                    </button>
                    <?php if (!empty($_GET['search']) || !empty($_GET['category_id'])): ?>
                        <a href="/admin/books" class="btn btn-sm btn-outline-secondary shadow-sm" title="Xóa bộ lọc">
                            <i class="fa-solid fa-rotate-left"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- Card Body: Bảng dữ liệu chuẩn 8 cột -->
        <div class="card-body p-0 mt-2">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle mb-0 text-center">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col" style="width: 60px;">ID</th>
                            <th scope="col" style="width: 100px;">Hình ảnh</th>
                            <th scope="col" class="text-start">Tiêu đề sách</th>
                            <th scope="col">Tác giả</th>
                            <th scope="col">Danh mục</th>
                            <th scope="col" style="width: 130px;">Giá bán</th>
                            <th scope="col" style="width: 110px;">Số lượng</th>
                            <th scope="col" style="width: 180px;">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($books)): ?>
                            <?php foreach ($books as $book): ?>
                                <tr>
                                    <td class="text-secondary fw-semibold"><?= $book['id']; ?></td>
                                    <td>
                                        <?php if (!empty($book['image'])): ?>
                                            <img src="/uploads/<?= $book['image']; ?>" alt="Book Cover" style="width: 45px; height: 60px; object-fit: cover;" class="img-thumbnail shadow-sm">
                                        <?php else: ?>
                                            <div class="bg-light text-muted d-flex align-items-center justify-content-center rounded border mx-auto" style="width: 45px; height: 60px; font-size: 10px;">
                                                No Image
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-start fw-bold text-primary">
                                        <?= htmlspecialchars($book['title']); ?>
                                    </td>
                                    <td class="text-muted"><?= htmlspecialchars($book['author']); ?></td>
                                    <td>
                                        <span class="badge bg-secondary px-2.5 py-2 fw-semibold text-white">
                                            <?= htmlspecialchars($book['category_name'] ?? 'Chưa phân loại'); ?>
                                        </span>
                                    </td>
                                    <td class="text-danger fw-bold">
                                        <?= number_format($book['price'], 0, ',', '.'); ?> đ
                                    </td>
                                    <td>
                                        <span class="badge bg-info text-dark fw-bold rounded-pill px-3 py-1.5">
                                            <?= $book['quantity']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="/admin/books/edit/<?= $book['id']; ?>" class="btn btn-warning btn-sm px-2.5 text-dark fw-semibold shadow-sm">
                                                <i class="fa-solid fa-pen-to-square me-1"></i> Sửa
                                            </a>
                                            <a href="/admin/books/delete/<?= $book['id']; ?>" 
                                               class="btn btn-danger btn-sm px-2.5 fw-semibold shadow-sm" 
                                               onclick="return confirm('Bạn có chắc chắn muốn xóa cuốn sách này không?');">
                                                <i class="fa-solid fa-trash me-1"></i> Xóa
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted fs-6">
                                    <i class="fas fa-folder-open me-2"></i>Không tìm thấy cuốn sách nào khớp với bộ lọc tìm kiếm.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Kịch bản JavaScript tự động ẩn sau 10 giây -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    const alertEl = document.getElementById("flash-alert");
    if (alertEl) {
        setTimeout(function() {
            alertEl.style.transition = "opacity 0.5s ease";
            alertEl.style.opacity = "0";
            setTimeout(function() {
                alertEl.remove();
            }, 500);
        }, 10000); 
    }
});
</script>
<?php include __DIR__ . '/../layouts/footer.php'; ?>