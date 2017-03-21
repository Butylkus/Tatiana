<?php
session_start();

 if($_SESSION['auth'][$_COOKIE['sid']] != "authorised")
    exit('{"error": 1, "info":"Ты не авторизован! Тебе сюда нельзя!"}');
 
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

//Данная часть кода отвечает за проверку статусов.
//В зависимости от значения статуса(0 или 1) выставяется цвет кнопок

$res = array(
"dev" => array()
);

  $data = mysql_query("SELECT * FROM `pins` WHERE `direction` = 'output' ORDER BY `pin` ASC");
  
	while($row = mysql_fetch_assoc($data))
	{  
		//Если в статусе содержится число 0 генерируем красную кнопку в противном случаи зелёную
		
		if($row['status'] == 0)
	    { 
	
		   array_push($res['dev'], array(
		   "pinNum"      => $row['pin'],
		   "status"      => 0,
		   "deviceName"  => $row['name']
		   ));
		 
		}
		else
		{
			
		   array_push($res['dev'], array(
		   "pinNum"      => $row['pin'],
		   "status"      => 1,
		   "deviceName"  => $row['name']
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
   	
//Проверяем, существует ли пин, если нет - генерируем ошибку
    $totalPins = mysql_query("SELECT `status` FROM `pins` WHERE `pin` = '{$pin_num}'");
	
    if(mysql_num_rows($totalPins) < 1) 
	exit('{"error" : 1, "info" : "Пин не существует"}');

//Если пин существует, обновляем статус
	
    if(mysql_query("UPDATE `pins` SET `status` = '{$status}' WHERE `pin` = '{$pin_num}'"))
		
	    print '{"error" : 0, "pin" : '.$pin_num.', "status" : '.$status.'}';
	
   else print '{"error" : 1, "info" : "Статус не был обновлён! Ошибка: '.mysql_error().'"}';
	
//Если статус успешно обновился, пишем это событие в лог

	 @writelog($pin_num);
	 
break;
		
//Планировщик
		
case'del_plan_item':

    if(!ctype_digit($_POST['unique_id']))
	  exit('{"error" : 1, "info" : "Задание не может быть удалено передан не корректный id"}');
	  else $uniqueId = $_POST['unique_id'];

	
	if(!mysql_query("DELETE FROM `plan` WHERE `id` = '{$uniqueId}'"))
	   exit('{"error" : 1, "info" : "'.mysql_error().'"}');	
	 
	if($total_del = mysql_affected_rows() > 0)
	print '{"error" : 0, "info" : "Удалено записей: '.$total_del.'"}';
 
break;

case'add_plan_item':

//Если pin устройства не является числом прерываем работу скрипта

     if(!ctype_digit($_POST['dev']))
	    exit('{"error" : 1, "info" : "Ты что мне впариваешь витаминый друг??"}'); 
        else $devSelect = $_POST['dev'];
  

//Проверяем время включения и выключения на соответствие формату 
  
	 if(stripos($_POST['timeOn'], ':') === false){
		exit('{"error" : 1, "timeOn" : "'.$_POST['timeOn'].'", "info" : "Ошибка во времени включения"}');
	   }
	   
        else
		{ 
	         $timeOn = explode(':',$_POST['timeOn']);
		     if($timeOn[0] <= 23 && $timeOn[1] <= 59 && $timeOn[2] <= 59)
				 
			   $on = $timeOn[0].':'.$timeOn[1].':'.$timeOn[2];
		   
		     else exit('{"error" : 1, "info" : "Некорректный формат времени"}');
	    }


	   
	 if(stripos($_POST['timeOff'], ':') === false){
		exit('{"error" : 1, "timeOff" : "'.$_POST['timeOff'].'", "info" : "Ошибка во времени отключения"}');
	   }
	   
	    else
		{ 
		     $timeOff = explode(':',$_POST['timeOff']);
		     if($timeOff[0] <= 23 && $timeOff[1] <= 59 && $timeOff[2] <= 59)
				 
			   $off = $timeOff[0].':'.$timeOff[1].':'.$timeOff[2];
			   
		     else exit('{"error" : 1, "info" : "Некорректный формат времени"}');
		}

/////

//Проверяем дни недели
//Режим 1 - Пн-Пт, режим 2 - Сб-Вс, режим 3 - Пн-Вс)
	 
	 if(ctype_digit($_POST['cal']))
	 {
	       if($_POST['cal'] < 1 || $_POST['cal'] > 3)
		   exit('{"error" : 1, "info" : "Дни недели заданы некорректно"}'); 
	       else $calendar = $_POST['cal'];
	 }
	 
	 else 
	 { 
         exit('{"error" : 1, "info" : "Здесь не лохи сидят!"}');
	 }

	
	
	 if(!mysql_query("INSERT INTO `plan` (pin, ontime, offtime, calendar) VALUES ('$devSelect','$on','$off','$calendar')"))
		exit('{"error" : 1, "info" : "'.mysql_error().'"}');


	
	 if($total_into = mysql_affected_rows() > 0)
	 print '{"error" : 0, "info" : "Добавлено записей: '.$total_into.'","lastId" : "'.mysql_insert_id().'"}';
 
break;



case'time_server':

  $time = time();

  $dataTime = array(
  "hour"     => date('H',$time),
  "minutes"  => date('i',$time),
  "seconds"   => date('s',$time)
  );
  
  print json_encode($dataTime);
   
break;
default:
print '{"error" : 1, "info" : "Не корректный запрос"}';
}
?>
