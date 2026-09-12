<?php

/**
 * @var array $vet      // Массив с данными доктора, пришедший из контроллера
 * @var array $clinics  // Массив объектов клиник для выпадающего списка
 * @var string $clinic_id   // ID клиники
 */
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактирование сотрудника</title>
    <!-- Подключаем Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://unpkg.com/htmlincludejs"></script>
</head>

<body class="bg-light">

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-10 col-md-8 col-lg-6">

                <!-- Ссылка возврата в личный филиал доктора -->
                <div class="mb-4">
                    <a href="/clinic/admin/vet-clinic/<?= htmlspecialchars($clinic_id) ?>" class="btn btn-link text-decoration-none p-0 d-inline-flex align-items-center gap-2 fw-semibold text-secondary">
                        &larr; Вернуться в админку
                    </a>
                </div>

                <div class="card border-0 shadow-sm rounded-3 overflow-hidden">

                    <div class="card-header bg-dark text-white p-4 border-0">
                        <h3 class="h5 mb-0 fw-bold">Редактирование специалиста</h3>
                        <p class="text-white-50 small mb-0">Измените анкетные данные ветеринарного врача в системе</p>
                    </div>

                    <!-- НАЧАЛО ФОРМЫ -->
                    <form action="/clinic/admin/doctor/update" method="POST" class="needs-validation" novalidate>
                        <div class="card-body p-4">

                            <!-- Скрытое поле с ID доктора -->
                            <input type="hidden" name="id" value="<?= htmlspecialchars($vet['id']) ?>">
                            <input type="hidden" name="clinic_id" value="<?= htmlspecialchars($clinic_id) ?>">

                            <h5 class="text-secondary fw-bold mb-4 small text-uppercase tracking-wider">Личные данные врача</h5>

                            <!-- Поле 1: ФИО Врача -->
                            <div class="mb-4">
                                <label for="doctor_name" class="form-label fw-bold text-dark small mb-2">ФИО Специалиста</label>
                                <div class="has-validation">
                                    <input type="text" class="form-control bg-light py-2.5 px-3 border" id="doctor_name" name="name" value="<?= htmlspecialchars($vet['name']) ?>" required>
                                    <div class="invalid-feedback">Пожалуйста, укажите имя и фамилию врача.</div>
                                </div>
                            </div>

                            <!-- Поле 2: Специализация -->
                            <div class="mb-4">
                                <label for="doctor_specialty" class="form-label fw-bold text-dark small mb-2">Специализация</label>
                                <div class="has-validation">
                                    <input type="text" class="form-control bg-light py-2.5 px-3 border" id="doctor_specialty" name="specialty" value="<?= htmlspecialchars($vet['specialty']) ?>" required>
                                    <div class="invalid-feedback">Пожалуйста, укажите специализацию врача.</div>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label for="doctor_clinic" class="form-label fw-bold text-dark small mb-2">Привязка к филиалу / Клинике</label>
                                <div class="has-validation">


                                    <select class="form-select bg-light py-2.5 px-3 border" id="doctor_clinic" disabled>
                                        <?php foreach ($clinics as $clinic): ?>

                                            <option value="<?= htmlspecialchars($clinic->id) ?>" selected>
                                                <?= htmlspecialchars($clinic->name) ?> (<?= htmlspecialchars($clinic->city) ?>)
                                            </option>

                                        <?php endforeach; ?>
                                    </select>

                                    <div class="invalid-feedback">Пожалуйста, выберите клинику для этого врача.</div>
                                </div>
                            </div>

                            <hr class="my-4 text-muted">
                            <h5 class="text-secondary fw-bold mb-4 small text-uppercase tracking-wider">Учетные данные врача (Для входа)</h5>

                            <!-- Поле 4: Email -->
                            <div class="mb-4">
                                <label for="doctor_email" class="form-label fw-bold text-dark small mb-2">Электронная почта (Email)</label>
                                <div class="has-validation">
                                    <input type="email" class="form-control bg-light py-2.5 px-3 border" id="doctor_email" name="email" value="<?= htmlspecialchars($vet['email']) ?>" required>
                                    <div class="invalid-feedback">Введите корректный адрес электронной почты.</div>
                                </div>
                            </div>

                            <!-- Поле 5: Пароль -->
                            <div class="mb-4">
                                <label for="doctor_password" class="form-label fw-bold text-dark small mb-2">Новый пароль доступа</label>
                                <div class="has-validation">
                                    <input type="password" class="form-control bg-light py-2.5 px-3 border" id="doctor_password" name="password" placeholder="Оставьте пустым, если не хотите менять" minlength="6">
                                    <div class="invalid-feedback">Пароль должен содержать не менее 6 символов.</div>
                                </div>
                            </div>

                        </div>

                        <!-- Подвал карточки -->
                        <div class="card-footer bg-light p-4 border-top-0 d-flex justify-content-end gap-3">
                            <a href="/clinic/admin/vet-clinic/<?= htmlspecialchars($clinic_id) ?>" class="btn btn-outline-secondary px-4 py-2 rounded-pill fw-medium btn-sm">Отмена</a>
                            <button type="submit" class="btn btn-dark px-4 py-2 rounded-pill fw-semibold btn-sm shadow-sm">
                                Сохранить изменения
                            </button>
                        </div>
                    </form>
                    <!-- КОНЕЦ ФОРМЫ -->

                </div>
            </div>
        </div>
    </div>

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