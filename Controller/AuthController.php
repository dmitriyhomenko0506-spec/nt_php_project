<?php

namespace App\Controller;

use App\Class\View;

class AuthController
{
    public function index()
    {
        // Если пользователь УЖЕ авторизован, контроллер сам решает, куда его отправить
        if (!empty($_SESSION['user'])) {
            $user = $_SESSION['user'];

            if ($user['status'] === 0) {
                header('Location: /clinic/admin'); // Админ
                exit();
            } elseif ($user['status'] === 1) {
                header('Location: /clinic/vet-clinic'); // Клиника
                exit();
            } else {
                header('Location: /clinic/vet'); // Врач
                exit();
            }
        }

        return View::render('admin/login');
    }

    public function submit(): void
    {

        $user = $_SESSION['user'] ?? null;

        if ($user) {
            // Проверяем статус и перенаправляем на нужный URL
            if ($user['status'] === 0) {
                header('Location: /clinic/admin'); // Админ
                exit();
            } elseif ($user['status'] === 1) {
                header('Location: /clinic/vet-clinic'); // Клиника
                exit();
            } else {
                header('Location: /clinic/vet');  // Врач
                exit();
            }
        }
    }

    public function logout(): void
    {
        // Очищаем все переменные сессии в памяти PHP
        $_SESSION = [];

        // Уничтожаем саму сессию на сервере
        if (session_id() !== '') {
            session_destroy();
        }

        // Перенаправляем пользователя обратно на страницу входа
        header('Location: /clinic/login');
        exit();
    }
}
