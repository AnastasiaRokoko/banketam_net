<?php
//подключение к файлу где бд
require_once "db.php";
$message="";
//подкл к серверу - создание переменных под поля users
if($_SERVER["REQUEST_METHOD"]=="POST"){
    $login=trim($_POST['login']);
    $password=trim($_POST['password']);
    $fio=trim($_POST['fio']);
    $phone=trim($_POST['phone']);
    $email=trim($_POST['email']);
    //проверка на заполненость всех полей
    if($login==""||$password==""||$fio==""||$phone==""||$email==""){
        $message="Заполните все поля.";
    }
    //проверка логина: должен содержать только латинские буквы и цифры, минимум 6 символов
    if (!preg_match("/^[A-Za-z0-9]{6,}$/",$login)){
        $message="Логин должен содержать только латинские буквы и цифры, минимум 6 символов.";
    }elseif
    //проверка пароля: минимум 8 символов
    (strlen($password)<8){
        $message="Пароль должен быть не менее 8 символов.";
    }else{
        //запрос к полю логин в табл users
        $check=mysqli_query($conn,"SELECT id FROM users WHERE login='$login'");
        //проверка на повтор логина
        if (mysqli_num_rows($check)>0){
            $message="Такой логин уже есть в системе.";
        }else{
            //добавить записи в табл users (создать запрос)
            $sql="INSERT INTO users(login,password,fio,phone,email) VALUES('$login','$password','$fio','$phone','$email')";
        //переход на новую стр при успешной регистрации
             if (mysqli_query($conn,$sql)){
            header("Location: login.php");
            exit;
        }else{
            $message="Ошибка регистрации";
        }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Регистрация</title>
</head>
<body>
    <h1>Регистрация</h1>
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

        <p>
        <label>ФИО</label><br>
        <input type="text" name="fio">
        </p>

        <p>
        <label>Телефон</label><br>
        <input type="text" name="phone">
        </p>

        <p>
        <label>Email</label><br>
        <input type="text" name="email">
        </p>
        <button type="submit">Зарегистрироваться</button>
    </form>
    <p>Уже зарегистрированы?<a href="login.php">Войти</p>
</body>
</html>