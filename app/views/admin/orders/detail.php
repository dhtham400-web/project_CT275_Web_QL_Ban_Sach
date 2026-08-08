<?php 
// 1. Kiểm tra nếu không tìm thấy đơn hàng
if (empty($order)) {
    $pageTitle = "Không Tìm Thấy Đơn Hàng - Admin";
    include_once __DIR__ . '/../layouts/header.php';
    echo '<div class="container py-5 text-center">
            <div class="alert alert-danger shadow-sm d-inline-block px-4 py-3">
                <i class="fa-solid fa-circle-exclamation me-2"></i>Không tìm thấy thông tin đơn hàng này.
            </div>
            <div class="mt-3">
                <a href="/admin/orders" class="btn btn-primary fw-bold"><i class="fa-solid fa-arrow-left me-1"></i>Quay lại danh sách</a>
            </div>
          </div>';
    include_once __DIR__ . '/../layouts/footer.php';
    exit();
}

// 2. Định dạng mã đơn hàng và tiêu đề trang đồng bộ
$orderCode = function_exists('format_order_code') 
    ? format_order_code($order['id'], $order['created_at'] ?? null) 
    : '#' . $order['id'];

$pageTitle = $title = "Chi Tiết Đơn Hàng " . $orderCode;
$currStatus = strtolower($order['status'] ?? 'pending');

include_once __DIR__ . '/../layouts/header.php'; 
?>

<div class="container-fluid px-4 mt-4 flex-grow-1">
    <!-- Breadcrumb & Nút Quay lại -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="/admin/dashboard" class="text-dark text-decoration-none">
                        <i class="fas fa-tachometer-alt me-1"></i>Bảng điều khiển
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="/admin/orders" class="text-dark text-decoration-none">Quản lý đơn hàng</a>
                </li>
                <li class="breadcrumb-item active text-muted">Chi tiết <?= $orderCode; ?></li>
            </ol>
        </nav>
    </div>

    <div class="row g-4 mb-4">
        <!-- Cột trái: Thông tin giao hàng & Cập nhật trạng thái -->
        <div class="col-lg-4">
            <!-- Card Thông tin người nhận -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold"><i class="fa-solid fa-truck-fast me-2"></i>Thông Tin Giao Hàng</h6>
                    <span class="badge bg-light text-dark fw-bold"><?= $orderCode; ?></span>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted d-block">Người nhận:</small>
                        <strong class="fs-6"><?= !empty($order['customer_name']) ? htmlspecialchars($order['customer_name']) : 'Khách lẻ'; ?></strong>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Số điện thoại:</small>
                        <strong><?= !empty($order['customer_phone']) ? htmlspecialchars($order['customer_phone']) : 'Chưa cập nhật'; ?></strong>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Địa chỉ nhận hàng:</small>
                        <strong><?= !empty($order['customer_address']) ? htmlspecialchars($order['customer_address']) : 'Chưa cập nhật'; ?></strong>
                    </div>
                    <div class="mb-0">
                        <small class="text-muted d-block">Hình thức thanh toán:</small>
                        <span class="badge bg-secondary">Thanh toán khi nhận hàng (COD)</span>
                    </div>
                </div>
            </div>

            <!-- Card Cập nhật trạng thái -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold text-dark"><i class="fa-solid fa-pen-to-square me-2 text-warning"></i>Cập Nhật Trạng Thái</h6>
                    <?php 
                        if ($currStatus === 'pending' || $currStatus === 'chờ xử lý'): 
                            echo '<span class="badge bg-warning text-dark">Chờ xử lý</span>';
                        elseif ($currStatus === 'confirmed' || $currStatus === 'đã xác nhận'): 
                            echo '<span class="badge bg-primary">Đã xác nhận</span>';
                        elseif ($currStatus === 'shipping' || $currStatus === 'đang giao hàng'): 
                            echo '<span class="badge bg-info text-dark">Đang giao hàng</span>';
                        elseif ($currStatus === 'completed' || $currStatus === 'đã hoàn thành'): 
                            echo '<span class="badge bg-success">Đã hoàn thành</span>';
                        else: 
                            echo '<span class="badge bg-danger">Đã hủy</span>';
                        endif;
                    ?>
                </div>
                <div class="card-body">
                    <form action="/admin/orders/update/<?= $order['id']; ?>" method="POST">
                        <div class="mb-3">
                            <label class="form-label small text-muted">Chọn trạng thái mới:</label>
                            <select name="status" class="form-select fw-bold">
                                <option value="pending" <?= ($currStatus === 'pending' || $currStatus === 'chờ xử lý') ? 'selected' : ''; ?>>⏳ Chờ xử lý</option>
                                <option value="confirmed" <?= ($currStatus === 'confirmed' || $currStatus === 'đã xác nhận') ? 'selected' : ''; ?>>⚡ Đã xác nhận</option>
                                <option value="shipping" <?= ($currStatus === 'shipping' || $currStatus === 'đang giao hàng') ? 'selected' : ''; ?>>🚚 Đang giao hàng</option>
                                <option value="completed" <?= ($currStatus === 'completed' || $currStatus === 'đã hoàn thành') ? 'selected' : ''; ?>>✅ Đã hoàn thành</option>
                                <option value="cancelled" <?= ($currStatus === 'cancelled' || $currStatus === 'hủy đơn hàng') ? 'selected' : ''; ?>>❌ Hủy đơn hàng</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 fw-bold">
                            <i class="fa-solid fa-floppy-disk me-1"></i> Lưu Cập Nhật
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Cột phải: Danh sách sản phẩm trong đơn -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold text-dark"><i class="fa-solid fa-book me-2 text-primary"></i>Sản Phẩm Đã Đặt</h6>
                    <span class="small text-muted">Mã đơn: <strong><?= $orderCode; ?></strong></span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">Sách</th>
                                    <th class="text-center">Mã Sách</th>
                                    <th class="text-end">Đơn Giá</th>
                                    <th class="text-center">Số Lượng</th>
                                    <th class="text-end pe-3">Thành Tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($orderDetails)): ?>
                                    <?php foreach ($orderDetails as $item): ?>
                                        <tr>
                                            <td class="ps-3 fw-semibold">
                                                <?= htmlspecialchars($item['book_title'] ?? ('Sách ID #' . $item['book_id'])); ?>
                                            </td>
                                            <td class="text-center text-muted">#<?= $item['book_id']; ?></td>
                                            <td class="text-end">
                                                <?= function_exists('format_currency') ? format_currency($item['price'] ?? 0) : number_format($item['price'] ?? 0, 0, ',', '.') . ' đ'; ?>
                                            </td>
                                            <td class="text-center fw-bold">x<?= $item['quantity'] ?? 1; ?></td>
                                            <td class="text-end pe-3 fw-bold">
                                                <?= function_exists('format_currency') ? format_currency(($item['price'] ?? 0) * ($item['quantity'] ?? 1)) : number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 1), 0, ',', '.') . ' đ'; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">Không tìm thấy sản phẩm trong đơn hàng.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="4" class="text-end fw-bold fs-6">Tổng Thanh Toán:</td>
                                    <td class="text-end pe-3 text-danger fw-bold fs-5">
                                        <?= function_exists('format_currency') ? format_currency($order['total_money'] ?? 0) : number_format($order['total_money'] ?? 0, 0, ',', '.') . ' đ'; ?>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/../layouts/footer.php'; ?>