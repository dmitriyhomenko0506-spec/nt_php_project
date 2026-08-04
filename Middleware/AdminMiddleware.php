<?php

namespace App\Middleware;

use App\Class\View;

class AdminMiddleware
{
    public function handle()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // КРИТИЧЕСКИЙ ФИКС: Проверяем на статус 0
        if (empty($_SESSION['user']) || (int)$_SESSION['user']['status'] !== 0) {
            return View::render('admin/login');
        }
    }
}
