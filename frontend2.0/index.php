<?php

include_once('settings.php');

if ($_POST['lgn']==$login and $_POST['pwd']==$password){
    $hash=$_SERVER['REMOTE_ADDR'].$login.$_SERVER['REMOTE_ADDR'].$password.date('sdsHsms').$_SERVER['REMOTE_ADDR'];
    $hash = md5($hash);
    setcookie("authcontrol",$hash,time()+600);
    $sessfile = fopen("sess.txt","w+");
    fwrite($sessfile,$hash);
    fclose($sessfile);
echo <<<RENEW
<html>
<head>
<meta http-equiv="refresh" content="3"/>
</head>
<body><center>Привет, одну секунду...</center></body>
</html>
RENEW;
exit();
}
$istherecookie = file_get_contents("sess.txt");
if ($istherecookie == $_COOKIE["authcontrol"]){
    $status = true;

    }else{
    $status = false;
    unset($_COOKIE["authcontrol"]);    
    }

if ($status==true) {


function show_plan($plan){
    #$echer=file_get_contents($plan);
    $echer = "\n".$plan;
    $echer = str_replace("\n", "%LINEEND%", $echer);
    $echer = str_replace(">", "%ONTIME%", $echer);
    $echer = str_replace("<", "%OFFTIME%", $echer);
    $echer = str_replace("%LINEEND%", "</tr>\n<tr>\n<td>", $echer);
    $echer = str_replace("%ONTIME%", "</td>\n<td class='onplan'>", $echer);
    $echer = str_replace("%OFFTIME%", "</td>\n<td class='offplan'>", $echer);
    $echer = str_replace("%NEWLINE%", "</td>\n</tr>", $echer);
    return $echer;
}

function pintoname($pin_name,$plan){
    $plan=file_get_contents($plan);
    foreach ($pin_name as $pin=>$name){
        $plan = str_replace($pin.">", $name.">", $plan);
    }
    return $plan;
}


$echoer = file_get_contents("template.html");
$echoer = str_replace("%PLAN%",show_plan(pintoname($pin_name,$planfile)),$echoer);
echo $echoer;
}
    
else {
$echoer = file_get_contents("form");
echo $echoer;

}
?>
