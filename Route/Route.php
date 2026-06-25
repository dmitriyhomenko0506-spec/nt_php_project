<?php

namespace App\Route;

use App\Traits\RoutePattern;
use App\Traits\RouteApiWeb;


class Route
{

    use RoutePattern, RouteApiWeb;

    private static array $routes = [];

    public static function get(string $url, array $action, ?string $middleware = null): void
    {
        $pattern = self::get_route_pattern($url);

        //Проверка на API вход
        $type = self::get_route_type($url);

        self::$routes[] = [
            'type' => $type,
            'method' => 'GET',
            'uri' => $pattern,
            'action' => $action,
            'middleware' => $middleware
        ];
    }

    public static function post(string $url, array $action, ?string $middleware = null): void
    {
        $pattern = self::get_route_pattern($url);

        //Проверка на API вход
        $type = self::get_route_type($url);

        self::$routes[] = [
            'type' => $type,
            'method' => 'POST',
            'uri' => $pattern,
            'action' => $action,
            'middleware' => $middleware
        ];
    }

    public static function put(string $url, array $action, ?string $middleware = null): void
    {
        $pattern = self::get_route_pattern($url);

        //Проверка на API вход
        $type = self::get_route_type($url);

        self::$routes[] = [
            'type' => $type,
            'method' => 'PUT',
            'uri' => $pattern,
            'action' => $action,
            'middleware' => $middleware
        ];
    }

    public static function getRoutes(): array
    {
        return self::$routes;
    }


    public static function dispatch(): void
    {

        // var_dump(self::$routes);

        //Проверка на запуск из консоли
        if (php_sapi_name() === 'cli') {
            return;
        }


        // 1. Узнаем метод запроса из браузера (GET, POST, PUT и т.д.)
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        // 2. Получаем чистый URL без подпапок сервера (например, просто /about)
        $current_url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        // var_dump($current_url);

        // Перебираем все наши маршруты из блокнота
        foreach (self::$routes as $route) {


            // ИСПРАВЛЕНО: Сначала проверяем, совпадает ли метод (GET/POST), 
            // и только если метод совпал — проверяем регулярное выражение URL
            if ($route['method'] === $requestMethod && preg_match($route['uri'], $current_url, $matches)) {


                array_shift($matches);
                $params = $matches;

                if (!empty($route['middleware'])) {
                    $middlewareClass = $route['middleware'];
                    /** @var mixed $middlewareObject */
                    $middlewareObject = new $middlewareClass();
                    call_user_func_array([$middlewareObject, 'handle'], $params); // Теперь тут будет лежать ID!
                }


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
