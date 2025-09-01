<?php

namespace Events;
use Bitrix\Main\Event;
use Bitrix\Main\EventManager;
use Bitrix\Main\EventResult;
use Bitrix\Main\Loader;
use Bitrix\Main\Diag\Debug;
use Bitrix\Main\ModuleManager;
use Models\CrmCustomTabManager; 
use Bitrix\Crm\DealTable;
use Bitrix\Main\SystemException;
use Bitrix\Main\Application;
 
//define("LOG_FILENAME", $_SERVER["DOCUMENT_ROOT"]."/log_Iblock.txt");

class CrmHandler
{
     

    const IBLOCK_ID = 29;

    public static function MyonElementAfterUpdate(&$arFields)
    {
       \Bitrix\Main\Loader::includeModule('crm');
         
        $deal_id = $arFields['ID'];
        $arFilter = array("ID"=>$deal_id);
        //Debug::writeToFile($arFilter, 'AfterUpdate_arFilter', "/local/app/Events/log_Iblock3.txt");
     
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
              //Debug::writeToFile($test_arr, 'AfterUpdate', "/local/app/Events/log_Iblock3.txt");
               
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

    /**
     * Before Add
     * @param Event 
     * @return void
     */
    public static function productChange(Event $event)
    {
        global $USER;
        $USER = new \CUser();
        $id = htmlspecialcharsback($event->getParameter("id"));

        if(is_array($id))

            $id = $id["ID"];

        if(!$id)

            return;

        $fields = htmlspecialcharsback($event->getParameter("fields"));
         

        if(!is_array($fields) || !array_key_exists('QUANTITY', $fields))

            return;
        else {
             
            if ($fields['QUANTITY']==0) {
               $el = new \CIBlockElement;
               $PROP = array();
               $PROP['nomenclatura'] = $id; 
               $PROP['quantity'] = mt_rand(1,4);
               $arLoadProductArray = Array(
                  
	             "MODIFIED_BY"    => $USER->GetID(), // элемент изменен текущим пользователем
	             "IBLOCK_SECTION_ID" => false,          // элемент лежит в корне раздела
	             "IBLOCK_ID"      => 30,
	             "PROPERTY_VALUES"=> $PROP,
	             "NAME"           => "Элемент",
	             "ACTIVE"         => "Y",            // активен

              );
            if($PRODUCT_ID = $el->Add($arLoadProductArray))
	          echo "New ID: ".$PRODUCT_ID;

            $arErrorsTmp = array();
            $wfId = \CBPDocument::StartWorkflow(
	          25,//шаблон бизнес процесса
	          ['lists', 'BizprocDocument', $PRODUCT_ID],
	          ['Automatic' => true],
	          $arErrorsTmp
            );
            if (count($arErrorsTmp) > 0)
            {
	            foreach ($arErrorsTmp as $e)
		           $errorMessage .= "[".$e["code"]."] ".$e["message"]."
                 ";
            }
            else {
	          echo "Error: ".$el->LAST_ERROR;
            }


          }

        }
        
 

    }

    /**
     * Before Add
     * @param array 
     * @return bool
     */
    public static function MyonElementBeforeAdd(&$arFields){
       
   
         global $APPLICATION;
        

         //$APPLICATION->SetTitle("Компонент списка таблицы базы данных");
          //file_put_contents("/local/app/Events/log_Iblock8.txt", 'PARAMS: ');
          
        if(is_array($arFields) || !array_key_exists('UF_USER_MASHINE', $arFields) || is_null($arFields['UF_USER_MASHINE'])){
         
          $APPLICATION->ThrowException("Вы не заполнили поле - автомобиль клиента!");
          return false;  
             
        
        }
        else {
            \Bitrix\Main\Loader::includeModule('crm');
            //Debug::writeToFile($arFields['UF_USER_MASHINE'], 'arFields', "/local/app/Events/log_Iblock4.txt");
            $filter = ['UF_USER_MASHINE' => $arFields['UF_USER_MASHINE'], '!STAGE_ID' =>'WON'];
            $result = DealTable::getList([
              'select' => ['ID', 'TITLE', 'UF_USER_MASHINE', 'STAGE_ID', 'OPPORTUNITY', 'CREATED_BY_ID'], // Укажите нужные вам ID полей
              'filter' => $filter, // Фильтр по ID сделки
             ]);
             $kol = 0;
             $kol = $result->getSelectedRowsCount();
              
             if (is_numeric($kol) && $kol>0){
                //Debug::writeToFile($kol, 'kol2', "/local/app/Events/log_Iblock5.txt");
                $APPLICATION->ThrowException("Нельзя записать эту форму у вас есть незакрытые заявки по данному автомобилю!");
                return false;
             }
        }

        return true;
    }

     
}
