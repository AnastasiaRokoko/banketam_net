<?php
session_start();
//подключение к файлу где бд
require_once "db.php";
$message="";
//подкл к серверу - создание переменных под поля users
if($_SERVER["REQUEST_METHOD"]=="POST"){
    $login=trim($_POST['login']);
    $password=trim($_POST['password']);
    
    //проверка на заполненость всех полей
    if($login==""||$password==""){
        $message="Заполните все поля.";
    }else{
        //создание sql-запроса
        $sql="SELECT*FROM users WHERE login='$login' AND password='$password'";
        //отправка sql-запроса на сервер
        $result=mysqli_query($conn,$sql);
        //проверка, существует ли уже такой пользователь
        if (mysqli_num_rows($result)==1){
            //превращаем заполненные строки в массив
            $user=mysqli_fetch_assoc($result);

            $_SESSION["user_id"]=$user["id"];
            $_SESSION["fio"]=$user["fio"];

            header("Location: profile.php");
            exit;
        }else{
            $message="Неправильный логин и пароль.";
        }
    }
}
    
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Авторизация</title>
</head>
<body>
    <h1>Авторизация</h1>
    <?php
    if ($message!=""):?>
        <p style="color:red;"><?=$message?></p>
    <?php endif;?>

    <form method="POST">
        <p>
        <label>Логин</label><br>
        <input type="text" name="login">
        </p>

        <p>
        <label>Пароль</label><br>
        <input type="password" name="password">
        </p>

       
        <button type="submit">Войти</button>
    </form>
    <p>Еще не зарегистрированы?<a href="register.php">Зарегистрироваться</a></p>
</body>
</html>