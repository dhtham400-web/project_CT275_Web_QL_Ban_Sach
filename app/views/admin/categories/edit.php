<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container-fluid px-4 mt-4 flex-grow-1">
    <!-- row và justify-content-center giúp đưa toàn bộ form ra giữa màn hình máy tính -->
    <div class="row justify-content-center">
        <!-- col-md-8 col-lg-6 giới hạn độ rộng form vừa vặn, không bị tràn màn hình -->
        <div class="col-12 col-md-8 col-lg-6">
            
            <!-- Phần tiêu đề nằm gọn phía trên form, đồng bộ hoàn toàn với trang thêm mới -->
            <div class="mb-3">
                <h2 class="fw-bold text-dark mb-1">Chỉnh Sửa Danh Mục</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="/admin/dashboard" class="breadcrumb-link">
                                <i class="fas fa-tachometer-alt me-1"></i>Bảng điều khiển
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="/admin/categories" class="breadcrumb-link">Danh mục</a>
                        </li>
                        <li class="breadcrumb-item active current-page" aria-current="page">Chỉnh sửa</li>
                    </ol>
                </nav>
            </div>

            <!-- Hiển thị thông báo lỗi (Ví dụ: Trùng tên danh mục từ Session) -->
            <?php 
            if (session_status() === PHP_SESSION_NONE) { session_start(); }
            if (isset($_SESSION['error'])): 
            ?>
                <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i><?= $_SESSION['error']; unset($_SESSION['error']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <!-- Khung chứa Form trắng sạch sẽ phẳng border-0 -->
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <form action="/admin/categories/update/<?= $category['id']; ?>" method="POST">
                        
                        <!-- Ô nhập liệu tên danh mục -->
                        <div class="mb-4 text-start">
                            <label for="name" class="form-label fw-semibold text-secondary">Tên danh mục</label>
                            <input type="text" 
                                   class="form-control form-control-lg fs-6" 
                                   id="name" 
                                   name="name" 
                                   value="<?= htmlspecialchars($category['name']); ?>" 
                                   required 
                                   autofocus>
                            <div class="form-text text-muted">Thay đổi tên danh mục và nhấn Cập nhật để lưu lại.</div>
                        </div>
                        
                        <!-- Thanh nút hành động -->
                        <div class="d-flex gap-2 justify-content-start">
                            <button type="submit" class="btn btn-warning text-dark fw-bold px-4 shadow-sm">
                                <i class="fas fa-sync-alt me-1"></i> Cập nhật
                            </button>
                            <a href="/admin/categories" class="btn btn-secondary px-4">
                                Quay lại
                            </a>
                        </div>
                        
                    </form>
                </div>
            </div>
            
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>