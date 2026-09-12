<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавление новой клиники</title>
    <!-- Подключаем Bootstrap 5 и иконки Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://unpkg.com/htmlincludejs"></script>
</head>

<body class="bg-light">

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-10 col-md-8 col-lg-6">

                <!-- Кнопка возврата в панель управления -->
                <div class="mb-4">
                    <a href="/clinic/admin/admin" class="btn btn-link text-decoration-none p-0 d-inline-flex align-items-center gap-2 fw-semibold text-secondary">
                        &larr; Вернуться в админку
                    </a>
                </div>

                <!-- Главная карточка -->
                <div class="card border-0 shadow-sm rounded-3 overflow-hidden">

                    <!-- Шапка карточки -->
                    <div class="card-header bg-dark text-white p-4 border-0">
                        <h3 class="h5 mb-0 fw-bold">Регистрация нового филиала</h3>
                        <p class="text-white-50 small mb-0">Запустите новый филиал, заполнив данные ниже</p>
                    </div>

                    <!-- НАЧАЛО ФОРМЫ (Все поля строго внутри неё) -->
                    <form action="/clinic/admin/admin/create_clinic" method="POST" class="needs-validation" novalidate>

                        <!-- Тело карточки с полями -->
                        <div class="card-body p-4">

                            <h5 class="text-secondary fw-bold mb-4 small text-uppercase tracking-wider">Основная информация</h5>

                            <!-- Поле 1: Название клиники -->
                            <div class="mb-4">
                                <label for="clinic_name" class="form-label fw-bold text-dark small mb-2">Название клиники / Филиала</label>
                                <div class="has-validation">
                                    <input type="text"
                                        class="form-control bg-light py-2.5 px-3 border"
                                        id="clinic_name"
                                        name="name"
                                        placeholder="Например: Вет-Город"
                                        required>
                                    <div class="invalid-feedback">Пожалуйста, укажите название клиники.</div>
                                </div>
                            </div>

                            <!-- Поле 2: Город -->
                            <div class="mb-4">
                                <label for="clinic_city" class="form-label fw-bold text-dark small mb-2">Город расположения</label>
                                <div class="has-validation">
                                    <input type="text"
                                        class="form-control bg-light py-2.5 px-3 border"
                                        id="clinic_city"
                                        name="city"
                                        placeholder="Например: Киев"
                                        required>
                                    <div class="invalid-feedback">Пожалуйста, укажите город.</div>
                                </div>
                            </div>

                            <hr class="my-4 text-muted">
                            <h5 class="text-secondary fw-bold mb-4 small text-uppercase tracking-wider">Учетные данные (Для входа)</h5>

                            <!-- Поле 3: Email -->
                            <div class="mb-4">
                                <label for="clinic_email" class="form-label fw-bold text-dark small mb-2">Электронная почта (Email)</label>
                                <div class="has-validation">
                                    <input type="email"
                                        class="form-control bg-light py-2.5 px-3 border"
                                        id="clinic_email"
                                        name="email"
                                        placeholder="Например: vet-kiev@clinic.ua"
                                        required>
                                    <div class="invalid-feedback">Введите корректный адрес электронной почты.</div>
                                </div>
                                <div class="form-text text-muted small mt-2">Используется в качестве логина для доступа сотрудников этого филиала.</div>
                            </div>

                            <!-- Поле 4: Пароль -->
                            <div class="mb-4">
                                <label for="clinic_password" class="form-label fw-bold text-dark small mb-2">Пароль доступа</label>
                                <div class="has-validation">
                                    <input type="password"
                                        class="form-control bg-light py-2.5 px-3 border"
                                        id="clinic_password"
                                        name="password"
                                        placeholder="Минимум 6 символов"
                                        minlength="6"
                                        required>
                                    <div class="invalid-feedback">Пароль должен содержать не менее 6 символов.</div>
                                </div>
                            </div>

                        </div>

                        <!-- Подвал карточки с кнопками отправки -->
                        <div class="card-footer bg-light p-4 border-top-0 d-flex justify-content-end gap-3">
                            <a href="/clinic/admin/admin" class="btn btn-outline-secondary px-4 py-2 rounded-pill fw-medium btn-sm">Отмена</a>
                            <button type="submit" class="btn btn-primary px-4 py-2 rounded-pill fw-semibold btn-sm shadow-sm">
                                Сохранить филиал
                            </button>
                        </div>

                    </form>
                    <!-- КОНЕЦ ФОРМЫ -->

                </div>

            </div>
        </div>
    </div>

    <!-- Скрипт Bootstrap 5 для валидации полей формы на стороне клиента -->
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