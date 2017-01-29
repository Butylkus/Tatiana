<?php

  session_start();
 
  if($_SESSION['auth'][$_COOKIE['sid']] != "authorised")
  {
      header("Location: http://".$_SERVER['HTTP_HOST']."/auth.php?logout");
      exit;
  }
  
 include_once 'sys/settings.php';
 include_once 'sys/function.php';

$userDB = mysql_query("SELECT `username` FROM `users` WHERE `user_sid` = '".session_id()."'");
$userDB = mysql_fetch_assoc($userDB);

$echoer = file_get_contents("template/template.html");



//Вижуал такой вижуал
$echoer = str_replace("%PLAN%",                  show_plan(),              $echoer);
$echoer = str_replace("%LOGOUT%",                logout(),                 $echoer);
$echoer = str_replace("%UPTIME%",                uptime(),                 $echoer);
$echoer = str_replace("%CPUTEMP%",               cpu_temp(),               $echoer);
$echoer = str_replace("%USERAUTH%",              $userDB['username'],      $echoer);
$echoer = str_replace("%LASTAUTH%",              $_COOKIE['lastTimeAuth'], $echoer);
$echoer = str_replace("%SELECTITEMPLAN%",        querySelectItem(),        $echoer);
$echoer = str_replace("%IS TATIANA.PY RUNNING%", check_tatiana(),          $echoer);
$echoer = str_replace("%WEATHER%",               show_weather(),           $echoer);


echo $echoer;
?>
