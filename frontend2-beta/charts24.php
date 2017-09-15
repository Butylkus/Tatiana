<!DOCTYPE HTML>
<head>
    <meta charset="UTF-8">
    <title>Графики за 24 часа</title>
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
    <h2>Температура за 24 часа</h2> 
    <svg width="1000" height="400">
<!--сеточка горизонталка-->
<!--5-->
        <line x1="0" x2="1000" y1="350" y2="350" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
<!--10-->
        <line x1="0" x2="1000" y1="300" y2="300" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
<!--15-->
        <line x1="0" x2="1000" y1="250" y2="250" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
<!--20-->
        <line x1="0" x2="1000" y1="200" y2="200" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
<!--25-->
        <line x1="0" x2="1000" y1="150" y2="150" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
<!--30-->
        <line x1="0" x2="1000" y1="100" y2="100" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
<!--35-->
        <line x1="0" x2="1000" y1="50" y2="50" 
        style="fill:none;stroke:slategrey;stroke-width:1" />

<!--сеточка вертикалочка-->
<!-- -24 -->
        <line x1="20" x2="20" y1="0" y2="390" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
<!-- -23 -->
        <line x1="60" x2="60" y1="0" y2="390" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
<!-- -22 -->
        <line x1="100" x2="100" y1="0" y2="390" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
<!-- -21 -->
        <line x1="140" x2="140" y1="0" y2="390" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
<!-- -20 -->
        <line x1="180" x2="180" y1="0" y2="390" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
<!-- -19 -->
        <line x1="220" x2="220" y1="0" y2="390" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
<!-- -18 -->
        <line x1="260" x2="260" y1="0" y2="390" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
<!-- -17 -->
        <line x1="300" x2="300" y1="0" y2="390" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
<!-- -16 -->
        <line x1="340" x2="340" y1="0" y2="390" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
<!-- -15 -->
        <line x1="380" x2="380" y1="0" y2="390" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
<!-- -14 -->
        <line x1="420" x2="420" y1="0" y2="390" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
<!-- -13 -->
        <line x1="460" x2="460" y1="0" y2="390" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
<!-- -12 -->
        <line x1="500" x2="500" y1="0" y2="390" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
<!-- -11 -->
        <line x1="540" x2="540" y1="0" y2="390" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
<!-- -10 -->
        <line x1="580" x2="580" y1="0" y2="390" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
<!-- -9 -->
        <line x1="620" x2="620" y1="0" y2="390" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
<!-- -8 -->
        <line x1="660" x2="660" y1="0" y2="390" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
<!-- -7 -->
        <line x1="700" x2="700" y1="0" y2="390" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
<!-- -6 -->
        <line x1="740" x2="740" y1="0" y2="390" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
<!-- -5 -->
        <line x1="780" x2="780" y1="0" y2="390" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
<!-- -4 -->
        <line x1="820" x2="820" y1="0" y2="390" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
<!-- -3 -->
        <line x1="860" x2="860" y1="0" y2="390" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
<!-- -2 -->
        <line x1="900" x2="900" y1="0" y2="390" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
<!-- -1 -->
        <line x1="940" x2="940" y1="0" y2="390" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
<!-- свежайший -->
        <line x1="980" x2="980" y1="0" y2="390" 
        style="fill:none;stroke:cyan;stroke-width:5" />

<?php
    $data = mysqli_query($db, "SELECT temperature, timestamp FROM dht_data WHERE pin=3 ORDER BY id DESC LIMIT 49;");    
?>
<!--собсна график-->        
        <polyline points="<?php
//лепим массив данных. всего 49 значений (0-48)
    $i=48;
    while ($row = mysqli_fetch_assoc($data)) {
        $temp[$i]=400-$row["temperature"]*10;//координаты по У, температура. Диапазон от 0 до 40 градусов
        $time[$i]=$row["timestamp"];//координаты по Х, время. координата вычисляется дальше
//        printf ("темп=%s - %s, #$i<br />\n", $last, date("d.m.Y H:i:s",$time[$i]));
        $i--;
    }
// переворачиваем массив, чтобы график шёл слева направо

    for ($i=0;$i<=48;$i++){
        $x=20+20*$i; //вычисляем иксовую координату времени
        echo "$x,$temp[$i] "; //эхаем координаты в точку на холсте
    }
?>"
        style="fill:none;stroke:#FF0000;stroke-width:4" />
<!-- точка свежайшего замера -->
        <circle cx="<?php echo $x;?>" cy="<?php echo $temp[48];?>" r="8px" stroke="#F00" stroke-width="2px" fill="#FFF"/>
<!-- и подпись к ней -->
        <text x="<?php echo $x;?>" y="<?php echo $temp[48];?>" fill="#F00" transform="rotate(-90 <?php echo $x+4;?>,<?php echo $temp[48]-14;?>)"><?php echo (400-$temp[48])/10; ?>&#8451;</text>



<!--подписи осей-->
        <text class="yvalue" x="0" y="350" fill="#200772" style="transform:translate(0px,-5px);">5&#8451;</text>
        <text class="yvalue" x="0" y="300" fill="#226078" style="transform:translate(0px,-5px);">10&#8451;</text>
        <text class="yvalue" x="0" y="250" fill="#008209" style="transform:translate(0px,-5px);">15&#8451;</text>
        <text class="yvalue" x="0" y="200" fill="#9FEE00" style="transform:translate(0px,-5px);">20&#8451;</text>
        <text class="yvalue" x="0" y="150" fill="#CACA40" style="transform:translate(0px,-5px);">25&#8451;</text>
        <text class="yvalue" x="0" y="100" fill="#FFC000" style="transform:translate(0px,-5px);">30&#8451;</text>
        <text class="yvalue" x="0" y="50" fill="#FF0000" style="transform:translate(0px,-5px);">35&#8451;</text>
        

        <?php 
            for ($i=0;$i<=48;$i=$i+6){
                $hour=17+20*$i; //вычисляем иксовую координату подписи времени
                $day=32+20*$i; //вычисляем иксовую координату подписи даты
                echo '<text class="xtime" x="'.$hour.'" y="399" fill="#777" transform="rotate(-90 '.$hour.',399)">'.date('H:i',$time[$i])."</text>\n";
                echo '<text class="xtime" x="'.$day.'" y="399" fill="#aaa" transform="rotate(-90 '.$day.',399)">'.date('d.m',$time[$i])."</text>\n";
            }
        
        ?>
        
        
        <!--<?php echo date("H:i, d.m",$time[48]);?>-->
        
        
    </svg>
    <p>Текущее: <strong><?php echo (400-$temp[48])/10; ?>&#8451;</strong>, снято <?php echo date("H:i:s, d.m.Y",$time[48]);?></p>

    </div>
    
    
    <div class="container">
<!--

ВЛАЖНОСТЬ

-->    
    
    <h2>Влажность за 24 часа</h2> 
    <svg width="1000" height="400">
<!--сеточка горизонталка-->
<!--10-->
    <line x1="0" x2="1000" y1="360" y2="360" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
<!--20-->
    <line x1="0" x2="1000" y1="320" y2="320" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
<!--30-->
    <line x1="0" x2="1000" y1="280" y2="280" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
<!--40-->
    <line x1="0" x2="1000" y1="240" y2="240" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
<!--50-->
    <line x1="0" x2="1000" y1="200" y2="200" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
<!--60-->
    <line x1="0" x2="1000" y1="160" y2="160" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
<!--70-->
    <line x1="0" x2="1000" y1="120" y2="120" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
<!--80-->
    <line x1="0" x2="1000" y1="80" y2="80" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
<!--90-->
    <line x1="0" x2="1000" y1="40" y2="40" 
        style="fill:none;stroke:slategrey;stroke-width:1" />


<!--сеточка вертикалочка-->
<!-- -24 -->
    <line x1="20" x2="20" y1="0" y2="390" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
<!-- -23 -->
    <line x1="60" x2="60" y1="0" y2="390" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
<!-- -22 -->
    <line x1="100" x2="100" y1="0" y2="390" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
<!-- -21 -->
    <line x1="140" x2="140" y1="0" y2="390" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
<!-- -20 -->
    <line x1="180" x2="180" y1="0" y2="390" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
<!-- -19 -->
    <line x1="220" x2="220" y1="0" y2="390" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
<!-- -18 -->
    <line x1="260" x2="260" y1="0" y2="390" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
<!-- -17 -->
    <line x1="300" x2="300" y1="0" y2="390" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
<!-- -16 -->
    <line x1="340" x2="340" y1="0" y2="390" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
<!-- -15 -->
    <line x1="380" x2="380" y1="0" y2="390" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
<!-- -14 -->
    <line x1="420" x2="420" y1="0" y2="390" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
<!-- -13 -->
    <line x1="460" x2="460" y1="0" y2="390" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
<!-- -12 -->
    <line x1="500" x2="500" y1="0" y2="390" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
<!-- -11 -->
    <line x1="540" x2="540" y1="0" y2="390" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
<!-- -10 -->
    <line x1="580" x2="580" y1="0" y2="390" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
<!-- -9 -->
    <line x1="620" x2="620" y1="0" y2="390" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
<!-- -8 -->
    <line x1="660" x2="660" y1="0" y2="390" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
<!-- -7 -->
    <line x1="700" x2="700" y1="0" y2="390" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
<!-- -6 -->
    <line x1="740" x2="740" y1="0" y2="390" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
<!-- -5 -->
    <line x1="780" x2="780" y1="0" y2="390" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
<!-- -4 -->
    <line x1="820" x2="820" y1="0" y2="390" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
<!-- -3 -->
    <line x1="860" x2="860" y1="0" y2="390" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
<!-- -2 -->
    <line x1="900" x2="900" y1="0" y2="390" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
<!-- -1 -->
    <line x1="940" x2="940" y1="0" y2="390" 
        style="fill:none;stroke:slategrey;stroke-width:1" />
<!-- свежайший -->
    <line x1="980" x2="980" y1="0" y2="390" 
        style="fill:none;stroke:cyan;stroke-width:5" />

<?php

    $data = mysqli_query($db, "SELECT humidity, timestamp FROM dht_data WHERE pin=3 ORDER BY id DESC LIMIT 49;");

?>
<!--собсна график-->        
    <polyline points="<?php
//лепим массив данных. всего 48 значений (0-47)
    $i=48;
    while ($row = mysqli_fetch_assoc($data)) {
        $hmd[$i]=400-$row["humidity"]*4;//координаты по У, влажность. Диапазон от 0 до 100%, зум х4
        $time[$i]=$row["timestamp"];//координаты по Х, время. координата вычисляется дальше
        //printf ("влага=%s - %s, #$i<br />\n", $hmd[$i], date("d.m.Y H:i:s",$time[$i]));
        $i--;
    }
    for ($i=0;$i<=48;$i++){
        $x=20+20*$i; //вычисляем иксовую координату времени
        echo "$x,$hmd[$i] "; //эхаем координаты в точку на холсте
    }
?>"
        style="fill:none;stroke:#00D;stroke-width:4" />
    
<!-- точка свежайшего замера -->
    <circle cx="<?php echo $x;?>" cy="<?php echo $hmd[48];?>" r="8px" stroke="#00F" stroke-width="2px" fill="#FFF"/>
<!-- и подпись к ней -->
    <text x="<?php echo $x;?>" y="<?php echo $hmd[48];?>" fill="#00F" transform="rotate(-90 <?php echo $x+4;?>,<?php echo $hmd[48]-14;?>)"><?php echo (400-$hmd[48])/4; ?>%</text>



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
            for ($i=0;$i<=49;$i=$i+6){
                $hour=17+20*$i; //вычисляем иксовую координату подписи времени
                $day=32+20*$i; //вычисляем иксовую координату подписи даты
                echo '<text class="xtime" x="'.$hour.'" y="399" fill="#777" transform="rotate(-90 '.$hour.',399)">'.date('H:i',$time[$i])."</text>\n";
                echo '<text class="xtime" x="'.$day.'" y="399" fill="#aaa" transform="rotate(-90 '.$day.',399)">'.date('d.m',$time[$i])."</text>\n";
            }
        
        ?>
                
        
        
    </svg>
    <p>Текущее: <strong><?php echo (400-$hmd[48])/4; ?>%</strong>, снято <?php echo date("H:i:s, d.m.Y",$time[48]);?></p>
    </div>
    </center>
</body>
</html>
