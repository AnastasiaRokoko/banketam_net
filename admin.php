<?php
session_start();
//подключение к бд
require_once "db.php";

$message = "";

if (isset($_POST["admin_login"])) {
    $login = $_POST["login"];
    $password = $_POST["password"];
    //проверка корректности логина и пароля администратора
    if ($login == "Admin26" && $password == "Demo20") {
        $_SESSION["admin"] = true;
    } else {
        $message = "Неверный логин или пароль администратора.";
    }
}

if (!isset($_SESSION["admin"])) {
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход администратора</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-6 col-lg-5">
            <div class="card shadow-sm">
                <div class="card-body p-4">
<div class="card shadow-sm">
                <div class="card-body p-4">
                     <div class="text-center mb-3">
            <img src="assets/logo.png"
                 alt="Банкетам.Нет"
                 class="logo">
        </div>
                    <h1 class="h3 text-center mb-4">Вход администратора</h1>
                    <a href="logout.php" class="btn btn-danger mb-3">
    Выйти
</a>
                    <?php if ($message != ""): ?>
                        <div class="alert alert-danger"><?= $message ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Логин</label>
                            <input type="text" name="login" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Пароль</label>
                            <input type="password" name="password" class="form-control">
                        </div>

                        <button type="submit" name="admin_login" class="btn btn-primary w-100">
                            Войти
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
    exit;
}

if (isset($_POST["change_status"])) {
    $order_id = $_POST["order_id"];
    $status = $_POST["status"];

    $sql = "UPDATE orders SET status='$status' WHERE id='$order_id'";

    if (mysqli_query($conn, $sql)) {
        $message = "Статус заявки изменён.";
    } else {
        $message = "Ошибка изменения статуса.";
    }
}

$sql = "SELECT 
            orders.id,
            orders.banket_date,
            orders.payment_method,
            orders.status,
            users.fio,
            users.phone,
            users.email,
            rooms.name,
            rooms.type
        FROM orders
        INNER JOIN users ON orders.user_id = users.id
        INNER JOIN rooms ON orders.room_id = rooms.id
        ORDER BY orders.id DESC";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Ошибка SQL: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Панель администратора</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container py-5">

    <h1 class="h3 mb-4">Панель администратора</h1>

    <?php if ($message != ""): ?>
        <div class="alert alert-success"><?= $message ?></div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body p-4">

            <h2 class="h4 mb-3">Все заявки</h2>

            <?php if (mysqli_num_rows($result) > 0): ?>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <tr>
                            <th>№</th>
                            <th>ФИО</th>
                            <th>Телефон</th>
                            <th>Email</th>
                            <th>Помещение</th>
                            <th>Тип</th>
                            <th>Дата банкета</th>
                            <th>Оплата</th>
                            <th>Статус</th>
                            <th>Изменить</th>
                        </tr>

                        <?php while ($order = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?= $order["id"] ?></td>
                                <td><?= $order["fio"] ?></td>
                                <td><?= $order["phone"] ?></td>
                                <td><?= $order["email"] ?></td>
                                <td><?= $order["name"] ?></td>
                                <td><?= $order["type"] ?></td>
                                <td><?= $order["banket_date"] ?></td>
                                <td><?= $order["payment_method"] ?></td>
                                <td><?= $order["status"] ?></td>
                                <td>
                                    <form method="POST" class="d-flex gap-2">
                                        <input type="hidden" name="order_id" value="<?= $order["id"] ?>">

                                        <select name="status" class="form-select form-select-sm">
                                            <option value="Новая">Новая</option>
                                            <option value="Банкет назначен">Банкет назначен</option>
                                            <option value="Банкет завершен">Банкет завершен</option>
                                        </select>

                                        <button type="submit" name="change_status" class="btn btn-primary btn-sm">
                                            Сохранить
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </table>
                </div>

            <?php else: ?>

                <p class="mb-0">Заявок пока нет.</p>

            <?php endif; ?>

        </div>
    </div>

</div>

<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>