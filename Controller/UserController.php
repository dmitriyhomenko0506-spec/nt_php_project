<?php

namespace App\Controller;

class UserController
{
    // PHP автоматически подставит значение ID в переменную $id
    public function show($id): void
    {
        if (!is_numeric($id)) {

            http_response_code(404);
            echo "<h1>404 — Страница не найдена</h1>";
            die();
        }

        echo "<h1>Профиль пользователя</h1>";
        // htmlspecialchars защищает от XSS атак (чтобы никто не вставил вредоносный код в URL)
        echo "<p>Вы просматриваете данные пользователя с ID: " . htmlspecialchars($id) . "</p>";
    }

    public function all(): void
    {
        echo "<h1>Все пользователи</h1>";
        echo "<p>Вы просматриваете данные всех пользователей </p>";
    }

}

