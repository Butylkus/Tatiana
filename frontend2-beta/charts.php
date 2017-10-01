<html>
  <head>
    <meta charset="UTF-8" />
    <title>Всяческие графики</title>
    <style>
        .svg-container {
            font-size: 1.6em;
            font-family: MONOSPACE;
            text-align: center;
           /* transform: scale(0.5); */
        }
        svg {
            font-weight: bold;
        }    
        .xtime {
            font-size: 0.75em;
            font-weight: normal;
        }
        </style>
  </head>
  <body>
  

        <div class="svg-container">

        
       <?php
include_once 'sys/settings.php';

//Подключение к базе данных
//ну так, на всякий случай


///////////////////////////////////////////
/////////////форма всей статы//////////////
///////////////////////////////////////////



// Генерируем форму для показа радиокнопок
$form = 'График '."\n";
//выводим селекты температуры и влажности
$form .= "\t" . '<span class="typeselector"><select id="type"><option value="temp">температуры</option><option value="hmdt">влажности</option></select>'."\n";

//запиливаем радиобатоны с пинами...
//ищем пины в главной таблице
$data = mysql_query("SELECT pin, name FROM pins WHERE direction='dht';");

while ($row = mysql_fetch_assoc($data)) {
    $pinname[$row["pin"]]=$row["name"]; //pin[№]=="имя"...
}

$form .= '<select id="pins">';
foreach ($pinname as $key => $value){
    $form .= "\t" . '<option value="'.$key.'">'.$value.'</option>'."\n";
}
$form .= "</select>\n";


$form .= ' за <select id="period"><option value="day">сутки</option><option value="week">неделю</option><option value="month">месяц</option><option value="year">год</option></select>';

//заканчиваем форму
$form .= "\n\t<p><button onclick=\"btn()\">Показать</button><p>\n";

echo $form;
//exit(0);
?>
        
        
<!-- начало вставки --->        
        <span id="chart-container"></span>
        
<!-- конец -->        

        </div>
        
        
        
        
<script>
  function loadChart(holder) {
    xmlhttp = new XMLHttpRequest();
    var spanid = "chart-container";
    var element = document.getElementById(spanid);
    element.innerHTML = 'гружусь...';
    xmlhttp.open("GET", holder);
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            element.innerHTML = xmlhttp.responseText;
        }
    }
    xmlhttp.send(null);
    }
	function btn(qstring){
		var qstring ='';
        var element = document.getElementById("type");
		qstring = qstring + '?type=' + element.options[element.selectedIndex].value;
		element = document.getElementById("pins");
		qstring = qstring + '&pin=' + element.options[element.selectedIndex].value;
		element = document.getElementById("period");
		qstring = qstring + '&period=' + element.options[element.selectedIndex].value;
		var element = document.getElementById("chart-container");
		loadChart("/sys/charts.php" + qstring);
    }
</script>

  </body>
</html>

