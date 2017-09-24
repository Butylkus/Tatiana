<?php

header("Content-Type:text/txt; charset=UTF-8");

//Подключение к базе данных
$db = mysqli_connect("localhost","tatiana","tatiana","tatiana") or die("Не могу подключится к серверу БД");
//ну так, на всякий случай
$polypoints = "";
$houraxis = "";
$dayaxis = "";

    
///////////////////////////////////////////
/////////////СУТОЧНАЯ ТЕМПЕРАТУРА//////////
///////////////////////////////////////////
if ($_GET['type']=="temp24"){
    $data = mysqli_query($db, "SELECT temperature, timestamp FROM dht_data WHERE pin=3 ORDER BY id DESC LIMIT 49;");    

//лепим массив данных. всего 49 значений (0-48)
    $i=48;
    while ($row = mysqli_fetch_assoc($data)) {
        $temp[$i]=400-$row["temperature"]*10;//координаты по У, температура. Диапазон от 0 до 40 градусов
        $time[$i]=$row["timestamp"];//координаты по Х, время. координата вычисляется дальше
        $i--;
    }
// переворачиваем массив, чтобы график шёл слева направо

    for ($i=0;$i<=48;$i++){
        $x = 20+20*$i; //вычисляем иксовую координату времени
        $polypoints = $polypoints."$x,$temp[$i] "; //%POLYPOINTS% - Собираем готовые координаты в строку 
    }

    $currdotval = (400-$temp[48])/10; //%CURRENTDOTVALUE%
    $rotatedotx = $x+4; //%ROTATEDOTX%
    $rotatedoty = $temp[48]-14; //%ROTATEDOTY%

//Собираем конечный код осей
    for ($i=0;$i<=48;$i=$i+6){
        $hour=17+20*$i; //вычисляем иксовую координату подписи времени
        $day=32+20*$i; //вычисляем иксовую координату подписи даты
        $houraxis = $houraxis   .'<text class="xtime" x="'.$hour.'" y="399" fill="#777" transform="rotate(-90 '.$hour.',399)">'.date('H:i',$time[$i])."</text>\n"; //%HOURAXIS%
        $dayaxis = $dayaxis     .'<text class="xtime" x="'.$day.'" y="399" fill="#aaa" transform="rotate(-90 '.$day.',399)">'.date('d.m',$time[$i])."</text>\n"; //%DAYAXIS%
    }
        
    $curdateval = date("H:i:s, d.m.Y",$time[48]); //%CURRENTDOTDATATIME%
    $template = file_get_contents('./tpl/tempday.txt');
    $returner = str_replace("%CURRENTDOTX%",$x,$template);
    $returner = str_replace("%CURRENTDOTY%",$temp[48],$returner);
    $returner = str_replace("%CURRENTDOTVALUE%",$currdotval,$returner);
    $returner = str_replace("%CURRENTDOTDATATIME%",$curdateval,$returner);
    $returner = str_replace("%ROTATEDOTX%",$rotatedotx,$returner);
    $returner = str_replace("%ROTATEDOTY%",$rotatedoty,$returner);
    $returner = str_replace("%HOURAXIS%",$houraxis,$returner);
    $returner = str_replace("%DAYAXIS%",$dayaxis,$returner);
    $returner = str_replace("%POLYPOINTS%",$polypoints,$returner);

    echo $returner;
}


///////////////////////////////////////////
/////////////СУТОЧНАЯ ВЛАЖНОСТЬ////////////
///////////////////////////////////////////
if ($_GET['type']=="hmdt24"){
    $data = mysqli_query($db, "SELECT humidity, timestamp FROM dht_data WHERE pin=3 ORDER BY id DESC LIMIT 49;");    

//лепим массив данных. всего 49 значений (0-48)
    $i=48;
    while ($row = mysqli_fetch_assoc($data)) {
        $hmdt[$i]=400-$row["humidity"]*4;//координаты по У, температура. Диапазон от 0 до 40 градусов
        $time[$i]=$row["timestamp"];//координаты по Х, время. координата вычисляется дальше
        $i--;
    }
// переворачиваем массив, чтобы график шёл слева направо

    for ($i=0;$i<=48;$i++){
        $x = 20+20*$i; //вычисляем иксовую координату времени
        $polypoints = $polypoints."$x,$hmdt[$i] "; //%POLYPOINTS% - Собираем готовые координаты в строку 
    }

    $currdotval = (400-$hmdt[48])/4; //%CURRENTDOTVALUE%
    $rotatedotx = $x+4; //%ROTATEDOTX%
    $rotatedoty = $hmdt[48]-14; //%ROTATEDOTY%

//Собираем конечный код осей
    for ($i=0;$i<=48;$i=$i+6){
        $hour=17+20*$i; //вычисляем иксовую координату подписи времени
        $day=32+20*$i; //вычисляем иксовую координату подписи даты
        $houraxis = $houraxis   .'<text class="xtime" x="'.$hour.'" y="399" fill="#777" transform="rotate(-90 '.$hour.',399)">'.date('H:i',$time[$i])."</text>\n"; //%HOURAXIS%
        $dayaxis = $dayaxis     .'<text class="xtime" x="'.$day.'" y="399" fill="#aaa" transform="rotate(-90 '.$day.',399)">'.date('d.m',$time[$i])."</text>\n"; //%DAYAXIS%
    }
        
    $curdateval = date("H:i:s, d.m.Y",$time[48]); //%CURRENTDOTDATATIME%
    $template = file_get_contents('./tpl/hmdtday.txt');
    $returner = str_replace("%CURRENTDOTX%",$x,$template);
    $returner = str_replace("%CURRENTDOTY%",$hmdt[48],$returner);
    $returner = str_replace("%CURRENTDOTVALUE%",$currdotval,$returner);
    $returner = str_replace("%CURRENTDOTDATATIME%",$curdateval,$returner);
    $returner = str_replace("%ROTATEDOTX%",$rotatedotx,$returner);
    $returner = str_replace("%ROTATEDOTY%",$rotatedoty,$returner);
    $returner = str_replace("%HOURAXIS%",$houraxis,$returner);
    $returner = str_replace("%DAYAXIS%",$dayaxis,$returner);
    $returner = str_replace("%POLYPOINTS%",$polypoints,$returner);

    echo $returner;
}




///////////////////////////////////////////
/////////////НЕДЕЛЬНАЯ ТЕМПЕРАТУРА/////////
///////////////////////////////////////////


if ($_GET['type']=="temp7"){
    $data = mysqli_query($db, "SELECT temperature, timestamp FROM dht_data WHERE pin=3 ORDER BY id DESC LIMIT 337;");    
    $i=336;
    while ($row = mysqli_fetch_assoc($data)) {
        $temp[$i]=400-$row["temperature"]*10;//координаты по У, температура. Диапазон от 0 до 40 градусов
        $time[$i]=$row["timestamp"];//координаты по Х, время. координата вычисляется дальше
        $i--;
    }
// переворачиваем массив, чтобы график шёл слева направо

    for ($i=0;$i<=337;$i++){
        $x=0+3*$i; //вычисляем иксовую координату времени
        $polypoints = $polypoints."$x,$temp[$i] "; //%POLYPOINTS% - Собираем готовые координаты в строку 
    }

    $currdotval = (400-$temp[336])/10; //%CURRENTDOTVALUE%
    $rotatedotx = $x+4; //%ROTATEDOTX%
    $rotatedoty = $temp[336]-14; //%ROTATEDOTY%

 
 
            for ($i=0;$i<=320;$i=$i+20){
                $hour=27+3*$i; //вычисляем иксовую координату подписи времени
                $day=42+3*$i; //вычисляем иксовую координату подписи даты
                $houraxis = $houraxis   .'<text class="xtime" x="'.$hour.'" y="399" fill="#777" transform="rotate(-90 '.$hour.',399)">'.date('H:i',$time[$i])."</text>\n"; //%HOURAXIS%
                $dayaxis = $dayaxis     .'<text class="xtime" x="'.$day.'" y="399" fill="#aaa" transform="rotate(-90 '.$day.',399)">'.date('d.m',$time[$i])."</text>\n"; //%DAYAXIS%
            }
        


    $curdateval = date("H:i:s, d.m.Y",$time[336]); //%CURRENTDOTDATATIME%
    $template = file_get_contents('./tpl/tempweek.txt');
    $returner = str_replace("%CURRENTDOTX%",$x,$template);
    $returner = str_replace("%CURRENTDOTY%",$temp[336],$returner);
    $returner = str_replace("%CURRENTDOTVALUE%",$currdotval,$returner);
    $returner = str_replace("%CURRENTDOTDATATIME%",$curdateval,$returner);
    $returner = str_replace("%ROTATEDOTX%",$rotatedotx,$returner);
    $returner = str_replace("%ROTATEDOTY%",$rotatedoty,$returner);
    $returner = str_replace("%HOURAXIS%",$houraxis,$returner);
    $returner = str_replace("%DAYAXIS%",$dayaxis,$returner);
    $returner = str_replace("%POLYPOINTS%",$polypoints,$returner);

    echo $returner;

}



///////////////////////////////////////////
/////////////НЕДЕЛЬНАЯ ВЛАЖНОСТЬ///////////
///////////////////////////////////////////


if ($_GET['type']=="hmdt7"){
    $data = mysqli_query($db, "SELECT humidity, timestamp FROM dht_data WHERE pin=3 ORDER BY id DESC LIMIT 337;");    

//лепим массив данных. всего 49 значений (0-48)
    $i=336;
    while ($row = mysqli_fetch_assoc($data)) {
        $hmdt[$i]=400-$row["humidity"]*4;//координаты по У, температура. Диапазон от 0 до 40 градусов
        $time[$i]=$row["timestamp"];//координаты по Х, время. координата вычисляется дальше
        $i--;
    }
// переворачиваем массив, чтобы график шёл слева направо

    for ($i=0;$i<=336;$i++){
        $x = 0+3*$i; //вычисляем иксовую координату времени
        $polypoints = $polypoints."$x,$hmdt[$i] "; //%POLYPOINTS% - Собираем готовые координаты в строку 
    }

    $currdotval = (400-$hmdt[336])/4; //%CURRENTDOTVALUE%
    $rotatedotx = $x+4; //%ROTATEDOTX%
    $rotatedoty = $hmdt[336]-14; //%ROTATEDOTY%

//Собираем конечный код осей
    for ($i=0;$i<=336;$i=$i+20){
        $hour=27+3*$i; //вычисляем иксовую координату подписи времени
        $day=42+3*$i; //вычисляем иксовую координату подписи даты
        $houraxis = $houraxis   .'<text class="xtime" x="'.$hour.'" y="399" fill="#777" transform="rotate(-90 '.$hour.',399)">'.date('H:i',$time[$i])."</text>\n"; //%HOURAXIS%
        $dayaxis = $dayaxis     .'<text class="xtime" x="'.$day.'" y="399" fill="#aaa" transform="rotate(-90 '.$day.',399)">'.date('d.m',$time[$i])."</text>\n"; //%DAYAXIS%
    }
        
    $curdateval = date("H:i:s, d.m.Y",$time[336]); //%CURRENTDOTDATATIME%
    $template = file_get_contents('./tpl/hmdtweek.txt');
    $returner = str_replace("%CURRENTDOTX%",$x,$template);
    $returner = str_replace("%CURRENTDOTY%",$hmdt[336],$returner);
    $returner = str_replace("%CURRENTDOTVALUE%",$currdotval,$returner);
    $returner = str_replace("%CURRENTDOTDATATIME%",$curdateval,$returner);
    $returner = str_replace("%ROTATEDOTX%",$rotatedotx,$returner);
    $returner = str_replace("%ROTATEDOTY%",$rotatedoty,$returner);
    $returner = str_replace("%HOURAXIS%",$houraxis,$returner);
    $returner = str_replace("%DAYAXIS%",$dayaxis,$returner);
    $returner = str_replace("%POLYPOINTS%",$polypoints,$returner);

    echo $returner;
}





?>
