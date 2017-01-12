<?php
//Запись в лог

function writeLog($pinNum)
{
   $pin = mysql_fetch_assoc( mysql_query("SELECT `status` FROM `pins` WHERE `pin` = '{$pinNum}'") );
   $stfile = fopen(LOGFILE,"a");
   $moment = date('d.m.Y H:i:s');
   
   switch($pin['status'])
   {
	   
	  case 1:
               $res = '{"statusDB",1}';
			   
	           fwrite($stfile,"%WEBON% $pinNum > $moment\n");
	  break;
	  
	  case 0:
	  	       $res = '{"statusDB",0}';
			   
	           fwrite($stfile,"%WEBOFF% $pinNum > $moment\n"); 
	  break;
	  
   }
fclose($stfile);
return $res;
}

//Вывод Лога
 
function readLog($num=1)
{
    $echer = file_get_contents(LOGFILE);
    $echer = str_replace(" ", "&nbsp;", $echer);
    $rq = mysql_query("SELECT `pin`,`name` FROM `pins` ORDER BY `pin` ASC");
	

    while ($row = mysql_fetch_assoc($rq)) {
        $searchStr  = "%&nbsp;". $row['pin'] ."&nbsp;>";
        $replaceStr = "%&nbsp;". $row['name'] ."&nbsp;</div><div class='logright'>";
        $echer = str_replace($searchStr, $replaceStr, $echer);
        $searchStr  = "%&nbsp;". $row['pin'] ."&nbsp;+";
        $replaceStr = "%&nbsp;". $row['name'] . "&nbsp;&#10040;";
        $echer = str_replace($searchStr, $replaceStr, $echer);
        $searchStr  = "&nbsp;". $row['pin'] ."&nbsp;>";
        $replaceStr = "&nbsp;". $row['name'] . "&nbsp;</div><div class='logright'>";
        $echer = str_replace($searchStr, $replaceStr, $echer);
    }
	   

    $echer = str_replace("%WEBON%", "<div class='logleft'><img src='images/logwebon.png' class='logpicture' title='Включено через веб-интерфейс'>", $echer);
    $echer = str_replace("%WEBOFF%", "<div class='logleft'><img src='images/logweboff.png' class='logpicture' title='Выключено через веб-интерфейс'>", $echer);
    $echer = str_replace("%PLANON%", "<div class='logleft'><img src='images/logplanon.png' class='logpicture' title='Запланированное включение'>", $echer);
    $echer = str_replace("%PLANOFF%", "<div class='logleft'><img src='images/logplanoff.png' class='logpicture' title='Запланированное выключение'>", $echer);
    $echer = str_replace("%BUTTONOFF%", "<div class='logleft'><img src='images/logbutoff.png' class='logpicture' title='Выключено нажатием на кнопку'>", $echer);
    $echer = str_replace("%BUTTONON%", "<div class='logleft'><img src='images/logbuton.png' class='logpicture' title='Включено нажатием на кнопку'>", $echer);
    $echer = str_replace("%UP%&nbsp;>", "<div class='logleft'><img src='images/logsysup.png' class='logpicture' title='Я проснулась! =)'> За работу!</div><div class='logright'>", $echer);
    $echer = str_replace("%DOWN%&nbsp;>", "<div class='logleft'><img src='images/logsysdown.png' class='logpicture' title='Я заснула...'> Татьяна отдыхает</div><div class='logright'>", $echer);
    $echer = str_replace("%IRON%", "<div class='logleft'><img src='images/logiron.png' class='logpicture' title='Включено пультом ДУ'>", $echer);
    $echer = str_replace("%IROFF%", "<div class='logleft'><img src='images/logiroff.png' class='logpicture' title='Выключено пультом ДУ'>", $echer);
    $echer = str_replace("\n", "</div>\n", $echer);
    
	$echer = explode("\n",$echer);
	$echer = array_reverse($echer);
	$total = count($echer)-1;
    
    if($num > $total) $num = $total;
	if($num <= 1) return '<div class="logger">'.$echer[1].'</div>';
	
	$res = '';
	
    for($i = 1; $i <= $num; $i++)
	   {
		  $res.= '<div class="logger">'.$echer[$i].'</div>';
	   }
	   
       return $res;	   
}


//Модный генератор ссылки выхода. Ибо требуется для шаблонизации.

function logout(){
    $logout = "http://".$_SERVER['HTTP_HOST']."/auth.php?logout";
    return $logout;
}


//Аптайм ВСЕЙ системы
function uptime(){
    return exec("uptime -s");
}


//Возвращает статус главного скрипта - есть ли в памяти.
//TODO: при демонизации переписать на вывод баша или опрос через инит.
//И вообще: изучить скелеты инита, ридми и воскурить баш как следует, ибо ЪТатьяна==Ъдемон и всё будет решаться через инит. Движение к неубиваемому процессу началось.

function check_tatiana(){
    exec('ps ax | grep tatiana',$mainpid);
    if (stristr($mainpid[0],"tatiana.py")){
        return "<span class='green'>трудится</span>";
    }else{
        return "<span class='red'>УПАЛА! =(</span>";
    }
}


//Возврат температуры ЦП  

function cpu_temp(){
    $string = exec('cat /sys/class/thermal/thermal_zone0/temp');
    if ($string<45000){
        return "<span class='blue'>прохладно (". round($string/1000, 1) ."&deg;C)</span>";
    }elseif ($string>55000){
        return "<span class='red'>ЖАРКО! (". round($string/1000, 1) ."&deg;C)</span>";
    }else{
        return "<span class='green'>комфортно (". round($string/1000, 1) ."&deg;C)</span>";
    }
}


//Показывает план из базы с готовыми кнопками удаления.
function show_plan(){
    $plan = array();
    $query = mysql_query("SELECT * FROM `plan`");
    while ($string = mysql_fetch_assoc($query)) {
        array_push($plan, array(
           "id"       => $string['id'],
           "pin"      => $string['pin'],
           "ontime"   => $string['ontime'],
           "offtime"  => $string['offtime'],
           "calendar" => $string['calendar']
        ));

    }
    
    $string = "<div class=\"planblock\" id=\"plan\">\n";
    foreach ($plan as $row){
        if ($row['calendar'] == 1){$row['calendar'] = "Пн-Пт";}
        elseif ($row['calendar'] == 2){$row['calendar'] = "Сб-Вс";}
        else {$row['calendar'] = "Пн-Вс";}
        $string = $string . 
            "<div class='planrow' id ='".$row['id'] . "'>\n" .
                "<div class='pinname'>" . pin_to_name($row['pin']) . "</div>\n" . 
                "<div class='ontime'>" . $row['ontime'] . "</div>\n" . 
                "<div class='offtime'>" . $row['offtime'] . "</div>\n" . 
                "<div class='calendar'>" . $row['calendar'] . "</div>\n" . 
                "</div>\n";
    }
    $string = $string . "</div>\n";

    
    
return $string;
}



//Самая нужная в мире функция, странно, что последняя в списке.
//Прнимает номер пина, возвращает его название.
function pin_to_name($pin){
    $query = mysql_query("SELECT `name` FROM `pins` WHERE `pin`='".$pin."'");
    $pin_name = mysql_fetch_assoc($query);
    return $pin_name['name'];
}


?>
