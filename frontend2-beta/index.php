<?php
 session_start();
 if ($_SESSION['authorizedsids'][$_COOKIE['sid']] != "authorised"){
     header("Location: http://".$_SERVER['HTTP_HOST']."/auth.php");
     exit();
 }

include_once 'sys/settings.php';

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

function logout(){
    $logout = "http://".$_SERVER['HTTP_HOST']."/auth.php?action=logout";
    return $logout;
}

function uptime(){
    return exec("uptime -s");
}

function check_tatiana(){
    exec('ps ax | grep tatiana',$mainpid);
    if (stristr($mainpid[0],"tatiana.py")){
        return "<span class='green'>трудится</span>";
    }else{
        return "<span class='red'>УПАЛА! =(</span>";
    }
}

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


$echoer = file_get_contents("template.html");
$echoer = str_replace("%PLAN%", show_plan(pintoname($pin_name,LOGFILE)),$echoer);
$echoer = str_replace("%LOGOUT%", logout(),$echoer);
$echoer = str_replace("%UPTIME%", uptime(),$echoer);
$echoer = str_replace("%IS TATIANA.PY RUNNING%", check_tatiana(),$echoer);
$echoer = str_replace("%CPUTEMP%", cpu_temp(),$echoer);
echo $echoer;

?>
