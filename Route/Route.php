<?php

namespace App\Route;
class Route
{
    private static array $routes = [];

    public static function get(string $url, array $action): void
    {
        $pattern = preg_replace('/\{[a-zA-Z0-9_]+\}/', '([a-zA-Z0-9_]+)', $url);
        $pattern = '#^' . $pattern . '$#';

        self::$routes[] = [
            'pattern' => $pattern,
            'action' => $action,
            'method' => 'GET'
        ];
    }

    public static function post(string $url, array $action): void
    {
        $pattern = preg_replace('/\{[a-zA-Z0-9_]+\}/', '([a-zA-Z0-9_]+)', $url);
        $pattern = '#^' . $pattern . '$#';

        self::$routes[] = [
            'pattern' => $pattern,
            'action' => $action,
            'method' => 'POST'
        ];
    }

    public static function dispatch(): void
    {

        // var_dump(self::$routes);


        // 1. Узнаем метод запроса из браузера (GET, POST, PUT и т.д.)
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        // 2. Получаем чистый URL без подпапок сервера (например, просто /about)
        $current_url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        // var_dump($current_url);

        // Перебираем все наши маршруты из блокнота
        foreach (self::$routes as $route) {

            // ИСПРАВЛЕНО: Сначала проверяем, совпадает ли метод (GET/POST), 
            // и только если метод совпал — проверяем регулярное выражение URL
            if ($route['method'] === $requestMethod && preg_match($route['pattern'], $current_url, $matches)) {

                array_shift($matches);
                $params = $matches;

                [$controllerClass, $methodName] = $route['action'];
                $controllerObject = new $controllerClass();

                call_user_func_array([$controllerObject, $methodName], $params);
                return;
            }
        }

        // Если перебрали все маршруты и совпадений нет
        http_response_code(404);
        echo "<h1>404 — Страница не найдена</h1>";
    }
}
