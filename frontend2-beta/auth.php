<?php
include_once 'sys/settings.php';
session_start();

 if(isset($_GET['logout'])) 
  {
      unset($_SESSION['auth']);
      setcookie("sid", "", time() - 3600);
	  setcookie(session_name(), "", time() - 3600);
      header("Location: http://".$_SERVER['HTTP_HOST']."/auth.php");
      exit; 
  }

if (!empty($_POST))
{
	$login = mysql_real_escape_string($_POST['user']);
    $query = mysql_query("SELECT `password` FROM `users` WHERE `login` = '{$login}' LIMIT 1");
	
	if(mysql_num_rows($query) > 0)
	{
        $user = mysql_fetch_assoc($query);
	
        if($user['password'] == md5($_POST['pass']))
        {
		     $dbTime = mysql_query("SELECT `last_login` FROM `users` WHERE `login` = '{$login}'");
		     $time = mysql_fetch_assoc($dbTime);
		
		     setcookie('lastTimeAuth', $time['last_login'], time() + 60*60*24*30);
		
             $hash = generateCode(16);
		
		     setcookie('sid', $hash);
		
             $_SESSION['auth'][$hash] = 'authorised';
		
		     mysql_query("UPDATE `users` SET `user_sid` = '".session_id()."' WHERE `login` = '{$login}'");
		
		     header("Location: http://".$_SERVER['HTTP_HOST']."/");
             exit;
        }
	}
	else
	{
		header("Location: http://".$_SERVER['HTTP_HOST']."/auth.php?logout");
        exit; 
	}
}


if (!isset($_SESSION['auth'][$_COOKIE['sid']]) or !isset($_COOKIE['sid']) or $_SESSION['auth'][$_COOKIE['sid']] != "authorised") {
    echo file_get_contents("loginform.html");
    exit;
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
