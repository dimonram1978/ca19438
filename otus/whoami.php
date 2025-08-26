<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
?>
<!DOCTYPE HTML PUBLIC>
<html lang="en">
<head>
  <meta charset="UTF-8"></meta>
  <title>Quick start. Local application</title>
</head>
<body>
  <div id="name"></div>
  <script src="//api.bitrix24.com/api/v1/"></script>
  <script> 
   
    BX24.init(function(){
      BX24.callMethod('user.current', {}, function(res){
       var name = document.getElementById('name');
       name.innerHTML = res.data().NAME+ ' ' + res.data.LAST_NAME;
       console.log(res.data());
       });
      
      console.log('B24 SDK is ready', BX24.isAdmin());   
  
    });
  </script>
</body>
</html>