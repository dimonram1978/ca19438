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
   $event = $_REQUEST['event'];
    $filename = __DIR__ . '/file1.txt'; 
    file_put_contents($filename, $event);
}
if ($_REQUEST['event'] == 'ONCRMACTIVITYADD') {
    $event = $_REQUEST['event'];
    $filename = __DIR__ . '/file1.txt'; 
    file_put_contents($filename, $event); 
    //Bitrix\Main\Diag\Debug::writeToFile($_REQUEST, '$_REQUEST', __DIR__ . '/file12.txt');
    //\Bitrix\Main\Diag\Debug::dumpToFile($_REQUEST, '$_REQUEST', "/local/app/Events/log_Iblock6.txt"); 
    $activityId = $_REQUEST['data']['FIELDS']['ID'];
    $filename = __DIR__ . '/file2.txt';
 
    file_put_contents($filename, serialize($_REQUEST));

    $filename = __DIR__ . '/file3.txt';
 
    file_put_contents($filename, $activityId);
    //@ TODO реализовать получение информации о деле CRM
    $result = CRest::call(
        'crm.activity.get',
        [
            'ID' => $activityId,
        ],
    );
    $filename = __DIR__ . '/file4.txt';
 
    file_put_contents($filename, serialize($result));
    // @TODO если дело является звонком или сообщением, то обновить поле "Дата коммуникации"
    
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

/*//Получить поля сделки
$arDealFields = CRest::call(
    "crm.deal.fields",
    []
);

echo "<pre>";var_dump($arDealFields);echo "</pre>";*/
/*//Создание сделки
$result = CRest::call(
  "crm.deal.add",
  [
    "fields" => [
      "TITLE" => "Название сделки",
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
 