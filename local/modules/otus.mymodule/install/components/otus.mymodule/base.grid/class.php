<?php

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
 

use Bitrix\Main\Loader;
use Models\ModuleCustomTable as ModuleCustom;
use Bitrix\Crm\DealTable;
use Bitrix\Main\Grid\Options as GridOptions;
use Bitrix\Main\Diag\Debug;
use \Bitrix\Main\UI\PageNavigation;
//use Bitrix\Currency\CurrencyTable;

//Loader::includeModule('currency');

class OtusMyModuleComponent extends \CBitrixComponent
{
  
  const GRID_ID = 'BASE_GRID';

  const PAGE_SIZE = 15;

     private function getGridColumns()
  {
    $columns = [
	     ['id' => 'ID', 'name' => 'ID'], 
         ['id' => 'NAME', 'name' => 'Название машины'],
	       ['id' => 'Client_id', 'name' => 'id клиента'], 
         ['id' => 'car_id', 'name' => 'id машины клиента'], 
         ['id' => 'CAR', 'name' => 'Машина клиента'], 
         ['id' => 'MODEL', 'name' => 'Марка машины'], 
         ['id' => 'Year_prod', 'name' => 'Год производства'], 
         ['id' => 'COLOR', 'name' => 'Цвет машины'],
         ['id' => 'mileage', 'name' => 'Пробег'],
        ];
 
        return $columns;
  }

    //Создам другую функцию т.к. эта не выводит для multyply значения
    private function getList($clientId=null)
    {
       $list = [];
      // Debug::writeToFile($clientId , '$clientId', "/local/app/Events/log_Iblock3.txt");
      if (!empty($clientId) || ($clientId!=null)){ 
        $obj_arr = \Bitrix\Iblock\Elements\ElementGarageTable::getList([
            'select' => ['ID','NAME','Client_id','CAR.ID','CAR.NAME','CAR.model_name','CAR.Year_prod','CAR.COLOR','CAR.mileage'],//,'UF_LASTNAME','UF_PHONE','UF_JOBPOSITION','UF_SCORE'
            'filter' => ['IBLOCK_ELEMENTS_ELEMENT_GARAGE_Client_id_VALUE' => $clientId],
            'runtime' => ['CAR' => [
               'data_type' => \Bitrix\Iblock\Elements\ElementCustomerCarsTable::class,
               'reference' => [
                '=this.car_id.ELEMENT.ID' => 'ref.ID',
                ]
              ],
            ],
        ])->fetchCollection();
      }
      else{
         $obj_arr = \Bitrix\Iblock\Elements\ElementGarageTable::getList([
            'select' => ['ID','NAME','Client_id','CAR.ID','CAR.NAME','CAR.model_name','CAR.Year_prod','CAR.COLOR','CAR.mileage'],//,'UF_LASTNAME','UF_PHONE','UF_JOBPOSITION','UF_SCORE'
            'runtime' => ['CAR' => [
               'data_type' => \Bitrix\Iblock\Elements\ElementCustomerCarsTable::class,
               'reference' => [
                '=this.car_id.ELEMENT.ID' => 'ref.ID',
                ]
              ],
            ],
        ])->fetchCollection(); 

      }
        foreach ($obj_arr as $key => $record){
           
	       $list[] = array('data' =>  [
             'ID' => $record->getid(), 
             'NAME' => $record->getName(),
             'Client_id' => $record->getClient_id()->getvalue(),
             'Car_id' => $record->get('CAR')->getId(),
			       'CAR' => $record->get('CAR')->getname(),
             'MODEL' => $record->get('CAR')->getmodel_name()->getvalue(),
             'Year_prod' => $record->get('CAR')->getYear_prod()->getvalue(),
             'COLOR' => $record->get('CAR')->getColor()->getvalue(),
             'mileage' => $record->get('CAR')->getmileage()->getvalue(),
            ]);  

        }
         
         
        return $list;
    }
    // Функция выводит массив для multipky значаний свойств
    private function getList_multiply($clientId=null)
    {
       
       
      if (!empty($clientId) || ($clientId!=null)){ 
        $obj_arr = \Bitrix\Iblock\Elements\ElementGarageTable::getList([
            'select' => ['ID','NAME','Client_id','Car_id','car_id.ELEMENT.NAME','car_id.ELEMENT.model_name','car_id.ELEMENT.Year_prod','car_id.ELEMENT.COLOR','car_id.ELEMENT.mileage'],
            'filter' => ['IBLOCK_ELEMENTS_ELEMENT_GARAGE_Client_id_VALUE' => $clientId],
            'runtime' => ['CAR' => [
               'data_type' => \Bitrix\Iblock\Elements\ElementCustomerCarsTable::class,
               'reference' => [
                '=this.car_id.ELEMENT.ID' => 'ref.ID',
                ]
              ],
            ],
        ])->fetchCollection();
      }
      else{
         $obj_arr = \Bitrix\Iblock\Elements\ElementGarageTable::getList([
            'select' => ['ID','NAME','Client_id','Car_id','car_id.ELEMENT.NAME','car_id.ELEMENT.model_name','car_id.ELEMENT.Year_prod','car_id.ELEMENT.COLOR','car_id.ELEMENT.mileage'],
            'runtime' => ['CAR' => [
               'data_type' => \Bitrix\Iblock\Elements\ElementCustomerCarsTable::class,
               'reference' => [
                '=this.car_id.ELEMENT.ID' => 'ref.ID',
                ]
              ],
            ],
        ])->fetchCollection(); 

      }
        $list = [];
        foreach ($obj_arr as $key => $record){
           
	         
          foreach($record->get('Car_id')->getAll() as $prItem) { 
            $list[] = array('data' =>  [
		          'ID' => $record->getid(), 
		          'NAME' => $record->getName(),
		          'Client_id' => $record->getClient_id()->getvalue(),
              'Car_id' => $prItem->getElement()->getid(),
		          //'CAR' => $prItem->getElement()->getName(),
              'CAR' => '<a href="javascript:void(0)" onclick="openFormPopup('.$prItem->getElement()->getid().')" class="recall">'.$prItem->getElement()->getName().'</a>',
              //'CAR' => '<a href="javascript:void(0)" onclick="ajaxcontentload('.$prItem->getElement()->getid().')" class="recall">'.$prItem->getElement()->getName().'</a>',
              'MODEL' => $prItem->getElement()->getmodel_name()->getvalue(),
              'Year_prod' => $prItem->getElement()->getYear_prod()->getvalue(),
              'COLOR' => $prItem->getElement()->getColor()->getvalue(),
              'mileage' => $prItem->getElement()->getmileage()->getvalue(),
          ]); 
           
        }
      }   

      
        // Debug::writeToFile($list, 'list', "/local/app/Events/log_Iblock7.txt");
        return $list;
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
               
            //Debug::writeToFile($this->arParams , '$arParams', "/local/app/Events/log_Iblock3.txt");
             if (isset($this->arParams['clientId'])) {
                
               $clientId = $this->arParams['clientId'];
               
             } 
             else {$clientId=null;} 
         
             
             
            $page_size = $this->arParams['PAGE_SIZE'] ?? self::PAGE_SIZE;
             
             
 
            $this->arResult['NAV'] = $nav;
    
            $this->arResult['GRID_ID'] = $grid_id;
            $this->arResult['GRID_FILTER'] = $grid_filter;
            $this->arResult['GRID_COLUMNS'] = $this->getGridColumns();
            //$this->arResult['ROWS'] = $grid_rows;
            //$this->arResult['ROWS'] = $this->getList($clientId); 
            $this->arResult['ROWS'] = $this->getList_multiply($clientId); 

            // подключаем шаблон
            $this->IncludeComponentTemplate();

        }
        catch (SystemException $e)
        {
            ShowError($e->getMessage());
        }

    }


} 
 