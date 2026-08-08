<?php 
$pageTitle = "Trang Chủ T-Bookstore";
include_once __DIR__ . '/../layouts/header.php'; 
?>

<div class="container py-4 flex-grow-1">

    <!-- TAG 1: THANH THẺ BẤM NHANH DANH MỤC Ở ĐẦU TRANG -->
    <div class="mb-4">
        <div class="d-flex flex-wrap gap-2 justify-content-start align-items-center bg-white p-3 rounded shadow-sm">
            <span class="fw-bold text-secondary me-2">
                <i class="fa-solid fa-tags me-1"></i> Danh mục:
            </span>
            <?php if (!empty($allCategories)): ?>
                <?php foreach ($allCategories as $cat): ?>
                    <a href="#category-<?= $cat['id']; ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-medium category-scroll-link">
                        <?= htmlspecialchars($cat['name']); ?>
                    </a>
                <?php endforeach; ?>
            <?php else: ?>
                <span class="text-muted small">Chưa có danh mục nào.</span>
            <?php endif; ?>
        </div>
    </div>

    <!-- KHỐI TOP SÁCH NỔI BẬT -->
    <?php if (!empty($topBooks)): ?>
        <div class="mb-5 bg-white p-4 rounded shadow-sm border border-warning position-relative">
            <span class="position-absolute top-0 start-0 translate-middle-y bg-danger text-white px-3 py-1 rounded-pill fw-bold text-uppercase shadow-sm" style="font-size: 0.75rem; left: 20px !important;">
                <i class="fa-solid fa-fire text-warning me-1"></i> Bán chạy nhất
            </span>

            <h4 class="fw-bold text-dark border-bottom pb-2 mb-4 mt-2 d-flex align-items-center justify-content-between">
                <span><i class="fa-solid fa-crown text-warning me-2"></i> TOP SÁCH NỔI BẬT</span>
                <span class="text-muted small fw-normal" style="font-size: 0.8rem;">Sản phẩm được mua nhiều nhất</span>
            </h4>

            <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 g-3">
                <?php foreach ($topBooks as $book): ?>
                    <div class="col">
                        <?php 
                        $componentPath = __DIR__ . '/../components/product-card.php';
                        if (file_exists($componentPath)) {
                            include $componentPath;
                        } else {
                            echo '<div class="alert alert-danger p-2 small">Thiếu file: app/views/components/product-card.php</div>';
                        }
                        ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- TAG 2: LIỆT KÊ TỪNG DANH MỤC VÀ SÁCH CUỘN NGANG -->
    <?php if (!empty($categoriesWithBooks)): ?>
        <?php foreach ($categoriesWithBooks as $category): ?>
            
            <div id="category-<?= $category['id']; ?>" class="mb-5 bg-white p-3 rounded shadow-sm position-relative scroll-container-parent pt-4">
                <h4 class="fw-bold text-uppercase text-dark border-bottom pb-2 mb-4">
                    <i class="fa-solid fa-book-bookmark text-primary me-2"></i>
                    <?= htmlspecialchars($category['name']); ?>
                </h4>
                
                <?php if (empty($category['books'])): ?>
                    <div class="alert alert-light border text-muted py-3 ps-3 rounded shadow-sm" style="font-size: 0.9rem;">
                        <i class="fa-solid fa-circle-info me-1 text-secondary"></i> Chưa có dữ liệu sách thuộc danh mục này.
                    </div>
                <?php else: ?>
                    <div class="position-relative">
                        
                        <button class="btn btn-scroll btn-scroll-left shadow-sm" onclick="scrollSlider(this, 'left')">
                            <i class="fa-solid fa-chevron-left"></i>
                        </button>

                        <div class="row flex-nowrap overflow-x-auto pb-3 custom-scrollbar scroll-slider-content">
                            <?php foreach ($category['books'] as $book): ?>
                                <div class="col-auto flex-shrink-0" style="width: 210px;">
                                    <?php 
                                    if (file_exists($componentPath)) {
                                        include $componentPath;
                                    } else {
                                        echo '<div class="alert alert-danger p-2 small">Thiếu file: app/views/components/product-card.php</div>';
                                    }
                                    ?>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <button class="btn btn-scroll btn-scroll-right shadow-sm" onclick="scrollSlider(this, 'right')">
                            <i class="fa-solid fa-chevron-right"></i>
                        </button>

                    </div>
                <?php endif; ?>
            </div>

        <?php endforeach; ?>
    <?php endif; ?>
</div>

<style>
.btn-scroll {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    z-index: 10;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: rgba(255, 255, 255, 0.9);
    border: 1px solid #ddd;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #333;
    transition: all 0.2s ease;
}
.btn-scroll:hover {
    background-color: #0d6efd;
    color: #fff;
    border-color: #0d6efd;
    box-shadow: 0 4px 8px rgba(0,0,0,0.15) !important;
}
.btn-scroll-left { left: -20px; }
.btn-scroll-right { right: -20px; }

@media (max-width: 768px) {
    .btn-scroll { display: none !important; }
}

.overflow-x-auto {
    -webkit-overflow-scrolling: touch;
    scroll-behavior: smooth;
}
.custom-scrollbar::-webkit-scrollbar { height: 6px; }
.custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: #d1d1d1; border-radius: 10px; }
.custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #b5b5b5; }
</style>

<script>
function scrollSlider(buttonElement, direction) {
    const parentContainer = buttonElement.parentElement;
    const slider = parentContainer.querySelector('.scroll-slider-content');
    const scrollAmount = 234 * 2; 
    
    if (direction === 'left') {
        slider.scrollLeft -= scrollAmount;
    } else {
        slider.scrollLeft += scrollAmount;
    }
}

document.querySelectorAll('.category-scroll-link').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();

        const targetId = this.getAttribute('href');
        try {
            const targetElement = document.querySelector(targetId);

            if (targetElement) {
                const headerOffset = 90; 
                const elementPosition = targetElement.getBoundingClientRect().top;
                const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });
            }
        } catch (err) {
            console.error("Lỗi selector cuộn danh mục:", err);
        }
    });
});
</script>

<?php include_once __DIR__ . '/../layouts/footer.php'; ?>