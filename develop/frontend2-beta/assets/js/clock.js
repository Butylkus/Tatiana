$(document).ready(function()
{


req = $.ajax
      ({
		   type       : 'post',
		   url        : 'api.php',
		   data       : 'act=time_server',
		   dataType   : 'json' 
	  });

	  
req.done(function(time){
	
  const h = time.hour;
  const m = time.minutes;
  const s = time.seconds;
  
  const startDate = new Date(2017, 0, 1, h, m, s);
  
  const root = document.querySelector('#clock');
  
  const pad = function(value){
  const charsLeft = 2 - String(value).length;

  if(charsLeft > 0){
    const padding   = (new Array(charsLeft)).fill('0');
    return `${padding}${value}`
  } else {
    return value;
  }
}

  const getTime = function(date){
  date = new Date(date);
  const time = [
    date.getHours(),
    date.getMinutes(),
    date.getSeconds()
  ].map(n => pad(n)).join(':');
  
  return time;
}

  const renderTime = function(){
  let passed = 0;

  let start = Date.now();
  
    const counter = function(){
          const timeDiff = Date.now() - start;
          if(timeDiff != 1000)
		    {
                 start = Date.now();
                 passed += timeDiff;
                 const time = getTime(startDate.getTime() + passed);
                 root.textContent = time;
            }
    
    requestAnimationFrame(counter);
  }
  
  counter();
}

  renderTime();	
});
});