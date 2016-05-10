<?php
//Здесь указываем папку в которой расположены статус-файлы
$path_file_status = '/home/pi/.tatiana/status/';
$path_file_log = '/home/pi/.tatiana/commonlog.txt';

switch($_POST['act'])
{
case'log_query':
    $echer=file_get_contents($path_file_log);
    $echer = str_replace(" ", "&nbsp;", $echer);
    $echer = str_replace("%WEBON%", "<img src='style/img/webon.png' class='logpicture'>", $echer);
    $echer = str_replace("%WEBOFF%", "<img src='style/img/weboff.png' class='logpicture'>", $echer);
    $echer = str_replace("%PLANON%", "<img src='/style/img/planon.png' class='logpicture'>", $echer);
    $echer = str_replace("%PLANOFF%", "<img src='/style/img/planoff.png' class='logpicture'>", $echer);
    $echer = str_replace("%BUTTONOFF%", "<img src='/style/img/butoff.png' class='logpicture'>", $echer);
    $echer = str_replace("%BUTTONON%", "<img src='/style/img/buton.png' class='logpicture'>", $echer);
    $echer = str_replace("%UP%", "<img src='/style/img/up.png' class='logpicture'>", $echer);
    $echer = str_replace("%DOWN%", "<img src='/style/img/down.png' class='logpicture'>", $echer);
    $echer=explode("\n",$echer);
    $countermax = count($echer);
    $countermin = $countermax - 30;
    while ($countermin <= $countermax){
        echo $echer[$countermax] . "<br/>\n";
        $countermax--;
}
break;
case "status_check":
//список устройств(пинов). ОБЯЗАТЕЛЬНО ЗАДЕЙСТВУЙТЕ УСТРОЙСТВА В ОСНОВНОМ СКРИПТЕ TATIANA.PY!!!
$pin_name[17] = 'Прихожая';
$pin_name[27] = 'Коридор';
$pin_name[18] = 'Спальня';
$pin_name[22] = 'Зал';

//Данная часть код отвечает за проверку статусов.
//В зависимости от содержимого файла(0 или 1) выставяется цвет кнопок

function del($var)
{
if($var != '..' && $var != '.' && $var != '.htaccess')
return $var;
}

	//Сканируем папку в которой расположены статус-файлы получаем массив из имён файлов
	$dir = scandir($path_file_status);
	//Вычищаем из массива всякий хлам
	$dir = array_filter($dir,'del');
	/* print_r($dir); */
	//Проходим циклом по массиву который нам возвращает функция scandir
	foreach($dir as $pins)
	{   
	    $pn = str_replace('.txt','',$pins);
		//Удаляем раcширение .txt из имён файлов для того, чтобы был только номер пина
	    $p = file_get_contents($path_file_status.$pins);
		//Считываем содержмое файла для того, что бы корректно задать кнопкам цвета согласно статусу
		//Если в файле содержится число 0 отправляем в браузер кнопку с красным цветом в противном случаи зелёную
		if($pn == 17 || $pn == 18 || $pn == 27 || $pn == 22)
		{
		if($p == 0)
	    {
                       
/* ЗДЕСЬ ДОЛЖЕН БЫТЬ ВЫЗОВ ФУНКЦИИ
/* $pin_name[$pn] - ИМЯ устройства
/* $pn - НОМЕР пина
/* ++++ ЗАПИСЬ В ЛОГФЙАЛ
/*
/*
*/                      
        print '<button class="action" id="pin_'.$pn.'" data-num-pin="'.$pn.'" data-status="0" style="background-color:rgba(0,255,0,0.1)">'.$pin_name[$pn] . "</button><br/>";
		}
		else
		{
		print '<button class="action" id="pin_'.$pn.'" data-num-pin="'.$pn.'" data-status="1" style="background-color:rgba(255,0,0,0.1)">'.$pin_name[$pn] . " </button><br/>";	
		}
	}
	}
break;
case'switch_button':	
//Этот код отвечает за статусы
$pin_num = $_POST['pin']; //в этой переменной находятся данные из атрибута data-num-pin
$status  = $_POST['power_status']; // здесь находятся данные из атрибута data-status

//Это условие инвертирует значение статуса
//Если включено значить пишем в файл 0 если выключено то пишем 1
if($status == 1)
{$status = 0;}
else{$status = 1;}

if($status == 0 || $status == 1){
    if(!file_exists($path_file_status.$pin_num)){
        exit('{"error":"1,"info":"Пин не существует"}');
        }
//Открываем файл и пишем в него циферки если файл не существует функция fopen попытается его создать
    $fp = fopen($path_file_status.$pin_num,"w+");
    //flock($fp);
//записываем данные в файл если по какой либо причине запись не удалась генерируем ошибку
    if(fwrite($fp,$status) === false){
        print '{"error":1,"info":"Ошибка записи в статус-файл"}';
        }
    else{
         fclose($fp);
//Если запись прошла успешно считываем содержимое статус файла и формируем json-массив.
        $fg = file_get_contents($path_file_status.$pin_num);
        //print '{"error":"0","pin":"'.$pin_num.'","filedata":"'.$fg.'"}';
  //pin - имя статус файла он же номер пина
  //filedata - содержимое статус-файла. Позвояет проверить был ли изменён статус. Информация для отладки.
    $stfile = fopen($path_file_log,"a");
    $moment = date('d.m.Y H:i:s');
    if ($fg == 1){
        fwrite($stfile,"%WEBOFF%      $pin_num > $moment\n");
		print '{"error":"0","pin":"'.$pin_num.'","filedata":"'.$fg.'","log":"%WEBOFF% '.$pin_num.'>'.$moment.'"}';
    }else{
        fwrite($stfile,"%WEBON%      $pin_num > $moment\n");
		print '{"error":"0","pin":"'.$pin_num.'","filedata":"'.$fg.'","log":"%WEBON%% '.$pin_num.'>'.$moment.'"}';
    }
    fclose($stfile);
        }
    }
else
{
   exit('{"error":"1,"info":"Ехай на хуй отсюда!"}');
}
	
break;
default:
print 'Не корректный запрос';
}
//После всех манипуляций файл нужно закрыть иначе будут проблемы:)
?>