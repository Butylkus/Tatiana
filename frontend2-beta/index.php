<?php
 session_start();
 if ($_SESSION['authorizedsids'][$_COOKIE['sid']] != "authorised"){
     header("Location: http://".$_SERVER['HTTP_HOST']."/auth.php");
     exit();
 }

include_once('settings.php');

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



$echoer = file_get_contents("template.html");
$echoer = str_replace("%PLAN%",show_plan(pintoname($pin_name,$planfile)),$echoer);
$echoer = str_replace("%LOGOUT%",logout(),$echoer);
echo $echoer;

?>
