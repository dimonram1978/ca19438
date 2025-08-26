<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
use Bitrix\Main\Diag\Debug;
$nav = new \Bitrix\Main\UI\PageNavigation('report_list');
$nav->setRecordCount($arResult['COUNT']);
$nav->allowAllRecords(false)->setPageSize($arResult['NUM_PAGE'])->initFromUri();


echo 'TEMPLATE';
 
// pr($arParams); 
//pr($arResult); 
//pr($templateFolder); 
//pr($componentPath); 
//die();
?>


<?
//Debug::writeToFile($arResult, 'arResult', "/local/app/Events/log_Iblock2.txt");
// здесь мы модключаем штатный компонет грид и передаем ему данные
$APPLICATION->includeComponent(
	"bitrix:main.ui.grid",
	"",
	[
		"GRID_ID" => "MY_GRID_ID",
		"COLUMNS" => $arResult['COLUMNS'],
		"ROWS" => $arResult['LISTS'],
		"NAV_OBJECT" => $nav,
		"AJAX_MODE" => "Y",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_HISTORY" => "N",
		"SHOW_ROW_CHECKBOXES" =>$arResult['SHOW_ROW_CHECKBOXES'],
		"SHOW_SELECTED_COUNTER" => false,
		"SHOW_PAGESIZE" => false,
		"TOTAL_ROWS_COUNT" =>$arResult['COUNT']
	]
);

if (!empty($arParams['AJAX_LOADER'])) { ?>
    <script>
        BX.addCustomEvent('Grid::beforeRequest', function (gridData, argse) {
            if (argse.gridId !== '<?=$arResult['FILTER_ID'];?>') {
                return;
            }

            if (argse.url === '') {
                argse.url = "<?=$component->getPath()?>/lazyload.ajax.php?site=<?=\SITE_ID?>&internal=true&grid_id=<?=$arResult['FILTER_ID']?>&grid_action=filter&"
            }

            argse.method = 'POST'
            argse.data = <?= Json::encode($arParams['AJAX_LOADER']['data']) ?>;
        });
    </script>
<?php } ?>
