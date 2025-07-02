<?php
  require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
  
  
 // echo '<pre>patientName:  '.$_POST['patientName'].'</pre>'.'<pre>datetime:  '.$_POST['datetime'].'</pre>';//.'<pre>doctor_name:  '.$_POST['doctor_name'].'</pre>'.'<pre>procedure_id:  '.$_POST['procedure_id'].'</pre>';   
 /*$data='';
// переберём массив $_POST
foreach ($_POST as $key => $value) {
  // добавим в переменную $data имя и значение ключа
  $data .= $key . ' = ' . $value . '';
}
// выведим результат
echo $data;*/

$booking = array(array("DATE" => $_POST['DATE'],"TIME_FROM" => $_POST['TIME_FROM'],"TIME_TO" => $_POST['TIME_TO']));

use \Bitrix\Iblock\Elements\ElementReservationTable as ReservationTable;
$reserv = ReservationTable::query()
    ->setSelect([
       'id',
       'NAME',
       'Iblock.ID',
        
    ])
    ->setOrder(['ID' => 'DESC']) 
    ->fetch();
$next_Id = $reserv['ID']+1;
$Iblock_ID = $reserv['IBLOCK_ELEMENTS_ELEMENT_RE_SE_RV_AT_IO_N_IBLOCK_ID'];
$col_zap = ReservationTable::getCount();

$procs = [
  //"NAME" => "Запись $col_zap",
    "doctor_id" => $_POST['doctor_id'],
    "patient" => $_POST['patientName'],
    "procedure_id" => $_POST['procedure_id'],
    "booking" => $booking 
    ];

$el = new CIBlockElement;

$arLoadProductArray = Array(
	"ID"    => $next_Id, // элемент изменен текущим пользователем
	//"IBLOCK_SECTION_ID" => false,          // элемент лежит в корне раздела
	"IBLOCK_ID"      => $Iblock_ID,
	"PROPERTY_VALUES"=> $procs,
	"NAME"           => "Запись $col_zap",
	"ACTIVE"         => "Y",            // активен
	//"PREVIEW_TEXT"   => "текст для списка элементов",
	//"DETAIL_TEXT"    => "текст для детального просмотра",
	//"DETAIL_PICTURE" => CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"]."/image.gif")
);
if($new_zapis = $el->Add($arLoadProductArray))
{
   
  $data='';
  foreach ($booking as $key => $items) {
   // $data .= $key . ' = ' . $value . '';
   foreach ($items as $key => $value)
    {
        $data .= $key . ' = ' . $value . '';
    }
  }
  echo 'Создано запись к врачу: Запись' .$col_zap.' на Время: '.$data;
}
  else 
	  {echo "Error: ".$el->LAST_ERROR;}
   