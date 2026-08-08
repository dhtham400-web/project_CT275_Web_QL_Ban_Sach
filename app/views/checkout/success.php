<?php 
$pageTitle = "Đặt hàng thành công - T-Bookstore";
include_once __DIR__ . '/../layouts/header.php'; 

// Đồng bộ định dạng mã đơn hàng chuẩn TBS-YYYYMMDD-XXXX
$orderCode = format_order_code($order['id'], $order['created_at'] ?? null);
?>

<div class="container py-5 text-center flex-grow-1">
    <div class="card shadow-sm border-0 mx-auto p-4" style="max-width: 650px;">
        <div class="card-body">
            <i class="fa-solid fa-circle-check text-success display-1 mb-3"></i>
            <h2 class="fw-bold text-dark">Đặt hàng thành công!</h2>
            <p class="text-muted">Cảm ơn bạn đã mua hàng tại T-Bookstore.</p>
            
            <div class="alert alert-light border text-start my-4">
                <p class="mb-1"><strong>Mã đơn hàng:</strong> <span class="badge bg-primary fs-6"><?= $orderCode; ?></span></p>
                <p class="mb-1"><strong>Người nhận:</strong> <?= htmlspecialchars($order['customer_name'] ?? ''); ?></p>
                <p class="mb-1"><strong>Số điện thoại:</strong> <?= htmlspecialchars($order['customer_phone'] ?? ''); ?></p>
                <p class="mb-1"><strong>Địa chỉ:</strong> <?= htmlspecialchars($order['customer_address'] ?? ''); ?></p>
                <p class="mb-0"><strong>Tổng tiền:</strong> <span class="text-danger fw-bold"><?= format_currency($order['total_money'] ?? 0); ?></span></p>
            </div>

            <!-- Danh sách sản phẩm đã đặt -->
            <?php if (!empty($orderItems)): ?>
                <div class="text-start mb-4">
                    <h6 class="fw-bold mb-3">Sản phẩm đã đặt:</h6>
                    <div class="list-group">
                        <?php foreach ($orderItems as $item): ?>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <img src="/uploads/<?= htmlspecialchars($item['image'] ?? 'default.jpg'); ?>" style="width: 40px; height: 50px; object-fit: contain;" class="me-3 border rounded">
                                    <div>
                                        <div class="fw-bold small"><?= htmlspecialchars($item['title'] ?? ''); ?></div>
                                        <small class="text-muted">SL: <?= $item['quantity'] ?? 1; ?> x <?= format_currency($item['price'] ?? 0); ?></small>
                                    </div>
                                </div>
                                <span class="fw-bold text-danger"><?= format_currency(($item['price'] ?? 0) * ($item['quantity'] ?? 1)); ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <div class="d-flex justify-content-center gap-2">
                <a href="/orders/detail/<?= $order['id']; ?>" class="btn btn-outline-primary px-4 fw-bold">
                    <i class="fa-solid fa-receipt me-1"></i> Xem chi tiết đơn hàng
                </a>
                <a href="/" class="btn btn-primary px-4 fw-bold">
                    <i class="fa-solid fa-cart-shopping me-1"></i> Tiếp tục mua sắm
                </a>
            </div>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/../layouts/footer.php'; ?>