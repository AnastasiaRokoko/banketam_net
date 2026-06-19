<?php
//подключение к бд
require_once "db.php";

$message="";
//проверка отправки формы регистрации
if ($_SERVER["REQUEST_METHOD"]=="POST"){
    $login=trim($_POST["login"]);
    $password=trim($_POST["password"]);
    $fio=trim($_POST["fio"]);
    $phone=trim($_POST["phone"]);
    $email=trim($_POST["email"]);

    if ($login==""||$password==""||$fio==""||$phone==""||$email==""){
        $message="Заполните все поля.";
    } elseif (!preg_match("/^[A-Za-z0-9]{6,}$/",$login)){
        //проверка логина
        $message="Логин должен содержать только латинские буквы и цифры, минимум 6 символов.";
    } elseif (strlen($password)<8){
        $message="Пароль должен быть не менее 8 символов.";
    }else{
        $check=mysqli_query($conn,"SELECT id FROM users WHERE login='$login'");

        if(mysqli_num_rows($check)>0){
            $message="Такой логин уже существует.";
        }else{
            //добавление записи в бд 
            $sql="INSERT INTO users(login,password,fio,phone,email)
            VALUES ('$login','$password','$fio','$phone','$email')";

            if(mysqli_query($conn,$sql)){
                header("Location: login.php");
                exit;
            } else{
                $message="Ошибка регистрации.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
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
                    <h1 class="h3 mb-4 text-center">Регистрация</h1>

                    <?php if ($message != ""): ?>
                        <div class="alert alert-danger">
                            <?= $message ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Логин</label>
                            <input type="text" name="login" class="form-control">
                            <div class="form-text">
                                Латинские буквы и цифры, минимум 6 символов.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Пароль</label>
                            <input type="password" name="password" class="form-control">
                            <div class="form-text">
                                Минимум 8 символов.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">ФИО</label>
                            <input type="text" name="fio" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Телефон</label>
                            <input type="text" name="phone" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control">
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            Зарегистрироваться
                        </button>
                    </form>

                    <p class="text-center mt-3 mb-0">
                        Уже зарегистрированы?
                        <a href="login.php">Войти</a>
                    </p>

                </div>
            </div>

        </div>
    </div>
</div>

<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>