<?
//Функция для агента
use Bitrix\Main\Loader;
use Models\RandomCustom;
 
Loader::includeModule('catalog');

function testAgent()
{
    

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

  //Просто считаем количество записей
  $kol=0;
  foreach ($products as $item) {
    $kol++;
  }
  $min =0;
  $max = 10;
  //Получаем массив рандомных значений из спец сервиса
  $rand_arr= RandomCustom::generateIntegers($kol, $min, $max);
 
  //Перебираем записи полученного массива и
  $iter = 0;
  foreach ($products as $product) {
	//pr($product);
    $rand =0;
    //$rand  = \Bitrix\Main\Security\Random::getInt(1,10);
    

    
    //echo $product['ID'].'  NAME'.': '.$product['NAME'].'  QUANTITY'.': '.$product['QUANTITY']./*'  rand: '.$rand.*/'<br>';
    \Bitrix\Catalog\ProductTable::update($product['ID'],array(

        'QUANTITY'=>$rand_arr[$iter],

        'AVAILABLE'=>\Bitrix\Catalog\ProductTable::calculateAvailable($product),

    ));
    $iter++;
   }
	 
	return "testAgent();";
 }

?>