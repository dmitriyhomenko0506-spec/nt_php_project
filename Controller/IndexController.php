<?php

namespace App\Controller;


class IndexController
{
    public function onePage(): void
    {
        // Подключаем файл шаблона. Поднимаемся на уровень выше из папки Controller и заходим в Views
        require_once __DIR__ . '/../Views/index.tpl.php';
    }
}
