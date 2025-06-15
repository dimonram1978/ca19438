
<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");?> 
<?

use Bitrix\Main\Web\Json;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
    die();

/**
 * @var array $arResult
 * @var array $arParams
 * @global $APPLICATION
 * @global $component
 */

\Bitrix\Main\Loader::includeModule('ui');
  
$APPLICATION->IncludeComponent(
	'bitrix:main.ui.grid',
	'',
	[
		'GRID_ID'    => $arResult['GRID_ID'],
		'COLUMNS'    => $arResult['GRID_COLUMNS'], 
		'ROWS'       => $arResult['ROWS'],
		'NAV_OBJECT' => $arResult['NAV'], 
		'PAGE_SIZES' => $pageSizes,

		'AJAX_MODE' => 'Y', 
		'AJAX_ID'   => \CAjax::getComponentID('bitrix:main.ui.grid', '.default', ''), 
		'AJAX_OPTION_JUMP'          => 'N', 
		'AJAX_OPTION_HISTORY'       => 'N' ,

		'SHOW_ROW_CHECKBOXES' => false, 
		'SHOW_CHECK_ALL_CHECKBOXES' => false, 
		'SHOW_ROW_ACTIONS_MENU'     => true, 
		'SHOW_GRID_SETTINGS_MENU'   => true, 
		'SHOW_NAVIGATION_PANEL'     => true, 
		'SHOW_PAGINATION'           => true, 

		'SHOW_SELECTED_COUNTER'     => false, 
		'SHOW_TOTAL_COUNTER'        => true, 
		'SHOW_PAGESIZE'             => true, 
		'ALLOW_COLUMNS_SORT'        => true, 
		'ALLOW_COLUMNS_RESIZE'      => true, 
		'ALLOW_HORIZONTAL_SCROLL'   => true, 
		'ALLOW_SORT'                => true, 
		'ALLOW_PIN_HEADER'          => true, 
	]
);
 
 ?> 
<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?> 