<?php
//Чтобы этот скрипт работал, необходимо в /etc/sudoers добавить строку:
//www-data  ALL=(root) NOPASSWD: /bin/systemctl start tatiana.service


function restart_tatiana(){
    exec('systemctl status tatiana.service',$messages);
    
    if (stristr($messages[2],"Active: active (running)")){
        $status = "<strong>Татьяна:</strong><br>-Не отвлекай меня от работы, пожалуйста. Кыш, кыш! =)";
    }else{
        exec('sudo systemctl start tatiana.service');
        $messages="";
        exec('systemctl status tatiana.service',$messages);
        if (stristr($messages[2],"Active: active (running)")){
            $status = "<strong>Татьяна:</strong><br>-Ой, извини, что-то я задремала... Ты вернёшься к своим делам через мгновенье =)";
        }
        
    }
    
    return $status;
}
?> 
<html>
<head>
<meta http-equiv="refresh" content="5; url=/">
</head>
<body>
<strong>Вы:</strong><br>-Татьяна, ты как?<br>
<?php
echo restart_tatiana();
?> 
</body>
</html>
