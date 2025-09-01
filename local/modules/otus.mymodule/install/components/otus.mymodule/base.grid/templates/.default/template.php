
<?php

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
	],
	$component,
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
 
<?php

CJSCore::Init(['popup']);
?>
<script>
    function openFormPopup(mashine_id)
    {
        var cont = ajaxcontentload(mashine_id);
		
        var authPopup = BX.PopupWindowManager.create("FormPopup", mashine_id,  {
           
            
            width: 500, // ширина окна
            height: 300, // высота окна
            zIndex: 100, // z-index
            autoHide: true,
            offsetLeft: 0,
            offsetTop: 0,
            resizable: true,
            overlay : true,
            draggable: {restrict:true},
            closeByEsc: true,
            closeIcon: { right : "12px", top : "10px"},
            titleBar: 'История заявок:',
            content: '<div id="History"></div>',
             
        });

        authPopup.show();
    }

    function ajaxcontentload(mashine_id){
          
        var request = new XMLHttpRequest();
          function reqReadyStateChange() {
           if (request.readyState == 4 && request.status == 200)
              
			document.getElementById("History").innerHTML= request.responseText;
          }
 
	   var goToUrl= '/local/ajax/Mashines_history.php';
       var body= 'mashine_id='+mashine_id;
       request.open("POST", goToUrl);
       request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
       request.onreadystatechange = reqReadyStateChange;
       request.send(body);
    }
 
</script>