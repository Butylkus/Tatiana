<?php
session_start();

 if($_SESSION['auth'][$_COOKIE['sid']] != "authorised")
    exit('{"error": 1, "info":"Ты не авторизован! Тебе сюда нельзя!"}');
 
include_once 'sys/settings.php';
include_once 'sys/function.php';






?> 
