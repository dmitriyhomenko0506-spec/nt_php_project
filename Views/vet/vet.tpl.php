<?php

/**
 * @var array  $vet    // Ассоциативный массив с данными ветеринара и вложенной связью ['appointment']
 * @var string $name   // Имя пользователя из сессии ($_SESSION['user']['name'])
 * @var string $email  // Email из сессии
 * @var int    $status // Статус роли (2 - Врач)
 */

// Извлекаем массив записей на прием для этого конкретного врача из структуры массива
$appointments = !empty($vet['appointment']) ? $vet['appointment'] : [];

// Определяем реальный ID врача из массива (проверяем vet_id, затем id, иначе ставим 2 по URL)
$currentVetId = !empty($vet['vet_id']) ? (int)$vet['vet_id'] : (!empty($vet['id']) ? (int)$vet['id'] : 2);
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Кабинет врача - <?= htmlspecialchars($name ?? 'Ветеринар') ?></title>
    <!-- Подключаем Bootstrap 5 и иконки Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://unpkg.com/htmlincludejs"></script>
</head>

<body class="bg-light">

    <!-- Верхняя панель навигации -->
    <nav class="navbar navbar-dark bg-dark shadow-sm">
        <div class="container">
            <span class="navbar-brand mb-0 h1 d-flex align-items-center gap-2">
                <i class="bi bi-heart-pulse-fill text-danger"></i> Панель ветеринарного врача
            </span>
            <div class="d-flex align-items-center gap-3">
                <span class="text-white-50 small d-none d-sm-inline">
                    <i class="bi bi-person-circle me-1"></i><?= htmlspecialchars($email) ?>
                </span>
                <a href="/clinic/logout" class="btn btn-outline-light btn-sm rounded-pill px-3">
                    <i class="bi bi-box-arrow-right me-1"></i> Выйти
                </a>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <div class="row">

            <!-- Левая колонка: Профиль врача -->
            <div class="col-12 col-lg-3 mb-4">
                <div class="card border-0 shadow-sm rounded-3 p-4 bg-white sticky-top" style="top: 20px;">
                    <div class="text-center mb-3">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle mx-auto d-flex align-items-center justify-content-center mb-2" style="width: 70px; height: 70px;">
                            <i class="bi bi-person-vcard fs-2"></i>
                        </div>

                        <!-- Выводим ФИО из переменной $name, которая прилетает из сессии -->
                        <h5 class="fw-bold text-dark mb-1"><?= htmlspecialchars($name ?? 'Не указано') ?></h5>

                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1.5 fs-7">
                            <?= htmlspecialchars(!empty($vet['specialty']) ? $vet['specialty'] : 'Врач-ветеринар') ?>
                        </span>
                    </div>

                    <hr class="text-muted my-4">

                    <div class="mb-3">
                        <label class="text-muted small d-block">Всего приёмов в графике:</label>
                        <span class="fs-4 fw-bold text-dark"><?= count($appointments) ?></span>
                    </div>
                    <div class="mb-1">
                        <!-- ТОЧНЫЙ ФИКС: Выводим вычисленный ID врача (убирает #---) -->
                        <label class="text-muted small d-block">Внутренний ID врача:</label>
                        <span class="text-secondary fw-semibold">#<?= $currentVetId ?></span>
                    </div>
                    <div class="mt-3">
                        <label class="text-muted small d-block">Код роли доступа:</label>
                        <span class="badge bg-secondary-subtle text-secondary-emphasis">Роль #<?= htmlspecialchars($status) ?></span>
                    </div>
                </div>
            </div>

            <!-- Правая колонка: График приёмов (Таблица) -->
            <div class="col-12 col-lg-9 mb-4">
                <div class="card border-0 shadow-sm rounded-3 bg-white overflow-hidden">

                    <!-- Шапка таблицы с кнопкой «Добавить запись» строго под этого врача -->
                    <div class="card-header bg-white p-4 border-0 border-bottom d-flex justify-content-between align-items-center">
                        <h4 class="h5 mb-0 fw-bold text-dark">
                            <i class="bi bi-calendar3 text-primary me-2"></i>Расписание ваших приёмов пациентов
                        </h4>
                        <!-- КНОПКА ДОБАВЛЕНИЯ: Передает вычисленный ID врача (убирает vet/0) -->
                        <a href="/clinic/admin/appointment/create/vet/<?= $currentVetId ?>" class="btn btn-primary btn-sm fw-semibold rounded-pill px-3 shadow-sm">
                            <i class="bi bi-calendar-plus me-1"></i> Добавить запись
                        </a>
                    </div>

                    <?php if (!empty($appointments)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light text-uppercase fs-7 text-secondary border-bottom">
                                    <tr>
                                        <th class="ps-4" style="width: 100px;">ID</th>
                                        <th style="width: 160px;">Дата и Время</th>
                                        <th style="width: 220px;">Пациент (Питомец)</th>
                                        <th>Владелец</th>
                                        <th style="width: 150px;">Статус</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($appointments as $appointment):
                                        if (!empty($appointment->scheduled_for)) {
                                            $dateObj = new DateTime($appointment->scheduled_for);
                                            $displayDate = $dateObj->format('d.m.Y');
                                            $displayTime = $dateObj->format('H:i');
                                        } else {
                                            $displayDate = 'Не указана';
                                            $displayTime = '--:--';
                                        }

                                        $petName = !empty($appointment->pet->name) ? htmlspecialchars($appointment->pet->name) : 'Без клички';
                                        $petSpecies = !empty($appointment->pet->species) ? htmlspecialchars($appointment->pet->species) : 'Вид не указан';

                                        $ownerName = 'Не указан';
                                        $ownerPhone = '';
                                        if (!empty($appointment->pet->owner)) {
                                            if (is_object($appointment->pet->owner)) {
                                                $ownerName = !empty($appointment->pet->owner->name) ? htmlspecialchars($appointment->pet->owner->name) : 'Не указан';
                                                $ownerPhone = !empty($appointment->pet->owner->phone) ? htmlspecialchars($appointment->pet->owner->phone) : '';
                                            } elseif (is_array($appointment->pet->owner)) {
                                                $ownerName = !empty($appointment->pet->owner['name']) ? htmlspecialchars($appointment->pet->owner['name']) : 'Не указан';
                                                $ownerPhone = !empty($appointment->pet->owner['phone']) ? htmlspecialchars($appointment->pet->owner['phone']) : '';
                                            }
                                        }
                                    ?>
                                        <tr>
                                            <td class="ps-4 fw-bold text-primary">#<?= htmlspecialchars($appointment->id) ?></td>
                                            <td>
                                                <div class="d-flex flex-column gap-1">
                                                    <span class="fw-bold text-dark small"><i class="bi bi-calendar-event text-muted me-1"></i><?= $displayDate ?></span>
                                                    <div>
                                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle font-monospace px-2 py-1">
                                                            <i class="bi bi-clock me-1"></i><?= $displayTime ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="fw-bold text-dark d-flex align-items-center gap-1">
                                                    <i class="bi bi-paw-fill text-warning"></i><?= $petName ?>
                                                </div>
                                                <div class="text-muted small ps-3"><?= $petSpecies ?></div>
                                            </td>
                                            <td>
                                                <div class="fw-semibold text-dark"><?= $ownerName ?></div>
                                                <?php if (!empty($ownerPhone)): ?>
                                                    <div class="text-muted small"><i class="bi bi-telephone text-secondary me-1"></i><?= $ownerPhone ?></div>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if (($appointment->status ?? '') === 'confirm'): ?>
                                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1.5 rounded-pill d-inline-flex align-items-center gap-1">
                                                        <i class="bi bi-check-circle-fill"></i> Подтверждена
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2.5 py-1.5 rounded-pill d-inline-flex align-items-center gap-1">
                                                        <i class="bi bi-hourglass-split"></i> В обработке
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="card-body text-center p-5">
                            <div class="text-muted mb-3 fs-1"><i class="bi bi-calendar-x"></i></div>
                            <h5 class="text-secondary fw-semibold">У вас пока нет запланированных приёмов</h5>
                            <p class="text-muted small mb-0">Все новые записи клиентов будут автоматически отображаться в этой таблице.</p>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>

    <!-- Скрипты Bootstrap 5 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>

</html>