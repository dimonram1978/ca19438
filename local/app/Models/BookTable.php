<?php
namespace Models;
use Bitrix\Main\Type;

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
use Bitrix\Main\Event;

use Models\AuthorTable as Author;
use Models\PublisherTable as Publisher;
use Models\Lists\ShopsPropertyValuesTable as ShopsTable;
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

class BookTable extends DataManager
{
	/**
	 * Returns DB table name for entity.
	 *
	 * @return string
	 */
	public static function getTableName()
	{
		return 'books';
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
			'name' => (new StringField('name',
					[
						'validation' => function()
						{
							return[
								new LengthValidator(null, 50),
							];
						},
					]
				))->configureTitle(Loc::getMessage('_ENTITY_NAME_FIELD'))
			,
			'text' => (new TextField('text',
					[]
				))->configureTitle(Loc::getMessage('_ENTITY_TEXT_FIELD'))
			,
			'publish_date' => (new DateField('publish_date',
					[]
				))->configureTitle(Loc::getMessage('_ENTITY_PUBLISH_DATE_FIELD'))
			,
			'ISBN' => (new StringField('ISBN',
					[
						'validation' => function()
						{
							return[
								new LengthValidator(null, 50),
							];
						},
					]
				))->configureTitle(Loc::getMessage('_ENTITY_ISBN_FIELD'))
			,
			'author_id' => (new IntegerField('author_id',
					[]
				))->configureTitle(Loc::getMessage('_ENTITY_AUTHOR_ID_FIELD'))
			,
			'publisher_id' => (new IntegerField('publisher_id',
					[]
				))->configureTitle(Loc::getMessage('_ENTITY_PUBLISHER_ID_FIELD'))
			,
			'wikiprofile_id' => (new IntegerField('wikiprofile_id',
					[]
				))->configureTitle(Loc::getMessage('_ENTITY_WIKIPROFILE_ID_FIELD'))
			,

			(new Reference('AUTHOR', Author::class, Join::on('this.author_id', 'ref.id')))
            ->configureJoinType('inner'),

			(new ManyToMany('PUBLISHERS', Publisher::class))
            ->configureTableName('book_publisher')
            ->configureLocalPrimary('id', 'book_id')
            ->configureLocalReference('BOOKS')
            ->configureRemotePrimary('id', 'publisher_id')
            ->configureRemoteReference('PUBLISHERS'),

			'shops_id' => (new IntegerField('shops_id',
                    []
                ))->configureTitle(Loc::getMessage('_ENTITY_SHOPS_ID_FIELD')),
 
			/*(new Reference('SHOPS', ShopsTable::class, Join::on('this.shops_id', 'ref.IBLOCK_ELEMENT_ID')))
				->configureJoinType('inner'),*/
			(new Reference('SHOPS', \Bitrix\Iblock\Elements\ElementShopsTable::class, Join::on('this.shops_id', 'ref.ID')))
				->configureJoinType('left'),	
			 
			'printinghouse_id' => (new IntegerField('printinghouse_id',
                    []
                ))->configureTitle(Loc::getMessage('_ENTITY_PRINTINGHOUSE_ID_FIELD')),
				
			(new Reference('PRINTINGHOUSE', \Bitrix\Iblock\Elements\ElementPrintinghouseTable::class, Join::on('this.printinghouse_id', 'ref.ID')))
				->configureJoinType('left'),

			/*'SHOPS' => new ReferenceField(
                'shops_id', 
                ShopsTable::class,
                ['=this.shops_id' => 'ref.IBLOCK_ELEMENT_ID']
            )->configureJoinType('inner')*/
		];
	}

 
	public static function add($fields)
    {
        if ( isset($fields['publish_date']) ) {
                        $publicationDate = explode ( '-', $fields['publish_date'] );
                        if ( count($publicationDate) == 3 ) {
                          list ( $y, $m, $d ) = $publicationDate;
                          //print_r($publicationDate);
                          //$generic_date = mktime ( 0, 0, 0, $m, $d, $y ); 
                          $generic_date = new Type\Date($fields['publish_date'], 'Y-m-d');
						  $fields['publish_date'] = $generic_date;
                        }
        }
		 
       // file_put_contents($_SERVER['DOCUMENT_ROOT'].'/logOCD.txt', 'FIELDS: '.var_export($fields, true).PHP_EOL, FILE_APPEND);
        $event = new Event("main", "OnBeforeOCDAdd", $fields);
        $event->send();
        //file_put_contents($_SERVER['DOCUMENT_ROOT'].'/log0.txt', 'EDATA: '.var_export($fields, true).PHP_EOL, FILE_APPEND);
		//file_put_contents($_SERVER['DOCUMENT_ROOT'].'/log01.txt', 'EDATA: '.var_export($event, true).PHP_EOL, FILE_APPEND);
        if ($event->getResults())
        {
			
            foreach($event->getResults() as $evenResult)
            {
                if ( $evenResult->getType() == \Bitrix\Main\EventResult::SUCCESS )
                {
                    $arEventData = $evenResult->getModified();
                    if (isset($arEventData['name']))
                        unset($arEventData['name']);
					if (isset($arEventData['text']))
                        unset($arEventData['text']);
					if (isset($arEventData['publish_date']))
                        unset($arEventData['publish_date']);
					if (isset($arEventData['ISBN']))
                        unset($arEventData['ISBN']);
					if (isset($arEventData['author_id']))
                        unset($arEventData['author_id']);
					if (isset($arEventData['shops_id']))
                        unset($arEventData['shops_id']);
					if (isset($arEventData['printinghouse_id']))
                        unset($arEventData['printinghouse_id']);
					if (isset($arEventData['wikiprofile_id']))
                        unset($arEventData['wikiprofile_id']);
////
                   $fields = array_merge($fields, $arEventData);
                    file_put_contents($_SERVER['DOCUMENT_ROOT'].'/logOCD.txt', 'EDATA: '.var_export($fields, true).PHP_EOL, FILE_APPEND);
                }
            }
        }

        return parent::add($fields);
    }

	public static function update($key_id, $fields)
    {
        if ( isset($fields['publish_date']) ) {
                        $publicationDate = explode ( '-', $fields['publish_date'] );
                        if ( count($publicationDate) == 3 ) {
                          list ( $y, $m, $d ) = $publicationDate;
                          //print_r($publicationDate);
                          //$generic_date = mktime ( 0, 0, 0, $m, $d, $y ); 
                          $generic_date = new Type\Date($fields['publish_date'], 'Y-m-d');
						  $fields['publish_date'] = $generic_date;
                        }
        }
		 
       //file_put_contents($_SERVER['DOCUMENT_ROOT'].'/logOCD.txt', 'FIELDS: '.var_export($fields, true).PHP_EOL, FILE_APPEND);
        $event = new Event("main", "OnBeforeOCDUpdate", $fields);
        $event->send();
        //file_put_contents($_SERVER['DOCUMENT_ROOT'].'/log0.txt', 'EDATA: '.var_export($fields, true).PHP_EOL, FILE_APPEND);
		//file_put_contents($_SERVER['DOCUMENT_ROOT'].'/log01.txt', 'EDATA: '.var_export($event, true).PHP_EOL, FILE_APPEND);
        if ($event->getResults())
        {
			 
            foreach($event->getResults() as $evenResult)
            {
                if ( $evenResult->getType() == \Bitrix\Main\EventResult::SUCCESS )
                {
                    $arEventData = $evenResult->getModified();
                    if (isset($arEventData['name']))
                        unset($arEventData['name']);
					if (isset($arEventData['text']))
                        unset($arEventData['text']);
					if (isset($arEventData['publish_date']))
                        unset($arEventData['publish_date']);
					if (isset($arEventData['ISBN']))
                        unset($arEventData['ISBN']);
					if (isset($arEventData['author_id']))
                        unset($arEventData['author_id']);
					if (isset($arEventData['shops_id']))
                        unset($arEventData['shops_id']);
					if (isset($arEventData['printinghouse_id']))
                        unset($arEventData['printinghouse_id']);
					if (isset($arEventData['wikiprofile_id']))
                        unset($arEventData['wikiprofile_id']);
////
                   $fields = array_merge($fields, $arEventData);
                   // file_put_contents($_SERVER['DOCUMENT_ROOT'].'/logOCD7.txt', 'EDATA: '.var_export($fields, true).PHP_EOL, FILE_APPEND);
                }
            }
        }

        return parent::update($key_id, $fields);
    }


	public static function delete($key_id)
    {
        file_put_contents($_SERVER['DOCUMENT_ROOT'].'/log0.txt', 'key_id: '.var_export($key_id, true).PHP_EOL, FILE_APPEND);
        /*$event = new Event("main", "OnBeforeOCDDelete", $fields);
        $event->send();
        
		//file_put_contents($_SERVER['DOCUMENT_ROOT'].'/log01.txt', 'EDATA: '.var_export($event, true).PHP_EOL, FILE_APPEND);
        if ($event->getResults())
        {
			 
            foreach($event->getResults() as $evenResult)
            {
                if ( $evenResult->getType() == \Bitrix\Main\EventResult::SUCCESS )
                {
                    $arEventData = $evenResult->getModified();
                    if (isset($arEventData['name']))
                        unset($arEventData['name']);
					if (isset($arEventData['text']))
                        unset($arEventData['text']);
					if (isset($arEventData['publish_date']))
                        unset($arEventData['publish_date']);
					if (isset($arEventData['ISBN']))
                        unset($arEventData['ISBN']);
					if (isset($arEventData['author_id']))
                        unset($arEventData['author_id']);
					if (isset($arEventData['shops_id']))
                        unset($arEventData['shops_id']);
					if (isset($arEventData['printinghouse_id']))
                        unset($arEventData['printinghouse_id']);
					if (isset($arEventData['wikiprofile_id']))
                        unset($arEventData['wikiprofile_id']);
////
                   $fields = array_merge($fields, $arEventData);
                   // file_put_contents($_SERVER['DOCUMENT_ROOT'].'/logOCD7.txt', 'EDATA: '.var_export($fields, true).PHP_EOL, FILE_APPEND);
                }
            }
        }*/

        return parent::delete($key_id);
    }


	public static function list()
    {
         
		$collection = parent::getList([
        'select'  => [            
            'id', 
            'name', 
            'text', 
            'publish_date',
            'ISBN',
            'AUTHOR',
            'PUBLISHERS',
            'SHOPS',
            'PRINTINGHOUSE',
        ], 
 
       ])->fetchCollection();
       $data = [];
	   foreach ($collection as $key => $record){
          $publisherstr = '';
		  foreach ($record->getPublishers() as $publisher)
          { $publisherstr = $publisherstr.''.$publisher->getName().' , ';}
		  if ($record->getShops() !== null){ $shops = $record->getShops()->getName();}else{$shops = '';}  
		  if ($record->getPrintinghouse() !== null){ $Printinghouse = $record->getPrintinghouse()->getName();}else{$Printinghouse = '';} 
		  $data[] = array (
           'ID' => $record->getid(),
		   'name' => $record->getName(),
           'text' => $record->getText(), 
           'publish_date' => $record->getpublish_date()->format("Y-m-d"),
           'ISBN' => $record->getIsbn(),
           'AUTHOR' => $record->getAuthor()->getName(),
		   
           'PUBLISHERS' => $publisherstr,
           'SHOPS' => $shops,
           'PRINTINGHOUSE' => $Printinghouse
		  );   
        }
      
        
	    
         
         
        return $data;
    }


}