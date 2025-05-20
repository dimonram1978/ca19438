<?php
require_once $_SERVER['DOCUMENT_ROOT']. '/bitrix/header.php';
$date = date('d.m.Y G:i:s');
//file_put_contents($_SERVER['DOCUMENT_ROOT'].'/logs/task_1.log', $date . PHP_EOL, FILE_APPEND);
file_put_contents('logs/task_1.log', $date . PHP_EOL, FILE_APPEND);
require_once $_SERVER['DOCUMENT_ROOT']. '/bitrix/footer.php';