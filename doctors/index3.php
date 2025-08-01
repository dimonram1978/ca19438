<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
/** @global $APPLICATION */
$APPLICATION->SetTitle('Врачи');
$APPLICATION->SetAdditionalCSS('/doctors/style.css');

// модели работающие с инфоблоками
use Models\ModuleCustomTable as ModuleCustom;
//use Models\ReservationTable as Reservation;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\Entity\Query\Join;
use Bitrix\Main\Entity\Query;

$iblockId = 29;
              
              //CModule::IncludeModule('iblock');
              //Для замены другиз свойств есть куда более простой метод SetPropertyValuesEx
              $SetProperty_arr = [
                "Client_id" => 17,
                "summ" => 40000,
                "responsible" => 1
              ];  
              //\Bitrix\Main\Loader::includeModule('iblock');
              
              CIBlockElement::SetPropertyValuesEx(180, $iblockId, $SetProperty_arr);
//use \Bitrix\Iblock\Elements\ElementReservationTable as Reservation;
/*use \Bitrix\Iblock\Elements\ElementCarsTable as CarsTable;
$cars = CarsTable::query()
    ->setSelect([
       'id',
       'NAME',
       'Iblock.ID',
        
       'Iblock.NAME',
       'manufacturer_id.ELEMENT',
       'MANUFACTURER'
    ])
    //->setOrder(['COUNTRY' => 'desc'])
    ->registerRuntimeField(
        null,
        new \Bitrix\Main\Entity\ReferenceField(
            'MANUFACTURER',
            'Bitrix\Iblock\Elements\ElementMANUFACTURERTable',
            ['=this.IBLOCK_ELEMENTS_ELEMENT_CA_RS_manufacturer_id_ELEMENT_ID' => 'ref.ID']
        )
    )
    ->fetchCollection();


 foreach ($cars as $item): 
    //var_dump($item->getIblock());
   echo '<pre>'.$item->getid().'--'.$item->getName().'--'.$item->getIblock()->getName().'--'.$item->get('MANUFACTURER')->getName().'--'.'</pre>';
 endforeach;*/
/*use \Bitrix\Iblock\Elements\ElementReservationTable as ReservationTable;
$reserv = ReservationTable::query()
    ->setSelect([
       'id',
       'NAME',
       'doctor_id.ELEMENT',
       //'doctor_id.ELEMENT' => 'doctor_id_ELEMENT'
       'DOCTOR'
    ])
    //->setOrder(['COUNTRY' => 'desc'])
    ->registerRuntimeField(
        null,
        new \Bitrix\Main\Entity\ReferenceField(
            'DOCTOR',
            'Bitrix\Iblock\Elements\ElementDoctorTable',
            ['=this.IBLOCK_ELEMENTS_ELEMENT_RE_SE_RV_AT_IO_N_doctor_id_ELEMENT_ID' => 'ref.ID']
        )
    )
    ->fetchAll();

pr($reserv);*/
/* foreach ($reserv as $item): 
    //echo $item->getdoctor_id()->getValue();
   echo '<pre>'.$item->getid().'--'.$item->getName().'--'.$item->get('DOCTOR')->getName().'--'.'</pre>';
 endforeach;*/

/*use \Bitrix\Iblock\Elements\ElementReservationTable as ReservationTable;
$reserv = ReservationTable::query()
    ->setSelect([
       'id',
       'NAME',
        'Iblock.ID',
    ])
    ->setOrder(['ID' => 'DESC']) 
    ->fetch();
$next_Id = $reserv['ID']+1;
echo $next_Id;
//$iblockId = 22;
$col_zap = ReservationTable::getCount();
echo 'Кол запи= '.$col_zap = ReservationTable::getCount();
$Iblock_ID = $reserv['IBLOCK_ELEMENTS_ELEMENT_RE_SE_RV_AT_IO_N_IBLOCK_ID'];
echo 'Блок= '.$Iblock_ID;*/
/*$procs = ["NAME" => "Запись 3",
    "doctor_id" => "47",
    "patient" => "Иван",
    "procedure_id" => 43];
    // Если было свойство BRAND, но здесь не указали, оно сотрётся
 

$el = new CIBlockElement;
$PROP = array();
$PROP[12] = "Белый";  // свойству с кодом 12 присваиваем значение "Белый"
$PROP[3] = 38;        // свойству с кодом 3 присваиваем значение 38
$arLoadProductArray = Array(
	"ID"    => $next_Id, // элемент изменен текущим пользователем
	//"IBLOCK_SECTION_ID" => false,          // элемент лежит в корне раздела
	"IBLOCK_ID"      => 22,
	"PROPERTY_VALUES"=> $procs,
	"NAME"           => "Запись 3",
	"ACTIVE"         => "Y",            // активен
	//"PREVIEW_TEXT"   => "текст для списка элементов",
	//"DETAIL_TEXT"    => "текст для детального просмотра",
	//"DETAIL_PICTURE" => CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"]."/image.gif")
);
if($PRODUCT_ID = $el->Add($arLoadProductArray))
	echo "New ID: ".$PRODUCT_ID;
else
	echo "Error: ".$el->LAST_ERROR;    
//CIBlockElement::SetPropertyValues($next_Id, $iblockId, $procs, false);
*/
/*use \Bitrix\Iblock\Elements\ElementReservationTable as ReservationTable;
$reserv = ReservationTable::query()
    ->setSelect([
       'id',
       'NAME',
       'doctor_id.ELEMENT',
       'Patient',
       'booking',
       //'doctor_id.ELEMENT' => 'doctor_id_ELEMENT'
       'DOCTOR'
    ])
    //->setFilter(['ID' => $next_Id])
        //->setOrder(['COUNTRY' => 'desc'])
    ->registerRuntimeField(
        null,
        new \Bitrix\Main\Entity\ReferenceField(
            'DOCTOR',
            'Bitrix\Iblock\Elements\ElementDoctorTable',
            ['=this.IBLOCK_ELEMENTS_ELEMENT_RE_SE_RV_AT_IO_N_doctor_id_ELEMENT_ID' => 'ref.ID']
        )
    )
    ->fetchALl();

pr($reserv); */
/*$reservation = \Bitrix\Iblock\Elements\ElementReservationTable::query()
    ->registerRuntimeField("DOCTOR",[
            "data_type" => "\Bitrix\Iblock\Elements\ElementDoctorTable",
            'reference' => [
                'this.doctor_id.ELEMENT.ID' => 'ref.ID'
            ],
        ])
    ->setSelect([
       'ID',
       'NAME',
        'DOCTOR.NAME',
        'DOCTOR.FIRST_NAME',
        'DOCTOR.MIDDLE_NAME',
    ])
    ->fetchALl();
foreach     ($reservation as $item){
    pr($item);
}*/
/*$reservation = \Bitrix\Iblock\Elements\ElementReservationTable::query()
    ->setSelect([
       'ID',
       'NAME',
       'doctor_id.ELEMENT.NAME',
       'doctor_id.ELEMENT.FIRST_NAME',
       'doctor_id.ELEMENT.MIDDLE_NAME',
       'doctor_id.ELEMENT.LAST_NAME',
    ])
    ->fetchCollection();
foreach ($reservation as $item){
    echo $item->get('NAME').'<br/>';
    echo $item->getDoctorId()->getElement()->getName().'<br/>';
    echo $item->getDoctorId()->getElement()->getFirstName()->getValue().'<br/>';
    echo $item->getDoctorId()->getElement()->getLastName()->getValue().'<br/>';
    echo $item->getDoctorId()->getElement()->getMiddleName()->getValue().'<br/>';
}*/
/*foreach ($reserv as $item): 
    //echo $item->getdoctor_id()->getValue();
    //pr(base64_decode($item->getBooking()->getValue()));
$arValue = unserialize(htmlspecialcharsback(base64_decode($item->getBooking()->getValue())), [stdClass::class]);
 echo '<pre>'.$arValue['DATE'].'</pre>';
   //echo '<pre>'.$item->getid().'--'.$item->getName().'--'.$item->get('DOCTOR')->getName().'--'.$item->getPatient()->getValue().'--'.base64_decode($item->getBooking()->getValue()).'--'.'</pre>';
 endforeach;*/
//pr($reserv);

/*Bitrix\Main\Loader::includeModule('iblock');
// создаем объект Query, в качестве параметра передаем объект сущности (инфоблок)
$query = new Bitrix\Main\Entity\Query(
    Bitrix\Iblock\Elements\ElementCarsTable::getEntity()
);
 $query->registerRuntimeField( 
    // поле element как ссылка на таблицу b_iblock_element
    'MANUFACTURER',
    array(
        // тип — сущность ElementTable
        'data_type' => 'Bitrix\Iblock\Elements\ElementMANUFACTURERTable',
        // this.ID относится к таблице, относительно которой строится запрос, т.е. b_iblock.ID = b_iblock_element.IBLOCK_ID
        'reference' => array('=this.IBLOCK_ELEMENTS_ELEMENT_CA_RS_manufacturer_id_IBLOCK_GENERIC_VALUE' => 'ref.ID'),
    )
);
// выбираем название элемента, символьный код, краткое описание, кол-во просмотров и название инфоблока
$query->setSelect(array('id', 'manufacturer_id',  'NAME','MANUFACTURER'));
// выбираем только элемент с идентификатором 349
 
// посмотрим, какой запрос был сформирован
echo '<pre>' . $query->getQuery() . '</pre>';
// выполняем запрос
$result = $query->exec();
// выводим результат
while ($row = $result->fetch()) {
    pr($row);
}*/