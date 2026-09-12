<?php

namespace App\Class;


class View
{
    /**
     * Рендеринг шаблона с передачей данных
     *
     * @param string $path Путь к шаблону (например: 'admin/admin')
     * @param array $data Ассоциативный массив с переменными
     */
    public static function render(string $path, array $data = []): void
    {
        // Встроенная функция PHP, которая превращает ['name' => 'admin'] в переменную $name
        if (!empty($data)) {
            extract($data);
        }

        // Формируем полный путь к файлу шаблона
        // BASE_DIR должна быть объявлена глобально в вашем index.php
        $file = BASE_DIR . "/Views/{$path}.tpl.php";

        if (file_exists($file)) {
            require_once $file;
        } else {
            die("Шаблон по адресу {$file} не найден!");
        }
    }
}
