<?php

namespace App\Controller;


class ContactController
{
    // Этот метод будет вызываться, когда пользователь зайдет на /about

    public function contactPage()
    {
        echo "<h1>Наши контакты</h1><br>";
        echo "<h3>Проверка метода POST</h3><br>";
        echo '
    <form action="/clinic/contact" method="POST">
        <input type="text" name="message" placeholder="Введите текст">
        <button type="submit">Отправить</button>
    </form>
    ';
    }

    public function submitForm()
    {
        echo "<h1>Успешно!</h1>";
        echo "Вы отправили POST-запрос со словом: " . htmlspecialchars($_POST['message']);
    }


}