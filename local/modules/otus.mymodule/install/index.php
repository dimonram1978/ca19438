<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Application;
use Bitrix\Main\EventManager;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Entity\Base;
use Bitrix\Main\IO\Directory;
use Bitrix\Main\SystemException;
use Bitrix\Main\IO\InvalidPathException;
use Bitrix\Main\DB\SqlQueryException;
use Bitrix\Main\LoaderException;

class otus_mymodule extends CModule
{
    public $MODULE_ID = "otus.mymodule";
    public $MODULE_NAME = "Модуль Otus - обучение";
    public $MODULE_DESCRIPTION = "Тестовый модуль для обучения";
    public $COMPONENT_NAME = "base.grid";

    public function __construct()
    {
        $this->PARTNER_NAME = "otus.mymodule";
        $this->MODULE_VERSION = "1.0.0";
        $this->MODULE_VERSION_DATE = "2025-03-25";
    }

   

    public function DoInstall()
    {
        ModuleManager::registerModule($this->MODULE_ID);
        $this->InstallFiles();
        $this->InstallDB();
        $this->InstallEvents();
    }

    public function DoUninstall()
    {
        $this->UninstallFiles();
        $this->UninstallDB();
        $this->UnInstallEvents();
        ModuleManager::unRegisterModule($this->MODULE_ID);
    }

    public function InstallFiles()
    {
        //$path_from = $_SERVER["DOCUMENT_ROOT"]."/local/modules/".$this->MODULE_ID."/templates";
        $path_from = $_SERVER["DOCUMENT_ROOT"]."/local/modules/".$this->MODULE_ID."/install/components/".$this->MODULE_ID;
        $path_to =  $_SERVER["DOCUMENT_ROOT"] . "/bitrix/components/".$this->MODULE_ID;
         
        CopyDirFiles($path_from, $path_to, true, true);
    }

    public  function InstallDB($arParams = []) //создание своих таблиц в БД
    {   global $DB, $APPLICATION;      
        //проверяем, есть ли таблицы
        $errors = false;
        //создаем таблицы, если они еще не существуют
        $errors = $DB->RunSQLBatch($_SERVER["DOCUMENT_ROOT"] . "/local/modules/".$this->MODULE_ID."/install/install.sql");
        if (!empty($errors)){
            $APPLICATION->ThrowException(implode("", $errors));
            return false;
        }

        //Заполним данными
        $errors = $DB->RunSQLBatch($_SERVER["DOCUMENT_ROOT"] . "/local/modules/".$this->MODULE_ID."/install/entities.sql");
        if (!empty($errors)){
            $APPLICATION->ThrowException(implode("", $errors));
            return false;
        }

        return true;
    }
 

    public function UninstallFiles()
    {
        DeleteDirFilesEx("/bitrix/components/".$this->MODULE_ID);
    }

    
    public  function UnInstallDB($arParams = []) //удаление своих таблиц в БД
    {   global $DB, $DBType, $APPLICATION;
        
        //if(array_key_exists("SAVEDATA", $arParams) and $arParams["SAVEDATA"] == "N"){ //удаляем, если параметр сохранить = "N"
            $errors = false;
            $errors = $DB->RunSQLBatch($_SERVER["DOCUMENT_ROOT"] . "/local/modules/".$this->MODULE_ID."/install/uninstall.sql");
            if (!empty($errors)){
                $APPLICATION->ThrowException(implode("", $errors));
                return false;
            }
        //}
        
        return true;
    }

    public function InstallEvents(): void
    {
        $eventManager = EventManager::getInstance();

        $eventManager->registerEventHandler(
            'crm',
            'onEntityDetailsTabsInitialized',
            $this->MODULE_ID,
            '\\Otus\\Mymodule\\Crm\\Handlers',
            //'updateTabs'
            'myOnEntityDetailsTabsInitialized'
        );
    }

    public function UnInstallEvents(): void
    {
        $eventManager = EventManager::getInstance();

        $eventManager->unRegisterEventHandler(
            'crm',
            'onEntityDetailsTabsInitialized',
            $this->MODULE_ID,
            '\\Otus\\Mymodule\\Crm\\Handlers',
            'myOnEntityDetailsTabsInitialized'
        );
    }

}
