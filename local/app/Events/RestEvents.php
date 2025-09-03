<?php
//namespace Rest;
namespace Events;
use Bitrix\Main\EventManager;
use Bitrix\Rest\RestException;
use Bitrix\Main\Event;
use Bitrix\Main\Engine\CurrentUser;

use Models\BookTable as Books;
use Models\AuthorTable as Authors;
    use Models\PublisherTable as Publishers;
    use Models\BookPublisherTable as BookPublisher;
    use Models\Lists\ShopsPropertyValuesTable as ShopsTable;
    use Models\Lists\PrintinghousePropertyValuesTable as PrintinghouseTable;

use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

//$eventManager = EventManager::getInstance();
//$eventManager->addEventHandlerCompatible('rest', 'OnRestServiceBuildDescription', ['Rest\Events', 'OnRestServiceBuildDescriptionHandler']);
//Rest методы
//$eventManager->addEventHandler('rest', 'OnRestServiceBuildDescription', ['Events\RestEvents', 'OnRestServiceBuildDescriptionHandler']);

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
        Loc::getMessage("NAME");

        return [
            'otus.bookstabledata' => [
                'otus.bookstabledata.add' => [__CLASS__, 'add'],
                'otus.bookstabledata.update' => [__CLASS__, 'update'],
                'otus.bookstabledata.delete' => [__CLASS__, 'delete'],
                'otus.bookstabledata.list' => [__CLASS__, 'list'],
            //'otus.originalcontactsdata' => [
            //    'otus.originalcontactsdata.add' => [__CLASS__, 'add'],
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
       // file_put_contents($_SERVER['DOCUMENT_ROOT'].'/logRest3.txt', 'NAV: '.var_export($navStart, true).PHP_EOL, FILE_APPEND);
       //file_put_contents($_SERVER['DOCUMENT_ROOT'].'/logRest3.txt', 'PARAMS: '.var_export($arParams, true).PHP_EOL, FILE_APPEND);
       // 
       // file_put_contents($_SERVER['DOCUMENT_ROOT'].'/logRest.txt', 'SERVER: '.var_export($server, true).PHP_EOL, FILE_APPEND);

        $originDataStoreResult = Books::add($arParams);
        if ($originDataStoreResult->isSuccess())
        {
            $id = $originDataStoreResult->getId();
            $arParams['ID'] = $id;
            $event = new Event('main', 'onAfterBooksTableDataAdd', $arParams);
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
        }
    }

    /**
     * update element
     * @param $arParams - request params
     * @param $navStart - default start parameter (START from POST-data)
     * @param \CRestServer $server - server data
     * @return mixed
     * @throws RestException
     */
    public static function update ($arParams, $navStart, \CRestServer $server)
    {
       //file_put_contents($_SERVER['DOCUMENT_ROOT'].'/logRest.txt', 'PARAMS: '.var_export($arParams, true).PHP_EOL, FILE_APPEND);
       // file_put_contents($_SERVER['DOCUMENT_ROOT'].'/logRest.txt', 'NAV: '.var_export($navStart, true).PHP_EOL, FILE_APPEND);
       // file_put_contents($_SERVER['DOCUMENT_ROOT'].'/logRest.txt', 'SERVER: '.var_export($server, true).PHP_EOL, FILE_APPEND);
      
        if ( !isset($arParams['id']) ) { die();}
        else { $key_id = intval($arParams['id']);}
        //file_put_contents($_SERVER['DOCUMENT_ROOT'].'/logRest5.txt', 'NAV: '.var_export($key_id, true).PHP_EOL, FILE_APPEND);
       // file_put_contents($_SERVER['DOCUMENT_ROOT'].'/logRest5.txt', 'SERVER: '.var_export($arParams, true).PHP_EOL, FILE_APPEND);
        $originDataStoreResult = Books::update($key_id, $arParams);
        if ($originDataStoreResult->isSuccess())
        {
            $id = $originDataStoreResult->getId();
            $arParams['ID'] = $id;
            $event = new Event('main', 'onAfterBooksTableDataUpdate', $arParams);
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
        }
    }

    /**
     * delete element
     * @param $arParams - request params
     * @param $navStart - default start parameter (START from POST-data)
     * @param \CRestServer $server - server data
     * @return mixed
     * @throws RestException
     */
    public static function delete ($arParams, $navStart, \CRestServer $server)
    {
       //file_put_contents($_SERVER['DOCUMENT_ROOT'].'/logRest.txt', 'PARAMS: '.var_export($arParams, true).PHP_EOL, FILE_APPEND);
        //file_put_contents($_SERVER['DOCUMENT_ROOT'].'/logRest.txt', 'NAV: '.var_export($navStart, true).PHP_EOL, FILE_APPEND);
        //file_put_contents($_SERVER['DOCUMENT_ROOT'].'/logRest.txt', 'SERVER: '.var_export($server, true).PHP_EOL, FILE_APPEND);
      
        if ( !isset($arParams['id']) ) { die();}
        else { $key_id = intval($arParams['id']);}
        //file_put_contents($_SERVER['DOCUMENT_ROOT'].'/logRest5.txt', 'NAV: '.var_export($key_id, true).PHP_EOL, FILE_APPEND);
        //file_put_contents($_SERVER['DOCUMENT_ROOT'].'/logRest5.txt', 'SERVER: '.var_export($arParams, true).PHP_EOL, FILE_APPEND);
        $originDataStoreResult = Books::delete($key_id);
        if ($originDataStoreResult->isSuccess())
        {
           //file_put_contents($_SERVER['DOCUMENT_ROOT'].'/logRest8.txt', 'SERVER: '.var_export($originDataStoreResult, true).PHP_EOL, FILE_APPEND);
           /* $id = $originDataStoreResult->getId();
            $arParams['ID'] = $id;
            $event = new Event('main', 'onAfterBooksTableDataDelete', $arParams);
            $event->send();
            */
            return true;
        }
        else
        {
            throw new RestException(
                json_encode($originDataStoreResult->getErrorMessages(), JSON_UNESCAPED_UNICODE),
                RestException::ERROR_ARGUMENT,
                \CRestServer::STATUS_OK
            );
        }
    }


    /**
     * list all element
     * @param $arParams - request params
     * @param $navStart - default start parameter (START from POST-data)
     * @param \CRestServer $server - server data
     * @return mixed
     * @throws RestException
     */
    public static function list ($arParams, $navStart, \CRestServer $server)
    {
       // file_put_contents($_SERVER['DOCUMENT_ROOT'].'/logRest3.txt', 'NAV: '.var_export($navStart, true).PHP_EOL, FILE_APPEND);
       //file_put_contents($_SERVER['DOCUMENT_ROOT'].'/logRest3.txt', 'PARAMS: '.var_export($arParams, true).PHP_EOL, FILE_APPEND);
       // 
       // file_put_contents($_SERVER['DOCUMENT_ROOT'].'/logRest.txt', 'SERVER: '.var_export($server, true).PHP_EOL, FILE_APPEND);
        //if (isset($arParams['id']) ) { $key_id = $arParams['id'];}
        
        $originDataStoreResult = Books::list();
        
        if (is_array($originDataStoreResult))
        { 
            /*$id = $originDataStoreResult->getId();
            $arParams['ID'] = $id;
            $event = new Event('main', 'onAfterBooksTableDataAdd', $arParams);
            $event->send();*/
            return  json_encode($originDataStoreResult, JSON_UNESCAPED_UNICODE);     
             
        }
        else
        {
            throw new RestException(
                json_encode($originDataStoreResult->getErrorMessages(), JSON_UNESCAPED_UNICODE),
                RestException::ERROR_ARGUMENT,
                \CRestServer::STATUS_OK
            );
        }
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