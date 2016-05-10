<?php
$plan_default = '/home/pi/.tatiana/plans/plan.txt';
$path_file_log = 'commonlog.txt';

#ОБЯЗАТЕЛЬНО ЗАДЕЙСТВУЙТЕ УСТРОЙСТВА В ОСНОВНОМ СКРИПТЕ tatiana.py!!!!
$pin_name[17] = 'Кофеварка';
$pin_name[27] = 'Ванная';
$pin_name[18] = 'Водонагреватель';
$pin_name[22] = 'Прихожая';

$version = "0.2b";


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
    echo $echer;
}

function pintoname($pin_name,$plan){
    $plan=file_get_contents($plan);
    foreach ($pin_name as $pin=>$name){
        $plan = str_replace($pin.">", $name.">", $plan);
    }
    return $plan;
}
?>
<html>
  <head>
    <title>Татьяна - умная домохозяйка</title>
    <meta name="robots" content="noindex,nofollow">
    <link href="style/css/style.css" type="text/css" rel="stylesheet">
    <style type="text/css">


    </style>
  </head>
  <body>






<div class="tabs">
    <input id="tab1" type="radio" name="tabs" checked>
    <label for="tab1" title="Панель управления">Управление</label>
    <input id="tab2" type="radio" name="tabs">
    <label for="tab2" title="Текущий план">Список дел</label>
    <input id="tab3" type="radio" name="tabs">
    <label for="tab3" title="Редактор плана">Редактор дел</label>
    <input id="tab4" type="radio" name="tabs">
    <label for="tab4" id="log_query" title="Сообщения Татьяны">Логи</label>
    <input id="tab5" type="radio" name="tabs">
    <label for="tab5" title="Видеонаблюдение">Что творится?</label>
    
    
<section id="content1">
    <div id="main">
    </div>
	<center><div id="reload_buttons"><center>Обновить<br /><br /><br />кнопки</center></div></center>
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/function.js"></script>
</section>  

<section id="content2">
    <p>Вот список дел на сегодня. И на завтра...</p>
    <center>
    <table border="1px">
    <tr><th>пин</th><th>Включить</th><th>Выключить</th>
        <?php
        show_plan(pintoname($pin_name,$plan_default));
        ?>
        </table>
    </center>
</section>

<section id="content3">
    <div class="planform">
    <center>Здесь можно добавить или удалить задания
    <form action="addplan.php">
        <textarea name="newplan" cols="60" rows="20"><?php echo file_get_contents($plan_default);?></textarea><br />
        <button>Зая, запиши!</button>
    </form>
    </center>
    </div>
</section> 

<section id="content4">
    <div class="logtab">
    </div>
</section>

<section id="content5">
    <div class="camera">
    <center>В разработке ;)</center>
    
    
    
    </div>
</section>   
   </div>


<center>
-------------------------------------<br />
Цопилефт: Бутылкус =)<br />
Version:&nbsp;<?php echo $version;?><br />
</center>
  
  </body>
</html>