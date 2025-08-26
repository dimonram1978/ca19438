<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

$APPLICATION->SetTitle("Компонент списка таблицы базы данных");

use Bitrix\Main\Type;

// use Models\Lists\CarsPropertyValuesTable as CarsTable;
// use Models\HospitalClientsTable as Clients;

// use Models\BookTable as Books;
// use Models\PublisherTable as Publishers;
// use Models\AuthorTable as Authors;
// use Models\WikiprofileTable as Wikiprofiles;

//use Models\ClientsTable as Clients; 

/*// получаем список клиентов
$collection = Clients::getList([
    'select' => [
        'ID',
        'UF_NAME',
        'UF_LASTNAME',
        'UF_PHONE',
        'UF_JOBPOSITION',
        'UF_SCORE'
    ]
])->fetchCollection();
    
foreach ($collection as $key => $item) {
    echo $item->getUfName().' '.$item->getUfLastname().' '.$item->getUfPhone().' '.$item->getUfJobposition().' '.$item->getUfScore().'<br />';
}*/

 

// получаем список клиентов в виде массива
/*$limit = 1;
$page = 2;
$offset = $limit * ($page-1);
$data = Clients::getList([
    'select' => [
        'ID',
        'UF_NAME',
        'UF_LASTNAME',
        'UF_PHONE',
        'UF_JOBPOSITION',
        'UF_SCORE'
    ],
    'order' => [
        'ID' => 'ASC'
    ],
    'limit' => $limit,
    'offset' =>$offset

])->fetchAll();
    
foreach ($data as $key => $item) {
    pr($item);
}*/
?>
<?

/*$APPLICATION->IncludeComponent(
	//"otus:mashines.views",
    "otus:base.grid",
    //"otus:garage.table",
    ".default",
	//"list",
	Array(
	//"COMPONENT_TEMPLATE" => ".default",
		//"NUM_PAGE" => "1",
		//"SHOW_CHECKBOXES" => "Y"
	)
);*/

use Bitrix\Main\Loader;
Loader::includeModule('catalog');

echo '<br>';
$products = \Bitrix\Catalog\ProductTable::getList(array(
	//'filter' => ['IBLOCK_ELEMENT.IBLOCK_ID' => 64, '!Store.STORE_ID' => false],IBLOCK_SECTION_ID
	'filter' => ['!CATALOG_PRODUCT_IBLOCK_SECTION_ID_IBLOCK_SECTION_ID' => 14], 
	'select' => ['ID','NAME'=>'IBLOCK_ELEMENT.NAME','QUANTITY','IBLOCK_SECTION_ID'],
	//'select' => ['*'],
    'runtime' => ['IBLOCK_SECTION_ID' => [
               'data_type' => \Bitrix\Iblock\Elements\ElementTovariTable::class,
               'reference' => [
                '=this.ID' => 'ref.ID',
                ]
              ],
            ],
))->fetchAll();


//Просто считаем количество
$kol=0;
foreach ($products as $item) {
  $kol++;
}
 echo $kol.'<br>';


foreach ($products as $product) {
	//pr($product);
    $rand =0;
    //$rand  = \Bitrix\Main\Security\Random::getInt(1,10);
    

    
    echo $product['ID'].'  NAME'.': '.$product['NAME'].'  QUANTITY'.': '.$product['QUANTITY'].'<br>';
}     
 
?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>