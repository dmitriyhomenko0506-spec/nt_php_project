<?php

/**
 * @var object $clinic  // Объект клиники со всей вложенной цепочкой связей
 */

// Вытаскиваем массив врачей этой клиники из объекта ORM
$doctors = !empty($clinic->vet) ? $clinic->vet : [];
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управление филиалом - <?= htmlspecialchars($clinic->name ?? 'Филиал') ?></title>
    <!-- Подключаем Bootstrap 5 и иконки Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://unpkg.com/htmlincludejs"></script>
</head>

<body class="bg-light">

    <div class="container my-5">

        <!-- Кнопка Назад на главную страницу админки -->
        <div class="mb-4">
            <a href="/clinic/admin/admin" class="btn btn-link text-decoration-none p-0 d-inline-flex align-items-center gap-2 fw-semibold text-secondary">
                <i class="bi bi-arrow-left"></i> Вернуться к списку всех клиник
            </a>
        </div>

        <!-- Шапка филиала -->
        <div class="card border-0 shadow-sm rounded-3 overflow-hidden mb-4">
            <div class="card-body p-4 bg-dark text-white d-flex justify-content-between align-items-center">
                <div>
                    <span class="badge bg-primary text-uppercase px-3 py-2 mb-2 rounded-pill"><?= htmlspecialchars($clinic->city ?? '') ?></span>
                    <h1 class="h3 mb-1 fw-bold"><?= htmlspecialchars($clinic->name ?? '') ?></h1>
                    <p class="text-white-50 small mb-0">Системный ID филиала: #<?= htmlspecialchars($clinic->id ?? '') ?></p>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Левая колонка: Статистика филиала -->
            <div class="col-12 col-lg-3 mb-4">
                <div class="card border-0 shadow-sm rounded-3 p-4 bg-white h-100">
                    <h5 class="text-secondary fw-bold small text-uppercase tracking-wider mb-3">Information</h5>
                    <div class="mb-3">
                        <label class="text-muted small d-block">Всего сотрудников:</label>
                        <span class="fs-4 fw-bold text-dark"><?= count($doctors) ?> чел.</span>
                    </div>
                    <hr class="text-muted">
                    <div class="text-muted small">
                        Здесь отображаются ветеринарные специалисты и журнал приемов, привязанные исключительно к филиалу <strong><?= htmlspecialchars($clinic->name ?? '') ?></strong>.
                    </div>
                </div>
            </div>

            <!-- Правая колонка: Реестр врачей филиала -->
            <div class="col-12 col-lg-9 mb-4">
                <div class="card border-0 shadow-sm rounded-3 bg-white overflow-hidden">
                    <div class="card-header bg-white p-4 border-0 border-bottom">
                        <h4 class="h5 mb-0 fw-bold text-dark">
                            <i class="bi bi-people-fill text-secondary me-2"></i>Медицинский персонал филиала
                        </h4>
                    </div>

                    <!-- Table -->
                    <?php if (!empty($doctors)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light text-secondary small text-uppercase border-bottom">
                                    <tr>
                                        <th class="ps-4" style="width: 10%">ID</th>
                                        <th style="width: 45%">ФИО Специалиста</th>
                                        <th style="width: 25%">Специализация</th>
                                        <th class="text-end pe-4" style="width: 20%">Действия</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($doctors as $doctor): ?>
                                        <tr>
                                            <td class="ps-4 fw-semibold text-muted">#<?= $doctor->id ?></td>
                                            <td>
                                                <div class="fw-bold text-dark"><?= htmlspecialchars($doctor->name) ?></div>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark border border-secondary-subtle px-2.5 py-1.5 rounded">
                                                    <?= htmlspecialchars($doctor->specialty) ?>
                                                </span>
                                            </td>
                                            <td class="text-end pe-4">
                                                <div class="d-flex justify-content-end gap-2">
                                                    <a href="/clinic/admin/admin/doctor/edit/<?= $doctor->id ?>" class="btn btn-outline-warning btn-sm rounded-pill px-3 fw-medium">
                                                        <i class="bi bi-pencil me-1"></i> Правка
                                                    </a>
                                                    <a href="/clinic/admin/admin/doctor/delete/<?= $doctor->id ?>" class="btn btn-outline-danger btn-sm rounded-pill px-3 fw-medium" onclick="return confirm('Вы уверены, что хотите удалить этого сотрудника?');">
                                                        <i class="bi bi-trash me-1"></i> Удалить
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="card-body text-center p-5">
                            <div class="text-muted mb-3 fs-1"><i class="bi bi-person-x"></i></div>
                            <h5 class="text-secondary fw-semibold">В этом филиале пока нет зарегистрированных врачей</h5>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </div> <!-- ТАБЛИЦА ЗАПИСЕЙ НА ПРИЕМ ДЛЯ КОНКРЕТНОЙ КЛИНИКИ -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-3 overflow-hidden">

                    <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center border-bottom">
                        <h5 class="mb-0 fw-bold text-dark">
                            <i class="bi bi-calendar-check text-primary me-2"></i>Журнал записей на прием филиала
                        </h5>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light text-uppercase fs-7 text-muted border-bottom">
                                <tr>
                                    <th class="ps-4" style="width: 120px;">ID Записи</th>
                                    <th>Дата и время приёма</th>
                                    <th>Пациент (Питомец)</th>
                                    <th>Владелец</th>
                                    <th>Врач (Ветеринар)</th>
                                    <th>Статус</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $hasAppointments = false;

                                if (!empty($clinic->vet)):
                                    foreach ($clinic->vet as $vet):
                                        if (!empty($vet->appointment)):
                                            foreach ($vet->appointment as $appointment):
                                                $hasAppointments = true;

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

                                                // ТОЧНЫЙ ФИКС С УЧЕТОМ СТРУКТУРЫ ВАШЕЙ СВЯЗИ [0]
                                                $ownerName = !empty($appointment->pet->owner[0]->name) ? htmlspecialchars($appointment->pet->owner[0]->name) : 'Не указан';
                                                $ownerPhone = !empty($appointment->pet->owner[0]->phone) ? htmlspecialchars($appointment->pet->owner[0]->phone) : '';
                                ?>
                                                <tr>
                                                    <td class="ps-4 fw-bold text-primary">#<?= htmlspecialchars($appointment->id) ?></td>
                                                    <td>
                                                        <div class="d-flex align-items-center gap-2">
                                                            <div class="bg-light p-2 rounded border d-flex flex-column align-items-center justify-content-center" style="min-width: 85px;">
                                                                <span class="fw-bold text-dark small mb-0"><?= $displayDate ?></span>
                                                            </div>
                                                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1.5 fw-bold">
                                                                <i class="bi bi-clock me-1"></i><?= $displayTime ?>
                                                            </span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="fw-bold text-dark d-flex align-items-center gap-1">
                                                            <i class="bi bi-paw-fill text-warning"></i><?= $petName ?>
                                                        </div>
                                                        <div class="text-muted small"><?= $petSpecies ?></div>
                                                    </td>
                                                    <td>
                                                        <div class="fw-semibold text-dark"><?= $ownerName ?></div>
                                                        <?php if (!empty($ownerPhone)): ?>
                                                            <div class="text-muted small"><i class="bi bi-telephone text-secondary me-1"></i><?= htmlspecialchars($ownerPhone) ?></div>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <div class="fw-bold text-dark"><?= htmlspecialchars($vet->name) ?></div>
                                                        <div class="text-muted small"><?= htmlspecialchars($vet->specialty) ?></div>
                                                    </td>
                                                    <td>
                                                        <?php if (($appointment->status ?? '') === 'confirm'): ?>
                                                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1.5 rounded-pill">Подтверждена</span>
                                                        <?php else: ?>
                                                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2.5 py-1.5 rounded-pill">В обработке</span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                    <?php
                                            endforeach;
                                        endif;
                                    endforeach;
                                endif;

                                if (!$hasAppointments):
                                    ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted">Записей на прием в этом филиале пока нет.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>

    </div>

    <!-- Подключаем скрипты Bootstrap 5 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>

</html>