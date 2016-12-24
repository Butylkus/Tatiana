$(document).ready(function()
{
/* Отправляем запрос на предмет актуальности статусов
сервер возвращает текущее положение кнопок согласно статусу */
function button_load()
{
$.ajax
({
 type: 'post',
 url: 'server.php',
 data: 'act=status_check',
 dataType: 'html',
 success: function(data, status, xhr)
{
$('#buttons').html(data);
}});
}
button_load();
$('#reload_buttons').click(button_load);

/* Запрашиваем лог */
$('#log_query').click(function(){
$.ajax
({
 type: 'post',
 url: 'server.php',
 data: 'act=log_query',
 dataType: 'html',
 success: function(data, status, xhr)
{
$('.logtab').html(data);
}});
});	
/* После клика по кнопке отправляем ajax-запрос для изменения статуса */	
function request(e)
{
  /* e.preventDefault(); */
  var pin = $(this).attr('data-num-pin');
  var power_status = $(this).attr('data-status');
$.ajax
({
 type: 'post',
 url: 'server.php',
 data: 'act=switch_button&pin='+pin+'&power_status='+power_status,
 dataType: 'json',
 success: function(data, status, xhr)
{
/* Если ошибок не возникло меняем цвет кнопки */
if(data.error == 0)
{
/* Данное условие проверяет статус который возвращает сервер
и соответствующим образом меняет цвет кнопки(Именно той по которой был клик),
а так же изменяет значение атрибута data-status */
if(data.filedata == 0)
{
$('#pin_'+data.pin).css("background-color","rgba(0,200,0,0.7)");
/*$('#pin_'+data.pin).css("float","right");*/
$('#pin_'+data.pin).attr('data-status','0');
}
else
{
$('#pin_'+data.pin).css("background-color","rgba(200,0,0,0.7)");
/*$('#pin_'+data.pin).css("float","left");*/
$('#pin_'+data.pin).attr('data-status','1');
}
}}});
}
$(document).on("click",".action",request);
/* Обработчик события который привязан к кнопке с классом .action 
он отвечает за отправку ajax-запроса после клика по кнопке*/
});
