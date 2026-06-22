<?php

namespace App\Controller;


class IndexController
{
    // Этот метод будет вызываться, когда пользователь зайдет на /clinic/
    public function onePage(): void
    {
        echo "<h1>Главная страница</h1>";
        echo "<p>Добро пожаловать! Эта страница должна нас перенаправлять на шаблон главная</p>";
    }
}