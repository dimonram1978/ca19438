<? CUtil::InitJSCore(array(‘ajax’)); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Quick start</title>
  <meta charset="UTF-8">
  <script src="//api.bitrix24.com/api/v1/"></script> 
  <script src="//cdn.bitrix24.ru/bitrix/js/main/core/core.min.js"></script>
</head>
<body>
  <div id="name">tra ta ta</div>
  
  <script>
    
     var name1 = document.getElementById('name');
     if (!BX24) {
        name1.innerHTML = 'Библиотека Bitrix24 не найдена!';
    } else {
      alert(1);
      B24.init(function(){
         console.log('567');
         
      });

       
    }
     
  </script>
</body>
</html>