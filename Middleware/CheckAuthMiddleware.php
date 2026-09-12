<?php

namespace App\Middleware;

class CheckAuthMiddleware
{
    public function handle(): void
    {
        // Если в сессии НЕТ данных пользователя (человек вышел или не заходил)
        if (empty($_SESSION['user'])) {
            // Записываем ошибку, чтобы форма логина сообщила причину
            $_SESSION['auth_error'] = "Доступ запрещен. Пожалуйста, авторизуйтесь.";

            // Принудительно выгоняем на страницу входа
            header('Location: /clinic/login');
            exit();
        }
    }
}
