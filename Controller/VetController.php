<?php

namespace App\Controller;

use App\Class\View;

class VetController
{
    public function vet(): void
    {
        // Вызываем метод render, передавая имя шаблона и массив с переменными
        View::render('admin/vet', [
            'name' => $_SESSION['user']['name'] ?? 'Врач',
            'email' => $_SESSION['user']['email'] ?? '',
            'status' => $_SESSION['user']['status'] ?? 0
        ]);
    }
}
