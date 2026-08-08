<?php
// app/helpers/functions.php

if (!function_exists('format_order_code')) {
    /**
     * Định dạng mã đơn hàng theo chuẩn TBS-YYYYMMDD-XXXX
     * 
     * @param int $id ID đơn hàng từ CSDL
     * @param string|null $createdAt Thời gian tạo (YYYY-MM-DD HH:II:SS)
     * @return string
     */
    function format_order_code($id, $createdAt = null) {
        $dateStr = $createdAt ? date('Ymd', strtotime($createdAt)) : date('Ymd');
        return 'TBS-' . $dateStr . '-' . sprintf('%04d', $id);
    }
}

if (!function_exists('format_currency')) {
    /**
     * Định dạng hiển thị tiền tệ VNĐ
     */
    function format_currency($amount) {
        return number_format((float)$amount, 0, ',', '.') . ' đ';
    }
}