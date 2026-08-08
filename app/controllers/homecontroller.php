<?php
namespace App\Controllers;

class HomeController extends BaseController
{
   public function index() {
        $categoryModel = new \App\Models\Category();
        $bookModel = new \App\Models\Book();

        // 1. Lấy tất cả danh mục và sách như cũ
        $allCategories = $categoryModel->getHomeCategories();
        $categoriesWithBooks = [];
        foreach ($allCategories as $cat) {
            $cat['books'] = $bookModel->getBooksByCategory($cat['id']);
            $categoriesWithBooks[] = $cat;
        }

        // 2. BỔ SUNG: Gọi Model lấy top 5 sách bán chạy
        $topBooks = $bookModel->getTopSelling(5);

        // Nạp giao diện trang chủ
        include __DIR__ . '/../views/home/index.php';
    }
}