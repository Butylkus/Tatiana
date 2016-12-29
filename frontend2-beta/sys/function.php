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

    for($i = 1; $i < 28; $i++) 	   {
        if (isset($pin_name[$i])){
            $searchstring="%&nbsp;". $i ."&nbsp;>";
            $replacestring="%&nbsp;". $pin_name[$i] . "&nbsp;>";
            $echer = str_replace($searchstring, $replacestring, $echer);
        }
    }
	   

    
    $echer = str_replace("%WEBON%", "<span class='logleft'><img src='images/logwebon.png' class='logpicture'>", $echer);
    $echer = str_replace("%WEBOFF%", "<span class='logleft'><img src='images/logweboff.png' class='logpicture'>", $echer);
    $echer = str_replace("%PLANON%", "<span class='logleft'><img src='images/logplanon.png' class='logpicture'>", $echer);
    $echer = str_replace("%PLANOFF%", "<span class='logleft'><img src='images/logplanoff.png' class='logpicture'>", $echer);
    $echer = str_replace("%BUTTONOFF%", "<span class='logleft'><img src='images/logbutoff.png' class='logpicture'>", $echer);
    $echer = str_replace("%BUTTONON%", "<span class='logleft'><img src='images/logbuton.png' class='logpicture'>", $echer);
    $echer = str_replace("%UP%", "<span class='logleft'><img src='images/logsysup.png' class='logpicture'>", $echer);
    $echer = str_replace("%DOWN%", "<span class='logleft'><img src='images/logsysdown.png' class='logpicture'>", $echer);
    
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
