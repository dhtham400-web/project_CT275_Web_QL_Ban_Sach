<?php 
$title = "Quản Lý Đơn Hàng - T-Bookstore";
include_once __DIR__ . '/../layouts/header.php'; 
?>

<div class="container-fluid px-4 mt-4 flex-grow-1">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item">
                <a href="/admin/dashboard" class="breadcrumb-link">
                     <i class="fas fa-tachometer-alt me-1"></i>Bảng điều khiển
                </a>
            </li>
            <li class="breadcrumb-item active text-muted">Quản lý đơn hàng</li>
        </ol>
    </nav>

    <div class="card shadow-sm border-0 rounded-3 mb-4">
        <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
            <h5 class="mb-0 fw-bold text-dark"><i class="fa-solid fa-cart-shopping me-2 text-danger"></i>Danh Sách Đơn Hàng</h5>
            <span class="badge bg-light text-dark border fs-6">Tổng: <?= count($orders ?? []); ?> đơn</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Mã ĐH</th>
                            <th>Khách Hàng</th>
                            <th>Số Điện Thoại</th>
                            <th>Địa Chỉ</th>
                            <th>Tổng Tiền</th>
                            <th>Trạng Thái</th>
                            <th class="text-center">Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($orders)): ?>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <!-- Áp dụng mã đơn TBS-YYYYMMDD-XXXX -->
                                    <td class="ps-3 fw-bold">
                                        <?= format_order_code($order['id'], $order['created_at'] ?? null); ?>
                                    </td>
                                    <td class="fw-semibold">
                                        <?= htmlspecialchars($order['customer_name'] ?? 'Khách lẻ'); ?>
                                    </td>
                                    <td><?= htmlspecialchars($order['customer_phone'] ?? 'Chưa cập nhật'); ?></td>
                                    <td class="text-truncate" style="max-width: 200px;">
                                        <?= htmlspecialchars($order['customer_address'] ?? 'Chưa cập nhật'); ?>
                                    </td>
                                    <!-- Áp dụng định dạng tiền tệ -->
                                    <td class="text-danger fw-bold">
                                        <?= format_currency($order['total_money'] ?? 0); ?>
                                    </td>
                                    <td>
                                        <?php 
                                            $status = strtolower($order['status'] ?? 'pending');
                                            if ($status === 'pending' || $status === 'chờ xử lý'): 
                                        ?>
                                            <span class="badge bg-warning text-dark">Chờ xử lý</span>
                                        <?php elseif ($status === 'confirmed' || $status === 'đã xác nhận'): ?>
                                            <span class="badge bg-primary">Đã xác nhận</span>
                                        <?php elseif ($status === 'shipping' || $status === 'đang giao hàng'): ?>
                                            <span class="badge bg-info text-dark">Đang giao hàng</span>
                                        <?php elseif ($status === 'completed' || $status === 'đã hoàn thành'): ?>
                                            <span class="badge bg-success">Đã hoàn thành</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Đã hủy</span>
                                        <?php endif; ?>
                                    </td>
                                    
                                    <td class="text-center">
                                        <!-- Đổi từ /orders/detail/... thành /admin/orders/detail/... -->
                                        <a href="/admin/orders/detail/<?= $order['id']; ?>" class="btn btn-outline-primary btn-sm rounded-2">
                                            <i class="fa-regular fa-eye me-1"></i>Chi tiết
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">Chưa có đơn hàng nào trong hệ thống.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/../layouts/footer.php'; ?>