<?php

namespace Events;

use Bitrix\Main\EventManager;
use Bitrix\Main\Loader;
use Bitrix\Main\Diag\Debug;
use Bitrix\Main\ModuleManager;
 
//define("LOG_FILENAME", $_SERVER["DOCUMENT_ROOT"]."/log_Iblock.txt");

class CrmHandler
{
     

    const IBLOCK_ID = 29;

    public static function MyonElementBeforeUpdate(&$arFields)
    {
       \Bitrix\Main\Loader::includeModule('crm');
         
        $deal_id = $arFields['ID'];
        $arFilter = array("ID"=>$deal_id);
        //Debug::writeToFile($arFilter, 'BeforeUpdate_arFilter', "/local/app/Events/log_Iblock3.txt");
        //Данные сделки  
        $entityResult = \CCrmDeal::GetListEx(
	        [],
	        $arFilter, 
            false,
            false,
            [
             'ID',
             'TITLE',
		         'COMPANY_ID',
             'OPPORTUNITY',//сумма
             'ASSIGNED_BY_ID',//ответственный
            ]
        )->fetch(); 
         
        //тк получить id заявки изнутри сделки непонятно как то будем отбирать все заявки и искать нашу сделку
        $zayavka_arr = \Bitrix\Iblock\Elements\ElementZayavkiTable::query()
        // ->setFilter(['deal_id'=>$deal_id]) 
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
        $curr_deal_id = 0;$zayavkaId=0;$summ =0;$Client_id=0;$responsible=0;
        foreach ($zayavka_arr as $item){
        // echo $item->get('ID').'<br/>';        
        // echo $item->get('NAME').'<br/>';
         
	     $curr_deal_id = $item->get('deal_id')->getValue();
          
         if ($curr_deal_id == $deal_id ) {
              //
              $zayavkaId = $item->getid();
              $summ = $item->get('summ')->getValue();
              $Client_id = $item->get('Client_id')->getValue();
              $responsible = $item->get('responsible')->getValue();
              $test_arr = ['zayavkaId' => $zayavkaId, 'summ' => $summ, 'Client_id' => $Client_id, 'responsible' => $responsible ];
              //Debug::writeToFile($test_arr, 'BeforeUpdate', "/local/app/Events/log_Iblock3.txt");
               
            //Если различаются данные в найденном инфоблоке то меняем его
            if(($Client_id!=$entityResult['COMPANY_ID']) || ($summ != $entityResult['OPPORTUNITY']) || ($responsible != $entityResult['ASSIGNED_BY_ID'])){
               
               //Поменять поле Name как выяснилось не так то просто. Для этого надо использовать метод update
              $name_arr = ["NAME" =>  'Заявка была изменена в '. date('d.m.Y H:i:s')];
              \Bitrix\Iblock\Elements\ElementZayavkiTable::update($zayavkaId, $name_arr);
              $iblockId = CrmHandler::IBLOCK_ID;
    
              //Для замены другиз свойств есть куда более простой метод SetPropertyValuesEx
              $SetProperty_arr = [
                "Client_id" => $entityResult['COMPANY_ID'],
                "summ" => $entityResult['OPPORTUNITY'],
                "responsible" => $entityResult['ASSIGNED_BY_ID']
              ]; 
              
              if(\Bitrix\Main\Loader::IncludeModule('iblock')) {
                 
	            \CIBlockElement::SetPropertyValuesEx($zayavkaId, $iblockId, $SetProperty_arr);

              } 
               
            }  
            break;
         }
         
        }
        
         
    }

    public static function MyonElementAfterUpdate(&$arFields)
    {
       \Bitrix\Main\Loader::includeModule('crm');
         
        $deal_id = $arFields['ID'];
        $arFilter = array("ID"=>$deal_id);
        Debug::writeToFile($arFilter, 'AfterUpdate_arFilter', "/local/app/Events/log_Iblock3.txt");
        //Данные сделки  
        $entityResult = \CCrmDeal::GetListEx(
	        [],
	        $arFilter, 
            false,
            false,
            [
             'ID',
             'TITLE',
		         'COMPANY_ID',
             'OPPORTUNITY',//сумма
             'ASSIGNED_BY_ID',//ответственный
            ]
        )->fetch(); 
         
        //тк получить id заявки изнутри сделки непонятно как то будем отбирать все заявки и искать нашу сделку
        $zayavka_arr = \Bitrix\Iblock\Elements\ElementZayavkiTable::query()
        // ->setFilter(['deal_id'=>$deal_id]) 
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
        $curr_deal_id = 0;$zayavkaId=0;$summ =0;$Client_id=0;$responsible=0;
        foreach ($zayavka_arr as $item){
        // echo $item->get('ID').'<br/>';        
        // echo $item->get('NAME').'<br/>';
         
	     $curr_deal_id = $item->get('deal_id')->getValue();
          
         if ($curr_deal_id == $deal_id ) {
              //
              $zayavkaId = $item->getid();
              $summ = $item->get('summ')->getValue();
              $Client_id = $item->get('Client_id')->getValue();
              $responsible = $item->get('responsible')->getValue();
              $test_arr = ['zayavkaId' => $zayavkaId, 'summ' => $summ, 'Client_id' => $Client_id, 'responsible' => $responsible ];
              Debug::writeToFile($test_arr, 'AfterUpdate', "/local/app/Events/log_Iblock3.txt");
               
            //Если различаются данные в найденном инфоблоке то меняем его
            if(($Client_id!=$entityResult['COMPANY_ID']) || ($summ != $entityResult['OPPORTUNITY']) || ($responsible != $entityResult['ASSIGNED_BY_ID'])){
               
               //Поменять поле Name как выяснилось не так то просто. Для этого надо использовать метод update
              $name_arr = ["NAME" =>  'Заявка была изменена в '. date('d.m.Y H:i:s')];
              \Bitrix\Iblock\Elements\ElementZayavkiTable::update($zayavkaId, $name_arr);
              $iblockId = CrmHandler::IBLOCK_ID;
    
              //Для замены другиз свойств есть куда более простой метод SetPropertyValuesEx
              $SetProperty_arr = [
                "Client_id" => $entityResult['COMPANY_ID'],
                "summ" => $entityResult['OPPORTUNITY'],
                "responsible" => $entityResult['ASSIGNED_BY_ID']
              ]; 
              
              if(\Bitrix\Main\Loader::IncludeModule('iblock')) {
                 
	            \CIBlockElement::SetPropertyValuesEx($zayavkaId, $iblockId, $SetProperty_arr);

              } 
               
            } 
            break;
         }
         
        }
        
         
    }
}
