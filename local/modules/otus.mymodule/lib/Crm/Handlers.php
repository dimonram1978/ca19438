<?php
namespace Otus\Mymodule\Crm;

//use Models\ModuleCustomTable as ModuleCustom;
use Bitrix\Crm\DealTable;
//use Aholin\Crmcustomtab\Orm\BookTable;
use Bitrix\Main\Event;
use Bitrix\Main\EventResult;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);
class Handlers
{
   /*public static function updateTabs(Event $event): EventResult
    {
        $entityTypeId = $event->getParameter('entityTypeID');
        $entityId = $event->getParameter('entityID');
        $tabs = $event->getParameter('tabs');
        $tabs[] = [
            'id' => 'book_tab_' . $entityTypeId . '_' . $entityId,
            'name' => Loc::getMessage('OTUS_MYMODULE_TAB_TITLE'),
            'enabled' => true,
            'loader' => [
                'serviceUrl' => sprintf(
                    '/bitrix/components/aholin.crmcustomtab/book.grid/lazyload.ajax.php?site=%s&%s',
                    \SITE_ID,
                    \bitrix_sessid_get(),
                ),
                'componentData' => [
                    'template' => '',
                    'params' => [
                        'ORM' => BookTable::class,
                        'DEAL_ID' => $entityId,
                    ],
                ],
            ],
        ];

        return new EventResult(EventResult::SUCCESS, ['tabs' => $tabs,]);
    }*/

    static function myOnEntityDetailsTabsInitialized($event): EventResult 
    {
	$tabs = $event->getParameter('tabs');
	// ID текущего элемента СРМ 
	$entityID = $event->getParameter('entityID');
	// ID типа сущности: Сделка, Компания, Контакт и т.д.
	$entityTypeID = $event->getParameter('entityTypeID');
	
	// Проверяем, что открыта карточка именно Сделки
	//if($entityTypeID == \CCrmOwnerType::Deal) {
    if($entityTypeID == \CCrmOwnerType::Contact ) {
		// Добавляем свою вкладку в массив вкладок
		$tabs[] = [
			'id' => 'newTab_'. $entityID,
			'name' => 'Гараж',
			// Выведим в содержимое новой вкладки ID текущей сделки
			//'html' => '<b>Содержимое новой вкладки. ID Сделки: '. $entityID .'</b>',
            'enabled' => true,
            'loader' => [
                'serviceUrl' => sprintf(
                    '/bitrix/components/otus.mymodule/base.grid/lazyload.ajax.php?site=%s&%s',
                    \SITE_ID,
                    \bitrix_sessid_get(),
                ),
                'componentData' => [
                    'template' => '',
                    'params' => [
                        //'ORM' => ModuleCustom::class,
                        'clientId' => $entityID,
                        //'DEAL_ID' => $entityID,
                    ],
                ],
            ],
		];
	}
	
	// Возвращаем модифицированный массив вкладок
	return new \Bitrix\Main\EventResult(\Bitrix\Main\EventResult::SUCCESS, [
		'tabs' => $tabs,
	]);
  }
}
