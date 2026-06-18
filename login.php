<?php
session_start();
require_once "db.php";

$message="";

if($_SERVER["REQUEST_METHOD"]=="POST"){
    $login=trim($_POST["login"]);
    $password=trim($_POST["password"]);

    if($login==""||$password==""){
        $message="Все поля должны быть заполнены";
    }else{
        $sql="SELECT*FROM users WHERE login='$login' AND password='$password'";

        $result=mysqli_query($conn,$sql);

        if(mysqli_num_rows($result)==1){
            $user=mysqli_fetch_assoc($result);

            $_SESSION["user_id"]=$user["id"];
            $_SESSION["fio"]=$user["fio"];

            header("Location: profile.php");
            exit;
        }else{
            $message="Неверный логин или пароль";
        }
    }

    
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Авторизация</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-6 col-lg-5">

            <div class="card shadow-sm">
                <div class="card-body p-4">

                    <h1 class="h3 text-center mb-4">Авторизация</h1>

                    <?php if ($message!=""):?>
                        <div class="alert alert-danger"><?=$message?></div>
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

                        <button type="submit" class="btn btn-primary w-100">Войти</button>
                    </form>

                    <p class="text-center mt-3 mb-0">
                        Еще не зарегистрированы?
                        <a href="register.php">Регистрация</a>
                    </p>

                </div>
            </div>

        </div>
    </div>
</div>

<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>