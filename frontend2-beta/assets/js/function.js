$(document).ready(function()
{

   var selectorName = '#buttons',
       freqLog = 5000;

/* Функция отрисовки кнопок */

function buttonLoad()
{
     xhr = $.ajax
           ({
                type     : 'post',
                url      : 'api.php',
                data     : 'act=status_check',
                dataType : 'json'
           });
		   
	xhr.done(function(data){
		
	$(selectorName).empty();
	 
     for(let btn of data.dev) {
	
     const status  = btn.status
	 
     const button  = $('<button>', 
	 {
          'class'         : 'action',
          'data-num-pin'  : btn.pinNum,
          'data-status'   : status,
          'text'          : btn.deviceName,
          'css'           : {
                               'backgroundColor': status === 0
                               ? 'rgba(200,0,0,0.5)'
                               : 'rgba(0,200,0,0.5)'
                            }
	 });
	
       $(selectorName).append(button);
    }
	   
    });
   
   xhr.fail(function(err){
   console.log('Ошибка в функции buttonLoad()');   
   });
}

/* Отправляем запрос на предмет актуальности статусов
сервер возвращает json-массив на основе которого 
кнопки окрашиваются в соответствующие цвета */

buttonLoad();

//Функция вывода лога

function readLog()
      {
		 xhr = $.ajax
               ({
                   type      : 'post',
                   url       : 'api.php',
                   data      : 'act=log_query',
                   dataType  : 'html'
               });

			   
	   xhr.done(function(data){
	   $('#log_list').html(data);	   
	   });
	  
	  
	   xhr.fail(function(err){
	   console.log('Ошибка в функции readLog()');
	   });
	  }
	  
//Если в лог файле произошли изменения выводим его на страницу
//В противном случаи ничего не делаем.

    var time = 0;
	
setTimeout(function isChangeLog()
    {
		
	   req = $.ajax
              ({
                   type      : 'post',
                   url       : 'api.php',
                   data      : 'act=is_change_log',
                   dataType  : 'json'
              });

	  
      req.done(function(data){
		  if(data.error == 0)
		  {
             if(time != data.lastTimeUpdate)
		      {
			     time = data.lastTimeUpdate;
			     readLog();
			     buttonLoad();
			     console.log('Лог был обновлён ' +time);
		      }
		         setTimeout(isChangeLog, freqLog); 
		   }
		     else{location.href = 'auth.php?logout';}
	      });

      
      req.fail(function(err){
	  console.log('Ошибка в функции isChangeLog()');
	  });
	  
    }, freqLog);


/* Эта функция отправляет ajax-запрос для смены статуса.
С сервера приходит ответ с текущим статусом пина 
исходя из этого кнопкам задаются цвета */
	
function request(e)
{
      /* e.preventDefault(); */
       var pin = $(this).attr('data-num-pin');
       var power_status = $(this).attr('data-status');
	  
       req = $.ajax
              ({
                   type      : 'post',
                   url       : 'api.php',
                   data      : 'act=switch_button&pin='+pin+'&power_status='+power_status,
                   dataType  : 'json'
              });

			  
    req.done(function(data){

/* Если ошибок не возникло меняем цвет кнопки */
   if(data.error == 0)
   {
	   
           /* Данное условие проверяет статус который возвращает сервер
            и соответствующим образом меняет цвет кнопки(Именно той по которой был клик),
            а так же меняет значение атрибута data-status */

        if(data.status < 1)
        {
             $('[data-num-pin="' +data.pin+ '"]')
			 .css("background-color","rgba(200,0,0,0.5)")
             .attr('data-status',0);
        }
   
        else
        {
             $('[data-num-pin="' +data.pin+ '"]')
			 .css("background-color","rgba(0,200,0,0.5)")
             .attr('data-status',1);
        }
		
		 console.log('pin : ' +data.pin+ ' status : ' +data.status);
   }
      else
	  {
		 console.log(data.info);
	  }   
   });
   
   req.fail(function(err){
   console.log('Ошибка в функции request()');
   });
}



//Функция добавления плана

function addPlan(e){

const dev  = $('#Dev option:selected'),
	  timeOn        = $('#timeOn').val(),
	  timeOff       = $('#timeOff').val(),
	  cal           = $('#calendar option:selected');

	    req = $.ajax
              ({
                   type      : 'post',
                   url       : 'api.php',
                   data      : 'act=add_plan_item&dev='+dev.val()+'&timeOn='+timeOn+'&timeOff='+timeOff+'&cal='+cal.val(),
                   dataType  : 'json'
              });
			  
        req.done(function(data){
        
		if(data.error == 0)
	    {
            const newLine = $('<div>',{
			
		          class : 'planrow',
	              html  : $('<div>',{
		          class : 'pinname',
                  text  : dev.text()
		
		    }).add('<div>',{
			 
		          class : 'ontime',
                  text  : timeOn+':00'	
			  
		    }).add('<div>',{
			
		         class : 'offtime',
		         text  : timeOff+':00'
		
		    }).add('<div>',{
			
		        class  : 'calendar',
                html   : cal.text()+ '<span class="delLine fa fa-minus-circle fa-1" data-unique-id="'+data.lastId+'"></span>'
		    })
		    }).hide();
			
            $('.planblock').append(newLine);
			$('[data-unique-id="' +data.lastId+ '"]').closest('.planrow').slideDown(700);
			
        }

//end done
        });	
        
        req.fail(function(err){
		console.log('Ошибка в функции: addPlan()');
		});		
}


function delPlan(e){
	
     const unique_id   = $(this).attr('data-unique-id');
	 const delSelector = $(this);
	 
	 que = $.ajax
	        ({
		          type     : 'post',
				  url      : 'api.php',
				  data     : 'act=del_plan_item&unique_id='+unique_id,
				  dataType : 'json'
	        });
	 
    que.done(function(data){
	   if(data.error == 0)
	   {  
		  $(delSelector).closest('.planrow').slideUp(700,function(){
		  $(delSelector).closest('.planrow').remove();  
		  });		  
	   }		 
	 });
	 
	que.fail(function(err){
	console.log('Ошибка в функции: delPlan()');	 
	});
}
/* Обработчик события привязанный к кнопкам с классом .action 
он отвечает за отправку ajax-запроса после клика по кнопке*/
   $(document).on('click','.action',request);
   
   $(document).on('click','.delLine',delPlan);
	
   $(document).on('click','#add',addPlan);

});
