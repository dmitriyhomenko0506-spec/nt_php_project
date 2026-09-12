<?php

namespace App\Middleware;

use App\Validator\AuthValidator;


class AuthMiddleware
{
    public function handle(): void
    {
        // Переходим сразу в контроллер если уже пользователь авторизирован
        if (!empty($_SESSION['user'])) {
            return;
        }

        if (!empty($_POST['email']) and !empty($_POST['password'])) {

            $isValid = AuthValidator::validator($_POST);

            if (empty($isValid)) {
                $_SESSION['auth_error'] = "Неверный логин или пароль";
                header('Location: /clinic/login');
                exit();
            } else {
                // Просто сохраняем массив в сессию без повторных запросов в БД
                $_SESSION['user'] = $isValid;
                //dd($_SESSION['user']['name']);
            }
        }
    }
}
