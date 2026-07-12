<?php

namespace App\Controller;

use App\Class\View;


class AdminController
{
    public function admin(): void
    {
        // Вызываем метод render, передавая имя шаблона и массив с переменными
        View::render('admin/admin', [
            'name' => $_SESSION['user']['name'] ?? 'Администратор',
            'email' => $_SESSION['user']['email'] ?? '',
            'status' => $_SESSION['user']['status'] ?? 0
        ]);
    }
}
