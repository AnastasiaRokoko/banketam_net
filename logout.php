<?php
session_start();

// Очистка всех данных сессии
session_unset();

// Полное уничтожение сессии
session_destroy();

// Переход на страницу авторизации
header("Location: login.php");
exit;