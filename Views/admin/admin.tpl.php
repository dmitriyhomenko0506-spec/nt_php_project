<?php

/**
 * @var string $name
 * @var string $email
 * @var int $status
 * @var array $clinics
 */
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Панель управления клиниками</title>
    <link rel='stylesheet' href='/clinic/assert/main.css'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://unpkg.com/htmlincludejs"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // 1. Получаем хэш из адресной строки (например, #doctors-content)
            var hash = window.location.hash;

            if (hash) {
                // 2. Ищем кнопку-переключатель вкладки, у которой в data-bs-target прописан этот хэш
                var tabButton = document.querySelector('button[data-bs-target="' + hash + '"]');

                if (tabButton) {
                    // 3. Активируем вкладку через встроенный JavaScript API Bootstrap 5
                    var tab = new bootstrap.Tab(tabButton);
                    tab.show();

                    // 4. Опционально: плавно скроллим экран к блоку вкладок, чтобы админ сразу видел таблицу
                    setTimeout(function() {
                        tabButton.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                    }, 100);
                }
            }
        });
    </script>
    <style>
        .fs-7 {
            font-size: 0.8rem;
        }

        .table th {
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .table td {
            padding-top: 1rem;
            padding-bottom: 1rem;
        }

        .card-clinic-hover {
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }

        .card-clinic-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 .5rem 1.5rem rgba(0, 0, 0, .08) !important;
        }
    </style>
</head>

<!-- Красивая верхняя панель (Navbar) с данными администратора -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm rounded-3 mb-4 p-3">
    <div class="container-fluid d-flex justify-content-between align-items-center">

        <!-- Левая часть: приветствие и статус -->
        <div class="d-flex align-items-center gap-3">
            <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 48px; height: 48px;">
                AD
            </div>
            <div>
                <h4 class="text-white mb-0 fw-bold">Добро пожаловать, <?= htmlspecialchars($name); ?></h4>
                <div class="d-flex gap-3 align-items-center mt-1">
                    <span class="text-white-50 small">
                        <i class="bi bi-envelope text-primary me-1"></i><?= htmlspecialchars($email); ?>
                    </span>
                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill fs-7 py-1 px-2.5">
                        <i class="bi bi-shield-check me-1"></i>Роль: Супер-админ (<?= (int)$status; ?>)
                    </span>
                </div>
            </div>
        </div>

        <!-- Правая часть: кнопка выхода -->
        <div>
            <!-- Оставляем вашу ссылку/кнопку выхода, стилизовав её под Bootstrap -->
            <a href="/clinic/logout" class="btn btn-outline-danger btn-sm rounded-pill px-3 d-flex align-items-center gap-2 transition-all">
                <i class="bi bi-box-arrow-right"></i> Выйти из кабинета
            </a>
        </div>

    </div>
</nav>

<body class="bg-light">
    <ul class="nav nav-pills mb-4 bg-white p-2 rounded-3 shadow-sm border" id="adminTabs" role="tablist">
        <!-- Кнопка 1: Все клиники -->
        <li class="nav-item" role="presentation">
            <button class="nav-link active d-flex align-items-center gap-2 px-4 py-2.5 fw-semibold" id="clinics-tab" data-bs-toggle="pill" data-bs-target="#clinics-content" type="button" role="tab">
                <i class="bi bi-hospital fs-5"></i> Все клиники
            </button>
        </li>

        <!-- Кнопка 2: Реестр врачей -->
        <li class="nav-item" role="presentation">
            <button class="nav-link d-flex align-items-center gap-2 px-4 py-2.5 fw-semibold" id="doctors-tab" data-bs-toggle="pill" data-bs-target="#doctors-content" type="button" role="tab">
                <i class="bi bi-person-heart fs-5"></i> Реестр ветеринарных врачей
            </button>
        </li>

        <!-- Кнопка 3: Реестр записей -->
        <li class="nav-item" role="presentation">
            <button class="nav-link d-flex align-items-center gap-2 px-4 py-2.5 fw-semibold" id="appointments-tab" data-bs-toggle="pill" data-bs-target="#appointments-content" type="button" role="tab">
                <i class="bi bi-calendar-check fs-5"></i> Реестр записей на прием
            </button>
        </li>
    </ul>

    <!-- Открывающий тег для контейнера содержимого (ОБЯЗАТЕЛЬНО ОСТАВЬТЕ ЕГО ОТКРЫТЫМ) -->
    <div class="tab-content" id="adminTabsContent">

        <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
            <!-- Уведомление об успешном добавлении клиники -->
            <div class="alert alert-success border-0 shadow-sm rounded-3 d-flex align-items-center gap-3 p-3 mb-4 position-relative" role="alert">

                <!-- Иконка галочки -->
                <div class="icon-shape bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; min-width: 32px;">
                    <i class="bi bi-check-lg fw-bold"></i>
                </div>

                <!-- Текст сообщения -->
                <div class="pe-5">
                    <strong class="text-dark d-block">Успешно!</strong>
                    <span class="text-muted small">Все данные успешно добавлено в систему.</span>
                </div>

                <!-- Ссылка-крестик, которая делает перенаправление (редирект) -->
                <a href="/clinic/admin/admin" class="btn-close position-absolute end-0 top-0 m-3 text-decoration-none" aria-label="Close"></a>

            </div>
        <?php endif; ?>

        <?php if (isset($_GET['error']) && $_GET['error'] == 1): ?>
            <!-- Уведомление об общей ошибке системы -->
            <div class="alert alert-danger border-0 shadow-sm rounded-3 d-flex align-items-center gap-3 p-3 mb-4 position-relative" role="alert">

                <!-- Иконка знака восклицания / ошибки -->
                <div class="icon-shape bg-danger text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; min-width: 32px;">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>

                <!-- Текст сообщения -->
                <div class="pe-5">
                    <strong class="text-dark d-block">Ошибка!</strong>
                    <span class="text-muted small">Не удалось сохранить данные. Проверьте правильность заполнения полей или обратитесь к системному администратору.</span>
                </div>

                <!-- Ссылка-крестик, которая делает перенаправление на чистый роут -->
                <a href="/clinic/admin/admin" class="btn-close position-absolute end-0 top-0 m-3 text-decoration-none" aria-label="Close"></a>

            </div>
        <?php endif; ?>

        <!-- ================= СОДЕРЖИМОЕ ВКЛАДКИ 1: ВСЕ КЛИНИКИ ================= -->
        <div class="tab-pane fade show active" id="clinics-content" role="tabpanel" aria-labelledby="clinics-tab">

            <!-- Верхняя панель управления внутри вкладки -->
            <div class="d-flex justify-content-between align-items-center mb-4 bg-white p-3 rounded-3 shadow-sm border">
                <div>
                    <h5 class="mb-0 fw-bold text-dark">Управление филиалами</h5>
                    <p class="text-muted small mb-0">Всего зарегистрировано клиник в вашей сети: <span class="fw-bold text-primary"><?= count($clinics) ?></span></p>
                </div>
                <!-- Кнопка, которая вызывает модальное окно по его ID -->
                <a href="/clinic/admin/admin/create_clinic" class="btn btn-primary d-flex align-items-center gap-2 px-3 py-2 rounded-pill fw-medium btn-sm shadow-sm">
                    <i class="bi bi-plus-circle-fill fs-6"></i> Добавить клинику
                </a>
            </div>

            <!-- Сетка карточек клиник -->
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mx-0 overflow-hidden">
                <?php foreach ($clinics as $clinic): ?>
                    <div class="col px-2">
                        <div class="card h-100 border-0 shadow-sm rounded-3 card-clinic-hover">
                            <div class="card-body p-4 d-flex flex-column">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="icon-shape bg-primary-subtle text-primary rounded-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                        <i class="bi bi-building-fill fs-4"></i>
                                    </div>
                                    <span class="badge bg-dark rounded-pill px-3 py-1.5"><?= htmlspecialchars($clinic->city) ?></span>
                                </div>
                                <a href="/clinic/admin/view/<?= $clinic->id ?>" class="text-decoration-none text-dark">
                                    <h4><?= htmlspecialchars($clinic->name) ?></h4>
                                </a>
                                <p class="card-text text-muted small mb-4">ID клиники в системе: <span class="fw-semibold">#<?= htmlspecialchars($clinic->id) ?></span></p>
                                <div class="border-top pt-3 mt-auto d-flex justify-content-between align-items-center">
                                    <span class="text-muted small">
                                        <i class="bi bi-people-fill me-1"></i> Персонал: <strong><?= !empty($clinic->vet) ? count($clinic->vet) : 0 ?></strong> врачей

                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- ================= СОДЕРЖИМОЕ ВКЛАДКИ 2: РЕЕСТР ВЕТЕРИНАРНЫХ ВРАЧЕЙ ================= -->
        <div class="tab-pane fade" id="doctors-content" role="tabpanel" aria-labelledby="doctors-tab">

            <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center border-bottom">
                <h5 class="mb-0 fw-bold text-dark">
                    <i class="bi bi-person-heart text-primary me-2"></i>Реестр специалистов
                </h5>

                <!-- Кнопка перехода на страницу создания нового сотрудника -->
                <a href="/clinic/admin/admin/doctor/create" class="btn btn-primary d-flex align-items-center gap-2 px-3 py-2 rounded-pill fw-medium btn-sm shadow-sm">
                    <i class="bi bi-plus-circle-fill fs-6"></i> Добавить сотрудника
                </a>
            </div>

            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">

                <!-- Интерактивная таблица -->
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-uppercase fs-7 text-muted border-bottom">
                            <tr>
                                <th class="ps-4" style="width: 100px;">ID</th>
                                <th>Специалист</th>
                                <th>Специализация</th>
                                <th>Место работы / Клиника</th>
                                <th>Статус</th>
                                <th class="text-end pe-4" style="width: 120px;">Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $hasDoctors = false;

                            // Обход структуры через прямую стрелочку ->
                            foreach ($clinics as $clinic):
                                if (!empty($clinic->vet)):
                                    foreach ($clinic->vet as $vet):
                                        $hasDoctors = true;
                            ?>
                                        <tr>
                                            <!-- ID врача -->
                                            <td class="ps-4 text-muted fw-medium">#<?= htmlspecialchars($vet->id) ?></td>

                                            <!-- ФИО врача и аватар -->
                                            <td>
                                                <div class="d-flex align-items-center gap-3">
                                                    <div class="avatar bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                        <i class="bi bi-person-badge fs-5"></i>
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold text-dark"><?= htmlspecialchars($vet->name) ?></div>
                                                        <div class="text-muted small">Вет-врач</div>
                                                    </div>
                                                </div>
                                            </td>

                                            <!-- Специализация -->
                                            <td>
                                                <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle px-2.5 py-1.5 rounded">
                                                    <?= htmlspecialchars($vet->specialty) ?>
                                                </span>
                                            </td>

                                            <!-- Название клиники и город -->
                                            <td>
                                                <div class="fw-medium text-dark">
                                                    <i class="bi bi-building text-muted me-1"></i><?= htmlspecialchars($clinic->name) ?>
                                                </div>
                                                <div class="text-muted small ps-4"><?= htmlspecialchars($clinic->city) ?></div>
                                            </td>

                                            <!-- Статус на смене -->
                                            <td>
                                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1">
                                                    <span class="spinner-grow spinner-grow-sm text-success me-1" style="width: 6px; height: 6px;"></span>
                                                    Автивный
                                                </span>
                                            </td>

                                            <!-- Кнопки управления врачом (Изменить и Удалить текстом) -->
                                            <td class="text-end pe-4">
                                                <div class="d-flex justify-content-end align-items-center gap-3">

                                                    <!-- Ссылка на редактирование (обычный текст) -->
                                                    <a href="/clinic/admin/admin/doctor/edit/<?= htmlspecialchars($vet->id) ?>" class="text-primary text-decoration-none small fw-semibold" title="Редактировать профиль">
                                                        Изменить
                                                    </a>

                                                    <!-- Ссылка на удаление в виде "Удалить ✕" -->
                                                    <a href="/clinic/admin/admin/doctor/delete/<?= htmlspecialchars($vet->id) ?>" class="btn btn-sm btn-outline-danger rounded-pill px-2.5 py-0.5 fw-medium" style="font-size: 0.8rem;" title="Удалить сотрудника" onclick="return confirm('Вы уверены, что хотите удалить этого сотрудника из системы?');">
                                                        ✕
                                                    </a>

                                                </div>
                                            </td>
                                        </tr>
                                <?php
                                    endforeach;
                                endif;
                            endforeach;

                            // Заглушка, если врачей нет
                            if (!$hasDoctors): ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-5">
                                        <i class="bi bi-people fs-2 d-block mb-2 text-secondary"></i>
                                        Врачи в базе данных не найдены.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
        <!-- ================= СОДЕРЖИМОЕ ВКЛАДКИ 3: РЕЕСТР ЗАПИСЕЙ НА ПРИЕМ ================= -->
        <div class="tab-pane fade" id="appointments-content" role="tabpanel" aria-labelledby="appointments-tab">
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">

                <!-- Заголовок таблицы записей -->
                <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center border-bottom">
                    <h5 class="mb-0 fw-bold text-dark">
                        <i class="bi bi-calendar-check text-primary me-2"></i>Журнал всех записей на прием
                    </h5>
                    <span class="badge bg-warning-subtle text-warning-emphasis rounded-pill px-3 py-2">
                        Требуют внимания
                    </span>
                </div>

                <!-- Таблица -->
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-uppercase fs-7 text-muted border-bottom">
                            <tr>
                                <th class="ps-4" style="width: 120px;">ID Записи</th>
                                <th>Дата и время приёма</th>
                                <th>Пациент (Питомец)</th>
                                <th>Владелец</th>
                                <th>Врач (Ветеринар)</th>
                                <th>Клиника / Город</th>
                                <th>Статус</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $hasAppointments = false;

                            // 1. Проходим по клиникам
                            foreach ($clinics as $clinic):
                                // 2. Заходим во врачей клиники через связь vet
                                if (!empty($clinic->vet)):
                                    foreach ($clinic->vet as $vet):
                                        // 3. Заходим в массив записей конкретного врача
                                        if (!empty($vet->appointment)):
                                            foreach ($vet->appointment as $appointment):
                                                $hasAppointments = true;

                                                // Извлекаем значение scheduled_for и форматируем его
                                                if (!empty($appointment->scheduled_for)) {
                                                    $dateObj = new DateTime($appointment->scheduled_for);
                                                    $displayDate = $dateObj->format('d.m.Y');
                                                    $displayTime = $dateObj->format('H:i');
                                                } else {
                                                    $displayDate = 'Не указана';
                                                    $displayTime = '--:--';
                                                }

                                                // Точный вывод питомца из ORM
                                                $petName = !empty($appointment->pet->name) ? htmlspecialchars($appointment->pet->name) : 'Без клички';
                                                $petSpecies = !empty($appointment->pet->species) ? htmlspecialchars($appointment->pet->species) : 'Вид не указан';

                                                // Точный вывод владельца из массива owner
                                                $ownerName = !empty($appointment->pet->owner[0]->name) ? htmlspecialchars($appointment->pet->owner[0]->name) : 'Не указан';
                                                $ownerPhone = !empty($appointment->pet->owner[0]->phone) ? htmlspecialchars($appointment->pet->owner[0]->phone) : '';
                            ?>
                                                <tr>
                                                    <?php if (($appointment->status ?? '') === 'confirm'): ?>
                                                        <!-- ID самой записи на прием -->
                                                        <td class="ps-4 fw-bold text-primary">#<?= htmlspecialchars($appointment->id) ?></td>

                                                        <!-- Вывод даты и времени приема -->
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

                                                        <!-- Вывод данных о животном -->
                                                        <td>
                                                            <div class="fw-bold text-dark d-flex align-items-center gap-1">
                                                                <i class="bi bi-paw-fill text-warning"></i>
                                                                <?= $petName ?>
                                                            </div>
                                                            <div class="text-muted small"><?= $petSpecies ?></div>
                                                        </td>

                                                        <!-- Вывод данных о хозяине и его телефоне -->
                                                        <td>
                                                            <div class="fw-semibold text-dark"><?= $ownerName ?></div>
                                                            <?php if (!empty($ownerPhone)): ?>
                                                                <div class="text-muted small">
                                                                    <i class="bi bi-telephone text-secondary me-1"></i><?= htmlspecialchars($ownerPhone) ?>
                                                                </div>
                                                            <?php endif; ?>
                                                        </td>

                                                        <!-- Информация о враче -->
                                                        <td>
                                                            <div class="fw-bold text-dark"><?= htmlspecialchars($vet->name) ?></div>
                                                            <div class="text-muted small"><?= htmlspecialchars($vet->specialty) ?></div>
                                                        </td>

                                                        <!-- Клиника и город -->
                                                        <td>
                                                            <div class="fw-medium text-dark">
                                                                <i class="bi bi-hospital text-muted me-1"></i><?= htmlspecialchars($clinic->name) ?>
                                                            </div>
                                                            <div class="text-muted small ps-4"><?= htmlspecialchars($clinic->city) ?></div>
                                                        </td>

                                                        <!-- Статус записи из ORM -->
                                                        <td>
                                                            <?php if (($appointment->status ?? '') === 'confirm'): ?>
                                                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1">
                                                                    Подтверждена
                                                                </span>
                                                            <?php endif; ?>
                                                        </td>
                                                    <?php endif; ?>
                                                </tr>
                                <?php
                                            endforeach;
                                        endif;
                                    endforeach;
                                endif;
                            endforeach;

                            // Заглушка, если записей нет
                            if (!$hasAppointments): ?>
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-5">
                                        <i class="bi bi-calendar-x fs-2 d-block mb-2 text-secondary"></i>
                                        На данный момент записей на прием в системе не обнаружено.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>


</html>