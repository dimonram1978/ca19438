<?php
//Автозагрузка классов
 if (file_exists (__DIR__ . '/../../vendor/autoload.php')) {
  require_once __DIR__ . '/../../vendor/autoload.php';
}
/*var_dump(__DIR__ . '/src/autoloader.php');*/
 if (file_exists (__DIR__ . '/src/autoloader.php')) {
    require_once __DIR__ . '/src/autoloader.php';
    
 }
 include_once __DIR__ . '/classes/LKIblock.php';

 include_once __DIR__ . '/classes/Dadata.php';

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
    //'aholin_crmcustomtab.useless_extensions.greeting-message',
    //'dev_helper.log_events',
   //'ajax.all_ajax_handler',
//    'otus_crm.negative_currency',
    'homework.begin_date_button',
   
]);

 

// обработчик событий инфоблока
 //$eventManager->addEventHandler("iblock", "OnBeforeIBlockElementAdd", ['Events\IblockHandler', 'onElementBeforeAdd']);
 $eventManager->addEventHandler("iblock", "OnBeforeIBlockElementUpdate", ['Events\IblockHandler', 'onElementBeforeUpdate']);
 //$eventManager->addEventHandler("iblock", "OnAfterIBlockElementUpdate", ['Events\IblockHandler', 'onElementAfterUpdate']);
 //$eventManager->addEventHandler("iblock", "OnBeforeIBlockElementDelete", ['Events\IblockHandler', 'onElementBeforeDelete']);


// обработчик событий CRM

//$eventManager->addEventHandler("crm","OnBeforeCrmDealUpdate", ['Events\CrmHandler', 'MyonElementBeforeUpdate']);
$eventManager->addEventHandler("crm","OnAfterCrmDealUpdate", ['Events\CrmHandler', 'MyonElementAfterUpdate']);
 //$eventManager->addEventHandler("crm","\Bitrix\Crm\Timeline\Entity\Timeline::OnBeforeAdd", ['Events\OrmHandler', 'onTimelineBeforeAdd']);
 //$eventManager->addEventHandler("crm","\Bitrix\Crm\Timeline\Entity\Timeline::OnBeforeAdd", ['Events\OrmHandler', 'onTimelineBeforeUpdate']);


// обработчик событий highload-блоков
// $entityName = Events\HlblockHandler::getHlIdByName('BooksList');
// $eventManager->addEventHandler('', "{$entityName}onBeforeAdd", ['Events\HlblockHandler', 'OnBeforeHLEAdd']);


// $eventManager = EventManager::getInstance();
// //$eventManager->addEventHandlerCompatible('main', 'OnProlog', ['Events\DuplicateCounter\Handler', 'duplicateCounter']);
// $eventManager->addEventHandlerCompatible('main', 'OnEpilog', ['Events\DuplicateCounter\Handler', 'duplicateCounter']);