<?php
namespace App\Middleware;

class UserIdMiddleware
{
    public function handle($id): void
    {
        if (!is_numeric($id)) {
            http_response_code(404);
            echo "<h1>404 — Страница не найдена</h1>";
            exit();
        }
    }
}