<?php 
$pageTitle = htmlspecialchars($book['title']) . " - T-Bookstore";
include_once __DIR__ . '/../layouts/header.php'; 
?>

<div class="container py-5 flex-grow-1">
    <!-- Breadcrumb điều hướng -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/" class="text-decoration-none"><i class="fas fa-home me-1"></i>Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="/category/<?= $book['category_id'] ?>" class="text-decoration-none"><?= htmlspecialchars($book['category_name'] ?? 'Chưa phân loại') ?></a></li>
            <li class="breadcrumb-item active text-truncate" aria-current="page" style="max-width: 300px;"><?= htmlspecialchars($book['title']) ?></li>
        </ol>
    </nav>

    <div class="row g-4 bg-white p-4 rounded shadow-sm border">
        <!-- Cột bên trái: Ảnh bìa sách lớn -->
        <div class="col-md-5 text-center border-end pe-md-4">
            <div class="p-3 bg-light rounded d-flex align-items-center justify-content-center" style="min-height: 400px;">
                <img src="/uploads/<?= htmlspecialchars($book['image']); ?>" class="img-fluid rounded shadow" alt="<?= htmlspecialchars($book['title']); ?>" style="max-height: 380px; object-fit: contain;">
            </div>
        </div>

        <!-- Cột bên phải: Thông tin chi tiết & Đặt mua -->
        <div class="col-md-7 ps-md-4 d-flex flex-column justify-content-between">
            <div>
                <h2 class="fw-bold text-dark mb-2"><?= htmlspecialchars($book['title']); ?></h2>
                
                <div class="mb-3" style="font-size: 0.9rem;">
                    <span class="text-muted me-3">Tác giả: <strong class="text-dark"><?= htmlspecialchars($book['author'] ?? 'Chưa rõ'); ?></strong></span>
                    <span class="text-muted">Danh mục: <span class="badge bg-secondary"><?= htmlspecialchars($book['category_name'] ?? 'Chưa phân loại'); ?></span></span>
                </div>

                <div class="p-3 bg-light rounded mb-4">
                    <span class="text-muted d-block small mb-1">Giá bán chính thức</span>
                    <h3 class="text-danger fw-bold mb-0"><?= number_format($book['price'], 0, ',', '.'); ?> đ</h3>
                </div>

                <!-- Tình trạng kho và lượt mua -->
                <div class="mb-4 text-muted small">
                    <span class="me-4"><i class="fa-solid fa-boxes-stacked me-1 text-success"></i>Tình trạng: <strong><?= $book['quantity'] > 0 ? 'Còn hàng' : 'Hết hàng'; ?></strong></span>
                    <span><i class="fa-solid fa-cart-shopping me-1 text-primary"></i>Đã bán: <strong><?= $book['sold'] ?? 0; ?></strong> cuốn</span>
                </div>

                <form action="/cart/add/<?= $book['id'] ?>" method="POST" class="mb-4">
                    <!-- Khối chọn số lượng -->
                    <div class="d-flex align-items-center mb-3">
                        <label for="quantity" class="fw-bold me-3">Số lượng:</label>
                        <input type="number" 
                            id="quantity" 
                            name="quantity" 
                            value="1" 
                            min="1" 
                            max="<?= $book['quantity'] ?>" 
                            class="form-control text-center me-2" 
                            style="width: 70px;">
                        <span class="text-muted small">(Còn lại <?= $book['quantity'] ?> cuốn)</span>
                    </div>

                    <!-- Khối nút bấm -->
                    <div class="d-flex gap-2">
                        <button type="submit" name="action" value="add" class="btn btn-outline-primary px-3 py-2 fw-semibold" <?= $book['quantity'] <= 0 ? 'disabled' : ''; ?>>
                            <i class="fas fa-cart-plus me-1"></i> Thêm vào giỏ hàng
                        </button>
                        
                        <button type="submit" name="action" value="buy_now" class="btn btn-warning px-4 py-2 text-white fw-bold" <?= $book['quantity'] <= 0 ? 'disabled' : ''; ?>>
                            <i class="fas fa-bolt me-1"></i> MUA NGAY
                        </button>
                    </div>
                </form>
            </div>

            <!-- Khối thông tin chi tiết thêm & Mô tả -->
            <div class="border-top pt-3">
                <h5 class="fw-bold text-dark mb-2"><i class="fa-solid fa-file-lines me-2 text-secondary"></i>Tóm tắt nội dung</h5>
                <p class="text-muted lh-base" style="font-size: 0.95rem; text-align: justify;">
                    <?= !empty($book['description']) ? nl2br(htmlspecialchars($book['description'])) : 'Hiện tại chưa có bài viết tóm tắt chi tiết nội dung cho cuốn sách này. Nội dung đang được cập nhật.'; ?>
                </p>
            </div>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/../layouts/footer.php'; ?>