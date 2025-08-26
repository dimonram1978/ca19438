<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

$APPLICATION->SetTitle("Компонент списка валют");
$APPLICATION->SetAdditionalCSS('/doctors/style.css');

 

$APPLICATION->IncludeComponent(
	"otus:currency.views", 
	".default", 
	array(
		//"COMPONENT_TEMPLATE" => ".default",
		//"SHOW_CHECKBOXES" => "Y",
		//"NUM_PAGE" => "1"
	),
	false
);
 ?> 
<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>