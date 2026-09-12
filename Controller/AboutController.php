<?php

namespace App\Controller;


class AboutController
{
    // Этот метод будет вызываться, когда пользователь зайдет на /about
    public function aboutPage(): void
    {
        echo "<h1>О компании</h1>";
        echo "<p>Добро пожаловать! Эта страница должна нас перенаправлять на шаблон about</p>";
    }
}
