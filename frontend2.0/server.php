<?php
include_once 'sys/settings.php';
include_once 'sys/function.php';

switch($_POST['act'])
{
case'is_change_log':

    print date('H:i:s',filemtime(LOGFILE));

break;
case'log_query':

   print readLog(30);
   
break;
case "status_check":

//Данная часть код отвечает за проверку статусов.
//В зависимости от содержимого файла(0 или 1) выставяется цвет кнопок

$res = array(
"dev" => array()
);

	//Проходим циклом по массиву который находится в файле settings.php
	
	foreach($pin_name as $key => $devName)
	{  
	
	    //Считываем содержмое файла для того, что бы корректно задать кнопкам цвета согласно статусу
		
	    $p = file_get_contents(STATUSES.$key);
		
		//Если в файле содержится число 0 генерируем красную кнопку в противном случаи зелёную
		
		if($p == 0)
	    { 
	
		   array_push($res['dev'], array(
		   "pinNum" =>$key,
		   "status" =>0,
		   "deviceName" => $devName
		   ));
		 
		}
		else
		{
			
		   array_push($res['dev'], array(
		   "pinNum" =>$key,
		   "status" =>1,
		   "deviceName" => $devName
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
//Если включено значить пишем в файл 0 если выключено то пишем 1

   switch($status)
   {
    case 1:
            $status = 0;
    break;
	
    case 0:
            $status = 1;
    break;

   default: print '{"error":1,"info":"Ехай на хуй отсюда"}';
}

//Проверяем существует ли файл(пин) если нет генерируем ошибку
//А то вдруг кому нибудь захочется создать зоопарк из файлов:)

    if(!file_exists(STATUSES.$pin_num))
	{
      exit('{"error":"1,"info":"Пин не существует"}');
    }

//Открываем файл и пишем в него циферки	

	  $fp = fopen(STATUSES.$pin_num,"w+");
	  
//Если по какой либо причине запись не удалась генерируем ошибку

    if(fwrite($fp,$status) === false)
	 {
        exit('{"error":1,"info":"Ошибка записи в статус-файл"}');
     }
//Если запись в статус файл прошла успешно пишем это событие в лог
//Для корректной работы кнопок ответ функции writelog необходимо выводить

	 print writelog($pin_num);

//После всех манипуляций файл нужно закрыть иначе будут проблемы:)
fclose($fp);
break;
default:
print '{"error":1,"info":"Не корректный запрос"}';
}
?>