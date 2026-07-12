<!DOCTYPE html>
<html lang='ru'>

<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>Admin panel</title>
    <link rel='stylesheet' href='/clinic/assert/main.css'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://unpkg.com/htmlincludejs"></script>
</head>

<body>
    <div class="container">
        <!-- Строка центрирует форму по вертикали и горизонтали -->
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-12 col-sm-8 col-md-6 col-lg-4">

                <!-- Карточка формы с тенью и скруглениями -->
                <div class="card shadow-sm border-0 p-4">
                    <div class="card-body">
                        <h3 class="card-title text-center mb-4fw-bold">Войти в аккаунт</h3>

                        <form method="post" action="/clinic/login">

                            <?php if (!empty($_SESSION['auth_error'])): ?>
                                <div class="alert alert-danger fs-7 py-2 text-center" role="alert">
                                    <?= $_SESSION['auth_error']; ?>
                                </div>
                                <?php
                                // Удаляем ошибку, чтобы она не висела при обновлении страницы F5
                                unset($_SESSION['auth_error']);
                                ?>
                            <?php endif; ?>

                            <!-- Поле Email / Логин -->
                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label text-muted fs-7">Email или логин</label>
                                <input type="email" name="email" class="form-control form-control-lg fs-6" id="exampleInputEmail1" placeholder="name@example.com" required>
                            </div>

                            <!-- Поле Пароль -->
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <label for="exampleInputPassword1" class="form-label text-muted fs-7 mb-1">Пароль</label>

                                </div>
                                <input type="password" name="password" class="form-control form-control-lg fs-6" id="exampleInputPassword1" placeholder="••••••••" required>
                            </div>

                            <!-- Кнопка отправки формы -->
                            <button type="submit" class="btn btn-primary btn-lg w-100 fs-6 shadow-sm mb-3">Войти</button>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>

</html>