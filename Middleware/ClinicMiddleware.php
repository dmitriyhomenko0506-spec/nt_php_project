<?php

namespace App\Middleware;

use App\Class\View;

class ClinicMiddleware
{
    public function handle()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user'])) {
            header('Location: /clinic/login');
            exit;
        }

        $status = (int)$_SESSION['user']['status'];

        // КРИТИЧЕСКИЙ ФИКС: Пускаем только роли 0 и 1
        if ($status !== 0 && $status !== 1) {
            return View::render('admin/login');
        }
    }
}
