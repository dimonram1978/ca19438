<?php
//Автозагрузка классов
 if (file_exists (__DIR__ . '/../../vendor/autoload.php')) {
  require_once __DIR__ . '/../../vendor/autoload.php';
}
/*var_dump(__DIR__ . '/src/autoloader.php');*/
 if (file_exists (__DIR__ . '/src/autoloader.php')) {
    require_once __DIR__ . '/src/autoloader.php';
 }

//Обработка событий
require dirname(__FILE__) . '/event_handler.php';

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
 
 
use Bitrix\Main\EventManager;
$eventManager = EventManager::getInstance();

//Вешаем обработчик на событие создания списка пользовательских свойств OnUserTypeBuildList
$eventManager->addEventHandler(
  'iblock', 'OnIBlockPropertyBuildList',
   [
    'UserTypes\CUserTypeTimesheet',
    'GetUserTypeDescription'
    ]
);

//\Bitrix\Main\UI\Extension::load(['otus_mymodule.greeting-message']);

\Bitrix\Main\UI\Extension::load([
//    'aholin_crmcustomtab.useless_extensions.greeting-message',
//    'dev_helper.log_events',
//    'ajax.all_ajax_handler',
//    'otus_crm.negative_currency',
    'homework.begin_date_button',
]);