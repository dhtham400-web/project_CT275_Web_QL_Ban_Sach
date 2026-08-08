<?php 
$pageTitle = "Chi Tiết Đơn Hàng - T-Bookstore";
include_once __DIR__ . '/../layouts/header.php'; 

$status = mb_strtolower($order['status'] ?? 'pending', 'UTF-8');
$canCancel = ($status === 'pending' || $status === 'chờ xử lý');

// Định dạng mã đơn hàng
$orderCode = function_exists('format_order_code') 
    ? format_order_code($order['id'], $order['created_at'] ?? null) 
    : '#' . $order['id'];

// Hàm định dạng tiền phòng thủ
if (!function_exists('render_money')) {
    function render_money($amount) {
        if (function_exists('format_currency')) {
            return format_currency($amount);
        }
        return number_format($amount, 0, ',', '.') . ' đ';
    }
}

$steps = [
    'pending'   => ['title' => 'Chờ xử lý', 'icon' => 'fa-clock'],
    'confirmed' => ['title' => 'Đã xác nhận', 'icon' => 'fa-box'],
    'shipping'  => ['title' => 'Đang giao hàng', 'icon' => 'fa-truck-fast'],
    'completed' => ['title' => 'Đã hoàn thành', 'icon' => 'fa-circle-check'],
];
?>

<div class="container py-3 flex-grow-1">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item">
                <a href="/" class="text-decoration-none text-secondary">
                    <i class="fa-solid fa-house me-1"></i>Trang chủ
                </a>
            </li>
            <li class="breadcrumb-item">
                <a href="/orders" class="text-decoration-none text-secondary">
                    Đơn hàng của tôi
                </a>
            </li>
            <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">
                Chi tiết đơn hàng <?= $orderCode; ?>
            </li>
        </ol>
    </nav>

    <!-- Thông báo Session -->
    <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i><?= $_SESSION['success']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
            <i class="fa-solid fa-circle-exclamation me-2"></i><?= $_SESSION['error']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- Tiêu đề -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold text-dark mb-0">
            <i class="fa-solid fa-file-invoice text-primary me-2"></i>Chi Tiết Đơn Hàng <?= $orderCode; ?>
        </h3>
    </div>

    <!-- Thanh tiến trình trạng thái -->
    <?php if ($status === 'cancelled' || $status === 'hủy đơn hàng'): ?>
        <div class="alert alert-danger shadow-sm rounded-3 fw-bold mb-4">
            <i class="fa-solid fa-circle-xmark me-2"></i>Đơn hàng này đã bị hủy.
        </div>
    <?php else: ?>
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body py-4">
                <div class="d-flex justify-content-between position-relative align-items-center">
                    <?php foreach ($steps as $key => $step): ?>
                        <?php 
                            $isCurrent = ($status === $key || ($key === 'pending' && $status === 'chờ xử lý'));
                            $badgeClass = $isCurrent ? 'bg-primary text-white shadow' : 'bg-light text-muted border';
                        ?>
                        <div class="text-center position-relative z-1">
                            <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2 <?= $badgeClass; ?>" style="width: 45px; height: 45px;">
                                <i class="fa-solid <?= $step['icon']; ?> fs-5"></i>
                            </div>
                            <small class="fw-bold d-block <?= $isCurrent ? 'text-primary' : 'text-muted'; ?>">
                                <?= $step['title']; ?>
                            </small>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Thông tin người nhận và Bảng sản phẩm -->
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body">
                    <h5 class="fw-bold border-bottom pb-2 mb-3">Thông tin nhận hàng</h5>
                    <p class="mb-1"><strong>Người nhận:</strong> <?= htmlspecialchars($order['customer_name'] ?? ''); ?></p>
                    <p class="mb-1"><strong>SĐT:</strong> <?= htmlspecialchars($order['customer_phone'] ?? ''); ?></p>
                    <p class="mb-3"><strong>Địa chỉ:</strong> <?= htmlspecialchars($order['customer_address'] ?? ''); ?></p>
                    
                    <?php if ($canCancel): ?>
                        <form action="/orders/cancel/<?= $order['id']; ?>" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này?');">
                            <button type="submit" class="btn btn-danger w-100 fw-bold">
                                <i class="fa-solid fa-xmark me-1"></i>Hủy Đơn Hàng
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">Sách</th>
                                    <th>Đơn Giá</th>
                                    <th>Số Lượng</th>
                                    <th class="text-end pe-3">Thành Tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orderDetails as $item): ?>
                                    <tr>
                                        <td class="ps-3 fw-bold"><?= htmlspecialchars($item['book_title'] ?? 'Sách'); ?></td>
                                        <td><?= render_money($item['price'] ?? 0); ?></td>
                                        <td>x<?= $item['quantity'] ?? 1; ?></td>
                                        <td class="text-end pe-3 fw-bold text-danger">
                                            <?= render_money(($item['price'] ?? 0) * ($item['quantity'] ?? 1)); ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/../layouts/footer.php'; ?>