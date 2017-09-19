<!DOCTYPE HTML>
<head>
    <meta charset="UTF-8">
    <title>Графики за неделю</title>
    <style>
        body {
            display: block;
            margin: 8px;
            font-family: MONOSPACE;
            font-size: 1.5em;
        }
        svg {
            font-weight: bold;
        }
        h2 {
            margin: auto;
        }
        p {
            margin: auto;
        }

        .container {
            width:1024px;
            border: solid slategrey 3px;
            margin-top: -3px;
        }

        .xtime {
            font-size: 0.75em;
            font-weight: normal;
        }
        
    </style>
</head>
<?php
    //Подключение к базе данных
    $db = mysqli_connect("localhost","tatiana","tatiana","tatiana") or die("Не могу подключится к серверу БД");


?>
<body>
    <center>

<!--

ТЕМПЕРАТУРА

-->  

    <div class="container">
    <h2>Температура за неделю</h2> 
    <svg width="1020" height="400">
<!--сеточка горизонталка-->
<!--5-->
        <line x1="0" x2="1020" y1="350" y2="350" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
<!--10-->
        <line x1="0" x2="1020" y1="300" y2="300" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
<!--15-->
        <line x1="0" x2="1020" y1="250" y2="250" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
<!--20-->
        <line x1="0" x2="1020" y1="200" y2="200" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
<!--25-->
        <line x1="0" x2="1020" y1="150" y2="150" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
<!--30-->
        <line x1="0" x2="1020" y1="100" y2="100" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
<!--35-->
        <line x1="0" x2="1020" y1="50" y2="50" 
        style="fill:none;stroke:slategrey;stroke-width:1" />

<!--сеточка вертикалочка-->
        <line x1="30" x2="30" y1="0" y2="400" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
        <line x1="60" x2="60" y1="0" y2="400" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
        <line x1="90" x2="90" y1="0" y2="400" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
        <line x1="120" x2="120" y1="0" y2="400" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
        <line x1="150" x2="150" y1="0" y2="400" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
        <line x1="180" x2="180" y1="0" y2="400" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
        <line x1="210" x2="210" y1="0" y2="400" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
        <line x1="240" x2="240" y1="0" y2="400" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
        <line x1="270" x2="270" y1="0" y2="400" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
        <line x1="300" x2="300" y1="0" y2="400" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
        <line x1="330" x2="330" y1="0" y2="400" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
        <line x1="360" x2="360" y1="0" y2="400" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
        <line x1="390" x2="390" y1="0" y2="400" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
        <line x1="420" x2="420" y1="0" y2="400" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
        <line x1="450" x2="450" y1="0" y2="400" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
        <line x1="480" x2="480" y1="0" y2="400" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
        <line x1="510" x2="510" y1="0" y2="400" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
        <line x1="540" x2="540" y1="0" y2="400" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
        <line x1="570" x2="570" y1="0" y2="400" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
        <line x1="600" x2="600" y1="0" y2="400" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
        <line x1="630" x2="630" y1="0" y2="400" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
        <line x1="660" x2="660" y1="0" y2="400" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
        <line x1="690" x2="690" y1="0" y2="400" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
        <line x1="720" x2="720" y1="0" y2="400" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
        <line x1="750" x2="750" y1="0" y2="400" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
        <line x1="780" x2="780" y1="0" y2="400" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
        <line x1="810" x2="810" y1="0" y2="400" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
        <line x1="840" x2="840" y1="0" y2="400" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
        <line x1="870" x2="870" y1="0" y2="400" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
        <line x1="900" x2="900" y1="0" y2="400" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
        <line x1="930" x2="930" y1="0" y2="400" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
        <line x1="960" x2="960" y1="0" y2="400" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
        <line x1="990" x2="990" y1="0" y2="400" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
        <line x1="1008" x2="1008" y1="0" y2="400" 
        style="fill:none;stroke:cyan;stroke-width:5" />

<?php
    $data = mysqli_query($db, "SELECT temperature, timestamp FROM dht_data WHERE pin=3 ORDER BY id DESC LIMIT 337;");    
?>
<!--собсна график-->        
        <polyline points="<?php
//лепим массив данных. всего 49 значений (0-48)
    $i=336;
    while ($row = mysqli_fetch_assoc($data)) {
        $temp[$i]=400-$row["temperature"]*10;//координаты по У, температура. Диапазон от 0 до 40 градусов
        $time[$i]=$row["timestamp"];//координаты по Х, время. координата вычисляется дальше
//        printf ("темп=%s - %s, #$i<br />\n", $last, date("d.m.Y H:i:s",$time[$i]));
        $i--;
    }
// переворачиваем массив, чтобы график шёл слева направо

    for ($i=0;$i<=337;$i++){
        $x=0+3*$i; //вычисляем иксовую координату времени
        echo "$x,$temp[$i] "; //эхаем координаты в точку на холсте
    }
?>"
        style="fill:none;stroke:#FF0000;stroke-width:4" />
<!-- точка свежайшего замера -->
        <circle cx="<?php echo $x;?>" cy="<?php echo $temp[336];?>" r="8px" stroke="#F00" stroke-width="2px" fill="#FFF"/>



<!--подписи осей-->
        <text class="yvalue" x="0" y="350" fill="#200772" style="transform:translate(0px,-5px);">5&#8451;</text>
        <text class="yvalue" x="0" y="300" fill="#226078" style="transform:translate(0px,-5px);">10&#8451;</text>
        <text class="yvalue" x="0" y="250" fill="#008209" style="transform:translate(0px,-5px);">15&#8451;</text>
        <text class="yvalue" x="0" y="200" fill="#9FEE00" style="transform:translate(0px,-5px);">20&#8451;</text>
        <text class="yvalue" x="0" y="150" fill="#CACA40" style="transform:translate(0px,-5px);">25&#8451;</text>
        <text class="yvalue" x="0" y="100" fill="#FFC000" style="transform:translate(0px,-5px);">30&#8451;</text>
        <text class="yvalue" x="0" y="50" fill="#FF0000" style="transform:translate(0px,-5px);">35&#8451;</text>
        

<?php 
            for ($i=0;$i<=320;$i=$i+20){
                $hour=27+3*$i; //вычисляем иксовую координату подписи времени
                $day=42+3*$i; //вычисляем иксовую координату подписи даты
                echo "\t\t".'<text class="xtime" x="'.$hour.'" y="399" fill="#777" transform="rotate(-90 '.$hour.',399)">'.date('H:i',$time[$i])."</text>\n";
                echo "\t\t".'<text class="xtime" x="'.$day.'" y="399" fill="#aaa" transform="rotate(-90 '.$day.',399)">'.date('d.m',$time[$i])."</text>\n";
            }
        
?>
        
        
        <!--<?php echo date("H:i, d.m",$time[336]);?>-->
        
        
    </svg>
    <p>Текущее: <strong><?php echo (400-$temp[336])/10; ?>&#8451;</strong>, снято <?php echo date("H:i:s, d.m.Y",$time[336]);?></p>

    </div>
    
    
    <div class="container">
<!--

ВЛАЖНОСТЬ

-->    
    
    <h2>Влажность за неделю</h2> 
    <svg width="1020" height="400">
<!--сеточка горизонталка-->
<!--10-->
    <line x1="0" x2="1020" y1="360" y2="360" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
<!--20-->
    <line x1="0" x2="1020" y1="320" y2="320" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
<!--30-->
    <line x1="0" x2="1020" y1="280" y2="280" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
<!--40-->
    <line x1="0" x2="1020" y1="240" y2="240" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
<!--50-->
    <line x1="0" x2="1020" y1="200" y2="200" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
<!--60-->
    <line x1="0" x2="1020" y1="160" y2="160" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
<!--70-->
    <line x1="0" x2="1020" y1="120" y2="120" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
<!--80-->
    <line x1="0" x2="1020" y1="80" y2="80" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
<!--90-->
    <line x1="0" x2="1020" y1="40" y2="40" 
        style="fill:none;stroke:slategrey;stroke-width:1" />

<!--сеточка вертикалочка-->
        <line x1="30" x2="30" y1="0" y2="400" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
        <line x1="60" x2="60" y1="0" y2="400" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
        <line x1="90" x2="90" y1="0" y2="400" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
        <line x1="120" x2="120" y1="0" y2="400" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
        <line x1="150" x2="150" y1="0" y2="400" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
        <line x1="180" x2="180" y1="0" y2="400" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
        <line x1="210" x2="210" y1="0" y2="400" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
        <line x1="240" x2="240" y1="0" y2="400" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
        <line x1="270" x2="270" y1="0" y2="400" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
        <line x1="300" x2="300" y1="0" y2="400" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
        <line x1="330" x2="330" y1="0" y2="400" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
        <line x1="360" x2="360" y1="0" y2="400" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
        <line x1="390" x2="390" y1="0" y2="400" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
        <line x1="420" x2="420" y1="0" y2="400" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
        <line x1="450" x2="450" y1="0" y2="400" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
        <line x1="480" x2="480" y1="0" y2="400" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
        <line x1="510" x2="510" y1="0" y2="400" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
        <line x1="540" x2="540" y1="0" y2="400" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
        <line x1="570" x2="570" y1="0" y2="400" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
        <line x1="600" x2="600" y1="0" y2="400" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
        <line x1="630" x2="630" y1="0" y2="400" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
        <line x1="660" x2="660" y1="0" y2="400" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
        <line x1="690" x2="690" y1="0" y2="400" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
        <line x1="720" x2="720" y1="0" y2="400" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
        <line x1="750" x2="750" y1="0" y2="400" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
        <line x1="780" x2="780" y1="0" y2="400" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
        <line x1="810" x2="810" y1="0" y2="400" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
        <line x1="840" x2="840" y1="0" y2="400" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
        <line x1="870" x2="870" y1="0" y2="400" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
        <line x1="900" x2="900" y1="0" y2="400" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
        <line x1="930" x2="930" y1="0" y2="400" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
        <line x1="960" x2="960" y1="0" y2="400" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
        <line x1="990" x2="990" y1="0" y2="400" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
        <line x1="1008" x2="1008" y1="0" y2="400" 
        style="fill:none;stroke:cyan;stroke-width:5" />





<?php

    $data = mysqli_query($db, "SELECT humidity, timestamp FROM dht_data WHERE pin=3 ORDER BY id DESC LIMIT 337;");

?>
<!--собсна график-->        
    <polyline points="<?php
//лепим массив данных. всего 48 значений (0-47)
    $i=336;
    while ($row = mysqli_fetch_assoc($data)) {
        $hmd[$i]=400-$row["humidity"]*4;//координаты по У, влажность. Диапазон от 0 до 100%, зум х4
        $time[$i]=$row["timestamp"];//координаты по Х, время. координата вычисляется дальше
        //printf ("влага=%s - %s, #$i<br />\n", $hmd[$i], date("d.m.Y H:i:s",$time[$i]));
        $i--;
    }
    for ($i=0;$i<=336;$i++){
        $x=0+3*$i; //вычисляем иксовую координату времени
        echo "$x,$hmd[$i] "; //эхаем координаты в точку на холсте
    }
?>"
        style="fill:none;stroke:#00D;stroke-width:4" />
    
<!-- точка свежайшего замера -->
    <circle cx="<?php echo $x;?>" cy="<?php echo $hmd[336];?>" r="8px" stroke="#00F" stroke-width="2px" fill="#FFF"/>



<!--подписи осей-->
        <text x="0" y="360" fill="#FF0000" style="transform:translate(0px,-5px);">10%</text>
        <text x="0" y="320" fill="#FFC000" style="transform:translate(0px,-5px);">20%</text>
        <text x="0" y="280" fill="#CACA40" style="transform:translate(0px,-5px);">30%</text>
        <text x="0" y="240" fill="#008209" style="transform:translate(0px,-5px);">40%</text>
        <text x="0" y="200" fill="#008209" style="transform:translate(0px,-5px);">50%</text>
        <text x="0" y="160" fill="#008209" style="transform:translate(0px,-5px);">60%</text>
        <text x="0" y="120" fill="#CACA40" style="transform:translate(0px,-5px);">70%</text>
        <text x="0" y="80" fill="#2222FF" style="transform:translate(0px,-5px);">80%</text>
        <text x="0" y="40" fill="#2222FF " style="transform:translate(0px,-5px);">90%</text>
        
        
<?php 
            for ($i=0;$i<=336;$i=$i+20){
                $hour=27+3*$i; //вычисляем иксовую координату подписи времени
                $day=42+3*$i; //вычисляем иксовую координату подписи даты
                echo "\t\t".'<text class="xtime" x="'.$hour.'" y="399" fill="#777" transform="rotate(-90 '.$hour.',399)">'.date('H:i',$time[$i])."</text>\n";
                echo "\t\t".'<text class="xtime" x="'.$day.'" y="399" fill="#aaa" transform="rotate(-90 '.$day.',399)">'.date('d.m',$time[$i])."</text>\n";
            }
        
?>
                
        
        
    </svg>
    <p>Текущее: <strong><?php echo (400-$hmd[336])/4; ?>%</strong>, снято <?php echo date("H:i:s, d.m.Y",$time[336]);?></p>
    </div>
    </center>
</body>
</html>
