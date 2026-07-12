<?php

/**
 * @var string $name
 * @var string $email
 * @var int $status
 */
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Ключ 'name' из массива стал переменной $name -->
    <title>Панель управления | <?= htmlspecialchars($name); ?></title>
    <link rel="stylesheet" href="/clinic/assert/main.css">
</head>

<body>

    <h1>Добро пожаловать, <?= htmlspecialchars($name); ?>!</h1>
    <p class="text-muted">Ваш Email: <?= htmlspecialchars($email); ?></p>
    <p class="text-muted">Код роли в системе: <?= (int)$status; ?></p>
    <a href="/clinic/logout" class="btn btn-outline-danger">Выйти из кабинета</a>

</body>

</html>