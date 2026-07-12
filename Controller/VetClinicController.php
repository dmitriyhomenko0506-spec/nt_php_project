<?php

namespace App\Controller;

use App\Class\View;

class VetClinicController
{
    public function vetClinic(): void
    {
        // Вызываем метод render, передавая имя шаблона и массив с переменными
        View::render('admin/vet-clinic', [
            'name' => $_SESSION['user']['name'] ?? 'Клиника',
            'email' => $_SESSION['user']['email'] ?? '',
            'status' => $_SESSION['user']['status'] ?? 0
        ]);
    }
}
