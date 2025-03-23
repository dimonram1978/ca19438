<?php
declare(strict_types = 1);
require_once $_SERVER['DOCUMENT_ROOT']. '/bitrix/header.php';

\Otus\Diag\FileExceptionHandlerLogCustom::print();


function division(float $a, float $b): int
{

    return $a / $b;

}
division (a:3, b:2);

 
require_once $_SERVER['DOCUMENT_ROOT']. '/bitrix/footer.php';