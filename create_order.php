<?php
session_start();
require_once "db.php";

if(!isset($_SESSION["user_id"])){
    header("Location: login.php");
    exit;
}

$message="";
$user_id=$_SESSION["user_id"];

$rooms=mysqli_query($conn,"SELECT*FROM rooms");

if($_SERVER["REQUEST_METHOD"]=="POST") {
    $room_id=$_POST["room_id"];
    $banket_date=$_POST["banket_date"];
    $payment_method=$_POST["payment_method"];

    if($room_id==""||$banket_date==""||$payment_method==""){
        $message="Заполните все поля.";
    }else {
        $sql="INSERT INTO orders (user_id,room_id,banket_date,payment_method,status)
        VALUES ('$user_id','$room_id','$banket_date','$payment_method','Новая')";

        if (mysqli_query($conn,$sql)){
        $message="Заявка успешно создана.";
        }else{
            $message="Ошибка создания заявки: ".mysqli_error($conn);
    }
}
    }
    ?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Создание заявки</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-7 col-lg-6">

            <div class="card shadow-sm">
                <div class="card-body p-4">

                    <h1 class="h3 text-center mb-4">Создание заявки</h1>

                    <p class="text-center">
                        <a href="profile.php">Личный кабинет</a>
                    </p>

                    <?php if ($message != ""): ?>
                        <div class="alert alert-success">
                            <?= $message ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST">

                        <div class="mb-3">
                            <label class="form-label">Выберите помещение</label>
                            <select name="room_id" class="form-select">
                                <option value="">-- выберите помещение --</option>

                                <?php while ($room = mysqli_fetch_assoc($rooms)): ?>
                                    <option value="<?= $room["id"] ?>">
                                        <?= $room["name"] ?> — <?= $room["type"] ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Дата начала банкета</label>
                            <input type="date" name="banket_date" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Способ оплаты</label>
                            <select name="payment_method" class="form-select">
                                <option value="">-- выберите способ оплаты --</option>
                                <option value="Наличные">Наличные</option>
                                <option value="Банковская карта">Банковская карта</option>
                                <option value="Перевод">Перевод</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-success w-100">
                            Создать заявку
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
