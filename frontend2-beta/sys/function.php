<?php
//Запись в лог

function writeLog($pinNum)
{
   $pin = mysql_fetch_assoc( mysql_query("SELECT `status` FROM `pins` WHERE `pin` = '{$pinNum}'") );
   $stfile = fopen(LOGFILE,"a");
   $moment = date('d.m.Y H:i:s');
   if (flock($stfile, LOCK_EX)) {
   
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
       fflush($stfile);
       flock($stfile, LOCK_UN);
    }

fclose($stfile);
return $res;
}

//Вывод Лога
 
function readLog($num=1)
{
    $echer = tailCustom(LOGFILE, $lines = $num+2);
    //$echer = file_get_contents(LOGFILE);
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
    $echer = str_replace("%DHTREAD%", "<div class='logleft'><img src='images/logdht.png' class='logpicture' title='Опрошен датчик температуры+влажности'>", $echer);
    $echer = str_replace("%PIRALARM%", "<div class='logleft'><img src='images/logalarm.png' class='logpicture' title='СРАБОТАЛ ДАТЧИК ДВИЖЕНИЯ!'>", $echer);
    $echer = str_replace("\n", "</div>\n", $echer);
    
	$echer = explode("\n",$echer);
	$echer = array_reverse($echer);
	$total = count($echer)-1;
    
    if($num > $total) $num = $total;
	if($num <= 1) return '<div class="logger">'.$echer[1].'</div>';
	
	$res = '';
	
    for($i = 0; $i <= $num; $i++)
	   {
		  $res.= '<div class="logger">'.$echer[$i].'</div>';
	   }
	   
       return $res;	   
}


	 /*ЭТО ЧЕСТНО УКРАДЕНО У СЛЕДУЮЩИХ РЕБЯТ.
	 * Оно нужно для быстрого чтения больших логов.
	 * Slightly modified version of http://www.geekality.net/2011/05/28/php-tail-tackling-large-files/
	 * @author Torleif Berger, Lorenzo Stanco
	 * @link http://stackoverflow.com/a/15025877/995958
	 * @license http://creativecommons.org/licenses/by/3.0/
	 */
	function tailCustom($filepath, $lines = 1, $adaptive = true) {
		// Open file
		$f = @fopen($filepath, "rb");
		if ($f === false) return false;
		// Sets buffer size, according to the number of lines to retrieve.
		// This gives a performance boost when reading a few lines from the file.
		if (!$adaptive) $buffer = 4096;
		else $buffer = ($lines < 2 ? 64 : ($lines < 10 ? 512 : 4096));
		// Jump to last character
		fseek($f, -1, SEEK_END);
		// Read it and adjust line number if necessary
		// (Otherwise the result would be wrong if file doesn't end with a blank line)
		if (fread($f, 1) != "\n") $lines -= 1;
		
		// Start reading
		$output = '';
		$chunk = '';
		// While we would like more
		while (ftell($f) > 0 && $lines >= 0) {
			// Figure out how far back we should jump
			$seek = min(ftell($f), $buffer);
			// Do the jump (backwards, relative to where we are)
			fseek($f, -$seek, SEEK_CUR);
			// Read a chunk and prepend it to our output
			$output = ($chunk = fread($f, $seek)) . $output;
			// Jump back to where we started reading
			fseek($f, -mb_strlen($chunk, '8bit'), SEEK_CUR);
			// Decrease our line counter
			$lines -= substr_count($chunk, "\n");
		}
		// While we have too many lines
		// (Because of buffer size we might have read too many)
		while ($lines++ < 0) {
			// Find first newline and remove all text before that
			$output = substr($output, strpos($output, "\n") + 1);
		}
		// Close file and return
		fclose($f);
		return trim($output);
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

function check_tatiana(){
    exec('systemctl status tatiana.service',$mainpid);
    if (stristr($mainpid[2],"Active: active (running)")){
        return "<span class='green'>трудится</span>";
    }else{
        return "<span class='red'><a href='/wakeup.php'>УПАЛА!<br>нажми меня</a></span>";
    }
}


//Возврат температуры ЦП  

function cpu_temp(){
    $string = exec('cat /sys/class/thermal/thermal_zone0/temp');
    if ($string < 45000){
        return "<span class='blue'>прохладно (". round($string/1000, 1) ."&deg;C)</span>";
    }elseif ($string > 55000){
        return "<span class='red'>ЖАРКО! (". round($string/1000, 1) ."&deg;C)</span>";
    }else{
        return "<span class='green'>комфортно (". round($string/1000, 1) ."&deg;C)</span>";
    }
}


//Показывает план из базы с готовыми кнопками удаления.
function show_plan(){
    $query = mysql_query("SELECT * FROM `plan`");
    while ($row = mysql_fetch_assoc($query)) 
	{
		
	     if ($row['calendar'] == 1){$dayWeek = "Пн-Пт";}
         elseif ($row['calendar'] == 2){$dayWeek = "Сб-Вс";}
         else {$dayWeek = "Пн-Вс";}
		
	     $string .= '
                     <div class="planrow">
			         <div class="pinname">'  .pin_to_name($row['pin']). '</div>
				     <div class="ontime">'   .$row['ontime'].           '</div> 
				     <div class="offtime">'  .$row['offtime'].          '</div> 
				     <div class="calendar">' .$dayWeek.' 
			         <span class="delLine fa fa-minus-circle fa-1" data-unique-id="'.$row['id'].'"></span>
				     </div>
				     </div>
				    ';
    }
  
return $string;
}



//Самая нужная в мире функция, странно, что последняя в списке.
//Прнимает номер пина, возвращает его название.
function pin_to_name($pin){
    $query = mysql_query("SELECT name FROM pins WHERE pin=$pin");
    $pin_name = mysql_fetch_assoc($query);
    if (stristr($pin_name, "\n")) /////// Разобраться, почему выводится через перевод строки и устранить косяк
    {
        return $pin;
    }else{
        return $pin_name['name'];
    }
}

function querySelectItem(){
    $q = mysql_query("SELECT `name`,`pin` FROM `pins` WHERE `direction` = 'output' ORDER BY `pin` ASC");
	
	while($row = mysql_fetch_assoc($q))
	{
		$strOptTag .= '
		<option value="'.$row['pin'].'">'.$row['name'].'</option>
		';
	}
	
    return $strOptTag;
}

//Возвращает погоду в доме и на улице

function show_weather(){
    $weather = array();
    $query = mysql_query("SELECT `pin`,`name` FROM `pins` WHERE `direction`='dht'");
    while ($sensor = mysql_fetch_assoc($query))
    {
        //$sensor['pin'] $sensor['name']
        $data = mysql_query("SELECT `temperature`,`humidity`,`timestamp` FROM `dht_data` WHERE `pin`='" . $sensor['pin'] . "' ORDER BY `timestamp` DESC");
        $weather[$sensor['name']] = mysql_fetch_assoc($data);
    }
    foreach ($weather as $name=>$data)
    {
        if (isset($data['timestamp']))
        {
            $result =  $result . "<div class='weatherrow'><div class='weathercell' id='dhtname'>" . $name . ": </div><div class='weathercell'>" . $data['temperature'] . "&deg;C</div><div class='weathercell'>" . $data['humidity'] . "%</div></div>";
            $actuality_time = date("H:i",$data['timestamp']);
        }
    }
    $result ="<div class='weather'><div class='weathercaption'>Погода на $actuality_time</div>" . $result . "</div>";
    return $result;
}



?>
