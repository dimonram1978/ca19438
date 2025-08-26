<?php

namespace Events;

use Bitrix\Main\EventManager;
use Bitrix\Main\Loader;
use Bitrix\Main\Diag\Debug;

 
//define("LOG_FILENAME", $_SERVER["DOCUMENT_ROOT"]."/log_Iblock.txt");

class IblockHandler
{
    //public static $restrictHour = 17;

    public static function onElementBeforeAdd(&$arFields)
    {
        if ($arFields["IBLOCK_ID"] != 16)
            return $arFields;

        $arFields['NAME'] = 'Изменен в обработчике события ' . date('d.m.Y H:i:s');

       // \Debug\Log::addLog('onElementBeforeAdd');
        //
    }

    public static function onElementAfterUpdate(&$arFields)
    {
       \Bitrix\Main\Loader::includeModule('iblock');
       if ($arFields["IBLOCK_ID"] != 29)
            return $arFields;
        //Debug::writeToFile($arFields, 'IBLOCK_arFields', "/local/app/Events/log_Iblock3.txt");
        $arFields['NAME'] = 'Заявка изменена в ' . date('d.m.Y H:i:s');
        //Получим поля инфоблока Заявки IBLOCK_ID = 29
        $element_id = $arFields['ID'];
        $zayavka_arr = \Bitrix\Iblock\Elements\ElementZayavkiTable::query()
         ->setFilter(['ID'=>$element_id])
         ->setSelect([
           'ID',
           'NAME',		   
           'deal_id',
           'summ',
           'Client_id',
           'responsible'

        ])
        ->fetchCollection();
        
        //Переберем данный объект и получим значение данного элемента инфоблока
        foreach ($zayavka_arr as $item){
        // echo $item->get('ID').'<br/>';        
        // echo $item->get('NAME').'<br/>';
	     $deal_id = $item->get('deal_id')->getValue();
         $summ = $item->get('summ')->getValue();
         $Client_id = $item->get('Client_id')->getValue();
         $responsible = $item->get('responsible')->getValue();
        }
        
        //Debug::writeToFile($Client_id, 'onElementBeforeUpdate', "/local/app/Events/log_Iblock2.txt");
        //Сравним полученные значение с данными а сделке
        if (! empty($deal_id)) {
           \Bitrix\Main\Loader::includeModule("crm");
           $arFilter = array("ID"=>$deal_id);
           
           $entityResult = \CCrmDeal::GetListEx(
	        [],
	        $arFilter, 
            false,
            false,
            [
             'ID',
             'TITLE',
		     'COMPANY_ID',
             'OPPORTUNITY',
             'ASSIGNED_BY_ID',
            ]
           )->fetch();
           
          //Если различаются клиент или сумма то изменяем сделку
          if(($entityResult['COMPANY_ID']!=$Client_id) || ($entityResult['OPPORTUNITY']!=$summ) || ($entityResult['ASSIGNED_BY_ID']!=$responsible)){
              $deal  =  new  \CCrmDeal( false );  //false - не учитывать права
              $arUpdateData = array("COMPANY_ID"=>$Client_id,"OPPORTUNITY"=>$summ,"ASSIGNED_BY_ID"=>$responsible); //поля которые обновляем
               //Debug::writeToFile($arUpdateData, 'onElementBeforeUpdate', "/local/app/Events/log_Iblock2.txt");
              //из под кого обновляем
              $arOptions = array(
                'CURRENT_USER' => \CCrmSecurityHelper::GetCurrentUserID(),
                'REGISTER_SONET_EVENT' => true,
                'ENABLE_SYSTEM_EVENTS' => true,
                'SYNCHRONIZE_STAGE_SEMANTICS' => true,
            );  
            
            $upRes = $deal->Update($deal_id, $arUpdateData, true, true, $arOptions);
          }

        }
        //$arFields['NAME'] = 'Заявка изменена в ' . date('d.m.Y H:i:s');
       //Debug::writeToFile($arFields, 'onElementBeforeUpdate', '\local\log_Iblock.log');
    }

    /*public static function OnAfterIBlockElementAddHandler(&$arFields)
    {
      \Bitrix\Main\Loader::includeModule('iblock');
       if ($arFields["IBLOCK_ID"] != 30)
            return $arFields;
      
    }*/

     

    public static function onElementBeforeDelete(&$id)
    {
        if (date('H') == self::$restrictHour)
        {
            global $APPLICATION;
            $APPLICATION->throwException("Нельзя удалять в ".self::$restrictHour." часов");
            return false;
        }
    }
}
