<?php

/**
 * @var object $vet Объект текущего врача со всей цепочкой связей
 * @var string $name Имя вошедшего пользователя из сессии
 * @var string $email Email вошедшего пользователя из сессии
 * @var int $status Статус роли
 */

// Извлекаем массив приёмов, привязанных к этому врачу через связь
$appointments = !empty($vet->appointment) ? $vet->appointment : [];
?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Панель ветеринарного врача</title>
    <!-- Подключение Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://unpkg.com/htmlincludejs"></script>
</head>

<body class="bg-light">

    <!-- Шапка (Навбар) -->
    <nav class="navbar navbar-dark bg-dark mb-4 py-3 shadow-sm">
        <div class="container-fluid px-4">
            <span class="navbar-brand mb-0 h1 fs-5 text-white">Панель ветеринарного врача</span>
            <div class="d-flex align-items-center gap-3">
                <span class="text-white-50 small"><?= htmlspecialchars($email ?? '') ?></span>
                <a href="/clinic/logout" class="btn btn-sm btn-outline-light px-3 rounded-pill">Выйти</a>
            </div>
        </div>
    </nav>

    <div class="container-fluid px-4">
        <div class="row g-4">
            <!-- ЛЕВАЯ КОЛОНКА: КАРТОЧКА ВРАЧА -->
            <div class="col-lg-3 col-md-4">
                <div class="card shadow-sm border-0 p-4 text-center rounded-4 bg-white">

                    <!-- Аватарка врача -->
                    <div class="d-flex justify-content-center mb-3">
                        <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <svg xmlns="http://w3.org" width="32" height="32" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
                                <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
                            </svg>
                        </div>
                    </div>

                    <!-- ФИО врача -->
                    <h5 class="fw-bold mb-1 text-dark"><?= htmlspecialchars($vet->name ?? 'Не указано') ?></h5>

                    <!-- Специальность -->
                    <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-1.5 fs-7 mb-4">
                        <?= htmlspecialchars($vet->specialty ?? 'Врач') ?>
                    </span>

                    <div class="text-start border-top pt-3">
                        <!-- КЛИНИКА СОТРУДНИКА (Добавлено на основе связи ->clinic) -->
                        <div class="mb-3">
                            <small class="text-muted d-block">Филиал работы:</small>
                            <span class="fw-bold text-success d-block" style="font-size: 0.95rem;">
                                🏥 <?= htmlspecialchars($clinic->name ?? 'Не привязан') ?>
                            </span>
                            <?php if (!empty($clinic->city)): ?>
                                <small class="text-muted d-block" style="font-size: 0.8rem; margin-top: -2px;">
                                    г. <?= htmlspecialchars($clinic->city) ?>
                                </small>
                            <?php endif; ?>
                        </div>

                        <!-- Количество приемов -->
                        <div class="mb-3">
                            <small class="text-muted d-block">Всего приёмов в графике:</small>
                            <span class="fw-bold fs-4 text-dark">
                                <?= count(array_filter($appointments, function ($item) {
                                    return ($item->status ?? '') === 'confirm';
                                })) ?>
                            </span>
                        </div>

                        <!-- Внутренний ID врача -->
                        <div class="mb-3">
                            <small class="text-muted d-block">Внутренний ID врача:</small>
                            <span class="fw-medium text-secondary">#<?= htmlspecialchars($vet->id ?? '') ?></span>
                        </div>

                        <!-- Роль доступа -->
                        <div>
                            <small class="text-muted d-block mb-1">Код роли доступа:</small>
                            <span class="badge bg-secondary-subtle text-secondary border px-2.5 py-1.5 rounded">
                                Роль #<?= htmlspecialchars($status ?? '2') ?>
                            </span>
                        </div>
                    </div>

                </div>
            </div>
            <!-- ПРАВАЯ КОЛОНКА: ЖУРНАЛ ЗАПИСЕЙ -->
            <div class="col-lg-9 col-md-8">
                <div class="card shadow-sm border-0 p-4 rounded-4 bg-white h-100">

                    <!-- Заголовок и динамическая кнопка «Добавить запись» с ID клиники -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fw-bold text-dark m-0 fs-5">Журнал записей на прием</h4>
                        <a href="/clinic/admin/vet/appointment/create/clinic/<?= htmlspecialchars($vet->clinic_id ?? '') ?>" class="btn btn-primary rounded-pill px-4 btn-sm fw-medium">Добавить запись</a>
                    </div>

                    <?php if (!empty($appointments)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle m-0" style="font-size: 0.9rem;">
                                <!-- Шапка таблицы в верхнем регистре -->
                                <thead class="table-light text-uppercase">
                                    <tr>
                                        <th class="text-secondary fw-bold border-0 ps-3" style="font-size: 0.72rem; letter-spacing: 0.5px;">ID записи</th>
                                        <th class="text-secondary fw-bold border-0" style="font-size: 0.72rem; letter-spacing: 0.5px;">Дата и время приёма</th>
                                        <th class="text-secondary fw-bold border-0" style="font-size: 0.72rem; letter-spacing: 0.5px;">Пациент (Питомец)</th>
                                        <th class="text-secondary fw-bold border-0" style="font-size: 0.72rem; letter-spacing: 0.5px;">Владелец</th>
                                        <th class="text-secondary fw-bold border-0" style="font-size: 0.72rem; letter-spacing: 0.5px;">Статус</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($appointments as $appointment): ?>
                                        <?php
                                        $pet = $appointment->pet ?? null;
                                        // Извлекаем первого владельца из массива owner
                                        $owner = (isset($pet->owner) && is_array($pet->owner)) ? ($pet->owner ?? null) : null;

                                        ?>
                                        <?php if (($appointment->status ?? '') === 'confirm'): ?>
                                            <tr class="border-bottom">
                                                <!-- ID ЗАПИСИ -->
                                                <td class="ps-3">
                                                    <span class="text-primary fw-bold">#<?= (int)$appointment->id ?></span>
                                                </td>

                                                <!-- ДАТА И ВРЕМЯ ПРИЁМА -->
                                                <td>
                                                    <div class="d-flex gap-1 align-items-center">
                                                        <span class="badge bg-white text-dark border border-secondary-subtle px-2.5 py-1.5 fw-medium rounded" style="font-size: 0.85rem;">
                                                            <?= htmlspecialchars(date('d.m.Y', strtotime($appointment->scheduled_for))) ?>
                                                        </span>
                                                        <span class="badge bg-primary-subtle text-primary px-2.5 py-1.5 fw-bold rounded" style="font-size: 0.85rem;">
                                                            <?= htmlspecialchars(date('H:i', strtotime($appointment->scheduled_for))) ?>
                                                        </span>
                                                    </div>
                                                </td>

                                                <!-- ПАЦИЕНТ (ПИТОМЕЦ) -->
                                                <td>
                                                    <div class="fw-bold text-dark mb-0" style="font-size: 0.95rem;"><?= htmlspecialchars($pet->name ?? 'Кличка') ?></div>
                                                    <small class="text-muted d-block" style="font-size: 0.75rem;"><?= htmlspecialchars($pet->species ?? 'Животное') ?></small>
                                                </td>

                                                <!-- ВЛАДЕЛЕЦ -->
                                                <td>
                                                    <div class="text-dark mb-0" style="font-size: 0.9rem;"><?= htmlspecialchars($owner[0]->name ?? 'Не указан') ?></div>
                                                    <small class="text-muted d-block" style="font-size: 0.8rem;"><?= htmlspecialchars($owner[0]->phone ?? '') ?></small>
                                                </td>

                                                <!-- СТАТУС И ДЕЙСТВИЯ -->
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">

                                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1.5 rounded-pill">Подтверждена</span>
                                                        <a href="/clinic/admin/vet/appointment/confirm/<?= (int)$appointment->id ?>" class="btn btn-outline-success btn-sm rounded-pill px-3 fw-medium">✅</a>
                                                        <a href="/clinic/admin/vet/appointment/delete/<?= (int)$appointment->id ?>" class="btn btn-outline-danger btn-sm rounded-pill px-3 fw-medium" onclick="return confirm('Вы уверены, что хотите удалить этого сотрудника?');">❌</a>

                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <!-- Блок-заглушка, если массив приёмов allappointments пуст -->
                        <div class="text-center py-5">
                            <p class="text-muted mb-1 fs-5">У вас пока нет запланированных приёмов</p>
                            <small class="text-black-50">Все новые записи клиентов будут автоматически отображаться в этой таблице.</small>
                        </div>
                    <?php endif; ?>

                </div>
            </div> <!-- Конец правой колонки -->

        </div> <!-- Конец row -->
    </div> <!-- Конец container-fluid -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</body>

</html>