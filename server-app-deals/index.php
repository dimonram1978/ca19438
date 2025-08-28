<?php
require_once (__DIR__.'/crest.php');
 
//header('Access-Control-Allow-Origin: *');
//var_dump($_REQUEST['member_id']);
 
if (empty($_REQUEST['event'])) {
    ?>
    <div>Приложение используется как обработчик события</div>
    <?php
}
else {
  // var_dump($_REQUEST['event']);?> <div>Привет мир!22</div> <?php
  
}
if ($_REQUEST['event'] == 'ONCRMACTIVITYADD') {
     
    
    $activityId = $_REQUEST['data']['FIELDS']['ID'];
    
    //@ TODO реализовать получение информации о деле CRM
   
   $result = CRest::call(
    'crm.activity.get',
      [
        'id' => $activityId
      ]
    );   
   // @TODO если дело является звонком или сообщением, то обновить поле "Дата коммуникации" 
    if(array_key_exists('result', $result)){
       if(array_key_exists('CREATED', $result['result']) && array_key_exists('TYPE_ID', $result['result'])){
         $date_time = $result['result']['CREATED'];
         $contact_id = $result['result']['OWNER_ID'];
         
   
         switch ($result['result']['TYPE_ID']){
           case 2:           
             //Телефонный звонок
             $result2 = CRest::call(
               'crm.contact.update',
               [
                'ID' => $contact_id,
                'FIELDS' => [
                   'UF_CRM_1756368917' => $date_time,
                   ] 
                ]
             );  
             case 1:           
             //Отправка почтового сообщения
             $result2 = CRest::call(
               'crm.contact.update',
               [
                'ID' => $contact_id,
                'FIELDS' => [
                   'UF_CRM_1756368917' => $date_time,
                   ] 
                ]
             );  
         }
       }
    }

}

/*$result = CRest::call('crm.deal.list');
?>
<ul>
    <?php
    foreach ($result['result'] as $deal) {?>
        <li>
            <?=$deal['TITLE']?>
        </li>
    <?php }
    ?>
</ul>
<?*/

//Получить поля сделки
/*$arDealFields = CRest::call(
    "crm.deal.fields",
    []
);

echo "<pre>";var_dump($arDealFields);echo "</pre>";*/

/*//Создание сделки
$result = CRest::call(
  "crm.deal.add",
  [
    "fields" => [
      "TITLE" => "Название сделки 33",
      "STAGE_ID" => "NEW",//стадия сделки
      "COMPANY_ID" => 3, //ID компании
      "CONTACT_ID" => 3, //ID контакта
      "ASSIGNED_BY_ID" => 3, //ID ответственного
    ]
  ]
);
echo "<pre>";var_dump($result);echo "</pre>";*/
/*//Обновоение сделки
$result = CRest::call(
        'crm.deal.update',
        [
"id" => 37,
"fields"=>[
//"STAGE_ID"=> "PREPAYMENT_INVOICE",
"OPPORTUNITY"=> 75000,
"COMMENTS"=> "Обновлено через API",
"CONTACT_ID" => 3,
]
]
    );
echo "<pre>";var_dump($result);echo "</pre>";*/

?>
 