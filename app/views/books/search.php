<?php 
$pageTitle = "Kết quả tìm kiếm";
include_once __DIR__ . '/../../views/layouts/header.php'; 
?>

<div class="container py-4 flex-grow-1">
    <!-- Breadcrumb điều hướng -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="/" class="text-decoration-none">Trang chủ</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tìm kiếm</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">
            Kết quả tìm kiếm cho từ khóa: <span class="text-primary">"<?= htmlspecialchars($keyword ?? '') ?>"</span>
        </h4>
        <?php if (!empty($books)): ?>
            <button class="btn btn-outline-secondary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#filterBox">
                <i class="fa-solid fa-filter me-1"></i> Bộ lọc nâng cao
            </button>
        <?php endif; ?>
    </div>

    <!-- Khung bộ lọc tìm kiếm nâng cao (Lọc nâng cao cùng lúc) -->
    <div class="collapse mb-4" id="filterBox">
        <div class="card card-body bg-light border-0 shadow-sm">
            <form action="/search" method="GET" class="row g-3">
                <input type="hidden" name="keyword" value="<?= htmlspecialchars($keyword ?? '') ?>">
                
                <div class="col-md-4">
                    <label class="form-label small fw-bold">Danh mục</label>
                    <select name="category_id" class="form-select form-select-sm">
                        <option value="">-- Tất cả danh mục --</option>
                        <!-- Bạn có thể truyền $categories từ Controller sang đây để lặp -->
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label small fw-bold">Khoảng giá</label>
                    <div class="input-group input-group-sm">
                        <input type="number" name="min_price" class="form-control" placeholder="Từ">
                        <span class="input-group-text">-</span>
                        <input type="number" name="max_price" class="form-control" placeholder="Đến">
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label small fw-bold">Sắp xếp theo</label>
                    <select name="sort" class="form-select form-select-sm">
                        <option value="latest">Mới nhất</option>
                        <option value="price_asc">Giá tăng dần</option>
                        <option value="price_desc">Giá giảm dần</option>
                    </select>
                </div>

                <div class="col-12 text-end">
                    <button type="submit" class="btn btn-primary btn-sm px-4 fw-bold">Áp dụng lọc</button>
                </div>
            </form>
        </div>
    </div>

    <?php if (empty($books)): ?>
        <!-- Hiển thị khi không tìm thấy kết quả -->
        <div class="text-center py-5 bg-white rounded shadow-sm border">
            <i class="fa-solid fa-magnifying-glass fa-3x text-muted mb-3"></i>
            <h5 class="fw-bold text-secondary">Không tìm thấy sách phù hợp</h5>
            <p class="text-muted small mb-4">Hãy thử tìm lại với từ khóa khác hoặc bỏ các bộ lọc.</p>
            <a href="/" class="btn btn-primary px-4 fw-bold">
                <i class="fa-solid fa-house me-1"></i> Về trang chủ
            </a>
        </div>

    <?php else: ?>
        <p class="text-muted mb-3">Tìm thấy <strong><?= count($books) ?></strong> kết quả phù hợp.</p>
        
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
            <?php foreach ($books as $book): ?>
                <div class="col">
                    <?php include __DIR__ . '/../components/product-card.php'; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- CSS hỗ trợ rút gọn chữ 2 dòng -->
<style>
.text-truncate-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>

<?php include_once __DIR__ . '/../../views/layouts/footer.php'; ?>