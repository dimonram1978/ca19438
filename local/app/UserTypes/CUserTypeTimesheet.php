<?php

namespace UserTypes;

use Bitrix\Main\Loader,
    Bitrix\Main\Localization\Loc,
    Bitrix\Iblock;

/**
 * Реализация свойство «Расписание врача»
 * Class CUserTypeTimesheet
 * @package lib\usertype
 */
class CUserTypeTimesheet
{
    /**
     * Метод возвращает массив описания собственного типа свойств
     * @return array
     */
    public static function GetUserTypeDescription()
    {
        return array(
            'USER_TYPE_ID' => 'user_timesheet', //Уникальный идентификатор типа свойств
            'USER_TYPE' => 'TIMESHEET',
            //'CLASS_NAME' => __CLASS__,
            'DESCRIPTION' => 'Расписание специалиста',
            'PROPERTY_TYPE' => Iblock\PropertyTable::TYPE_STRING,
            'ConvertToDB' => array(self::class, 'ConvertToDB'),
            'ConvertFromDB' =>  array(self::class, 'ConvertFromDB'),
            'GetPropertyFieldHtml' => array(self::class, 'GetPropertyFieldHtml'),
        );
    }

    /**
     * Конвертация данных перед сохранением в БД
     * @param $arProperty
     * @param $value
     * @return mixed
     */
    public static function ConvertToDB($arProperty, $value)
    {
       $value['VALUE'] = base64_encode(serialize($value['VALUE']));
       /*if ($value['VALUE']['TIME_FROM'] != '')
        {
            try {
                $value['VALUE'] = base64_encode(serialize($value['VALUE']));
           } catch(Bitrix\Main\ObjectException $exception) {
                echo $exception->getMessage();
            }
       } else {
            $value['VALUE'] = '';
        }*/

        return $value;
    }

    /**
     * Конвертируем данные при извлечении из БД
     * @param $arProperty
     * @param $value
     * @param string $format
     * @return mixed
     */
    public static function ConvertFromDB($arProperty, $value, $format = '')
    {
        $value['VALUE'] = base64_decode($value['VALUE']);
        /*if ($value['VALUE'] != '')
        {
            try {
                $value['VALUE'] = base64_decode($value['VALUE']);
           } catch(Bitrix\Main\ObjectException $exception) {
               echo $exception->getMessage();
            }
        }*/

        return $value;
    }

    /**
     * Представление формы редактирования значения
     * @param $arUserField
     * @param $arHtmlControl
     */
    public static function GetPropertyFieldHtml($arProperty, $value, $arHtmlControl)
    {
         

        $itemId = 'row_' . substr(md5($arHtmlControl['VALUE']), 0, 10); //ID для js
        $fieldName =  htmlspecialcharsbx($arHtmlControl['VALUE']);
        //htmlspecialcharsback нужен для того, чтобы избавиться от многобайтовых символов из-за которых не работает unserialize()
        $arValue = unserialize(htmlspecialcharsback($value['VALUE']), [stdClass::class]);

 
        $Date = $arValue['DATE'];
        $timeFrom = ($arValue['TIME_FROM']) ? $arValue['TIME_FROM'] : '';
        $timeTo = ($arValue['TIME_TO']) ? $arValue['TIME_TO'] : '';

        $html2 .= '<div class="day_zapic" id="'. $itemId .'">';
        $html2 .='&nbsp;Дата приёма: &nbsp;<input type="date" name="'. $fieldName .'[DATE]" value="'. $Date . '">';
        $html2 .='&nbsp;время приёма: с&nbsp;<input type="time" name="'. $fieldName .'[TIME_FROM]" value="'. $timeFrom . '">';
        $html2 .='&nbsp;по&nbsp;<input type="time" name="'. $fieldName .'[TIME_TO]" value="'. $timeTo .'">';
     
        $html2 .= '</div><br/>';

       

        return $html2;
    }
}