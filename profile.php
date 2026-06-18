<?php
session_start();
require_once "db.php";

if(!isset($_SESSION["user_id"])){
    header("Location: login.php");
    exit;
}

$user_id=$_SESSION["user_id"];
$fio=$_SESSION["fio"];
$message="";

if($_SERVER["REQUEST_METHOD"]=="POST"){
    $order_id=$_POST["order_id"];
    $text=trim($_POST["text"]);

    if($text==""){
        $message="Введите текст отзыва.";
    }else{
        $check=mysqli_query($conn,"SELECT id FROM reviews WHERE order_id='$order_id'");

        if(mysqli_num_rows($check)>0){
            $message="Вы уже оставили отзыв по этой заявке.";
        }else{
            $sql="INSERT INTO reviews (user_id,order_id,text)
            VALUES ('$user_id','$order_id','$text')";

            if(mysqli_query($conn,$sql)){
                $message="Отзыв успешно добавлен.";
            }else{
                $message="Ошибка добавления отзыва.";
            }
        }
    }
}

$sql="SELECT
orders.id,
orders.banket_date,
orders.payment_method,
orders.status,
rooms.name,
rooms.type,
reviews.text AS review_text FROM orders
INNER JOIN rooms ON orders.room_id=rooms.id
LEFT JOIN reviews ON reviews.order_id=orders.id
WHERE orders.user_id='$user_id'
ORDER BY orders.id DESC";

$result=mysqli_query($conn,$sql);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Личный кабинет</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Личный кабинет</h1>
            <p class="mb-0">Здравствуйте, <?= $fio ?>!</p>
        </div>

        <a href="create_order.php" class="btn btn-primary">
            Создать заявку
        </a>
    </div>

    <?php if ($message != ""): ?>
        <div class="alert alert-success">
            <?= $message ?>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body p-4">

            <h2 class="h4 mb-3">История заявок</h2>

            <?php if (mysqli_num_rows($result) > 0): ?>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <tr>
                            <th>№</th>
                            <th>Помещение</th>
                            <th>Тип</th>
                            <th>Дата банкета</th>
                            <th>Способ оплаты</th>
                            <th>Статус</th>
                            <th>Отзыв</th>
                        </tr>

                        <?php while ($order = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?= $order["id"] ?></td>
                                <td><?= $order["name"] ?></td>
                                <td><?= $order["type"] ?></td>
                                <td><?= $order["banket_date"] ?></td>
                                <td><?= $order["payment_method"] ?></td>
                                <td><?= $order["status"] ?></td>
                                <td>
                                    <?php if ($order["review_text"] != ""): ?>

                                        <?= $order["review_text"] ?>

                                    <?php elseif ($order["status"] == "Банкет завершен"): ?>

                                        <form method="POST">
                                            <input type="hidden" name="order_id" value="<?= $order["id"] ?>">
                                            <textarea name="text" class="form-control mb-2" placeholder="Оставьте отзыв"></textarea>
                                            <button type="submit" class="btn btn-success btn-sm">
                                                Отправить отзыв
                                            </button>
                                        </form>

                                    <?php else: ?>

                                        <span class="text-muted">
                                            Отзыв можно оставить после завершения банкета.
                                        </span>

                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </table>
                </div>

            <?php else: ?>

                <p class="mb-0">У вас пока нет заявок.</p>

            <?php endif; ?>

        </div>
    </div>

</div>

<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>