<?php
session_start();

 if($_SESSION['auth'][$_COOKIE['sid']] != "authorised"){
	 
    exit('{"error": 1, "info":"Ты не авторизован! Тебе сюда нельзя!"}');
 }
 
include_once 'sys/settings.php';
include_once 'sys/function.php';

switch($_POST['act'])
{
case'is_change_log':
    
    print '{"error" : 0, "lastTimeUpdate" : "'.date('H:i:s',filemtime(LOGFILE)).'"}';

break;
case'log_query':

   print readLog(30);
   
break;
case "status_check":

//Данная часть код отвечает за проверку статусов.
//В зависимости от значения статуса(0 или 1) выставяется цвет кнопок

$res = array(
"dev" => array()
);

  $data =  mysql_query("SELECT * FROM `pins` WHERE `direction` = 'output' ORDER BY `pin` ASC");
  
	while($row = mysql_fetch_assoc($data))
	{  
		//Если в статусе содержится число 0 генерируем красную кнопку в противном случаи зелёную
		
		if($row['status'] == 0)
	    { 
	
		   array_push($res['dev'], array(
		   "pinNum" =>$row['pin'],
		   "status" =>0,
		   "deviceName" => $row['name']
		   ));
		 
		}
		else
		{
			
		   array_push($res['dev'], array(
		   "pinNum" =>$row['pin'],
		   "status" =>1,
		   "deviceName" => $row['name']
		   ));
		 
		}
	}
print json_encode($res);
break;
case'switch_button':	
//Этот код отвечает за статусы
   $pin_num = $_POST['pin']; //Данные из атрибута data-num-pin
   $status  = $_POST['power_status']; //Данные из атрибута data-status

//Защита от тех кто считает себя умнее других:)
//Ну и походу инвертирование статуса. Так сказать совмещаем приятное с полезным:)
//Если включено значить пишем в БД 0 если выключено то 1

   switch($status)
   {
      case 1:
              $status = 0;
      break;
	
      case 0:
              $status = 1;
      break;

      default: exit('{"error" : 1, "info" : "Ехай на хуй отсюда"}');
   }
   	
//Проверяем существует ли пин если нет генерируем ошибку
    $disc = @mysql_query("SELECT `status` FROM `pins` WHERE `pin` = '{$pin_num}'");
	
    if(mysql_num_rows($disc) < 1) 
	exit('{"error" : 1, "info" : "Пин не существует"}');

//Если пин существует обновляем статус
	
    if(mysql_query("UPDATE `pins` SET `status` = '{$status}' WHERE `pin` = '{$pin_num}'"))
	 {
		 print '{"error" : 0, "pin" : '.$pin_num.', "status" : '.$status.'}';
	 }

     else 
	  {
		 print '{"error" : 1, "info" : "Статус не был обновлён! Ошибка: '.mysql_error().'"}';		 
	  }
	
//Если статус успешно обновился пишем это событие в лог

	 writelog($pin_num);
	 
break;
default:
print '{"error" : 1, "info" : "Не корректный запрос"}';
}
?>
