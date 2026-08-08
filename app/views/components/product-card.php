<style>
.product-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.product-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 15px rgba(0,0,0,0.1) !important;
}

.product-img-container {
    height: 200px;
    width: 100%;
    background-color: #fff;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
}

.product-img-container a {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.product-img {
    max-height: 100%;
    max-width: 100%;
    width: auto;
    height: auto;
    object-fit: contain;
}

/* Ràng buộc tiêu đề hiển thị tối đa 2 dòng */
.text-truncate-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
    min-height: 2.4em;
    line-height: 1.2;
}

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

<?php 
// Kiểm tra trạng thái tồn kho của sách
$isOutOfStock = isset($book['quantity']) && $book['quantity'] <= 0; 
?>

<div class="card h-100 shadow-sm border-0 product-card">
    <!-- Khung chứa ảnh -->
    <div class="product-img-container position-relative">
        <a href="/books/detail/<?= $book['id'] ?>">
            <img src="/uploads/<?= htmlspecialchars($book['image'] ?? 'default.jpg') ?>" 
                 class="product-img" 
                 alt="<?= htmlspecialchars($book['title'] ?? '') ?>">
        </a>
        
        <?php if ($isOutOfStock): ?>
            <span class="position-absolute top-0 start-0 bg-secondary text-white px-2 py-1 m-2 rounded small fw-bold" style="font-size: 0.7rem;">
                Hết hàng
            </span>
        <?php endif; ?>
    </div>

    <!-- Thông tin sách -->
    <div class="card-body d-flex flex-column p-3">
        <small class="text-muted d-block mb-1 text-truncate">
            <?= htmlspecialchars($book['author'] ?? 'Nhiều tác giả') ?>
        </small>
        
        <h6 class="card-title fw-bold mb-2">
            <a href="/books/detail/<?= $book['id'] ?>" 
               class="text-dark text-decoration-none text-truncate-2" 
               title="<?= htmlspecialchars($book['title'] ?? '') ?>">
                <?= htmlspecialchars($book['title'] ?? '') ?>
            </a>
        </h6>

        <div class="mt-auto">
            <span class="text-danger fs-5 fw-bold d-block mb-3">
                <?= number_format($book['price'] ?? 0, 0, ',', '.') ?> đ
            </span>

            <!-- Nút tương tác -->
            <div class="d-flex gap-2">
                <!-- Xem chi tiết -->
                <a href="/books/detail/<?= $book['id'] ?>" 
                   class="btn btn-outline-secondary btn-action-icon" 
                   title="Xem chi tiết">
                    <i class="fa-solid fa-eye"></i>
                </a>

                <?php if ($isOutOfStock): ?>
                    <!-- Nút vô hiệu hóa khi hết hàng -->
                    <button class="btn btn-secondary w-100 fw-bold disabled" style="height: 38px; font-size: 0.85rem;" disabled>
                        Tạm hết hàng
                    </button>
                <?php else: ?>
                    <!-- Thêm vào giỏ hàng -->
                    <form action="/cart/add/<?= $book['id'] ?>" method="POST" class="m-0">
                        <input type="hidden" name="quantity" value="1">
                        <input type="hidden" name="action" value="add">
                        <button type="submit" class="btn btn-outline-primary btn-action-icon" title="Thêm vào giỏ hàng">
                            <i class="fa-solid fa-cart-plus"></i>
                        </button>
                    </form>

                    <!-- MUA NGAY -->
                    <form action="/cart/add/<?= $book['id'] ?>" method="POST" class="m-0 flex-grow-1">
                        <input type="hidden" name="quantity" value="1">
                        <input type="hidden" name="action" value="buy_now">
                        <button type="submit" class="btn btn-warning text-white fw-bold btn-buy-now w-100">
                            <i class="fa-solid fa-bolt me-1"></i> MUA NGAY
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>