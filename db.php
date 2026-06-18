<?php
$host = "mysql-8.0";
$user="root";
$password="";
$database="banketam_net";

$conn=mysqli_connect($host,$user,$password,$database);

if(!$conn){
    die("Ошибка подключения: ".mysqli_connect_error());
}