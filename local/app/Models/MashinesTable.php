<?php
namespace Models;

use Bitrix\Main\Localization\Loc,
	Bitrix\Main\ORM\Data\DataManager,
	Bitrix\Main\ORM\Fields\IntegerField,
	Bitrix\Main\ORM\Fields\StringField,
	Bitrix\Main\ORM\Fields\Validators\LengthValidator;

/**
 * Class ClientsTable
 *
 * @package Models
*/

class MashinesTable extends DataManager
{
	/**
	 * Returns DB table name for entity.
	 *
	 * @return string
	 */
	public static function getTableName()
	{
		return 'b_iblock_element_prop_s26';

	}

	/**
	 * Returns entity map definition.
	 *
	 * @return array
	 */
	public static function getMap()
	{
		return [
			'ID' => (new IntegerField('ID',
					['title' => 'ID',]
				))->configureTitle(Loc::getMessage('LISTS_ENTITY_ID_FIELD'))
						->configurePrimary(true)
						->configureAutocomplete(true),
			'NAME' => (new StringField('NAME',
				))->configureTitle('Имя'),
			'Client_id' => (new IntegerField('Client_id',
					[]
				))->configureTitle(Loc::getMessage('id клиента'))
						->configurePrimary(true)
						->configureAutocomplete(true)
			,
			'car_id' => (new IntegerField('Car_id',
					[]
				))->configureTitle(Loc::getMessage('id машины клиента'))
						->configurePrimary(true)
						->configureAutocomplete(true)
			,
			'CAR' => (new IntegerField('CAR',
					[]
				))->configureTitle(Loc::getMessage('Машина клиента'))
						->configurePrimary(true)
						->configureAutocomplete(true)
			,
			'MODEL' => (new IntegerField('MODEL',
					[]
				))->configureTitle(Loc::getMessage('Марка машины клиента'))
						->configurePrimary(true)
						->configureAutocomplete(true)
			,
			'Year_prod' => (new IntegerField('Year_prod',
					[]
				))->configureTitle(Loc::getMessage('Год производства машины клиента'))
						->configurePrimary(true)
						->configureAutocomplete(true)
			,
			'COLOR' => (new IntegerField('COLOR',
					[]
				))->configureTitle(Loc::getMessage('Цвет машины клиента'))
						->configurePrimary(true)
						->configureAutocomplete(true)
			, 
			'mileage' => (new IntegerField('mileage',
					[]
				))->configureTitle(Loc::getMessage('Пробег машины клиента'))
						->configurePrimary(true)
						->configureAutocomplete(true)
			, 
			/*'UF_PHONE' => (new StringField('UF_PHONE',
					[
						'validation' => [__CLASS__, 'validateUfPhone']
					]
				))->configureTitle('Телефон'),
			'UF_JOBPOSITION' => (new StringField('UF_JOBPOSITION',
					[
						'validation' => [__CLASS__, 'validateUfJobposition']
					]
				))->configureTitle('Должность'),
			'UF_SCORE' => (new StringField('UF_SCORE',
					[
						'validation' => [__CLASS__, 'validateUfScore']
					]
				))->configureTitle('Лояльность клиента'),*/
		];
	}

	/**
	 * Returns validators for UF_NAME field.
	 *
	 * @return array
	 */
	public static function validateUfName()
	{
		return [
			new LengthValidator(null, 50),
		];
	}

	/**
	 * Returns validators for UF_LASTNAME field.
	 *
	 * @return array
	 */
	public static function validateUfLastname()
	{
		return [
			new LengthValidator(null, 50),
		];
	}

	/**
	 * Returns validators for UF_PHONE field.
	 *
	 * @return array
	 */
	public static function validateUfPhone()
	{
		return [
			new LengthValidator(null, 50),
		];
	}

	/**
	 * Returns validators for UF_JOBPOSITION field.
	 *
	 * @return array
	 */
	public static function validateUfJobposition()
	{
		return [
			new LengthValidator(null, 50),
		];
	}

	/**
	 * Returns validators for UF_SCORE field.
	 *
	 * @return array
	 */
	public static function validateUfScore()
	{
		return [
			new LengthValidator(null, 50),
		];
	}

	
}