<?php

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
 

use Bitrix\Main\Loader;
use Bitrix\Currency\CurrencyTable;

Loader::includeModule('currency');

class CurrencyViewsComponent extends \CBitrixComponent
{
  
  public function onPrepareComponentParams($arParams) {
       // тут пишем логику обработки параметров, дополнение к параметрам по умолчанию
       return $arParams;
  }

   

  private function getListMassiv($CURRENCY=null)
  {

     if (!empty($CURRENCY) || ($CURRENCY!=null)){ 
        $list = [];
        $data = \Bitrix\Currency\CurrencyTable::getList([
            'select' => ['CURRENCY','AMOUNT'],
            'filter' => ['CURRENCY' => $CURRENCY],

        ])->fetch();
     }
     else{
        $list = [];
        $data = \Bitrix\Currency\CurrencyTable::getList([
            'select' => ['CURRENCY','AMOUNT'],
            //'order' => ['ID' => 'ASC'],

        ])->fetchAll();

     }
        /*while ($item = $data->fetch()) {
            $list[] = array('data' => $item);
        }*/

     return $data;
  } 

  static  function name_currency($CURRENCY){
       $name = "";
  
     switch ($CURRENCY){
      case "RUB": $name = "рубль РФ"; break;
      case "USD": $name = "доллар США"; break;
      case "EUR": $name = "ЕВРО EC"; break;
      case "UAH": $name = "Гривна из 404"; break;
      case "BYN": $name = "Юань КНР"; break;
      }

   return $name;

}

    public function executeComponent() {

        try
        {
            /*if (isset($_GET)) {
              if (isset($_GET['CURRENCY'])) {
                $CURRENCY = $_GET['CURRENCY'];
               }
            }*/
           /* if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["comment_text"])) {
               $this->addComment($_POST["comment_text"]);
            }*/
             if (isset($_GET['currency-submit'])) {
               
               $this->arResult['LISTS_OTBOR'] = $this->getListMassiv($_GET['CURRENCY']);
             }
             
             $this->arResult['LISTS'] = $this->getListMassiv();// получаем записи таблицы
            // подключаем шаблон
            $this->IncludeComponentTemplate();

        }
        catch (SystemException $e)
        {
            ShowError($e->getMessage());
        }

    }

} 
 