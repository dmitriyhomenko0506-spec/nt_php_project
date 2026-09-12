<?php

namespace App\Middleware;

use App\Class\View;

class DoctorMiddleware
{
    public function handle()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // КРИТИЧЕСКИЙ ФИКС: Проверяем на статус 2
        if (empty($_SESSION['user']) || (int)$_SESSION['user']['status'] !== 2) {
            return View::render('admin/login');
        }
    }
}
