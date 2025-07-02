<?php

spl_autoload_register(function ($className) {
    $classPath = str_replace('\\', '/', $className);
    $file = __DIR__."/$classPath.php";
    //pr( $file);
    if (file_exists($file)) {
        include_once $file;
    }
});

use Bitrix\Main\Loader;
//Папка с пользовательскими классами
define('APP_CLASS_FOLDER', '/local/php_interface/app/');
//Автозагрузка наших классов
Loader::registerAutoLoadClasses(null, [
    'app\usertypes\CUserTypeTimesheet' => APP_CLASS_FOLDER . 'usertypes/CUserTypeTimesheet.php',
]);
