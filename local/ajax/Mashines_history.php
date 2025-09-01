<?php
  require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
  
  
  \Bitrix\Main\Loader::includeModule('crm');
  use Bitrix\Crm\DealTable;

  $MASHINE_ID = intval($_POST['mashine_id']);
  
  $result = DealTable::getList([
    'select' => ['ID', 'TITLE', 'UF_USER_MASHINE', 'OPPORTUNITY', 'CREATED_BY_ID', 'STAGE_ID'], // Укажите нужные вам ID полей
    'filter' => ['UF_USER_MASHINE' => $MASHINE_ID], // Фильтр по ID сделки
  ]);

  $kol=0;
 
  $kol=$result->getSelectedRowsCount();

  if (is_numeric($kol)){
    if ($kol==0) { echo '<span style="color: #F00;">Сделок по данной машине не было!</span>';}
    else {
      $str .= '<table>';
      $str .= '<tr><td colspan="5" bgcolor="#FBF0DB">Список сделок по данной машине:</td><tr>';
      $str .= '<tr><td width="50">Номер:</td><td width="50">Заявка:</td><td width="50">Сумма:</td><td width="50">Мастер:</td><td width="50">Результат:</td><tr>';
      foreach ($result->fetchCollection() as $item): 
       $str .= '<tr><td>'.$item->getid().'</td>';
       $str .= '<td>'.$item->gettitle().'</td>';
       $str .= '<td>'.$item->get('OPPORTUNITY').'</td>';
       $user_id = $item->get('CREATED_BY_ID');
       if(is_numeric($user_id)){$rsUser = CUser::GetByID($user_id); $arUser = $rsUser->Fetch(); $name_user = $arUser['NAME'];}
       else {$name_user ='';}
       $str .= '<td>'.$name_user.'</td>';
       $str .= '<td>'.$item->get('STAGE_ID').'</td></tr>';
     endforeach;
    $str .= '</table>';
    }
}
 
echo $str;
?>
   