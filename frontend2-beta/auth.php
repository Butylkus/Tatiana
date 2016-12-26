<?php
include_once('settings.php');
session_start();

if (isset($_POST['user']) && isset($_POST['password']) or $_SESSION['authorizedsids'][$_COOKIE['sid']] =="authorised")
{
    if ($_POST['user']==$user && $_POST['password']==$password)
    {
        header("Location: http://".$_SERVER['HTTP_HOST']."/");
        $_SESSION['ssid']=session_id();
        $hash=generateCode($length=16);
        $_SESSION['authorizedsids'][$hash]="authorised";
        setcookie('sid',$hash);
        exit();
    }
}

if (isset($_GET['action']) and $_GET['action']=="logout") {
    session_start();
    $_SESSION['authorizedsids']="";
    session_destroy();
    setcookie ("sid", "", time() - 3600);
    header("Location: http://".$_SERVER['HTTP_HOST']."/auth.php");
    exit();
    
}



if (!isset($_SESSION['authorizedsids'][$_COOKIE['sid']]) or !isset($_COOKIE['sid']) or $_SESSION['authorizedsids'][$_COOKIE['sid']] != "authorised") {
    echo file_get_contents("loginform.html");
    exit();
}



function generateCode($length=9) {

    $chars = "qwertyuiopasdfghjklzxcvbnm1234567890ZXCVBNMASDFGHJKLQWERTYUIOP";

    $code = "";

    $clen = strlen($chars) - 1;  
    while (strlen($code) < $length) {

            $code .= $chars[mt_rand(0,$clen)];  
    }

    return $code;

}
?>
