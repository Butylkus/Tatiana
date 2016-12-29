<?php
function writeLog($pinNum)
{

   $fg = file_get_contents(STATUSES.$pinNum);
   $stfile = fopen(LOGFILE,"a");
   flock($stfile,LOCK_EX);
   $moment = date('d.m.Y H:i:s');
   
   switch($fg)
   {
//pin - имя статус файла он же номер пина
//filedata - содержимое статус-файла. Позвояет проверить был ли изменён статус.

	  case 1:
	           $res = array(
			   "error"    => 0,
			   "pin"      => $pinNum,
			   "log"      => "%WEBOFF% $pinNum > $moment",
			   "filedata" => 0
			   );
			   
	           fwrite($stfile,"%WEBOFF% $pinNum > $moment\n");
	  break;
	  
	  case 0:
	  	       $res = array(
			   "error"    => 0,
			   "pin"      => $pinNum,
			   "log"      => "%WEBON% $pinNum > $moment",
			   "filedata" => 1
			   );
			   
	           fwrite($stfile,"%WEBON% $pinNum > $moment\n"); 
	  break;
	  
   }
fclose($stfile);
return json_encode($res);
}


function readLog($num=1,$pin_name)
{
    $echer = file_get_contents(LOGFILE);
    $echer = str_replace(" ", "&nbsp;", $echer);

    foreach ($pin_name as $pin=>$name) {
        $searchstring="%&nbsp;". $pin ."&nbsp;>";
        $replacestring="%&nbsp;". $name ."&nbsp;</div><div class='logright'>";
        $echer = str_replace($searchstring, $replacestring, $echer);
    }
	   

    
    $echer = str_replace("%WEBON%", "<div class='logleft'><img src='images/logwebon.png' class='logpicture' title='Включено через веб-интерфейс'>", $echer);
    $echer = str_replace("%WEBOFF%", "<div class='logleft'><img src='images/logweboff.png' class='logpicture' title='Выключено через веб-интерфейс'>", $echer);
    $echer = str_replace("%PLANON%", "<div class='logleft'><img src='images/logplanon.png' class='logpicture' title='Запланированное включение'>", $echer);
    $echer = str_replace("%PLANOFF%", "<div class='logleft'><img src='images/logplanoff.png' class='logpicture' title='Запланированное выключение'>", $echer);
    $echer = str_replace("%BUTTONOFF%", "<div class='logleft'><img src='images/logbutoff.png' class='logpicture' title='Выключено нажатием на кнопку'>", $echer);
    $echer = str_replace("%BUTTONON%", "<div class='logleft'><img src='images/logbuton.png' class='logpicture' title='Включено нажатием на кнопку'>", $echer);
    $echer = str_replace("%UP%&nbsp;>", "<div class='logleft'><img src='images/logsysup.png' class='logpicture' title='Я проснулась! =)'>За работу!</div><div class='logright'>", $echer);
    $echer = str_replace("%DOWN%&nbsp;>", "<div class='logleft'><img src='images/logsysdown.png' class='logpicture' title='Я заснула...'>Татьяна отдыхает</div><div class='logright'>", $echer);
    $echer = str_replace("%IRON%", "<div class='logleft'><img src='images/logiron.png' class='logpicture' title='Включено пультом ДУ'>", $echer);
    $echer = str_replace("%IROFF%", "<div class='logleft'><img src='images/logiroff.png' class='logpicture' title='Выключено пультом ДУ'>", $echer);
    $echer = str_replace("\n", "</div>\n", $echer);
    
	$echer = explode("\n",$echer);
	$echer = array_reverse($echer);
	$total = count($echer)-1;

    if($num > $total) $num = $total;
	if($num <= 1) return '<div class="logger">'.$echer[1].'</div>';
	
    for($i = 1; $i <= $num; $i++)
	   {
		  $res.= '<div class="logger">'.$echer[$i].'</div>';
	   }
	   
       return $res;	   
}
?>
