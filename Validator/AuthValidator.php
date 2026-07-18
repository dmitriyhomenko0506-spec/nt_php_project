<?php

namespace App\Validator;

use App\Model\Roll;

class AuthValidator
{
    public static function validator(array $post): array
    {
        $email = trim($post['email'] ?? '');
        $pass = trim($post['password'] ?? '');

        // 1. Запрашиваем данные из модели
        $result = Roll::where("email = '$email'")->get();

        // Извлекаем массив пользователя из индекса 0
        $user = !empty($result[0]) ? $result[0] : null;

        // dd($result);

        // Сверяем пароли. 
        if ($user && isset($user['pass']) && password_verify($pass, trim($user['pass']))) {
            // Пароль подошел! Возвращаем массив пользователя в middleware
            return $user;
        }
        // Если email не найден или пароль неверный
        return [];
    }
}
