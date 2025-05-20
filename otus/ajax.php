<?php
  require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
use Bitrix\Main\Loader;
use Bitrix\Currency\CurrencyTable;
Loader::includeModule('currency');
   
   $AMOUNT = null;
   if ( isset($_GET['cur_uniid'])) {
       //echo $_GET['cur_uniid'];
       $currencyData2 = [];
       $currencyData2 = CurrencyTable::getList([
       'select' => ['CURRENCY','AMOUNT'],
       'filter' => ['CURRENCY' => $_GET['cur_uniid']],
        
       ])->fetch();
       $AMOUNT = $currencyData2['AMOUNT'];
    } 
    echo $AMOUNT;


?>