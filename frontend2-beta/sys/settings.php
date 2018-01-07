<?php
//Подключение к базе данных
//Да Татьяна теперь у нас крутая!:)
$db = @mysql_connect("localhost","tatiana","tatiana") or die("Не могу подключится к серверу БД"); 
@mysql_select_db('tatiana',$db) or die("Не могу подключится к базе");
@mysql_set_charset("utf8", $db);


//Интересно, как скоро исчезнет эта строка?
define("LOGFILE","/home/pi/tatiana/commonlog.txt"); #файл общего лога работы



?> 
