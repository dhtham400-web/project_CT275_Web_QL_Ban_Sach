<?php 
$pageTitle = "Lịch Sử Đơn Hàng - T-Bookstore";
include_once __DIR__ . '/../layouts/header.php'; 

if (!function_exists('render_money')) {
    function render_money($amount) {
        if (function_exists('format_currency')) {
            return format_currency($amount);
        }
        return number_format($amount, 0, ',', '.') . ' đ';
    }
}
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
            <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">
                Đơn hàng của tôi
            </li>
        </ol>
    </nav>

    <!-- Tiêu đề trang -->
    <h3 class="fw-bold text-dark mb-3">
        <i class="fa-solid fa-receipt text-primary me-2"></i>Đơn Hàng Của Tôi
    </h3>

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

    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Mã Đơn Hàng</th>
                            <th>Ngày Đặt</th>
                            <th>Tổng Tiền</th>
                            <th>Trạng Thái</th>
                            <th class="text-center">Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($orders)): ?>
                            <?php foreach ($orders as $order): ?>
                                <?php 
                                    $orderCode = function_exists('format_order_code') 
                                        ? format_order_code($order['id'], $order['created_at'] ?? null) 
                                        : '#' . $order['id'];
                                ?>
                                <tr>
                                    <td class="ps-3 fw-bold text-primary">
                                        <?= $orderCode; ?>
                                    </td>
                                    <td><?= date('d/m/Y H:i', strtotime($order['created_at'] ?? 'now')); ?></td>
                                    <td class="fw-bold text-danger">
                                        <?= render_money($order['total_money'] ?? 0); ?>
                                    </td>
                                    <td>
                                        <?php 
                                            $status = mb_strtolower(trim($order['status'] ?? 'pending'), 'UTF-8');
                                            if ($status === 'pending' || $status === 'chờ xử lý'): 
                                        ?>
                                            <span class="badge bg-warning text-dark">⏳ Chờ xử lý</span>
                                        <?php elseif ($status === 'confirmed' || $status === 'đã xác nhận'): ?>
                                            <span class="badge bg-primary">⚡ Đã xác nhận</span>
                                        <?php elseif ($status === 'shipping' || $status === 'đang giao hàng'): ?>
                                            <span class="badge bg-info text-dark">🚚 Đang giao hàng</span>
                                        <?php elseif ($status === 'completed' || $status === 'đã hoàn thành'): ?>
                                            <span class="badge bg-success">✅ Đã hoàn thành</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">❌ Đã hủy</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <a href="/orders/detail/<?= $order['id']; ?>" class="btn btn-sm btn-outline-primary">
                                            <i class="fa-solid fa-eye me-1"></i>Xem chi tiết
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="fa-solid fa-box-open display-4 d-block mb-3 opacity-50"></i>
                                    Bạn chưa có đơn hàng nào. <a href="/" class="text-decoration-none">Khám phá sản phẩm ngay!</a>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/../layouts/footer.php'; ?>