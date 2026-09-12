<?php

namespace App\Controller;

use App\Class\View;

class AuthController
{
    public function index()
    {
        if (!empty($_SESSION['user'])) {
            $user = $_SESSION['user'];
            $id = $_SESSION['user']['clinic_id'];

            if ($user['status'] === 0) {
                header('Location: /clinic/admin/admin'); // Админ
                exit();
            } elseif ($user['status'] === 1) {
                header('Location: /clinic/admin/vet-clinic/' . $id); // Клиника
                exit();
            } else {
                header('Location: /clinic/admin/vet/' . $id); // Врач
                exit();
            }
        }

        return View::render('admin/login');
    }



    public function submit(): void
    {
        if (!empty($_SESSION['user'])) {
            $user = $_SESSION['user'];
            $id = $_SESSION['user']['clinic_id'];

            if ($user['status'] === 0) {
                header('Location: /clinic/admin/admin'); // Админ
                exit();
            } elseif ($user['status'] === 1) {
                header('Location: /clinic/admin/vet-clinic/' . $id); // Клиника
                exit();
            } else {
                header('Location: /clinic/admin/vet/' . $id); // Врач
                exit();
            }
        }

        header('Location: /clinic/login');
        exit();
    }

    public function logout(): void
    {
        // 1. Обязательно запускаем сессию, чтобы PHP понял, какую именно сессию закрывать
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // 2. Полностью очищаем массив $_SESSION (стираем данные пользователя)
        $_SESSION = [];

        // 3. Удаляем сессионные куки в браузере клиента (чтобы сессия не восстановилась)
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        // 4. Уничтожаем файл сессии на самом сервере
        session_destroy();

        // 5. Перенаправляем пользователя на главную или на страницу входа
        header('Location: /clinic/login');
        exit;
    }
}
