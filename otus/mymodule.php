<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");


$APPLICATION->IncludeComponent(
	//"otus:otus.mymodule1", 
    "otus:base.grid", 
	".default", 
	array(
		//"COMPONENT_TEMPLATE" => ".default",
		//"SHOW_CHECKBOXES" => "Y",
		//"NUM_PAGE" => "1"
	),
	false
);
/*use Models\ModuleCustomTable as ModuleCustom;
$data = ModuleCustom::getList([
            'select'=>[
                       'id',
		               'cars_id',
                       'CARSNAME',],
            //'filter' => ['CURRENCY' => $CURRENCY],

        ])->fetchAll();
 pr($data);  */      
/*$APPLICATION->SetTitle("Компонент для проверки вывода таблицы");
$APPLICATION->SetAdditionalCSS('/doctors/style.css');

 

$APPLICATION->IncludeComponent(
	"otus:otus.mymodule", 
	".default", 
	array(
		//"COMPONENT_TEMPLATE" => ".default",
		//"SHOW_CHECKBOXES" => "Y",
		//"NUM_PAGE" => "1"
	),
	false
);*/


/*$APPLICATION->SetTitle('Вывод связанных полей 333');

use Models\ModuleCustomTable as ModuleCustom;

$arr = ModuleCustom::getList([       
		'select'=>[
          'id',
		  'cars_id',
          'CARSNAME',
 		  //'MANUFACTURER_ID'=>'MANUFACTURER_ID'
      ]
  //])->fetchAll();
  ])->fetchCollection();
 
  pr($arr);*/


/* use Bitrix\Crm\DealTable;

 $arSelect = array(
   "ID",
   "TITLE",
   "COMPANY_ID",    
   //UF_CRM_PROGRAMMER, //пользовательское свойство   
   "STAGE_ID"
);            
 


$arDeals=DealTable::getList([
            'order'=>['ID' => 'DESC'],
            //'filter'=>$arFilter,
            'select'=>$arSelect,
            //'cache' => ['ttl' => 3600]
        ])->fetchAll();
 pr($arDeals);


        $deals=[];
        foreach($arDeals as $deal){
            $deals[$deal['ID']]=$deal;
        }
pr($deals);*/
 /*use Models\BookTable as Books;

// вывод данных по списку записей из инфоблока Автомобили
$cars = Books::getList([       
		'select' => [            
                'id', 
                'name', 
                'text', 
                'publish_date',
                'ISBN',
                'AUTHOR',
                //'PUBLISHERS',
                'SHOPS',
                'PRINTINGHOUSE',
        
            ]
  ])->fetchAll();

 pr($cars);*/



 ?> 
<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>