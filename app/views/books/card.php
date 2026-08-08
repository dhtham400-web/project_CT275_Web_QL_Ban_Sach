<style>
.btn-action-icon {
    width: 38px;
    height: 38px;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
    flex-shrink: 0;
}

.btn-buy-now {
    height: 38px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #ff9900 0%, #ff5500 100%);
    border: none;
    border-radius: 6px;
    font-size: 0.85rem !important;
}
</style>
<div class="card h-100 shadow-sm border-0 product-card">
    <!-- Khung ảnh sách -->
    <div class="product-img-container">
        <a href="/books/detail/<?= $book['id'] ?>">
            <img src="/uploads/<?= htmlspecialchars($book['image'] ?? 'default.jpg') ?>" 
                 class="product-img" 
                 alt="<?= htmlspecialchars($book['title']) ?>">
        </a>
    </div>

    <!-- Thông tin sách -->
    <div class="card-body d-flex flex-column p-3">
        <small class="text-muted d-block mb-1 text-truncate">
            <?= htmlspecialchars($book['author'] ?? 'Nhiều tác giả') ?>
        </small>
        
        <h6 class="card-title fw-bold mb-2">
            <a href="/books/detail/<?= $book['id'] ?>" 
               class="text-dark text-decoration-none text-truncate-2" 
               title="<?= htmlspecialchars($book['title']) ?>">
                <?= htmlspecialchars($book['title']) ?>
            </a>
        </h6>

        <div class="mt-auto">
            <span class="text-danger fs-5 fw-bold d-block mb-3">
                <?= number_format($book['price'], 0, ',', '.') ?> đ
            </span>

            <!-- Bộ nút hành động chuẩn có chữ -->
            <div class="d-grid gap-2">
                <div class="btn-group w-100" role="group">
                    <a href="/books/detail/<?= $book['id'] ?>" class="btn btn-outline-secondary btn-sm" title="Xem chi tiết">
                        <i class="fa-solid fa-eye"></i> Chi tiết
                    </a>
                    <a href="/cart/add/<?= $book['id'] ?>" class="btn btn-outline-primary btn-sm" title="Thêm vào giỏ">
                        <i class="fa-solid fa-cart-plus"></i> Thêm giỏ hàng
                    </a>
                </div>
                <a href="/cart/buy-now/<?= $book['id'] ?>" class="btn btn-warning btn-sm text-white fw-bold btn-buy-now">
                    <i class="fa-solid fa-bolt me-1"></i> MUA NGAY
                </a>
            </div>
        </div>
    </div>
</div>