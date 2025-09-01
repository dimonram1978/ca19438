<?php
//namespace Rest;
namespace Events;
use Bitrix\Main\EventManager;
use Bitrix\Rest\RestException;
use Bitrix\Main\Event;
use Bitrix\Main\Engine\CurrentUser;

use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

//$eventManager = EventManager::getInstance();
//$eventManager->addEventHandlerCompatible('rest', 'OnRestServiceBuildDescription', ['Rest\Events', 'OnRestServiceBuildDescriptionHandler']);

class RestEvents
{ 
    /**
     * Register rest methods
     * Clear scope cache after register
     * Bitrix\Main\Data\Cache::clearCache(true, '/rest/scope/');
     * @return array[]
     */
    public static function OnRestServiceBuildDescriptionHandler()
    {
        //Loc::getMessage('REST_SCOPE_OTUS.ORIGINALCONTACTSDATA');
        Loc::getMessage('OTUS Оригинальные данные контактов');

        return [
            'otus.originalcontactsdata' => [
                'otus.originalcontactsdata.add' => [__CLASS__, 'add'],
               // 'otus.originalcontactsdata.list' => [__CLASS__, 'list'],
               // 'otus.originalcontactsdata.update' => [__CLASS__, 'update'],
               // 'otus.originalcontactsdata.delete' => [__CLASS__, 'delete'],
//                \CRestUtil::EVENTS => [
//                    //код в списке событий
//                    'onAfterOOCDAdd' => [
//                        'main', //модуль события
//                        'onAfterOtusOriginalContactsDataAdd', //название события
//                        [__CLASS__, 'prepareEventData'] //обработчик
//                    ]
//                ]
            ],
        ];
    }

    /**
     * Add element
     * @param $arParams - request params
     * @param $navStart - default start parameter (START from POST-data)
     * @param \CRestServer $server - server data
     * @return mixed
     * @throws RestException
     */
    public static function add ($arParams, $navStart, \CRestServer $server)
    {
       file_put_contents($_SERVER['DOCUMENT_ROOT'].'/logRest.txt', 'PARAMS: '.var_export($arParams, true).PHP_EOL, FILE_APPEND);
        file_put_contents($_SERVER['DOCUMENT_ROOT'].'/logRest.txt', 'NAV: '.var_export($navStart, true).PHP_EOL, FILE_APPEND);
        file_put_contents($_SERVER['DOCUMENT_ROOT'].'/logRest.txt', 'SERVER: '.var_export($server, true).PHP_EOL, FILE_APPEND);

        /*$originDataStoreResult = OriginalContactsDataTable::add($arParams);
        if ($originDataStoreResult->isSuccess())
        {
            $id = $originDataStoreResult->getId();
            $arParams['ID'] = $id;
            $event = new Event('main', 'onAfterOtusOriginalContactsDataAdd', $arParams);
            $event->send();

            return $id;
        }
        else
        {
            throw new RestException(
                json_encode($originDataStoreResult->getErrorMessages(), JSON_UNESCAPED_UNICODE),
                RestException::ERROR_ARGUMENT,
                \CRestServer::STATUS_OK
            );
        }*/
    }

    /**
     * Prepare data
     * @param $arguments - data
     * @param $handler - handler
     * @return mixed
     */
    public static function prepareEventData($arguments, $handler)
    {
        file_put_contents($_SERVER['DOCUMENT_ROOT'].'/logRestEvent.txt', 'A: '.var_export($arguments, true).PHP_EOL, FILE_APPEND);
        file_put_contents($_SERVER['DOCUMENT_ROOT'].'/logRestEvent.txt', 'H: '.var_export($handler, true).PHP_EOL, FILE_APPEND);
        /** @var Event $event */
       /* $event = reset($arguments);
        $response = $event->getParameters();
        file_put_contents($_SERVER['DOCUMENT_ROOT'].'/logRestEvent.txt', 'R: '.var_export($response, true).PHP_EOL, FILE_APPEND);
*/
        //bl

        return $response;
    }
}