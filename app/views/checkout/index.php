<?php 
$pageTitle = "Thanh toán - T-Bookstore";
include_once __DIR__ . '/../layouts/header.php'; 
?>

<div class="container py-4 flex-grow-1">
    <!-- Breadcrumb điều hướng đồng bộ -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item">
                <a href="/" class="text-decoration-none text-secondary">
                    <i class="fa-solid fa-house me-1"></i>Trang chủ
                </a>
            </li>
            <li class="breadcrumb-item">
                <a href="/cart" class="text-decoration-none text-secondary">
                    Giỏ hàng
                </a>
            </li>
            <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">
                Thanh toán
            </li>
        </ol>
    </nav>
    <h3 class="fw-bold mb-4 text-dark border-bottom pb-2">
        <i class="fa-solid fa-credit-card text-primary me-2"></i>Thanh toán đơn hàng
    </h3>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fa-solid fa-circle-exclamation me-2"></i><?= $_SESSION['error']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <form action="/checkout/process" method="POST">
        <div class="row g-4">
            <!-- Cột trái: Thông tin giao hàng -->
            <div class="col-md-7">
                <div class="bg-white p-4 rounded shadow-sm border">
                    <h5 class="fw-bold mb-3 text-secondary">
                        <i class="fa-solid fa-truck-fast me-2"></i>Thông tin người nhận
                    </h5>
                    
                    <div class="mb-3">
                        <label class="form-label fw-medium">Họ và tên <span class="text-danger">*</span></label>
                        <input type="text" name="customer_name" class="form-control" placeholder="Nguyễn Văn A" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Số điện thoại <span class="text-danger">*</span></label>
                            <input type="tel" name="customer_phone" class="form-control" placeholder="0901234567" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Email (không bắt buộc)</label>
                            <input type="email" name="customer_email" class="form-control" placeholder="email@example.com">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Địa chỉ nhận hàng <span class="text-danger">*</span></label>
                        <textarea name="address" class="form-control" rows="2" placeholder="Số nhà, đường, phường/xã, quận/huyện, tỉnh/thành..." required></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Ghi chú đơn hàng</label>
                        <textarea name="note" class="form-control" rows="2" placeholder="Lưu ý cho người bán hoặc shipper..."></textarea>
                    </div>

                    <div class="mt-4 pt-3 border-top">
                        <h6 class="fw-bold text-secondary mb-2">Hình thức thanh toán</h6>
                        <div class="form-check p-3 border rounded bg-light">
                            <input class="form-check-input" type="radio" name="payment_method" value="COD" checked id="codMethod">
                            <label class="form-check-label fw-bold text-dark ms-2" for="codMethod">
                                <i class="fa-solid fa-hand-holding-dollar text-success me-1"></i> Thanh toán khi nhận hàng (COD)
                            </label>
                            <div class="small text-muted mt-1 ms-4">
                                Bạn sẽ thanh toán bằng tiền mặt trực tiếp cho nhân viên giao hàng khi nhận được sách.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cột phải: Tóm tắt đơn hàng -->
            <div class="col-md-5">
                <div class="bg-white p-4 rounded shadow-sm border">
                    <h5 class="fw-bold mb-3 text-secondary">Sản phẩm đã chọn</h5>
                    
                    <div class="list-group list-group-flush mb-3" style="max-height: 300px; overflow-y: auto;">
                        <?php if (!empty($cartItems)): ?>
                            <?php foreach ($cartItems as $item): ?>
                                <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <div class="d-flex align-items-center me-2">
                                        <img src="/uploads/<?= htmlspecialchars($item['image']); ?>" style="width: 45px; height: 60px; object-fit: contain;" class="me-2 rounded border">
                                        <div>
                                            <h6 class="my-0 small fw-bold text-truncate" style="max-width: 180px;"><?= htmlspecialchars($item['title']); ?></h6>
                                            <small class="text-muted">SL: <?= $item['quantity']; ?> x <?= number_format($item['price'], 0, ',', '.'); ?>đ</small>
                                        </div>
                                    </div>
                                    <span class="fw-bold small text-danger"><?= number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?>đ</span>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <div class="border-top pt-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tạm tính:</span>
                            <span class="fw-bold"><?= number_format($totalPrice ?? 0, 0, ',', '.'); ?> đ</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Phí vận chuyển:</span>
                            <span class="text-success fw-bold">Miễn phí</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-4">
                            <span class="fw-bold fs-5">Tổng cộng:</span>
                            <span class="fw-bold fs-5 text-danger"><?= number_format($totalPrice ?? 0, 0, ',', '.'); ?> đ</span>
                        </div>

                        <button type="submit" class="btn btn-warning w-100 py-2.5 fw-bold text-uppercase text-white shadow-sm">
                            <i class="fa-solid fa-check-circle me-1"></i> Xác nhận đặt hàng
                        </button>
                        <a href="/cart" class="btn btn-link w-100 text-primary mt-2 text-decoration-none small">
                            <i class="fa-solid fa-arrow-left me-1"></i> Quay lại giỏ hàng
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<?php include_once __DIR__ . '/../layouts/footer.php'; ?>