<?php
namespace Models;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\DateField;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\ORM\Fields\TextField;
use Bitrix\Main\ORM\Fields\Validators\LengthValidator;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\Entity\Query\Join;
use Bitrix\Main\ORM\Fields\Relations\OneToMany;
use Bitrix\Main\ORM\Fields\Relations\ManyToMany;


use Models\Lists\PrintinghousePropertyValuesTable as PrintinghouseTable;
/**
 * Class Table
 * 
 * Fields:
 * <ul>
 * <li> id int mandatory
 * <li> name string(50) optional
 * <li> text text optional
 * <li> publish_date date optional
 * <li> ISBN string(50) optional
 * <li> author_id int optional
 * <li> publisher_id int optional
 * <li> wikiprofile_id int optional
 * </ul>
 *
 * @package Bitrix\
 **/

class ModuleCustomTable extends DataManager
{
	/**
	 * Returns DB table name for entity.
	 *
	 * @return string
	 */
	public static function getTableName()
	{
		return 'cars_custom_table';
	}

	/**
	 * Returns entity map definition.
	 *
	 * @return array
	 */
	public static function getMap()
	{
		return [
			'id' => (new IntegerField('id',
					[]
				))->configureTitle(Loc::getMessage('_ENTITY_ID_FIELD'))
						->configurePrimary(true)
						->configureAutocomplete(true)
			,
			'cars_id' => (new IntegerField('cars_id',
					[]
				))->configureTitle(Loc::getMessage('_ENTITY_CARS_ID_FIELD'))
						->configurePrimary(true)
						->configureAutocomplete(true)
			,
			(new Reference('CARS', \Bitrix\Iblock\Elements\ElementCarsTable::class, Join::on('this.cars_id', 'ref.ID')))
				->configureJoinType('left'), 
			'deal_id' => (new IntegerField('deal_id',
					[]
				))->configureTitle(Loc::getMessage('_ENTITY_ID_FIELD'))
						->configurePrimary(true)
						->configureAutocomplete(true)
			,
			/*'deal_id' => (new IntegerField('deal_id',
					[]
				))->configureTitle(Loc::getMessage('_ENTITY_DEALTITLE_FIELD'))
						->configurePrimary(true)
						->configureAutocomplete(true)
			,
			(new Reference('DEALTITLE', \Bitrix\Iblock\Elements\ElementCarsTable::class, Join::on('this.cars_id', 'ref.ID')))
				->configureJoinType('left'),*/
		];
	}
}