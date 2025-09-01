<?php
  require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
  
  
  \Bitrix\Main\Loader::includeModule('crm');
  use Bitrix\Crm\DealTable;

  $MASHINE_ID = $_POST['mashine_id'];
  
  $result = DealTable::getList([
    'select' => ['ID', 'TITLE', 'UF_USER_MASHINE', 'OPPORTUNITY', 'STAGE_ID'], // Укажите нужные вам ID полей
    'filter' => ['UF_USER_MASHINE' => $MASHINE_ID], // Фильтр по ID сделки
  ]);

  $kol=0;
 
  $kol=$result->getSelectedRowsCount();

  if (is_numeric($kol)){
    if ($kol==0) { echo '';}
    else {
      $str='тра та та';
      $str .= '<table>';
      $str .= '<tr><td colspan="5" bgcolor="#FBF0DB">Список сделок по данной машине:</td><tr>';
      $str .= '<tr><td>Номер:</td><td>Наименование:</td><td>Сумма:</td><td>Мастер:</td><td>Результат:</td><tr>';
      /*foreach ($reserv as $item): 
        echo '<tr><td>'.$item->getid().'</td>';
        echo '<td>'.$item->getName().'</td>';
          $arValue = unserialize(htmlspecialcharsback(base64_decode($item->getBooking()->getValue())), [stdClass::class]);
      
        $Date = ($arValue['DATE']) ? $arValue['DATE'] : '';
        $timeFrom = ($arValue['TIME_FROM']) ? $arValue['TIME_FROM'] : '';
        $timeTo = ($arValue['TIME_TO']) ? $arValue['TIME_TO'] : '';

        $html2 = '<div>&nbsp;Дата приёма: &nbsp;'.$Date.'&nbsp;время приёма: с&nbsp;'.$timeFrom.'&nbsp;по&nbsp;'.$timeTo.'</div>';
         
       echo '<td>'.$html2.'</td>';
       echo '<td>'.$item->get('DOCTORS')->getName().'</td>';
       echo '<td>'.$item->getPatient()->getValue().'</td>';
       echo '<td>'.$item->get('PROCEDURES')->getName().'</td>';
     endforeach;*/
    $str .= '</table>';
    }
}
 
echo $str;
?>
   