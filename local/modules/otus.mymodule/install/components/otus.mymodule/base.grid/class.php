<?php

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
 

use Bitrix\Main\Loader;
use Models\ModuleCustomTable as ModuleCustom;
use Bitrix\Crm\DealTable;
use Bitrix\Main\Grid\Options as GridOptions;

use \Bitrix\Main\UI\PageNavigation;
//use Bitrix\Currency\CurrencyTable;

//Loader::includeModule('currency');

class OtusMyModuleComponent extends \CBitrixComponent
{
  
  const GRID_ID = 'BASE_GRID';

  const PAGE_SIZE = 15;

  public function onPrepareComponentParams($arParams) {
       // тут пишем логику обработки параметров, дополнение к параметрам по умолчанию
       return $arParams;
  }

  private function getListMassiv($DEAL_ID=null)
  {

      
        /*$list = [];
        $data = ModuleCustom::getList([
            'select'=>[
                       'id',
		                   'cars_id',
                       'CARS',
                       'deal_id',],
            'filter' => ['deal_id' => $DEAL_ID],

        ])->fetchCollection();*/
      if (!empty($DEAL_ID) || ($DEAL_ID!=null)){ 
        $list = [];
        $data = ModuleCustom::getList([
            'select'=>[
                       'id',
		                   'cars_id',
                       'CARS',
                       'deal_id',],
            'filter' => ['deal_id' => $DEAL_ID],

        ])->fetchCollection();
         
     }
     else{
       $list = [];
        $data = ModuleCustom::getList([
            'select'=>[
                       'id',
		                   'cars_id',
                       'CARS',
                       'deal_id',],
            //'filter' => ['deal_id' => $DEAL_ID],

        ])->fetchCollection();

     }
      


     return $data;
  } 

   
    public function executeComponent() {

        try
        {
            
              
            $grid_id = self::GRID_ID;
            $grid_options = new GridOptions($grid_id);
 

            $sort = $grid_options->GetSorting([
            	'sort' => ['ID' => 'DESC'],
            	'vars' => ['by' => 'by', 'order' => 'order']
            ]);
            $pageSizes = [ 
	             ['NAME' => "5", 'VALUE' => '5'], 
	             ['NAME' => '10', 'VALUE' => '10'], 
		           ['NAME' => '20', 'VALUE' => '20'], 
	             ['NAME' => '50', 'VALUE' => '50'], 
		           ['NAME' => '100', 'VALUE' => '100'] 
	            ];
              $navOption = $grid_options->GetNavParams();
	             $nav = (new PageNavigation('our_custom_grid_nav'))
		             ->allowAllRecords(false)
		             ->setPageSize($navOption['nPageSize'])
		             ->setPageSizes($pageSizes)
		             ;

	             $nav->initFromUri();
               
              
             if (isset($this->arParams['DEAL_ID'])) {
                
               $DEAL_ID = $this->arParams['DEAL_ID'];
               
             } 
             else {$DEAL_ID=null;} 
         
            $elements = $this->getEntity($DEAL_ID);
 
             
            $page_size = $this->arParams['PAGE_SIZE'] ?? self::PAGE_SIZE;
             
            $grid_rows = [];

            foreach ($elements as $element) {
              $prepared_element = $this->getPreparedElement($element);

             // $actions = $this->getElementActions($element);

             $row = [
               'id' => $element['ID'],
               'data' => $element,
               'columns' => $prepared_element,
               'editable' => 'Y',
               //'actions' => $actions
              ];

             $grid_rows[] = $row;
            }
 
            $this->arResult['NAV'] = $nav;
    
            $this->arResult['GRID_ID'] = $grid_id;
            $this->arResult['GRID_FILTER'] = $grid_filter;
            $this->arResult['GRID_COLUMNS'] = $this->getGridColumns();
            $this->arResult['ROWS'] = $grid_rows;

    
            // подключаем шаблон
            $this->IncludeComponentTemplate();

        }
        catch (SystemException $e)
        {
            ShowError($e->getMessage());
        }

    }

    public function getPreparedElement($fields)
    {
       //$fields['ACTIVE'] = $fields['ACTIVE'] == 'Y' ? 'Пользователь активен' : 'Пользователь не активен';
  
       return $fields;
    }

    public function getEntity($DEAL_ID)
    {
       
             $ArrObj = $this->getListMassiv($DEAL_ID);
             $ArrMass = [];
             foreach ($ArrObj as $key => $record) {
                $arSelect = array(
                   "ID",
                   "TITLE",
                   "COMPANY_ID",    
                    //UF_CRM_PROGRAMMER, //пользовательское свойство   
                   "STAGE_ID"
                );            
                $arFilter = array(
                 "ID"=> $record->getdeal_id(), //выбираем определенную сделку по ID
                );


                $arDeals=DealTable::getList([
                  'order'=>['ID' => 'DESC'],
                  'filter'=>$arFilter,
                  'select'=>$arSelect,
                  //'cache' => ['ttl' => 3600]
                ])->fetch();
                 //pr($arDeals);
                 
                if (isset ($arDeals['ID'])) {$deal_id=$arDeals['ID'];}
                if (isset ($arDeals['TITLE'])) {$deal_TITLE=$arDeals['TITLE'];} 
                if (isset ($arDeals['COMPANY_ID'])) {$deal_COMPANY_ID=$arDeals['COMPANY_ID'];} 
                
                if (isset ($arDeals['STAGE_ID'])) {$deal_STAGE_ID=$arDeals['STAGE_ID'];}
                 
              
                $ArrMass[] = [
                  "ID" => $record->getid(), 
                  "cars_id" => $record->getcars_id(),
                  "cars_name" => $record->getCars()->getName(),
                  "deal_id" => $deal_id,
                  "deal_TITLE" => $deal_TITLE,
                  "deal_COMPANY_ID" => $deal_COMPANY_ID,
                  "deal_STAGE_ID" => $deal_STAGE_ID
                ];
                 
             }
  //pr($ArrMass);
       return $ArrMass;
    }


 private function getGridColumns()
 {
    $columns = [
	   ['id' => 'ID', 'name' => 'ID', 'sort' => 'ID', 'default' => true], 
	   ['id' => 'cars_id', 'name' => 'cars_id', 'sort' => 'cars_id', 'default' => true], 
	   ['id' => 'cars_name', 'name' => 'Марка машины', 'sort' => 'cars_name', 'default' => true], 
	   ['id' => 'deal_id', 'name' => 'deal_id', 'sort' => 'deal_id', 'default' => true], 
	   ['id' => 'deal_TITLE', 'name' => 'Сделка', 'sort' => 'deal_TITLE', 'default' => true], 
	   ['id' => 'deal_COMPANY_ID', 'name' => 'deal_COMPANY_ID', 'sort' => 'deal_COMPANY_ID', 'default' => true],
     ['id' => 'deal_STAGE_ID', 'name' => 'deal_STAGE_ID', 'sort' => 'deal_STAGE_ID', 'default' => true],  
   ];

    return $columns;
 }
 private function getFilterFields()
{
    $filterFields = [
        [
            
            'cars_name' => 'Марка машины',
            'deal_TITLE' => 'Сделка',
            'default' => true
        ],
         
    ];

    return $filterFields;
}
 
} 
 