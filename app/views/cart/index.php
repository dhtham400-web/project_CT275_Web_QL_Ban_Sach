<?php 
$pageTitle = "Giỏ hàng của bạn - T-Bookstore";
include_once __DIR__ . '/../layouts/header.php'; 

// Lấy danh sách sản phẩm từ Controller truyền qua hoặc từ Session
$items = $cartItems ?? $_SESSION['cart'] ?? [];
$total = $totalPrice ?? 0;

if (empty($total) && !empty($items)) {
    foreach ($items as $item) {
        $total += ($item['price'] ?? 0) * ($item['quantity'] ?? 1);
    }
}
?>

<div class="container py-4 flex-grow-1">
    <!-- 1. Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item">
                <a href="/" class="text-decoration-none text-secondary">
                    <i class="fa-solid fa-house me-1"></i>Trang chủ
                </a>
            </li>
            <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">
                Giỏ hàng
            </li>
        </ol>
    </nav>

    <!-- 2. Tiêu đề Giỏ hàng -->
    <h3 class="fw-bold text-dark mb-4">
        <i class="fa-solid fa-cart-shopping text-primary me-2"></i>Giỏ hàng của bạn
    </h3>

    <!-- Thông báo lỗi (nếu có) -->
    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <i class="fa-solid fa-circle-exclamation me-2"></i><?= $_SESSION['error']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- 3. Khối nội dung giỏ hàng -->
    <?php if (empty($items)): ?>
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body text-center py-5">
                <i class="fa-solid fa-cart-flatbed display-4 text-muted mb-3 d-block"></i>
                <h5 class="fw-bold text-dark mb-2">Giỏ hàng của bạn đang trống</h5>
                <p class="text-muted small mb-4">Hãy chọn thêm vài cuốn sách yêu thích nhé!</p>
                <a href="/" class="btn btn-primary fw-bold px-4 py-2">
                    <i class="fa-solid fa-store me-2"></i>Khám phá sản phẩm ngay
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <!-- Bảng danh sách sản phẩm -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col" class="ps-3" style="width: 40%;">Sản phẩm</th>
                                        <th scope="col" class="text-center">Đơn giá</th>
                                        <th scope="col" class="text-center" style="width: 18%;">Số lượng</th>
                                        <th scope="col" class="text-end">Thành tiền</th>
                                        <th scope="col" class="text-center" style="width: 8%;">Xóa</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($items as $id => $item): ?>
                                        <?php 
                                            $bookId = $item['id'] ?? $id;
                                            $subtotal = ($item['price'] ?? 0) * ($item['quantity'] ?? 1); 
                                        ?>
                                        <tr>
                                            <td class="ps-3">
                                                <div class="d-flex align-items-center gap-3 py-1">
                                                    <img src="/uploads/<?= htmlspecialchars($item['image'] ?? 'default.jpg'); ?>" 
                                                         alt="<?= htmlspecialchars($item['title'] ?? ''); ?>" 
                                                         class="rounded border" 
                                                         style="width: 55px; height: 70px; object-fit: cover;">
                                                    <div>
                                                        <a href="/books/detail/<?= $bookId; ?>" class="text-dark fw-bold text-decoration-none d-block text-truncate" style="max-width: 220px;">
                                                            <?= htmlspecialchars($item['title'] ?? ''); ?>
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center text-nowrap">
                                                <?= number_format($item['price'] ?? 0, 0, ',', '.'); ?> đ
                                            </td>
                                            <td>
                                                <form action="/cart/update/<?= $bookId; ?>" method="POST" class="d-flex justify-content-center m-0">
                                                    <input type="number" 
                                                           name="quantity" 
                                                           value="<?= $item['quantity'] ?? 1; ?>" 
                                                           min="1" 
                                                           class="form-control form-control-sm text-center" 
                                                           style="width: 65px;" 
                                                           onchange="this.form.submit()">
                                                </form>
                                            </td>
                                            <td class="text-end fw-bold text-danger text-nowrap">
                                                <?= number_format($subtotal, 0, ',', '.'); ?> đ
                                            </td>
                                            <td class="text-center">
                                                <a href="/cart/delete/<?= $bookId; ?>" 
                                                   class="btn btn-sm btn-outline-danger border-0"
                                                   onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này khỏi giỏ hàng?');">
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Khối tổng tiền & Thanh toán -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body p-4">
                        <h5 class="fw-bold text-dark border-bottom pb-3 mb-3">Tóm tắt đơn hàng</h5>
                        
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Tạm tính:</span>
                            <span class="fw-semibold"><?= number_format($total, 0, ',', '.'); ?> đ</span>
                        </div>
                        
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Phí vận chuyển:</span>
                            <span class="text-success fw-semibold">Miễn phí</span>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between mb-4">
                            <span class="fw-bold text-dark fs-5">Tổng tiền:</span>
                            <span class="fw-bold text-danger fs-4"><?= number_format($total, 0, ',', '.'); ?> đ</span>
                        </div>

                        <a href="/checkout" class="btn btn-warning text-white fw-bold w-100 py-2 fs-6 shadow-sm">
                            <i class="fa-solid fa-credit-card me-2"></i>Tiến hành thanh toán
                        </a>
                        <div class="mt-3">
                            <a href="/" class="btn btn-outline-primary fw-semibold w-100 py-2 fs-6 shadow-sm">
                                <i class="fa-solid fa-arrow-left me-2"></i>Tiếp tục mua hàng
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include_once __DIR__ . '/../layouts/footer.php'; ?>