<?php

namespace App\Core;

class Router
{
    // Mang luu tru cac duong dan duoc dinh nghia
    protected $routes = [];

    // Ham dang ky duong dan GET
    public function get($route, $controllerAction)
    {
        $this->routes['GET'][$route] = $controllerAction;
    }

    // Ham dang ky duong dan POST
    public function post($route, $controllerAction)
    {
        $this->routes['POST'][$route] = $controllerAction;
    }

    // Ham xu ly va dieu huong URL hien tai
    public function dispatch()
    {
        // Lay phuong thuc request (GET, POST,...)
        $method = $_SERVER['REQUEST_METHOD'];
        
        // Lay URL hien tai va loai bo cac ky tu khong can thiet
        $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $url = rtrim($url, '/');
        if (empty($url)) {
            $url = '/';
        }

        // Duyet tat ca route da dang ky
        foreach ($this->routes[$method] as $route => $action) {

            // Tao bieu thuc chinh quy
            $pattern = "#^" . $route . "$#";

            // Kiem tra URL co khop route hay khong
            if (preg_match($pattern, $url, $matches)) {

                // Bo ket qua khop toan bo
                array_shift($matches);

                // Goi controller va truyen tham so
                $this->executeAction($action, $matches);

                return;
            }
        }

        // Khong tim thay route
        http_response_code(404);
        echo "404 - Trang không tồn tại!";
            }

    // Ham khoi tao Controller va goi Method tuong ung
    // Ham khoi tao Controller va goi Method tuong ung
   // Ham khoi tao Controller va goi Method tuong ung
    protected function executeAction($action, $params = [])
    {
        // Tách chuỗi controller@method
        list($controller, $method) = explode('@', $action);
        
        $parts = explode('\\', $controller);

        foreach ($parts as &$part) {
            if (strtolower(substr($part, -10)) == 'controller') {
                $part = ucfirst(str_replace('controller', 'Controller', $part));
            } else {
                $part = ucfirst($part);
            }
        }

        $controllerClass = "App\\Controllers\\" . implode("\\", $parts);

        // Kiểm tra class và method có tồn tại không để thực thi
        if (class_exists($controllerClass)) {
            $controllerInstance = new $controllerClass();
            if (method_exists($controllerInstance, $method)) {
                call_user_func_array([$controllerInstance, $method], $params);
            } else {
                http_response_code(404);
                die("Method $method khong ton tai trong class $controllerClass.");
            }
        } else {
            http_response_code(404);
            die("Controller class $controllerClass khong ton tai. Vui long kiem tra lai namespace va ten class.");
        }
    }
}