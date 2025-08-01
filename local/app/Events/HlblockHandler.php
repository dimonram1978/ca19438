<?php

namespace Events;

use Bitrix\Main\Entity\Event;
use Bitrix\Main\Entity\EventResult;
use Bitrix\Main\Entity\EntityError;
use Bitrix\Main\EventManager;
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Loader;

class HlblockHandler
{
    public static function getHlIdByName($name = 'BooksList')
    {
        $hlBlockId = 0;
        $dbHL = HL\HighloadBlockTable::getList(['filter' => ['NAME' => $name]]);
        while ($arItem = $dbHL->Fetch()) {
            $hlBlockId = $arItem['ID'];
        }

        if (!$hlBlockId)
            return;

        $objHlblock = HL\HighloadBlockTable::getById($hlBlockId)->fetch(); //определяем объект hl-блока
        $entity = HL\HighloadBlockTable::compileEntity($objHlblock); //генерация класса
        return $entityName = $entity->getName();
    }


    public static function OnBeforeHLEAdd(Event $event)
    {
        $eventType = $event->getEventType(); // получаем тип события
        file_put_contents($_SERVER['DOCUMENT_ROOT'].'/logHL.txt', '$eventType: '.var_export($eventType, true).PHP_EOL, FILE_APPEND);

        $arFields = $event->getParameter("fields"); // получаем список полей
        file_put_contents($_SERVER['DOCUMENT_ROOT'].'/logHL.txt', '$arFields: '.var_export($arFields, true).PHP_EOL, FILE_APPEND);

        $result = new EventResult();
        // $changedFields = [];
        // $changedFields['UF_NAME'] = 'Мгла';
        // $result->modifyFields($changedFields);

        // $arUnsetFields = ['UF_TEXT'];
        // $result->unsetFields($arUnsetFields);

       // $arErrors = [];
       // $arErrors[] = new EntityError("Общая ошибка");
       // $arErrors[] = new EntityError("Частная ошибка");
       // $result->setErrors($arErrors);

        return $result;
    }

    
}