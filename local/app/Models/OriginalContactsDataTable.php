<?php
//namespace Otus\Rest;
namespace Models;

use Bitrix\Main\Entity;
use Bitrix\Main\Event;
use Bitrix\Main\Application;
use Bitrix\Main\ORM\Data\DataManager;

class OriginalContactsDataTable extends DataManager
{
    public static function getTableName()
    {
        return 'otus_original_contacts_data';
    }

    public static function getMap()
    {
        return [
            new Entity\IntegerField('ID', [
                'primary' => true,
                'autocomplete' => true
            ]),
            new Entity\StringField('ENTITY_ID', [
                'required' => true
            ]),
            new Entity\StringField('ELEMENT_ID', [
                'required' => true
            ]),
            new Entity\StringField('TYPE_ID'),
            new Entity\StringField('VALUE_TYPE'),
            new Entity\StringField('VALUE'),
            new Entity\StringField('NEW_VALUE')
        ];
    }

    /**
     * Install table
     * @return void
     */
    /*public static function installTable()
    {
        $entity = self::getEntity();
        $tableName = self::getTableName();
        $connection = Application::getConnection();
        if (!$connection->isTableExists($tableName))
        {
            $entity->createDbTable();
        }
    }*/

//    use Otus\Rest\OriginalContactsDataTable;
//
//    $arF = ['ENTITY_ID' => 'CONTACT', 'ELEMENT_ID' => 15, 'VALUE' => 25555];
//    OriginalContactsDataTable::add($arF);

//    public static function onBeforeAdd(Entity\Event $event)
//    {
//        $result = new Entity\EventResult;
//        $data = $event->getParameter("fields");
//        if (isset($data['VALUE']))
//        {
//            $result->modifyFields(['NEW_VALUE' => 'EVENT!']);
//        }
//
//        return $result;
//    }

    public static function add($fields)
    {
        file_put_contents($_SERVER['DOCUMENT_ROOT'].'/log1.txt', 'test1');
        file_put_contents($_SERVER['DOCUMENT_ROOT'].'/logOCD.txt', 'FIELDS: '.var_export($fields, true).PHP_EOL, FILE_APPEND);
        $event = new Event("main", "OnBeforeOCDAdd", $fields);
        $event->send();

        if ($event->getResults())
        {
            foreach($event->getResults() as $evenResult)
            {
                if ( $evenResult->getType() == \Bitrix\Main\EventResult::SUCCESS )
                {
                    $arEventData = $evenResult->getModified();
                    if (isset($arEventData['ENTITY_ID']))
                        unset($arEventData['ENTITY_ID']);
////
                   $fields = array_merge($fields, $arEventData);
                    file_put_contents($_SERVER['DOCUMENT_ROOT'].'/logOCD.txt', 'EDATA: '.var_export($fields, true).PHP_EOL, FILE_APPEND);
                }
            }
        }

        return parent::add($fields);
    }
}