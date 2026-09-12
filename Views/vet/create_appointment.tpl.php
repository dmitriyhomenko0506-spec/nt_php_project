<?php

/**
 * @var array $clinics
 * @var int $clinic_id   // ID клиники
 * @var int $vet_id   // ID врача
 */
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Запись в ветеринарную клинику</title>
    <!-- Подключение Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://unpkg.com/htmlincludejs"></script>
</head>

<body class="bg-light">

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">

                <!-- Перебираем массив клиник -->
                <?php foreach ($clinics as $clinic): ?>

                    <!-- Ссылка-возврат НАД карточкой (Вариант 1) -->
                    <div class="mb-3">
                        <a href="/clinic/admin/vet/<?= htmlspecialchars($vet_id) ?>" class="btn btn-outline-secondary btn-sm text-decoration-none">
                            <i class="bi bi-arrow-left"></i> Назад к панель управления
                        </a>
                    </div>

                    <div class="card shadow mb-4">
                        <!-- Вариант 2: Кнопка "Назад" встроена прямо в шапку формы (флексбокс распределяет заголовок и кнопку по краям) -->
                        <div class="card-header bg-success text-white py-3 d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-0 fs-5">🐾 Запись в клинику «<?= htmlspecialchars($clinic->name) ?>»</h4>
                            </div>
                        </div>

                        <div class="card-body p-4">

                            <!-- Экшн формы с отправкой на ваш административный роут создания записи -->
                            <form action="/clinic/admin/vet/appointment/create/clinic/<?= htmlspecialchars($clinic->id) ?>" method="POST" class="needs-validation" novalidate>

                                <input type="hidden" name="clinic_id" value="<?= htmlspecialchars($clinic_id) ?>">
                                <input type="hidden" name="vet_id" value="<?= htmlspecialchars($vet_id) ?>">

                                <!-- БЛОК 1: Владелец -->
                                <h5 class="mb-3 text-secondary border-bottom pb-2">1. Данные владельца</h5>

                                <div class="mb-3">
                                    <label for="ownerName" class="form-label">Ваше имя и фамилия</label>
                                    <input type="text" name="ownerName" class="form-control" id="ownerName" placeholder="Иван Иванов" required>
                                    <div class="invalid-feedback">Пожалуйста, введите ваше имя.</div>
                                </div>

                                <div class="mb-3">
                                    <label for="ownerPhone" class="form-label">Номер телефона</label>
                                    <input type="tel" name="ownerPhone" class="form-control" id="ownerPhone" placeholder="380XXXXXXXXX" pattern="^\+?[0-9]{10,14}$" required>
                                    <div class="invalid-feedback">Введите корректный номер телефона (например, 380671234567).</div>
                                </div>

                                <!-- БЛОК 2: Питомец -->
                                <h5 class="mb-3 mt-4 text-secondary border-bottom pb-2">2. Данные питомца</h5>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="petType" class="form-label">Вид животного</label>
                                        <select name="petType" class="form-select" id="petType" required>
                                            <option value="" selected disabled>Выберите...</option>
                                            <option value="Кошка">Кошка / Кот</option>
                                            <option value="Собака">Собака</option>
                                            <option value="Грызун">Грызун</option>
                                            <option value="Птица">Птица</option>
                                            <option value="Другое">Другое</option>
                                        </select>
                                        <div class="invalid-feedback">Выберите вид животного.</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="petName" class="form-label">Кличка питомца</label>
                                        <input type="text" name="petName" class="form-control" id="petName" placeholder="Барсик" required>
                                        <div class="invalid-feedback">Укажите кличку.</div>
                                    </div>
                                </div>

                                <!-- БЛОК 3: Детали визита -->
                                <h5 class="mb-3 mt-4 text-secondary border-bottom pb-2">3. Детали приема</h5>

                                <div class="mb-3">
                                    <label for="doctorSelect" class="form-label">Выберите специалиста</label>
                                    <select name="vet_id" class="form-select" id="doctorSelect" required>
                                        <option value="" disabled>Выберите врача из списка...</option>

                                        <!-- Перебираем массив клиник (берем первую из переданного массива $clinics) -->
                                        <?php $currentClinic = $clinics[0] ?? null; ?>

                                        <?php if ($currentClinic && !empty($currentClinic->vet) && is_array($currentClinic->vet)): ?>
                                            <?php foreach ($currentClinic->vet as $vet): ?>

                                                <!-- Проверяем соответствие ID врача переданному из контроллера $vet_id -->
                                                <?php if ((int)$vet->id === (int)$vet_id): ?>
                                                    <option value="<?= htmlspecialchars($vet->id) ?>" selected>
                                                        <?= htmlspecialchars($vet->name) ?> (<?= htmlspecialchars($vet->specialty) ?>)
                                                    </option>
                                                <?php endif; ?>

                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <option value="" disabled selected>Нет доступных врачей</option>
                                        <?php endif; ?>

                                    </select>
                                    <div class="invalid-feedback">Выберите специалиста из списка.</div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="visitDate" class="form-label">Дата визита</label>
                                        <input type="date" name="visitDate" class="form-control" id="visitDate" required>
                                        <div class="invalid-feedback">Выберите дату визита.</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="visitTime" class="form-label">Удобное время (с 9:00 до 19:00)</label>
                                        <!-- Выпадающий список, который гарантирует выбор ТОЛЬКО рабочего времени -->
                                        <select name="visitTime" class="form-select" id="visitTime" required>
                                            <option value="" selected disabled>--:--</option>
                                            <?php
                                            $start = new DateTime('09:00');
                                            $end   = new DateTime('19:00');
                                            while ($start <= $end) {
                                                $timeFormatted = $start->format('H:i');
                                                echo "<option value=\"{$timeFormatted}\">{$timeFormatted}</option>";
                                                $start->modify('+30 minutes'); // Шаг записи — 30 минут
                                            }
                                            ?>
                                        </select>
                                        <div class="invalid-feedback">Пожалуйста, выберите время приема.</div>
                                    </div>
                                </div>

                                <!-- Кнопка отправки -->
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-success btn-lg">Записать питомца</button>
                                </div>

                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>
        </div>
    </div>

    <!-- Скрипт для валидации Bootstrap 5 -->
    <script>
        (() => {
            'use strict'
            const forms = document.querySelectorAll('.needs-validation')
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
        })()
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>

</html>