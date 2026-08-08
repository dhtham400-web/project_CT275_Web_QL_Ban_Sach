<?php

namespace App\Controllers;

class BaseController
{
    // Ham de tai va hien thi giao dien View
    protected function render($view, $data = [])
    {
        // Chuyen doi mang du lieu thanh cac bien doc lap
        extract($data);

        // Chuyen doi dau cham (.) thanh dau gach cheo (/) trong duong dan view
        $viewPath = str_replace('.', '/', $view);

        // Chuyen doi toan bo duong dan view ve chu thuong
        $viewPath = strtolower($viewPath);

        // Tro thang vao thu muc app/views viet thuong
        $viewFile = __DIR__ . "/../views/" . $viewPath . ".php";

        // Kiem tra file view co ton tai khong truoc khi nap
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            die("Giao diện View [ $view ] không tồn tại trên hệ thống.");
        }
    }
}