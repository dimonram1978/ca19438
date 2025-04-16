<?php
 if (file_exists (__DIR__ . '/../../vendor/autoload.php')) {
  require_once __DIR__ . '/../../vendor/autoload.php';
}
/*var_dump(__DIR__ . '/src/autoloader.php');*/
 if (file_exists (__DIR__ . '/src/autoloader.php')) {
    require_once __DIR__ . '/src/autoloader.php';
 }

 // автолоадер проекта
include_once __DIR__ . '/../app/autoload.php';
 
 function pr($var, $type = false){
    echo '<PRE style=@font-size: 10px; border: 1px solid #000; background: #FF; text-align:left; color:#000;">';
    if($type)
      var_dump($var);
    else
      print_r($var);
 echo '</PRE>';

 }