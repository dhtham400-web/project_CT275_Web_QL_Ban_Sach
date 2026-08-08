<?php

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static $instance = null;

    // Hàm kết nối Cơ sở dữ liệu (Singleton Pattern)
    public static function getConnection()
    {
        if (self::$instance === null) {
            // Cấu hình thông tin kết nối PostgreSQL
            $host     = 'localhost';
            $port     = '5432';       // Cổng mặc định của PostgreSQL
            $db       = 'CT275_QLBSach'; // Thay bằng tên DB thực tế bạn vừa tạo
            $user     = 'postgres';   // Username mặc định
            $password = '0967196400';     // Thay bằng mật khẩu PostgreSQL của bạn

            $dsn = "pgsql:host=$host;port=$port;dbname=$db;";

            try {
                // Khởi tạo đối tượng PDO
                self::$instance = new PDO($dsn, $user, $password, [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Bật chế độ báo lỗi qua Exception
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Trả dữ liệu về dạng mảng Key => Value
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]);
            } catch (PDOException $e) {
                // Nếu kết nối lỗi, dừng hệ thống và hiển thị thông báo
                die("Kết nối Database thất bại: " . $e->getMessage());
            }
        }

        return self::$instance;
    }
}